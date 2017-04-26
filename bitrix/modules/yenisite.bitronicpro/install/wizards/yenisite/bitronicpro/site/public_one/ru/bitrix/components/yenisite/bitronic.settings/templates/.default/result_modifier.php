<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($this->__folder) {
	$pathToTemplateFolder = $this->__folder;
} else {
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
}

$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/colorpicker/js/colorpicker.js");

?>
