<?php

use Sfblib\SfbConverter;
use Sfblib\SfbToFb2Converter;
use Sfblib\SfbToHtmlConverter;

class MainTest extends TestCase {

	private static $inputFiles = array(
		'accent',
		'ampersand',
		'annotation-author-dedication',
		'annotation',
		'annotation-with-image',
		'author-date-author',
		'author-not-last',
		'author',
		'bug-body-section-swap',
		'bug-redundant-stanza',
		'bug-m-with-author',
		'cite',
		'date-alone',
		'date-letter-begin',
		'date-multi',
		'dedication',
		'dedication-with-subtitle',
		'deleted',
		'emphasis',
		//'emphasis-strong-mishmash', // will probably never work
		'epigraph',
		'epigraph-with-separator',
		'epigraph-dedication',
		'header',
		'image-complex',
		'image-in-blocks',
		'image',
		'images-start-section',
		'image-start-subsection',
		'index',
		'infoblock',
		'letter',
		'm-in-m',
		'note-in-author',
		'notes-high-numbers',
		'notes-mixed',
		'notes',
		'notes-with-brackets',
		'notice',
		'poem-center',
		'poem-complex',
		'poem-epigraph-note-with-poem',
		'poem-epigraph-poem',
		'poem-in-epigraph',
		'poem',
		'poem-note-poem',
		'poem-titles',
		'poem-with-notes',
		'poem-with-numbers',
		'poem-with-preformatted',
		'poem-with-separators',
		'preformatted',
		'section-dedication-only',
		'section-empty',
		'section-empty-with-note',
		'separator',
		'sign',
		'sign-with-note',
		'sign-with-subtitle',
		'strong',
		'subtitle',
		'section-with-annotation',
		'table-align',
		'table',
		'table-span',
		'table-th2',
		'table-th',
		'table-with-img',
		'title-note-multiline',
		'title-note-notitle',
		'titles',
		'title-with-note',
		//'all', // currently broken
	);

	/**
	 * @param string $file
	 * @dataProvider getInputFiles
	 */
	public function testFb2Converter($file) {
		$this->doTestConverter(new SfbToFb2Converter("$file.sfb", dirname($file)), "$file.sfb", "$file.fb2", array($this, 'clearFb2String'));
	}

	/**
	 * @param string $file
	 * @dataProvider getInputFiles
	 */
	public function testHtmlConverter($file) {
		$this->doTestConverter(new SfbToHtmlConverter("$file.sfb", 'img'), "$file.sfb", "$file.html");
	}

	public static function getInputFiles(): array {
		return array_map(function($file){
			return [__DIR__.'/converter/'.$file];
		}, self::$inputFiles);
	}

	private function doTestConverter(SfbConverter $conv, $inFile, $outFile, $callback = null) {
		$conv->setObjectCount(1);
		$conv->rmPattern(' —')->rmRegExpPattern('/^— /');
		$conv->disableParagraphIds();
		$conv->convert();
		$testOutput = $conv->getContent();
		if ( is_callable($callback) ) {
			$testOutput = call_user_func($callback, $testOutput);
		}
		// remove double new lines
		$testOutput = preg_replace('/\n\n+/', "\n", $testOutput);
		$testOutput = strtr($testOutput, [
			"<p>\n" => '<p>',
			"\n</p>" => '</p>',
		]);
		$testOutput = rtrim($testOutput, "\n");

		// save output if wanted
		$outDir = dirname($outFile) . '/output';
		if (file_exists($outDir)) {
			file_put_contents($outDir .'/'. basename($outFile), $testOutput);
		}

		$expected = rtrim(file_get_contents($outFile), "\n");
		$this->assertEquals($expected, $testOutput, get_class($conv).": $inFile");
	}


	private function clearFb2File($file) {
		if ( ! file_exists($file) ) {
			return;
		}
		$contents = file_get_contents($file);
		if ( $this->shouldClearFb2String($contents) ) {
			file_put_contents($file, strtr($contents, array(
				"\xEF\xBB\xBF" => '', // BOM
				"\r"    => '',
				'fn_'  => 'note_',
			)));
			$this->removeElementFromFile($file, 'id');
			$this->removeElementFromFile($file, 'program-used');
			$this->removeElementFromFile($file, 'date');
			$this->removeElementFromFile($file, 'stylesheet');
		}
	}


	private function clearFb2String($string) {
		if ( $this->shouldClearFb2String($string) ) {
			$string = $this->removeElementFromString($string, 'id');
			$string = $this->removeElementFromString($string, 'program-used');
			$string = $this->removeElementFromString($string, 'date');
			$string = $this->removeElementFromString($string, 'stylesheet');
		}
		return $string;
	}


	private function shouldClearFb2String($string) {
		return strpos($string, '<id>') !== false;
	}


	private function removeElementFromFile($file, $elm) {
		$contents = file_get_contents($file);
		if ( strpos($contents, "<$elm>") !== false ) {
			file_put_contents($file, $this->removeElementFromString($contents, $elm));
		}
	}


	private function removeElementFromString($string, $elm) {
		$start = strpos ( $string, "<$elm" );
		if ( $start === false ) {
			return $string;
		}

		$end = strpos ( $string, "</$elm>", $start ) + strlen("</$elm>");
		return substr_replace ( $string, '', $start, $end - $start );
	}
}
