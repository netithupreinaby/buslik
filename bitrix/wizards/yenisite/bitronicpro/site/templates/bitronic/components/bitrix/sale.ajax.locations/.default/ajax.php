<?
define("STOP_STATISTICS", true);
if(isset($_REQUEST["SITE_ID"]) && strlen($_REQUEST["SITE_ID"]) > 0)
	define("SITE_ID", htmlspecialchars($_REQUEST["SITE_ID"]));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	'bitrix:sale.ajax.locations', 
	'', 
	array(
		"AJAX_CALL" => "Y", 
		"COUNTRY" => intval($_REQUEST["COUNTRY"]),
		"COUNTRY_INPUT_NAME" => $_REQUEST["COUNTRY_INPUT_NAME"],
		"REGION" => intval($_REQUEST["REGION"]),
		"REGION_INPUT_NAME" => $_REQUEST["REGION_INPUT_NAME"],
		"CITY_INPUT_NAME" => $_REQUEST["CITY_INPUT_NAME"],
		"CITY_OUT_LOCATION" => $_REQUEST["CITY_OUT_LOCATION"],
		"ALLOW_EMPTY_CITY" => $_REQUEST["ALLOW_EMPTY_CITY"],
		"ONCITYCHANGE" => $_REQUEST["ONCITYCHANGE"],
		//"ZIP" => trim($_REQUEST["ZIP"]), 
	),
	null,
	array('HIDE_ICONS' => 'Y'));

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");
?>