<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arTemplateParameters = array(
	"TITLE" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_TITLE"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"TEXT"
	),
	"DISPLAY_NAME" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_NAME"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_PICTURE" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_PICTURE"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_MORE_PHOTO" => array(
		"NAME"=>GetMessage("YENISITE_MORE_PHOTO"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_TIMER" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_TIMER"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_SALE" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_SALE"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_PRICES" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_PRICES"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_SAVE" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_SAVE"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	"DISPLAY_BUY_BTN" => array(
		"NAME"=>GetMessage("APROF_TIME2BUY_TEMPLATE_BUYBTN"),
		"PARENT"=>"TEMPLATE",
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"Y"
	),
	
	"SHOW_ELEMENT" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("APROF_TIME2BUY_TEMPLATE_SHOW_ELEMENT"),
		"TYPE" => "STRING",
		"DEFAULT" => $ys_options['show_element'],
	),
);

?>