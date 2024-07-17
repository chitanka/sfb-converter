<?php

use PHPUnit\Framework\Attributes\DataProvider;
use Sfblib\SfbConverter;
use Sfblib\SfbToFb2Converter;
use Sfblib\SfbToHtmlConverter;

class MainTest extends TestCase {

	#[DataProvider('data_fb2')]
	public function testFb2Converter(string $sfbFile, string $expectedHtmlFile) {
		$this->doTestConverter(new SfbToFb2Converter($sfbFile, dirname($sfbFile)), $sfbFile, $expectedHtmlFile, array($this, 'clearFb2String'));
	}
	public static function data_fb2(): array {
		return [
			[__DIR__.'/converter/accent.sfb', __DIR__.'/converter/accent.fb2'],
			[__DIR__.'/converter/ampersand.sfb', __DIR__.'/converter/ampersand.fb2'],
			[__DIR__.'/converter/annotation-author-dedication.sfb', __DIR__.'/converter/annotation-author-dedication.fb2'],
			[__DIR__.'/converter/annotation.sfb', __DIR__.'/converter/annotation.fb2'],
			[__DIR__.'/converter/annotation-with-image.sfb', __DIR__.'/converter/annotation-with-image.fb2'],
			[__DIR__.'/converter/author-date-author.sfb', __DIR__.'/converter/author-date-author.fb2'],
			[__DIR__.'/converter/author-not-last.sfb', __DIR__.'/converter/author-not-last.fb2'],
			[__DIR__.'/converter/author.sfb', __DIR__.'/converter/author.fb2'],
			[__DIR__.'/converter/bug-body-section-swap.sfb', __DIR__.'/converter/bug-body-section-swap.fb2'],
			[__DIR__.'/converter/bug-redundant-stanza.sfb', __DIR__.'/converter/bug-redundant-stanza.fb2'],
			[__DIR__.'/converter/bug-m-with-author.sfb', __DIR__.'/converter/bug-m-with-author.fb2'],
			[__DIR__.'/converter/cite.sfb', __DIR__.'/converter/cite.fb2'],
			[__DIR__.'/converter/date-alone.sfb', __DIR__.'/converter/date-alone.fb2'],
			[__DIR__.'/converter/date-letter-begin.sfb', __DIR__.'/converter/date-letter-begin.fb2'],
			[__DIR__.'/converter/date-multi.sfb', __DIR__.'/converter/date-multi.fb2'],
			[__DIR__.'/converter/dedication.sfb', __DIR__.'/converter/dedication.fb2'],
			[__DIR__.'/converter/dedication-with-subtitle.sfb', __DIR__.'/converter/dedication-with-subtitle.fb2'],
			[__DIR__.'/converter/deleted.sfb', __DIR__.'/converter/deleted.fb2'],
			[__DIR__.'/converter/emphasis.sfb', __DIR__.'/converter/emphasis.fb2'],
			//[__DIR__.'/converter/emphasis-strong-mishmash.sfb', __DIR__.'/converter/emphasis-strong-mishmash.fb2'], // will probably never work
			[__DIR__.'/converter/epigraph.sfb', __DIR__.'/converter/epigraph.fb2'],
			[__DIR__.'/converter/epigraph-with-separator.sfb', __DIR__.'/converter/epigraph-with-separator.fb2'],
			[__DIR__.'/converter/epigraph-dedication.sfb', __DIR__.'/converter/epigraph-dedication.fb2'],
			[__DIR__.'/converter/header.sfb', __DIR__.'/converter/header.fb2'],
			[__DIR__.'/converter/image-complex.sfb', __DIR__.'/converter/image-complex.fb2'],
			[__DIR__.'/converter/image-in-blocks.sfb', __DIR__.'/converter/image-in-blocks.fb2'],
			[__DIR__.'/converter/image.sfb', __DIR__.'/converter/image.fb2'],
			[__DIR__.'/converter/images-start-section.sfb', __DIR__.'/converter/images-start-section.fb2'],
			[__DIR__.'/converter/image-start-subsection.sfb', __DIR__.'/converter/image-start-subsection.fb2'],
			[__DIR__.'/converter/index.sfb', __DIR__.'/converter/index.fb2'],
			[__DIR__.'/converter/infoblock.sfb', __DIR__.'/converter/infoblock.fb2'],
			[__DIR__.'/converter/letter.sfb', __DIR__.'/converter/letter.fb2'],
			[__DIR__.'/converter/m-in-m.sfb', __DIR__.'/converter/m-in-m.fb2'],
			[__DIR__.'/converter/note-in-author.sfb', __DIR__.'/converter/note-in-author.fb2'],
			[__DIR__.'/converter/notes-high-numbers.sfb', __DIR__.'/converter/notes-high-numbers.fb2'],
			[__DIR__.'/converter/notes-mixed.sfb', __DIR__.'/converter/notes-mixed.fb2'],
			[__DIR__.'/converter/notes.sfb', __DIR__.'/converter/notes.fb2'],
			[__DIR__.'/converter/notes-with-brackets.sfb', __DIR__.'/converter/notes-with-brackets.fb2'],
			[__DIR__.'/converter/notice.sfb', __DIR__.'/converter/notice.fb2'],
			[__DIR__.'/converter/poem-center.sfb', __DIR__.'/converter/poem-center.fb2'],
			[__DIR__.'/converter/poem-complex.sfb', __DIR__.'/converter/poem-complex.fb2'],
			[__DIR__.'/converter/poem-epigraph-note-with-poem.sfb', __DIR__.'/converter/poem-epigraph-note-with-poem.fb2'],
			[__DIR__.'/converter/poem-epigraph-poem.sfb', __DIR__.'/converter/poem-epigraph-poem.fb2'],
			[__DIR__.'/converter/poem-in-epigraph.sfb', __DIR__.'/converter/poem-in-epigraph.fb2'],
			[__DIR__.'/converter/poem.sfb', __DIR__.'/converter/poem.fb2'],
			[__DIR__.'/converter/poem-note-poem.sfb', __DIR__.'/converter/poem-note-poem.fb2'],
			[__DIR__.'/converter/poem-titles.sfb', __DIR__.'/converter/poem-titles.fb2'],
			[__DIR__.'/converter/poem-with-notes.sfb', __DIR__.'/converter/poem-with-notes.fb2'],
			[__DIR__.'/converter/poem-with-numbers.sfb', __DIR__.'/converter/poem-with-numbers.fb2'],
			[__DIR__.'/converter/poem-with-preformatted.sfb', __DIR__.'/converter/poem-with-preformatted.fb2'],
			[__DIR__.'/converter/poem-with-separators.sfb', __DIR__.'/converter/poem-with-separators.fb2'],
			[__DIR__.'/converter/preformatted.sfb', __DIR__.'/converter/preformatted.fb2'],
			[__DIR__.'/converter/section-dedication-only.sfb', __DIR__.'/converter/section-dedication-only.fb2'],
			[__DIR__.'/converter/section-empty.sfb', __DIR__.'/converter/section-empty.fb2'],
			[__DIR__.'/converter/section-empty-with-note.sfb', __DIR__.'/converter/section-empty-with-note.fb2'],
			[__DIR__.'/converter/separator.sfb', __DIR__.'/converter/separator.fb2'],
			[__DIR__.'/converter/sign.sfb', __DIR__.'/converter/sign.fb2'],
			[__DIR__.'/converter/sign-with-note.sfb', __DIR__.'/converter/sign-with-note.fb2'],
			[__DIR__.'/converter/sign-with-subtitle.sfb', __DIR__.'/converter/sign-with-subtitle.fb2'],
			[__DIR__.'/converter/strong.sfb', __DIR__.'/converter/strong.fb2'],
			[__DIR__.'/converter/subtitle.sfb', __DIR__.'/converter/subtitle.fb2'],
			[__DIR__.'/converter/section-with-annotation.sfb', __DIR__.'/converter/section-with-annotation.fb2'],
			[__DIR__.'/converter/table-align.sfb', __DIR__.'/converter/table-align.fb2'],
			[__DIR__.'/converter/table.sfb', __DIR__.'/converter/table.fb2'],
			[__DIR__.'/converter/table-span.sfb', __DIR__.'/converter/table-span.fb2'],
			[__DIR__.'/converter/table-th2.sfb', __DIR__.'/converter/table-th2.fb2'],
			[__DIR__.'/converter/table-th.sfb', __DIR__.'/converter/table-th.fb2'],
			[__DIR__.'/converter/table-with-img.sfb', __DIR__.'/converter/table-with-img.fb2'],
			[__DIR__.'/converter/title-note-multiline.sfb', __DIR__.'/converter/title-note-multiline.fb2'],
			[__DIR__.'/converter/title-note-notitle.sfb', __DIR__.'/converter/title-note-notitle.fb2'],
			[__DIR__.'/converter/titles.sfb', __DIR__.'/converter/titles.fb2'],
			[__DIR__.'/converter/title-with-note.sfb', __DIR__.'/converter/title-with-note.fb2'],
			//[__DIR__.'/converter/all.sfb', __DIR__.'/converter/all.fb2'], // currently broken
		];
	}

