<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"ID_LIST" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_ID_LIST"),
			"TYPE" => "LIST",
		),
		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_FILTER_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		"PAGE_ELEMENT_COUNT" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>86400),
	),
);
?>