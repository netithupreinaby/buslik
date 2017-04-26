<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/*--------catalog ajax*/
	?><script>YS.Ajax.Init('<?=SITE_TEMPLATE_PATH."/ajax/compare.php"?>','<?=SITE_ID?>','<?=$arParams["IBLOCK_ID"]?>','<?=$_REQUEST["compareQuery"]?>');</script><?
/*-----end catalog ajax*/
CModule::IncludeModule('iblock');

foreach ($_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"] as $elId => $element) {
	$elements[$element["CODE"]] = $elId;
}

$query = htmlentities(strip_tags($_REQUEST["compareQuery"]));
$arCodes = explode('-vs-', $query);

if ( strpos($arCodes[count($arCodes) - 1], '?') !== false ) {
	$arTmp = explode('?', $arCodes[count($arCodes) - 1]);
	$arCodes[count($arCodes) - 1] = $arTmp[0];
}

$obCache = new CPHPCache;
$lifeTime = $arParams['CACHE_TIME'] ? IntVal($arParams['CACHE_TIME']) : 604800;
$cacheId = $query;

if ($obCache->InitCache($lifeTime, $cacheId, "/ys-compare")) {
	$vars = $obCache->GetVars();

	if (is_array($vars['YS_COMPARE']) && count($vars['YS_COMPARE']) > 0)
		$arRes = $vars['YS_COMPARE'];
}

if (!is_array($arRes)) {
	foreach ($arCodes as $code) {
		$arEl = CIBlockElement::GetList(array(), array("CODE" => $code), false, false, array("ID"))->GetNext();
		$arRes[$code] = $arEl["ID"];
	}

	if ($obCache->StartDataCache($lifeTime, $cacheId, "/ys-compare")) {
		$obCache->EndDataCache(array("YS_COMPARE" => $arRes)); 
	}
}

foreach ($arRes as $code => $id) {
	if (!isset($elements[$code])) {
		echo "<input type='hidden' name='ys-comp-code-".$code."' value='".$id."' />";
	}
}

if ($this->__folder) {
	$pathToTemplateFolder = $this->__folder;
} else {
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
}

if (count($arCodes) >= 2) {
	foreach ($elements as $code => $id) {
		if (!isset($arRes[$code])) {
			echo "<input type='hidden' name='ys-del-code-".$code."' value='".$id."' />";
		}
	}

	$APPLICATION->AddHeadScript($pathToTemplateFolder . "/js/sef-compare.js");
}
?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"FIELD_CODE" => $arParams["COMPARE_FIELD_CODE"], // <-- modify in result_modifier.php
		"PROPERTY_CODE" => $arResult['YS_SHOW_PROPERTIES'], // <-- generation in result_modifier.php
		"NAME" => $arParams["COMPARE_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"DISPLAY_ELEMENT_SELECT_BOX" => $arParams["DISPLAY_ELEMENT_SELECT_BOX"],
		"ELEMENT_SORT_FIELD_BOX" => $arParams["ELEMENT_SORT_FIELD_BOX"],
		"ELEMENT_SORT_ORDER_BOX" => $arParams["ELEMENT_SORT_ORDER_BOX"],
		"ELEMENT_SORT_FIELD" => $arParams["COMPARE_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["COMPARE_ELEMENT_SORT_ORDER"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"OFFERS_FIELD_CODE" => $arParams["COMPARE_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["COMPARE_OFFERS_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"COMPARE_IMG" => $arParams["COMPARE_IMG"],
		"SETTINGS_HIDE" => $arParams["SETTINGS_HIDE"],
		'STORE_CODE' => $arParams["STORE_CODE"],
		"COMPARE_META_H1" => $arParams["COMPARE_META_H1"],
		"COMPARE_META_TITLE_PROP" => $arParams["COMPARE_META_TITLE_PROP"],
		"COMPARE_META_KEYWORDS" => $arParams["COMPARE_META_KEYWORDS"],
		"COMPARE_META_DESCRIPTION" => $arParams["COMPARE_META_DESCRIPTION"],
	),
	$component
);?>
