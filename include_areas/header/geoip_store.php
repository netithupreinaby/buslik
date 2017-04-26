<?
global $prices, $stores;

$arRes = $APPLICATION->IncludeComponent("yenisite:geoip.store", ".default", array(
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "360000",
	"COLOR_SCHEME" => $ys_options["color_scheme"],
	"INCLUDE_JQUERY" => "N",
	"NEW_FONTS" => "Y"
	),
	false
);

if (!empty($arRes['PRICES'])) {
	$prices = $arRes['PRICES'];
}
$stores = $arRes['STORES'];

?>