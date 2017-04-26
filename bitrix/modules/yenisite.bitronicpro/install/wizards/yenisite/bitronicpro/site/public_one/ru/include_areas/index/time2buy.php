<?global $ys_options;

$APPLICATION->IncludeComponent("aprof:time2buy", "list1", array(
	"IBLOCK_TYPE" => "",
	"IBLOCK_ID" => "",
	"SECTION_ID" => "",
	"SECTION_CODE" => "",
	"ELEMENT_ID" => "",
	"ELEMENT_CODE" => "",
	"BASKET_URL" => "/personal/basket.php",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"INCLUDE_SUBSECTIONS" => "N",
	"ELEMENT_COUNT" => "5",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"PRICE_CODE" => array(
		0 => "BASE",
	),
	"VAT_INCLUDE" => "Y",
	"TITLE" => "Успей купить!",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "N",
	"DISPLAY_MORE_PHOTO" => "Y",
	"DISPLAY_TIMER" => "Y",
	"DISPLAY_SALE" => "Y",
	"DISPLAY_PRICES" => "Y",
	"DISPLAY_SAVE" => "Y",
	"DISPLAY_BUY_BTN" => "Y",
	
	"SHOW_ELEMENT" => $ys_options['show_element'],
	),
	false
);?>