<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
if (!CModule::IncludeModule("iblock")){
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    die(GetMessage('IBLOCKS_NOT_INSTALL'));
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

$action = htmlspecialchars($_REQUEST["action_button"]);
$id = htmlspecialchars($_REQUEST["ID"]);
if($action == 'delete' && $id > 0)
{
    global $DB;
    $strSql = "DELETE FROM yen_market_import_profile WHERE id=".$id;
    $dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);
}




if($action == 'copy' && $id > 0 && !isset($_REQUEST["by"]))
{
    global $DB;
    $strSql = "INSERT INTO yen_market_import_profile(
            name,
            file,
            iblock_type,
            iblock_id,
            delimiter,
            first_names,
            metki,
            first_names2,
            fields_type,
            file_fields,
            PATH2IMAGE_FILES,
            PATH2PROP_FILES,
            IMAGE_RESIZE,
            outFileAction,
            inFileAction,
            max_execution_time,
            data
        )
        SELECT
            name,
            file,
            iblock_type,
            iblock_id,
            delimiter,
            first_names,
            metki,
            first_names2,
            fields_type,
            file_fields,
            PATH2IMAGE_FILES,
            PATH2PROP_FILES,
            IMAGE_RESIZE,
            outFileAction,
            inFileAction,
            max_execution_time,
            data
        FROM yen_market_import_profile
        WHERE id=".$id;

    $dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);

    $strSql = "SELECT MAX(id) as ID FROM yen_market_import_profile";
    $res1 = $DB->Query($strSql, false, $err_mess.__LINE__);
    $res = $res1->GetNext();

    $strSql = "SELECT id as ID, name FROM yen_market_import_profile WHERE id=".$res['ID'];
    $res1 = $DB->Query($strSql, false, $err_mess.__LINE__);
    $res = $res1->GetNext();
    
    $strSql = "UPDATE yen_market_import_profile SET name='".$res["name"]."".GetMessage('COPY_TXT')."' WHERE id=".$res["ID"];
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);

}

if(!isset($_REQUEST["by"]))
{
    $by = "ID";
    $sort = "asc";
}
else
{
    $by = $_REQUEST["by"];
    $sort = $_REQUEST["sort"];
}



$sTableID = "market_price_list";
$oSort = new CAdminSorting($sTableID, "id", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();




global $DB;
$strSql = "SELECT id, name FROM yen_market_import_profile ORDER BY ".$DB->ForSql($by)." ".$DB->ForSql($order);
$dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);

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

        )
);

while($prof = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($prof["id"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_market_import.php?profile_id=".$prof["id"]."&lang=".LANG.""));
    $arActions[] = array("ICON"=>"copy", "TEXT"=>GetMessage('COPY'), "ACTION"=>"if(confirm('".GetMessage('COPY_MESS')."')) ".$lAdmin->ActionDoGroup($prof["id"], "copy"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('START'),  "ACTION"=>$lAdmin->ActionRedirect("yci_market_import.php?profile_id=".$prof["id"]."&lang=".LANG."&start=Y"));

    
    $row =& $lAdmin->AddRow(1, array("ID"=>$prof["id"],"NAME"=>$prof["name"]), "yci_market_import.php?profile_id=".$prof["id"]."&lang=".LANG."");
    $row->AddActions($arActions);
}




$aContext = array(
		array(
			"TEXT" => GetMessage("CGAN_ADD_NEW"),
			"ICON" => "btn_new",
			"LINK" => "yci_market_import.php?lang=".LANG,
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


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>