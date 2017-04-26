<?
if(isset($_POST["ys_compare_ajax"]) && $_POST["ys_compare_ajax"] === "y")
{	
	define("SITE_ID", htmlspecialchars($_POST["site_id"]));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}else{
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}
if(!defined('BX_UTF'))
{
	$_REQUEST = $APPLICATION->ConvertCharsetArray($_REQUEST, 'utf-8', SITE_CHARSET);
	$_GET = $APPLICATION->ConvertCharsetArray($_GET, 'utf-8', SITE_CHARSET);
	$_POST = $APPLICATION->ConvertCharsetArray($_POST, 'utf-8', SITE_CHARSET);
}

CModule::IncludeModule('yenisite.bitronic');
CModule::IncludeModule('yenisite.bitronicpro');
CModule::IncludeModule('yenisite.bitroniclite');
CModule::IncludeModule('catalog');
CModule::IncludeModule("yenisite.resizer2");

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');

global $USER;
global $ys_options;
$ys_options = CYSBitronicSettings::getAllSettings();

$save_param = new CPHPCache();
if($save_param->InitCache(86400*14, SITE_ID."_".$_POST["iblock_id"], "/ys_filter_ajax_params/"))
{
	$catalogParams = $save_param->GetVars();
}
unset($save_param);

if(!is_array($catalogParams))
	die("[ajax died] loading params");

$APPLICATION->IncludeComponent("bitrix:catalog.compare.result", "", array (
		"IBLOCK_TYPE" => $catalogParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $catalogParams["IBLOCK_ID"],
		"BASKET_URL" => $catalogParams["BASKET_URL"],
		"ACTION_VARIABLE" => $catalogParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $catalogParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $catalogParams["SECTION_ID_VARIABLE"],
		"FIELD_CODE" => $catalogParams["COMPARE_FIELD_CODE"],
		"PROPERTY_CODE" => $catalogParams['YS_SHOW_PROPERTIES'],
		"NAME" => $catalogParams["COMPARE_NAME"],
		"CACHE_TYPE" => $catalogParams["CACHE_TYPE"],
		"CACHE_TIME" => $catalogParams["CACHE_TIME"],
		"PRICE_CODE" => $catalogParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $catalogParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $catalogParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $catalogParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $catalogParams["PRICE_VAT_SHOW_VALUE"],
		"DISPLAY_ELEMENT_SELECT_BOX" => $catalogParams["DISPLAY_ELEMENT_SELECT_BOX"],
		"ELEMENT_SORT_FIELD_BOX" => $catalogParams["ELEMENT_SORT_FIELD_BOX"],
		"ELEMENT_SORT_ORDER_BOX" => $catalogParams["ELEMENT_SORT_ORDER_BOX"],
		"ELEMENT_SORT_FIELD" => $catalogParams["COMPARE_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $catalogParams["COMPARE_ELEMENT_SORT_ORDER"],
		//"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"OFFERS_FIELD_CODE" => $catalogParams["COMPARE_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $catalogParams["COMPARE_OFFERS_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $catalogParams["OFFERS_CART_PROPERTIES"],
		"COMPARE_IMG" => $catalogParams["COMPARE_IMG"],
		"SETTINGS_HIDE" => $catalogParams["SETTINGS_HIDE"],
		'STORE_CODE' => $catalogParams["STORE_CODE"],

	),
	false,
	array("HIDE_ICONS" => "Y")
);

?>