<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.market");

global $USER;
if(!$USER->IsAdmin())
	return;

$action = htmlspecialchars($_REQUEST["action"]);
$id = htmlspecialchars($_REQUEST["ID"]);
/*if($action == 'delete' && $id > 0)
    CMarketPrice::Delete($id);*/

$sTableID = "market_price_list";
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();

//CMarketPrice::Add("Цена Базовая","price2_skidka","N", array("1", "2"));
$dbResultList = CMarketCatalog::GetList($by, $order);
$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint("you"));



$lAdmin->AddHeaders(
        array(
            array(
                "id"    =>"ID",
                "content"  => GetMessage('ID'),
                "sort"     =>"id",
                "default"  =>true,
              ),
            array(
                "id"    =>"CODE",
                "content"  => GetMessage('CODE'),
                "sort"     =>"code",
                "default"  =>true,
              ),
            array(
                "id"    =>"NAME",
                "content"  => GetMessage('NAME'),
                "sort"     =>"name",
                "default"  =>true,
              ),
            array(
                "id"    =>"PROPERTIES",
                "content"  => GetMessage('PROPERTIES'),
                "sort"     =>"properties",
                "default"  =>true,
              )

        )
);

while($catalog = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($catalog["ID"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_market_price_edit.php?ID=".$price["id"]."&lang=".LANG."&action=edit"));

    
    $row =& $lAdmin->AddRow(1, array("ID"=>$catalog["ID"],"NAME"=>$catalog["NAME"], "CODE"=>$catalog["CODE"], "PROPERTIES"=>$props));
    $row->AddActions($arActions);
}


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