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
		"NAME" => GetMessage("YS_BS_COLOR"),
		"TYPE" => "LIST",
		"VALUES" => array("red" => GetMessage("YS_BS_COLOR_RED"), "green" => GetMessage("YS_BS_COLOR_GREEN"), "ice" => GetMessage("YS_BS_COLOR_BLUE"), "metal" => GetMessage("YS_BS_COLOR_METAL"), "pink" => GetMessage("YS_BS_COLOR_PINK"), "yellow" => GetMessage("YS_BS_COLOR_YELLOW")),
		"ADDITIONAL_VALUES" => "Y",
	),
);
?> 