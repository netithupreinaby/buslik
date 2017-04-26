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

global $YS_AJAX_FILTER_ENABLE;
$arParams['AJAX_FILTER'] = $YS_AJAX_FILTER_ENABLE;

global $FILTER_BY_QUANTITY;
global ${$arParams['FILTER_NAME']};
if ($FILTER_BY_QUANTITY == 'Y')
{	
	if (($_REQUEST["f_Quantity"] == "Y" || ($APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y' && empty($_REQUEST["set_filter"]))) && !isset($_GET["del_filter"]))
	{
		$arResult['q_checked'] = 'Y';
		// show all
		
		//${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'N';
	}
	else
	{
		$arResult['q_checked'] = 'N';
		${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'Y';
	}
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");
}
$arResult["FORM_ACTION"] = CHTTP::urlDeleteParams($arResult["FORM_ACTION"], array('bxrand','f_Quantity'), array("delete_system_params" => true));

$arParams['VISIBLE_PROPS_COUNT'] = isset($arParams['VISIBLE_PROPS_COUNT']) ? intval($arParams['VISIBLE_PROPS_COUNT']) : 5;

$bMarket = CModule::IncludeModule('yenisite.market');
if ($bMarket) {
	$dbRes = CMarketPrice::GetList();
	$arPriceCodes = array();
	while($arPrice = $dbRes->Fetch()) {
		$arPriceCodes[] = $arPrice['code'];
	}
}

$itemNum = 0;
foreach ($arResult['ITEMS'] as &$arItem) {
	if ($bMarket) {
		if (in_array($arItem['CODE'], $arPriceCodes)) {
			$arItem['PRICE'] = 'Y';
		}
	}
	if (isset($arItem['PRICE'])) continue;

	if(!empty($arItem["VALUES"]) && $arItem["PROPERTY_TYPE"] != "N") {
		$itemNum++;
		$i = 0;
		foreach ($arItem["VALUES"] as $key => $value) {
			$i++;
			if (!$value["CHECKED"]) continue;
			if ($itemNum > $arParams['VISIBLE_PROPS_COUNT']) $arParams['START_EXPANDED'] = 'Y';
			if ($i <= 5) continue;
			$arItem['EXPAND'] = true;
			break;
		}
	}

	$arProp = CIBlockProperty::GetByID($arItem['ID'])->GetNext();
	if (!$arProp) continue;

	$arItem['HINT'] = $arProp['HINT'];
}
?>