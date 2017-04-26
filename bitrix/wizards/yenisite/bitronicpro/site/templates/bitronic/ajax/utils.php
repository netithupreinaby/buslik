<?
define("STOP_STATISTICS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!CModule::IncludeModule("sale") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

global $USER, $APPLICATION;

if (!check_bitrix_sessid() || $_SERVER["REQUEST_METHOD"] != "POST")
{
	return;
}

include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');

switch ($_POST['action']) {
    case 'GET_ELEMENT_PICTURE':
		if(!$_POST['resizer_set'] || !CModule::IncludeModule('yenisite.resizer2'))
			break;
			
		$path = CFile::GetPath(yenisite_GetPicSrc($_POST['element']));
        $arRes['PICT_SRC'] = CResizer2Resize::ResizeGD2($path, intval($_POST['resizer_set']));

        break;
    default:
        $arRes['ERROR'] = "Wrong action!";
        break;
}

header('Content-Type: application/json; charset='.LANG_CHARSET);
echo CUtil::PhpToJSObject($arRes);
die();
?>