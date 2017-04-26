<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($this->__folder)
	$pathToTemplateFolder = $this->__folder ;
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

// if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));

global $APPLICATION;

if (strlen($tmp) == 0) {
	$arResult["IS_BITRONIC"] = "N";

	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/jquery-ui-1.10.3.custom.min.js");
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css/jquery-ui-1.10.3.custom.min.css");
	
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/jquery.uniform.min.js");
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css/uniform.default.css");

	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/js/ajax.js");
}

$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

global $FILTER_BY_QUANTITY;
global ${$arParams['FILTER_NAME']};

if ($FILTER_BY_QUANTITY == 'Y') {
	if (($_REQUEST["set_filter"] && $APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y')
	|| empty($_REQUEST["set_filter"])
		|| isset($_REQUEST["del_filter"])) {
		$arResult['q_checked'] = 'Y';
		${$arParams['FILTER_NAME']}['>CATALOG_QUANTITY'] = '0';
	} else {
		$arResult['q_checked'] = 'N';
		${$arParams['FILTER_NAME']}[] = Array("LOGIC" => "OR",
			array('CATALOG_QUANTITY' => false),
			array('<=CATALOG_QUANTITY' => 0));
	}
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");
}

?>