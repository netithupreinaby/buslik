<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сравнение товаров");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"",
	Array(
		"ACTION_VARIABLE" => "action",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/personal/basket.php",
		"COMPARE_IMG" => "3",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"FIELD_CODE" => array("NAME","PREVIEW_TEXT"),
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => "58",
		"IBLOCK_TYPE" => "1c_catalog",
		"NAME" => "CATALOG_COMPARE_LIST",
		"OFFERS_FIELD_CODE" => array("","ID","CODE","XML_ID","NAME","TAGS","SORT",""),
		"OFFERS_PROPERTY_CODE" => array("","_5_OBKHVAT_GOLOVY","VOZRAST_POTREBITELYA","_1_ROST_ODEZHDA",""),
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PROPERTY_CODE" => array("","VID_SMESI","VID_UPAKOVKI","VOZRAST_DETSKOE_PITANIE","CML2_BAR_CODE","CML2_ARTICLE",""),
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SETTINGS_HIDE" => array(),
		"SHOW_PRICE_COUNT" => "1",
		"USE_PRICE_COUNT" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>