<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Планшетные компьютеры");?>
<?$APPLICATION->IncludeComponent("bitrix:catalog", "catalog", array(
	"IBLOCK_TYPE" => SITE_ID."_computers_and_laptops",
	"IBLOCK_ID" => "#IBLOCK_ID#",
	"BLOCK_IMG_SMALL" => "3",
	"BLOCK_IMG_BIG" => "4",
	"LIST_IMG" => "3",
	"TABLE_IMG" => "5",
	"DETAIL_IMG_SMALL" => "2",
	"DETAIL_IMG_BIG" => "7",
	"DETAIL_IMG_ICON" => "6",
	"COMPARE_IMG" => "4",
	"BASKET_URL" => "/personal/basket.php",
	"ACTION_VARIABLE" => "action",
	"PRODUCT_ID_VARIABLE" => "id",
	"SECTION_ID_VARIABLE" => "SECTION_ID",
	"PRODUCT_QUANTITY_VARIABLE" => "quantity",
	"USE_PRODUCT_QUANTITY" => "N",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "#SEF_FOLDER#",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"USE_FILTER" => "Y",
	"FILTER_NAME" => "arrFilter",
	"FILTER_FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"FILTER_PROPERTY_CODE" => array(
		0 => "PRODUCER",
		1 => "COUNTRY",
		2 => "",
	),
	"FILTER_PRICE_CODE" => $prices,
	"USE_REVIEW" => "Y",
	"MESSAGES_PER_PAGE" => "10",
	"USE_CAPTCHA" => "Y",
	"REVIEW_AJAX_POST" => "N",
	"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
	"FORUM_ID" => "#FORUM_ID#",
	"URL_TEMPLATES_READ" => "",
	"SHOW_LINK_TO_FORUM" => "Y",
	"POST_FIRST_MESSAGE" => "N",
	"PREORDER" => "N",
	"USE_COMPARE" => "Y",
	"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
	"COMPARE_FIELD_CODE" => array(
		0 => "NAME",
		1 => "",
	),
	"COMPARE_PROPERTY_CODE" => array(
		0 => "PRODUCER",
		1 => "COUNTRY",
		2 => "",
	),
	"COMPARE_ELEMENT_SORT_FIELD" => "sort",
	"COMPARE_ELEMENT_SORT_ORDER" => "asc",
	"DISPLAY_ELEMENT_SELECT_BOX" => "N",
	"PRICE_CODE" => $prices,
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"SHOW_TOP_ELEMENTS" => "Y",
	"TOP_ELEMENT_COUNT" => "9",
	"TOP_LINE_ELEMENT_COUNT" => "3",
	"TOP_ELEMENT_SORT_FIELD" => "sort",
	"TOP_ELEMENT_SORT_ORDER" => "asc",
	"TOP_PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"PAGE_ELEMENT_COUNT" => "20",
	"LINE_ELEMENT_COUNT" => "3",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"LIST_PROPERTY_CODE" => array(
		0 => "",
		1 => "PHOTO",
		2 => "",
	),
	"INCLUDE_SUBSECTIONS" => "Y",
	"LIST_META_KEYWORDS" => "-",
	"LIST_META_DESCRIPTION" => "-",
	"LIST_OFFERS_FIELD_CODE" => array(
		0 => "NAME",		
	),
	"DETAIL_OFFERS_FIELD_CODE" => array(
		0 => "NAME",		
	),
	"LIST_BROWSER_TITLE" => "-",
	"DETAIL_PROPERTY_CODE" => array(
		0 => "PRODUCER",
		1 => "COUNTRY",
		2 => "",
	),
	"DETAIL_META_KEYWORDS" => "-",
	"DETAIL_META_DESCRIPTION" => "-",
	"DETAIL_BROWSER_TITLE" => "-",
	"COMPARE_META_H1" => "Что лучше ",
    "COMPARE_META_TITLE_PROP" => "Что лучше купить ",
    "COMPARE_META_KEYWORDS" => "Сравнение ",
	"COMPARE_META_DESCRIPTION" => "Сравнение ",
	"LINK_IBLOCK_TYPE" => "",
	"LINK_IBLOCK_ID" => "",
	"LINK_PROPERTY_SID" => "",
	"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
	"USE_ALSO_BUY" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "Y",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"AJAX_OPTION_ADDITIONAL" => "",
	"USE_STORE" => "Y",
	"USE_STORE_PHONE" => "N",
	"USE_STORE_SCHEDULE" => "N",
	"USE_MIN_AMOUNT" => "Y",
	"MIN_AMOUNT" => "10",
	"STORE_PATH" => "/store/#store_id#",
	"MAIN_TITLE" => "Наличие на складах",
	"YS_STORES_MUCH_AMOUNT" => "15",
	"STORE_CODE" => $stores,
	"SEF_URL_TEMPLATES__SEF" => array(
		"sections" => "",
		"section" => "#SECTION_CODE#/",
		"element" => "#SECTION_CODE#/#ELEMENT_CODE#.html",
		"compare" => "compare.php?action=#ACTION_CODE#",
	),
"VARIABLE_ALIASES" => array("SECTION_ID" => "SECTION_ID","ELEMENT_ID" => "ELEMENT_ID",)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>