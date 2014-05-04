<?php
error_reporting(E_ALL | E_STRICT);

spl_autoload_register(function($class) {
	if (strpos($class, 'Sfblib') === 0) {
		$class = strtr($class, array(
			'Sfblib\\' => '',
			'\\' => '/',
		));
		require_once "$class.php";
	}
});

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../lib'));

require __DIR__ . '/TestCase.php';
