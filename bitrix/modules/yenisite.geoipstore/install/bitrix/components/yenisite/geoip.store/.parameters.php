<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("statistic"))
{
	return;
}

$arComponentParameters["GROUPS"]["YS_GEOIPSTORE"]= array(
	"NAME" => GetMessage("YS_GEOIPSTORE_GROUP_NAME"),
	"SORT" => 2000,
);

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"CACHE_TIME"  =>  Array("DEFAULT" => 3600),
		"COLOR_SCHEME" => Array(
			"PARENT" => "YS_GEOIPSTORE",
			"NAME" => GetMessage("YS_GEOIPSTORE_COLOR"),
			"TYPE" => "LIST",
			"VALUES" => Array(
				"red" => GetMessage("YS_GEOIPSTORE_COLOR_RED"),
				"green" => GetMessage("YS_GEOIPSTORE_COLOR_GREEN"),
				"ice" => GetMessage("YS_GEOIPSTORE_COLOR_BLUE"),
				"metal" => GetMessage("YS_GEOIPSTORE_COLOR_METAL"),
				"pink" => GetMessage("YS_GEOIPSTORE_COLOR_PINK"),
				"yellow" => GetMessage("YS_GEOIPSTORE_COLOR_YELLOW")
			),
			"ADDITIONAL_VALUES" => "Y",
		),
		"INCLUDE_JQUERY" => Array(
			"PARENT" => "YS_GEOIPSTORE",
			"NAME" => GetMessage("INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N'
		),
		"NEW_FONTS" => Array(
			"PARENT" => "YS_GEOIPSTORE",
			"NAME" => GetMessage("NEW_FONTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y'
		),
		"DETERMINE_CURRENCY" => Array(
			"PARENT" => "YS_GEOIPSTORE",
			"NAME" => GetMessage("DETERMINE_CURRENCY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		),
	),
);
if(CModule::IncludeModule('yenisite.geoip'))
{
	$arComponentParameters['PARAMETERS']["ONLY_GEOIP"] = Array(
			"PARENT" => "YS_GEOIPSTORE",
			"NAME" => GetMessage("ONLY_GEOIP"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N'
		) ;
}
?>
