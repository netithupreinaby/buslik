<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(CModule::IncludeModule("statistic"))
{
	if($this->__folder)
	{
		$pathToTemplateFolder = $this->__folder;
	}
	else
	{
		$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
	}

	$arColorSchemes = array('red', 'green', 'ice', 'metal', 'pink', 'yellow');
	$tmp = COption::GetOptionString('yenisite.market', 'color_scheme');

	$color_scheme = 'red';

	if ($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true))
		$color_scheme = $arParams['COLOR_SCHEME'];
	else if ($arParams['COLOR_SCHEME'] === "blue")
		$color_scheme = 'ice';
	else if (strlen($tmp) != 0)
	{
		if (function_exists('getBitronicSettings') && ($bitronic_color_scheme = getBitronicSettings("COLOR_SCHEME")) && in_array($bitronic_color_scheme, $arColorSchemes))
			$color_scheme = $bitronic_color_scheme;
	}

	// old or new fonts
	if($arParams['NEW_FONTS'] == 'Y')
	{
		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/new_fonts.css");
		$arResult['FONTS'] = "_NEW";
	}
	else
	{
		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/old_fonts.css");
		$arResult['FONTS'] = "";
	}

	if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));
		
	$path = "http://" . $_SERVER['HTTP_HOST'] . $arResult["COMPONENT_DIRECTORY"];

	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

	//$APPLICATION->AddHeadScript($path . "js/underscore-min.js");
	//$APPLICATION->AddHeadScript($path . "js/locator.js");
	//$APPLICATION->AddHeadScript($path . "js/jquery.textchange.min.js");

	/* if(strlen($tmp) != 0)
	{
		$APPLICATION->AddHeadScript($path . "js/bitronic.js");
	}
	else
	{
		$APPLICATION->AddHeadScript($path . "js/eshop.js");
		$APPLICATION->AddHeadScript($path . "js/jquery.jgrowl_minimized.js");

		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css/jquery.jgrowl.css");
	} */
}
?>