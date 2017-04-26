<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_BS_FILTER"]= array(
	"NAME" => GetMessage("YS_BS_GROUP_NAME"),
	"SORT" => 2000,
);
$arTemplateParameters = array(
	"INCLUDE_JQUERY" => Array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("INCLUDE_JQUERY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y'
	),
	"THEME" => Array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("THEME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"red" => GetMessage("THEME_RED"),
			"ice" => GetMessage("THEME_ICE"),
			"green" => GetMessage("THEME_GREEN"),
		),
		"DEFAULT" => "red",
	),
);
?> 