<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$settings = array(
    "COLOR_SCHEME" => GetMessage("COLOR_SCHEME"),
    "BASKET_POSITION" => GetMessage("BASKET_POSITION"),
    "MENU_FILTER" => GetMessage("MENU_FILTER"),
    "BACKGROUND" => GetMessage("BACKGROUND"),
    "WINDOW" => GetMessage("WINDOW"),
    "MIN_MAX" => GetMessage("MIN_MAX"),
    "ORDER" => GetMessage("ORDER"),
    "TABS_INDEX" => GetMessage("TABS_INDEX"),
    //"SMART_FILTER" => GetMessage("SMART_FILTER"),
    "SMART_FILTER_AJAX" => GetMessage("SMART_FILTER_AJAX"),
	"SMART_FILTER_TYPE" => GetMessage("SMART_FILTER_TYPE"),
	"SKU_TYPE" => GetMessage("SKU_TYPE"),
    //"SEF" => GetMessage("SEF"),
    "SHOW_ELEMENT" => GetMessage("SHOW_ELEMENT"),
    "ACTION_ADD2B" => GetMessage("ACTION_ADD2B"),
    "BLOCK_VIEW_MODE" => GetMessage("BLOCK_VIEW_MODE"),
	"VIEW_PHOTO" => GetMessage("VIEW_PHOTO"),
);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"EDIT_SETTINGS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("EDIT_SETTINGS"),
			"TYPE" => "LIST",
			"SIZE" => 8,
			"MULTIPLE" => "Y",
			"VALUES" => $settings,
		),
	),
);
?>
