<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CD_RO_NAME"),
	"DESCRIPTION" => GetMessage("CD_RO_DESCRIPTION"),
	"ICON" => "/images/rbox.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(	
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "bitronic_rz",
			"NAME" => GetMessage("CD_RO_RSS"),
			"SORT" => 30
		)

	),
);
?>
