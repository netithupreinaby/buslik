<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"BASKET_URL" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_BASKET_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/account/cart/",
		),
		"VALUTA" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("VALUTA"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("RUB"),
		),
	),
);

?>