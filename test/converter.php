<?php
require_once dirname(__FILE__) . '/bootstrap.php';

define('ML_INFO', 0);
define('ML_OK', 1);
define('ML_WARNING', 2);
define('ML_ERROR', 3);


function testFile($file)
{
	if (preg_match('/\.sfb$/', $file)) {
		doLog("=> $file ...", ML_INFO);

		$fb2File = strtr($file, array('.sfb' => '.fb2'));
		clearFb2File($fb2File);
		testConverter('SfbToFb2Converter', $file, dirname($file), $fb2File, 'clearFb2String');

		$htmlFile = strtr($file, array('.sfb' => '.html'));
		testConverter('SfbToHtmlConverter', $file, 'img', $htmlFile);
	}
}


function testConverter($converterClass, $inFile, $imgDir, $outFile, $callback = null)
{
	if ( file_exists($outFile) ) {
		$converterClass = 'Sfblib_' . $converterClass;
		$conv = new $converterClass($inFile, $imgDir);
		$conv->setObjectCount(1);
		$conv->rmPattern(' —')->rmRegExpPattern('/^— /');
		$conv->convert();
		$testOutput = $conv->getContent();
		if ( is_callable($callback) ) {
			$testOutput = call_user_func($callback, $testOutput);
		}
		$hasErrors = $testOutput != file_get_contents($outFile);
		doLog("$converterClass: $inFile", $hasErrors ? ML_ERROR : ML_OK);
		file_put_contents(sprintf('%s/current/%s', dirname($outFile), basename($outFile)), $testOutput);
	}
}


function clearFb2File($file)
{
	if ( ! file_exists($file) ) {
		return;
	}
	$contents = file_get_contents($file);
	if ( shouldClearFb2String($contents) ) {
		file_put_contents($file, strtr($contents, array(
			"\xEF\xBB\xBF" => '', // BOM
			"\r"    => '',
			'fn_'  => 'note_',
		)));
		removeElementFromFile($file, 'id');
		removeElementFromFile($file, 'program-used');
		removeElementFromFile($file, 'date');
	}
}


function clearFb2String($string)
{
	if ( shouldClearFb2String($string) ) {
		$string = removeElementFromString($string, 'id');
		$string = removeElementFromString($string, 'program-used');
		$string = removeElementFromString($string, 'date');
	}
	return $string;
}


function shouldClearFb2String($string)
{
	return strpos($string, '<id>') !== false;
}


function removeElementFromFile($file, $elm)
{
	$contents = file_get_contents($file);
	if ( strpos($contents, "<$elm>") !== false ) {
		file_put_contents($file, removeElementFromString($contents, $elm));
	}
}


function removeElementFromString($string, $elm)
{
	$start = strpos ( $string, "<$elm" );
	if ( $start === false ) {
		return $string;
	}

	$end = strpos ( $string, "</$elm>", $start ) + strlen("</$elm>");
	return substr_replace ( $string, '', $start, $end - $start );
}


function doLog($msg, $level)
{
	switch ($level) {
		case ML_INFO:    $msg = formatMsg($msg, '1;33m');          break; /* yellow fg */
		case ML_WARNING: $msg = formatMsg($msg, '1;31m', 'WRN:');  break; /* light red fg */
		case ML_ERROR:   $msg = formatMsg($msg, '41;1m', 'ERR:');  break; /* red bg */
		case ML_OK:
		default:         $msg = formatMsg($msg);                   break;
	}
	print $msg . "\n";
}


function formatMsg($msg, $color = null, $prefix = '', $format = '%-4s %s')
{
	if ( is_null($color) ) {
		return sprintf($format, $prefix, $msg);
	}
	return "\033[$color" . sprintf($format, $prefix, $msg) . "\033[0m";
}


/****************************************************************************/


if ( isset($argv[1]) && is_readable($argv[1]) ) {
	testFile($argv[1]);
}
else {
	/*
		converter/ contains:
			sample.sfb  - sfb input
			sample.html - test against this html file
			sample.fb2  - test against this fb2 file
	*/
	$dir = dirname(__FILE__) . '/converter';
	foreach ( scandir($dir) as $file ) {
		if ($file[0] == '.') {
			continue;
		}
		testFile("$dir/$file");
	}
}
