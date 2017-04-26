<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('sale');
CModule::IncludeModule('yenisite.geoipstore');

global $USER;
if(!$USER->IsAdmin())
	return;

$action = (!empty($_REQUEST["action"])) ? htmlspecialcharsEx($_REQUEST["action"]) : htmlspecialcharsEx($_REQUEST["action_button"]);
$id = htmlspecialcharsEx($_REQUEST["ID"]);

if($action == 'delete' && $id > 0)
{
    CYSGeoIPStore::RemoveItem($id);
}

$sTableID = "ys_geoip_store_entities";
$oSort = new CAdminSorting($sTableID, "id", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);

$dbResultList = CYSGeoIPStore::GetList();

//$ar = $dbResultList->GetNext();
//print_r($ar);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage('SET')));

$lAdmin->AddHeaders(
    array(
        array(
            "id"        => "ID",
            "content"   => GetMessage('ID'),
            "sort"      => "id",
            "default"   => true,
        ),
        array(
            "id"        => "NAME",
            "content"   => GetMessage('NAME'),
            "sort"      => "NAME",
            "default"   => true,     
        ),
        array(
            "id"        => "LOCATION_DEL",
            "content"   => GetMessage('LOCATION_DEL'),
            "sort"      => "LOCATION_DEL",
            "default"   => true,     
        ),
        array(
            "id"        => "DOMAIN_NAME",
            "content"   => GetMessage('DOMAIN_NAME'),
            "sort"      => "DOMAIN_NAME",
            "default"   => true,
        ),
    )
);

while($prof = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($prof["ID"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("ys-geoip-store-item_edit.php?id=".$prof["ID"]."&action=edit&lang=".LANG.""));

    $arLocation = CSaleLocation::GetByID($prof["LOCATION_ID_DELIVERY"]);

    $row = &$lAdmin->AddRow($prof["ID"],
                            array(
				                "ID" =>$prof["ID"],
                                "NAME" => $prof["NAME"],
                                "LOCATION_DEL" => $arLocation['COUNTRY_NAME_LANG'].', '.$arLocation['CITY_NAME_LANG'],
				                "DOMAIN_NAME" => $prof["DOMAIN_NAME"],
				            ), "ys-geoip-store-item_edit.php?id=".$prof["ID"]."&action=edit&lang=".LANG."");
    $row->AddActions($arActions);
}

$aContext = array(
		array(
			"TEXT" => GetMessage("ADD"),
			"ICON" => "btn_new",
			"LINK" => "ys-geoip-store-item_edit.php?lang=".LANG,
			"TITLE" => GetMessage("ADD")
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