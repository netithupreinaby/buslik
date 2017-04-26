<?
global $ys_options;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"bitronic",
	Array(
		"AJAX_BASKET" => "Y",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"CATEGORY_0" => array("iblock_1c_catalog"),
		"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
		"CATEGORY_0_iblock_1c_catalog" => array("all"),
		"CATEGORY_1" => array("iblock_news"),
		"CATEGORY_1_TITLE" => "Новости",
		"CATEGORY_1_iblock_news" => array("all"),
		"CATEGORY_2" => array(),
		"CATEGORY_2_TITLE" => "",
		"CATEGORY_OTHERS_TITLE" => "",
		"CHECK_DATES" => "N",
		"COLOR_SCHEME" => $ys_options["color_scheme"],
		"CONTAINER_ID_CSS" => "ys-title-search",
		"CURRENCY" => "RUB",
		"EXAMPLES" => array(""),
		"EXAMPLE_ENABLE" => "Y",
		"INCLUDE_JQUERY" => "Y",
		"INPUT_ID_CSS" => "ys-title-search-input",
		"NUM_CATEGORIES" => "2",
		"ORDER" => "date",
		"PAGE" => SITE_DIR."search/catalog.php",
		"PAGE_2" => "/search/catalog.php",
		"PHOTO_SIZE" => "5",
		"PRICE_CODE" => array("1"),
		"SEARCH_IN_TREE" => "Y",
		"SHOW_OTHERS" => "Y",
		"TOP_COUNT" => "10",
		"USE_LANGUAGE_GUESS" => "Y"
	)
);?>
