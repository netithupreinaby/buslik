<?
// for sef of standart bitrix component
$_SERVER["REQUEST_URI"] = !empty($_POST["ys-request-uri"]) ? $_POST["ys-request-uri"] : $_SERVER["REQUEST_URI"];
$_SERVER["SCRIPT_NAME"] = !empty($_POST["ys-script-name"]) ? $_POST["ys-script-name"] : $_SERVER["SCRIPT_NAME"];

if(isset($_POST["ys_filter_ajax"]) && $_POST["ys_filter_ajax"] === "y")
{	
	define("SITE_ID", htmlspecialchars($_POST["site_id"]));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}else{
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}
if(!defined('BX_UTF'))
{
	$_REQUEST = $APPLICATION->ConvertCharsetArray($_REQUEST, 'utf-8', SITE_CHARSET);
	$_GET = $APPLICATION->ConvertCharsetArray($_GET, 'utf-8', SITE_CHARSET);
	$_POST = $APPLICATION->ConvertCharsetArray($_POST, 'utf-8', SITE_CHARSET);
}
$folder = rtrim($_POST["ys-folder-url"], '\/');

unset($_REQUEST["ajax"]); // it used for smart filter ajax
		
$_GET = $_POST;

unset($_GET["ajax"], $_GET["iblock_id"], $_GET["ys_filter_ajax"], $_GET["site_id"], $_GET["ys-folder-url"], $_GET["ys-script-name"], $_GET["ys-request-uri"], $_GET["order"], $_GET["by"], $_GET["view"], $_GET["page_count"]);

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');

?>
<?

CModule::IncludeModule('yenisite.bitronic');
CModule::IncludeModule('yenisite.bitronicpro');
CModule::IncludeModule('yenisite.bitroniclite');
CModule::IncludeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');

global $ys_n_for_ajax;
global $USER;
global $ys_options;
$ys_options = CYSBitronicSettings::getAllSettings();
$ys_n_for_ajax = COption::GetOptionString(CYSBitronicSettings::getModuleId(), 'ys_n_for_ajax', '0');

if (!function_exists(getBitronicSettings)) {
	function getBitronicSettings($key) {
		$k = $key;
		$value = CYSBitronicSettings::getSetting($key, "");
		return $value;
	}
}

?>
<?
$save_param = new CPHPCache();
if($save_param->InitCache(86400*14, SITE_ID."_".$_POST["iblock_id"], "/ys_filter_ajax_params/"))
{
	$params = $save_param->GetVars();
}
unset($save_param);

if (CModule::IncludeModule('yenisite.geoipstore'))
{ 	// here set global var $stores
	$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/geoip_store.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));
}

if(!is_array($params))
	die("[ajax died] loading params");
$params["SMART_FILTER"] = 'Y';
$params["FOLDER"] = $folder;
$params["SET_STATUS_404"] = "N";
$params["KOMBOX_BITRONIC_AJAX"] = "Y";
if(intval($_POST["offers_iblock_id"]) > 0)
	$params["IBLOCK_ID"] = $_POST["offers_iblock_id"];
?>
<?$APPLICATION->IncludeComponent("bitrix:catalog", "catalog", $params, false);?>