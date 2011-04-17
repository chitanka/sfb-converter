<?php
function __autoload($class)
{
	$class = str_replace('_', '/', $class);
	require_once $class .'.php';
}

function addIncludePath($path)
{
	if ( is_array($path) ) {
		$path = implode(PATH_SEPARATOR, $path);
	}
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}


error_reporting(E_ALL | E_STRICT);

addIncludePath(dirname(__FILE__) . '/../lib');
