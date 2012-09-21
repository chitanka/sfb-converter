<?php
abstract class TestCase extends PHPUnit_Framework_TestCase
{
	protected function doTestFile($file)
	{
		$fb2File = strtr($file, array('.sfb' => '.fb2'));
		$this->clearFb2File($fb2File);
		$this->doTestConverter('SfbToFb2Converter', $file, dirname($file), $fb2File, array($this, 'clearFb2String'));

		$htmlFile = strtr($file, array('.sfb' => '.html'));
		#$this->doTestConverter('SfbToHtmlConverter', $file, 'img', $htmlFile);
	}


	protected function doTestConverter($converter, $inFile, $imgDir, $outFile, $callback = null)
	{
		$converterClass = 'Sfblib_' . $converter;
		$conv = new $converterClass($inFile, $imgDir);
		$conv->setObjectCount(1);
		$conv->rmPattern(' —')->rmRegExpPattern('/^— /');
		$conv->convert();
		$testOutput = $conv->getContent();
		if ( is_callable($callback) ) {
			$testOutput = call_user_func($callback, $testOutput);
		}
		// remove double new lines
		$testOutput = preg_replace('/\n\n+/', "\n", $testOutput);

		$this->assertEquals(file_get_contents($outFile), $testOutput, "$converter: $inFile");

		// save output if wanted
		$outDir = dirname($outFile) . '/output';
		if (file_exists($outDir)) {
			file_put_contents($outDir .'/'. basename($outFile), $testOutput);
		}
	}


	protected function clearFb2File($file)
	{
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


	protected function clearFb2String($string)
	{
		if ( $this->shouldClearFb2String($string) ) {
			$string = $this->removeElementFromString($string, 'id');
			$string = $this->removeElementFromString($string, 'program-used');
			$string = $this->removeElementFromString($string, 'date');
			$string = $this->removeElementFromString($string, 'stylesheet');
		}
		return $string;
	}


	protected function shouldClearFb2String($string)
	{
		return strpos($string, '<id>') !== false;
	}


	protected function removeElementFromFile($file, $elm)
	{
		$contents = file_get_contents($file);
		if ( strpos($contents, "<$elm>") !== false ) {
			file_put_contents($file, $this->removeElementFromString($contents, $elm));
		}
	}


	protected function removeElementFromString($string, $elm)
	{
		$start = strpos ( $string, "<$elm" );
		if ( $start === false ) {
			return $string;
		}

		$end = strpos ( $string, "</$elm>", $start ) + strlen("</$elm>");
		return substr_replace ( $string, '', $start, $end - $start );
	}

}
