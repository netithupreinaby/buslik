<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$arTemplateParameters = array(
	"THEME" => Array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("FILTER_THEME"),
		"TYPE" => "LIST",
		"VALUES" => array("red" => GetMessage("FILTER_THEME_RED"), "green" => GetMessage("FILTER_THEME_GREEN"), "ice" => GetMessage("FILTER_THEME_ICE"), "metal" => GetMessage("FILTER_THEME_METAL"), "pink" => GetMessage("FILTER_THEME_PINK"), "yellow" => GetMessage("FILTER_THEME_YELLOW")),
		"ADDITIONAL_VALUES" => "Y",
	),
	"EXPAND_PROPS" => 	array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("EXPAND_PROPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"ENABLE_EXPANSION" => 	array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("ENABLE_EXPANSION"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"START_EXPANDED" => 	array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("START_EXPANDED"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"VISIBLE_PROPS_COUNT" => 	array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("VISIBLE_PROPS_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "5",
	),
);
?>