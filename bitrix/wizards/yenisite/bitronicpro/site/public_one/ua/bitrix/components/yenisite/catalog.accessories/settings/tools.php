<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$component_path = '/bitrix/components/yenisite/catalog.accessories/' ;
include($_SERVER['DOCUMENT_ROOT'].$component_path.'settings/template.php') ;

// protect :
global $USER;
if(!$USER->IsAdmin() || !CModule::IncludeModule('catalog') || !CModule::IncludeModule('sale') || !check_bitrix_sessid('bxsessid') || $_SERVER['REQUEST_METHOD'] != 'POST' || !$_POST['action'])
{
	return false;
}
// request reaction
$n  = $_POST['row'] ? IntVal($_POST['row']) : 0 ;

switch($_POST['action'])
{
	case 'GetNewProp':
		if($_POST['PARENT_IBLOCK_ID'])
		{
			$arSelectParentIBlockProperty = yenisite_GetIBlockProps($_POST['PARENT_IBLOCK_ID']);		
			$arSelectIBlockTypes = yenisite_GetIBlockTypes() ;
			
			yenisite_PrintTypesRow ( $n, $arSelectParentIBlockProperty, $arSelectIBlockTypes ) ;
			
		}
	break;
	case 'GetIBlocks':
		if($_POST['IBLOCK_TYPE'])
		{
			$arSelectIBlocks = yenisite_GetIBlocks($_POST['IBLOCK_TYPE']) ;
			yenisite_PrintIBlocksRow ( $n, $arSelectIBlocks ) ;
		}
	break;
	case 'GetProps':
		if(IntVal($_POST["IBLOCK_ID"]))
		{
			$arSelectIBlockProperties = yenisite_GetIBlockProps ( $_POST['IBLOCK_ID'] ) ;
			yenisite_PrintPropertyRow ($n, $arSelectIBlockProperties) ;
		}
	break;

	case 'GetSerialize':
		echo base64_encode(serialize($_POST)) ;
	break ;
	
	default:
		return false;
	break;
}?>