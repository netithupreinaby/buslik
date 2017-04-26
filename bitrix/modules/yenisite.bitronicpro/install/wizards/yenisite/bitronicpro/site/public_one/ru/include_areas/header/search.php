<?
global $ys_options;
?>
<?$APPLICATION->IncludeComponent("bitrix:search.title", "bitronic", array(
	"NUM_CATEGORIES" => "1",
	"TOP_COUNT" => "10",
	"ORDER" => "date",
	"USE_LANGUAGE_GUESS" => "Y",
	"CHECK_DATES" => "N",
	"SHOW_OTHERS" => "N",
	"PRICE_CODE" => array(
		0 => "1",
	),
	"SEARCH_IN_TREE" => "Y",
	"PAGE" => SITE_DIR."search/catalog.php",
	"PAGE_2" => "#SITE_DIR#search/catalog.php",
	"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
	"CATEGORY_0" => array(
	),
	"COLOR_SCHEME" => $ys_options["color_scheme"],
	"CURRENCY" => "RUB",
	"PHOTO_SIZE" => "5",
	"EXAMPLE_ENABLE" => "Y",
	"EXAMPLES" => array(
		0 => "Apple iPad",
	),
	"CACHE_TIME" => "86400",
	"INCLUDE_JQUERY" => "Y"
	),
	false
);?>
