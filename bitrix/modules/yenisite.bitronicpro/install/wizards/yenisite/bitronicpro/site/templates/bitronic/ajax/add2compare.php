<?
define('SITE_ID', htmlspecialchars($_REQUEST['SITE_ID']));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$_REQUEST['iblock_id'] = intval($_REQUEST['iblock_id']);
if(!check_bitrix_sessid() || $_REQUEST['iblock_id'] <= 0)
	die();
	
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");
if(!CModule::IncludeModule(CYSBitronicSettings::getModuleId()))
{
		//YOU MUST DIE!!!
}
IncludeTemplateLangFile($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');
	
$arCompareListParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "CompareListParams_{$_REQUEST['iblock_id']}", '');
if($arCompareListParams)
{
	$arCompareListParams = unserialize($arCompareListParams) ;
	if(count($arCompareListParams))
	{
		$arCompareListParams['IT_IS_AJAX_CALL'] = 'Y' ;
		if($_REQUEST['remove'])
			$arCompareListParams['IT_IS_AJAX_REMOVE'] = 'Y';
		//echo '<pre>'; print_r($arCompareListParams); echo '</pre>';
		$APPLICATION->IncludeComponent("bitrix:catalog.compare.list", "", $arCompareListParams, false, array("HIDE_ICONS" => "Y"));
	}
$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/counter_ya_metrika.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
}
?>