<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SPPA_DEFAULT_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("SPPA_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_profile_add.gif",
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "personal_rz",
			"NAME" => GetMessage("YENISITE_DESC_PERSONAL"),
			"SORT" => 30
				),
			),
);
?>