	#[DataProvider('data_html')]
	public function testHtmlConverter(string $sfbFile, string $expectedHtmlFile) {
		$this->doTestConverter(new SfbToHtmlConverter($sfbFile, 'img'), $sfbFile, $expectedHtmlFile);
	}
	public static function data_html(): array {
		return [
			[__DIR__.'/converter/accent.sfb', __DIR__.'/converter/accent.html'],
			[__DIR__.'/converter/ampersand.sfb', __DIR__.'/converter/ampersand.html'],
			[__DIR__.'/converter/annotation-author-dedication.sfb', __DIR__.'/converter/annotation-author-dedication.html'],
			[__DIR__.'/converter/annotation.sfb', __DIR__.'/converter/annotation.html'],
			[__DIR__.'/converter/annotation-with-image.sfb', __DIR__.'/converter/annotation-with-image.html'],
			[__DIR__.'/converter/author-date-author.sfb', __DIR__.'/converter/author-date-author.html'],
			[__DIR__.'/converter/author-not-last.sfb', __DIR__.'/converter/author-not-last.html'],
			[__DIR__.'/converter/author.sfb', __DIR__.'/converter/author.html'],
			[__DIR__.'/converter/bug-body-section-swap.sfb', __DIR__.'/converter/bug-body-section-swap.html'],
			[__DIR__.'/converter/bug-redundant-stanza.sfb', __DIR__.'/converter/bug-redundant-stanza.html'],
			[__DIR__.'/converter/bug-m-with-author.sfb', __DIR__.'/converter/bug-m-with-author.html'],
			[__DIR__.'/converter/cite.sfb', __DIR__.'/converter/cite.html'],
			[__DIR__.'/converter/date-alone.sfb', __DIR__.'/converter/date-alone.html'],
			[__DIR__.'/converter/date-letter-begin.sfb', __DIR__.'/converter/date-letter-begin.html'],
			[__DIR__.'/converter/date-multi.sfb', __DIR__.'/converter/date-multi.html'],
			[__DIR__.'/converter/dedication.sfb', __DIR__.'/converter/dedication.html'],
			[__DIR__.'/converter/dedication-with-subtitle.sfb', __DIR__.'/converter/dedication-with-subtitle.html'],
			[__DIR__.'/converter/deleted.sfb', __DIR__.'/converter/deleted.html'],
			[__DIR__.'/converter/emphasis.sfb', __DIR__.'/converter/emphasis.html'],
			//[__DIR__.'/converter/emphasis-strong-mishmash.sfb', __DIR__.'/converter/emphasis-strong-mishmash.html'], // will probably never work
			[__DIR__.'/converter/epigraph.sfb', __DIR__.'/converter/epigraph.html'],
			[__DIR__.'/converter/epigraph-with-separator.sfb', __DIR__.'/converter/epigraph-with-separator.html'],
			[__DIR__.'/converter/epigraph-dedication.sfb', __DIR__.'/converter/epigraph-dedication.html'],
			[__DIR__.'/converter/header.sfb', __DIR__.'/converter/header.html'],
			[__DIR__.'/converter/image-complex.sfb', __DIR__.'/converter/image-complex.html'],
			[__DIR__.'/converter/image-in-blocks.sfb', __DIR__.'/converter/image-in-blocks.html'],
			[__DIR__.'/converter/image.sfb', __DIR__.'/converter/image.html'],
			[__DIR__.'/converter/images-start-section.sfb', __DIR__.'/converter/images-start-section.html'],
			[__DIR__.'/converter/image-start-subsection.sfb', __DIR__.'/converter/image-start-subsection.html'],
			[__DIR__.'/converter/index.sfb', __DIR__.'/converter/index.html'],
			[__DIR__.'/converter/infoblock.sfb', __DIR__.'/converter/infoblock.html'],
			[__DIR__.'/converter/letter.sfb', __DIR__.'/converter/letter.html'],
			[__DIR__.'/converter/m-in-m.sfb', __DIR__.'/converter/m-in-m.html'],
			[__DIR__.'/converter/note-in-author.sfb', __DIR__.'/converter/note-in-author.html'],
			[__DIR__.'/converter/notes-high-numbers.sfb', __DIR__.'/converter/notes-high-numbers.html'],
			[__DIR__.'/converter/notes-mixed.sfb', __DIR__.'/converter/notes-mixed.html'],
			[__DIR__.'/converter/notes.sfb', __DIR__.'/converter/notes.html'],
			[__DIR__.'/converter/notes-with-brackets.sfb', __DIR__.'/converter/notes-with-brackets.html'],
			[__DIR__.'/converter/notice.sfb', __DIR__.'/converter/notice.html'],
			[__DIR__.'/converter/poem-center.sfb', __DIR__.'/converter/poem-center.html'],
			[__DIR__.'/converter/poem-complex.sfb', __DIR__.'/converter/poem-complex.html'],
			[__DIR__.'/converter/poem-epigraph-note-with-poem.sfb', __DIR__.'/converter/poem-epigraph-note-with-poem.html'],
			[__DIR__.'/converter/poem-epigraph-poem.sfb', __DIR__.'/converter/poem-epigraph-poem.html'],
			[__DIR__.'/converter/poem-in-epigraph.sfb', __DIR__.'/converter/poem-in-epigraph.html'],
			[__DIR__.'/converter/poem.sfb', __DIR__.'/converter/poem.html'],
			[__DIR__.'/converter/poem-note-poem.sfb', __DIR__.'/converter/poem-note-poem.html'],
			[__DIR__.'/converter/poem-titles.sfb', __DIR__.'/converter/poem-titles.html'],
			[__DIR__.'/converter/poem-with-notes.sfb', __DIR__.'/converter/poem-with-notes.html'],
			[__DIR__.'/converter/poem-with-numbers.sfb', __DIR__.'/converter/poem-with-numbers.html'],
			[__DIR__.'/converter/poem-with-preformatted.sfb', __DIR__.'/converter/poem-with-preformatted.html'],
			[__DIR__.'/converter/poem-with-separators.sfb', __DIR__.'/converter/poem-with-separators.html'],
			[__DIR__.'/converter/preformatted.sfb', __DIR__.'/converter/preformatted.html'],
			[__DIR__.'/converter/section-dedication-only.sfb', __DIR__.'/converter/section-dedication-only.html'],
			[__DIR__.'/converter/section-empty.sfb', __DIR__.'/converter/section-empty.html'],
			[__DIR__.'/converter/section-empty-with-note.sfb', __DIR__.'/converter/section-empty-with-note.html'],
			[__DIR__.'/converter/separator.sfb', __DIR__.'/converter/separator.html'],
			[__DIR__.'/converter/sign.sfb', __DIR__.'/converter/sign.html'],
			[__DIR__.'/converter/sign-with-note.sfb', __DIR__.'/converter/sign-with-note.html'],
			[__DIR__.'/converter/sign-with-subtitle.sfb', __DIR__.'/converter/sign-with-subtitle.html'],
			[__DIR__.'/converter/strong.sfb', __DIR__.'/converter/strong.html'],
			[__DIR__.'/converter/subtitle.sfb', __DIR__.'/converter/subtitle.html'],
			[__DIR__.'/converter/section-with-annotation.sfb', __DIR__.'/converter/section-with-annotation.html'],
			[__DIR__.'/converter/table-align.sfb', __DIR__.'/converter/table-align.html'],
			[__DIR__.'/converter/table.sfb', __DIR__.'/converter/table.html'],
			[__DIR__.'/converter/table-span.sfb', __DIR__.'/converter/table-span.html'],
			[__DIR__.'/converter/table-th2.sfb', __DIR__.'/converter/table-th2.html'],
			[__DIR__.'/converter/table-th.sfb', __DIR__.'/converter/table-th.html'],
			[__DIR__.'/converter/table-with-img.sfb', __DIR__.'/converter/table-with-img.html'],
			[__DIR__.'/converter/title-note-multiline.sfb', __DIR__.'/converter/title-note-multiline.html'],
			[__DIR__.'/converter/title-note-notitle.sfb', __DIR__.'/converter/title-note-notitle.html'],
			[__DIR__.'/converter/titles.sfb', __DIR__.'/converter/titles.html'],
			[__DIR__.'/converter/title-with-note.sfb', __DIR__.'/converter/title-with-note.html'],
			//[__DIR__.'/converter/all.sfb', __DIR__.'/converter/all.html'], // currently broken
		];
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
