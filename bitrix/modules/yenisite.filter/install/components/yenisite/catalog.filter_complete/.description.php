<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("YENISITE_NAME"),
	"DESCRIPTION" => GetMessage("YENISITE_DESCRIPTION"),
	"ICON" => "/images/iblock_filter.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(	
		"ID" => "yenisite",
		"NAME" => GetMessage("YENISITE_COMPONENTS"),
		"CHILD" => array(
			"ID" => "catalog_ys",
			"NAME" => GetMessage("YENISITE_DESC_CATALOG"),
			"SORT" => 30
		)

	),
);
?>