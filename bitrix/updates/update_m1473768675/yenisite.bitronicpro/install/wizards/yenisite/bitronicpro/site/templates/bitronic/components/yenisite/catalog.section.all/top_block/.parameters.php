<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;
$arComponentParameters["GROUPS"]["STICKERS"]= array(
	"NAME" => GetMessage("STICKER_GROUP"),
	"SORT" => '10000',
);
$arTemplateParameters = array(
	"MENU_FILTER" => array(
		"PARENT" => "VISUAL",
		"NAME" 	 => GetMessage('MENU_FILTER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => $ys_options["menu_filter"],
	),
	"SHOW_ELEMENT" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SHOW_ELEMENT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	"AUTO_SLIDE" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("AUTO_SLIDE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	"DELAY_SLIDE" => array(
		"PARENT" => "VISUAL",
		"NAME" 	 => GetMessage('DELAY_SLIDE'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '10',
	),
		
);
?>