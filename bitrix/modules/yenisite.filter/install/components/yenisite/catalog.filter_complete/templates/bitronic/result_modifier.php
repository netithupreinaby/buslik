<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

if($this->__folder)
	$pathToTemplateFolder = $this->__folder;
else
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));

$arColorSchemes = array('red', 'green', 'ice', 'metal', 'pink', 'yellow');
$tmp = COption::GetOptionString('yenisite.market', 'color_scheme');

if ($arParams['THEME'] && in_array($arParams['THEME'], $arColorSchemes, true)) 
	$color_scheme = $arParams['THEME'];
else if ($arParams['THEME'] === "blue")
	$color_scheme = 'ice';
else if (strlen($tmp) != 0)
{
	if (($bitronic_color_scheme = getBitronicSettings("COLOR_SCHEME")) && in_array($bitronic_color_scheme, $arColorSchemes))
		$color_scheme = $bitronic_color_scheme;
}
else
	$color_scheme = 'red';

if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));
	
if(strlen($tmp) == 0)
{	
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/js/jquery-ui-1.9.2.custom.css");
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/jquery-ui-1.9.2.custom.min.js");
	
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/selectbox.js");
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/jquery.uniform.min.js");
}

$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/js/uniform.default.css");
$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/script.js");

?>