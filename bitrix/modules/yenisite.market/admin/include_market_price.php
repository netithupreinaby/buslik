<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
if (!CModule::IncludeModule("iblock")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    die('<b>'.GetMessage('IBLOCKS_NOT_INSTALL').'</b>');
}

if (!CModule::IncludeModule("yenisite.market")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    die(GetMessage('MARKET_NOT_INSTALL'));
}

$res = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
$iblock = $res->GetNext();

if (!$iblock){
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    die('<b>'.GetMessage("MARKET_IBLOCK_NOT_INSTALL")."</b>");
}

global $USER;
if(!$USER->IsAdmin())
	return;

$action = htmlspecialcharsEx($_REQUEST["action_button"]);
$id = htmlspecialchars($_REQUEST["ID"]);
if($action == 'delete' && $id > 0)
    CMarketPrice::Delete($id);

$sTableID = "market_price_list";
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();

//CMarketPrice::Add("Цена Базовая","price2_skidka","N", array("1", "2"));
$dbResultList = CMarketPrice::GetList($by, $order);
$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage('PRICE')));



$lAdmin->AddHeaders(
        array(
            array(
                "id"    =>"ID",
                "content"  => GetMessage('ID'),
                "sort"     =>"id",
                "default"  =>true,
              ),
            array(
                "id"    =>"NAME",
                "content"  => GetMessage('NAME'),
                "sort"     =>"name",
                "default"  =>true,
              ),
            array(
                "id"    =>"CODE",
                "content"  => GetMessage('CODE'),
                "sort"     =>"code",
                "default"  =>true,
              ),
            array(
                "id"    =>"BASE",
                "content"  => GetMessage('BASE'),
                "sort"     =>"base",
                "default"  =>true,
              ),

        )
);

while($price = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($price["id"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_market_price_edit.php?ID=".$price["id"]."&lang=".LANG."&action=edit"));

    $base = ($price["base"]=='Y')?GetMessage('YES'):GetMessage('NO');
    $row =& $lAdmin->AddRow(1, array("ID"=>$price["id"],"NAME"=>$price["name"], "CODE"=>$price["code"], "BASE"=>$base));
    $row->AddActions($arActions);
}




$aContext = array(
		array(
			"TEXT" => GetMessage("CGAN_ADD_NEW"),
			"ICON" => "btn_new",
			"LINK" => "yci_market_price_edit.php?action=add&lang=".LANG,
			"TITLE" => GetMessage("CGAN_ADD_NEW")
		),
	);
$lAdmin->AddAdminContextMenu($aContext, false, false);

?>

<?
    $lAdmin->CheckListMode();
    require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
    $lAdmin->DisplayList();

?>


<? //$tabControl->End(); ?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>