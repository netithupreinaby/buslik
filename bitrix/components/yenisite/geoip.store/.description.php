<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("YENISITE_GEOIPSTORE_NAME"),
	"DESCRIPTION" => GetMessage("YENISITE_GEOIPSTORE_DESCRIPTION"),
	"ICON" => "/images/icon.png",
	"CACHE_PATH" => "Y",
	"SORT" => 40,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "ys-geoipstore",
			"NAME" => GetMessage("YENISITE_GEOIPSTORE"),
			"SORT" => 40
		)
	)
);
?>
