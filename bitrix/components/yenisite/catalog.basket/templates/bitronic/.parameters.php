<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



$arTemplateParameters = array(
		"INCLUDE_JQUERY" => Array(
			"PARENT" => "YENISITE_BS_FLY",
			"NAME" => GetMessage("YS_BS_INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y'
		),
		"YENISITE_BS_FLY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BASKET_PHOTO"),
			"TYPE" => "LIST",
			"VALUES" => $list,
		),
		
		"COLOR_SCHEME" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_COLOR"),
		"TYPE" => "LIST",
		"VALUES" => array("red" => GetMessage("YS_BS_COLOR_RED"), "green" => GetMessage("YS_BS_COLOR_GREEN"), "ice" => GetMessage("YS_BS_COLOR_BLUE"), "metal" => GetMessage("YS_BS_COLOR_METAL"), "pink" => GetMessage("YS_BS_COLOR_PINK"), "yellow" => GetMessage("YS_BS_COLOR_YELLOW")),
		"ADDITIONAL_VALUES" => "Y",
	),
		
);



?>
