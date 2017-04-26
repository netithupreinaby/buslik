<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои заказы");
?>
<?
global $USER;
if($USER->GetID()):
global $ys_options;
?>
<br />
 <?$APPLICATION->IncludeComponent("bitrix:catalog.filter", "bitronic", array(
	"IBLOCK_TYPE" => "yenisite_market",
	"IBLOCK_ID" => "#IBLOCK_ID#",
	"FILTER_NAME" => "arrFilter",
	"FIELD_CODE" => array(
		0 => "ID",
		1 => "DATE_CREATE",
		2 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "FIO",
		1 => "EMAIL",
		2 => "PHONE",
		3 => "ABOUT",
		4 => "PAYMENT",
		5 => "STATUS",
		6 => "DELIVERY",
		7 => "",
	),
	"LIST_HEIGHT" => "5",
	"TEXT_WIDTH" => "20",
	"NUMBER_WIDTH" => "5",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"SAVE_IN_SESSION" => "N",
	"PRICE_CODE" => array(
	),
	"INCLUDE_JQUERY" => "Y",
	"THEME" =>  ($ys_options["color_scheme"]=="ice"?"blue":$ys_options["color_scheme"]),
	),
	false
);?>
<?
global $arrFilter ;
$arrFilter['CREATED_BY'] = $USER->GetID() ;
?>
<br />
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"bitronic",
	Array(
		"IBLOCK_TYPE" => "yenisite_market",
		"IBLOCK_ID" => "#IBLOCK_ID#",
		"NEWS_COUNT" => "100",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arrFilter",
		"FIELD_CODE" => array(0=>"ID",1=>"DATE_CREATE",2=>"",),
		"PROPERTY_CODE" => array(0=>"FIO",1=>"EMAIL",2=>"PHONE",3=>"ABOUT",4=>"DELIVERY",5=>"PAYMENT",6=>"STATUS",7=>"ITEMS",8=>"SITE_ID",),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "detail.php?ID=#ID#",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	)
);?> 
<br />
 
<br />
<?endif?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
