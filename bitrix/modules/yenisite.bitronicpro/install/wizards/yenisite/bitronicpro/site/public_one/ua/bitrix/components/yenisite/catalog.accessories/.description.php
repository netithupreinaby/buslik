<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("YENISITE_ACCESSORIES_NAME"),
	"DESCRIPTION" => GetMessage("YENISITE_ACCESSORIES_DESCRIPTION"),
	"ICON" => "/images/ys_ac.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 110,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "catalog_rz",
			"NAME" => GetMessage("YENISITE_CATALOG"),
			"SORT" => 30,
		),
	),
);
?>