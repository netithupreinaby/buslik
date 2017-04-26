<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
	
$arComponentParameters = Array(
	"PARAMETERS" => Array(
		
		
		"PATH_TO_LIST" => Array(
			"NAME" => GetMessage("SPPA_PATH_TO_LIST"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PATH_TO_DETAIL" => Array(
			"NAME" => GetMessage("SPPA_PATH_TO_DETAIL"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		'USE_AJAX_LOCATIONS' => array(
			'NAME' => GetMessage("SPPA_USE_AJAX_LOCATIONS"),
			'TYPE' => 'CHECKBOX',
			'MULTIPLE' => 'N',
			'DEFAULT' => 'N',
			"PARENT" => "ADDITIONAL_SETTINGS",
			'REFRESH' => 'Y',
		),
		"SET_TITLE" => Array(),

	)
);

if ($arCurrentValues['USE_AJAX_LOCATIONS'] != 'Y')
{
	$arComponentParameters['PARAMETERS']['TEMP_FOR_LOCATION'] = array('HIDDEN' => 'Y');
} else
{
	if (Loader::includeModule('sale') && CSaleLocation::isLocationProMigrated())
	{
		$arComponentParameters['PARAMETERS']['TEMP_FOR_LOCATION'] = array(
			'NAME' => GetMessage("SPPA_TEMPLATE"),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'VALUES' => array(
				'search'  => 'search',
				'steps' => 'steps',
			),
			'DEFAULT' => 'search',
			"PARENT" => "ADDITIONAL_SETTINGS",);
	}
}
?>