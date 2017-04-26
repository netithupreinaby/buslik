<?php
$moduleId = 'yenisite.coreparser';

spl_autoload_register(function($className){
	if (strpos($className, 'Symfony\\Component\\') !== 0) {
		return false;
	}
	$path = str_replace(
		array('Symfony\\Component', '\\'),
		array('/vendor', '/'),
		$className
	);
	$path = dirname(__FILE__) . $path . '.php';
	if (@file_exists($path)) {
		require_once $path;
		return true;
	}
	return false;
});

CJSCore::RegisterExt(
	'ys_core_parser_log',
	array(
		'js'   => BX_ROOT . '/js/' . $moduleId . '/log.js',
		'css'  => BX_ROOT . '/js/' . $moduleId . '/log.css',
		'lang' => BX_ROOT . '/modules/' . $moduleId . '/lang/' . LANGUAGE_ID . '/js/log.js.php'
	)
);

CJSCore::RegisterExt(
	'ys_core_parser',
	array(
		'js'   => BX_ROOT . '/js/' . $moduleId . '/coreparser.js',
		'lang' => BX_ROOT . '/modules/' . $moduleId . '/lang/' . LANGUAGE_ID . '/js/coreparser.js.php',
		'rel'  => array('ys_core_parser_log')
	)
);
