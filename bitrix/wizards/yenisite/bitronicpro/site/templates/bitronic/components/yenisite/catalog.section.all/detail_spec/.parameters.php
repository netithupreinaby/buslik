<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;
$arComponentParameters["GROUPS"]["STICKERS"]= array(
	"NAME" => GetMessage("STICKER_GROUP"),
	"SORT" => '10000',
);
$arTemplateParameters = array(
	"STICKER_NEW" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_NEW'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '14',
	),
	"STICKER_HIT" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_HIT'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '100',
	),
	"STICKER_BESTSELLER" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '3',
	),
);
?>