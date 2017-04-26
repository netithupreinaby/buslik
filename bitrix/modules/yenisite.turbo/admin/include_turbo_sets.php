<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$MODULE_ID = "yenisite.turbo";
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule($MODULE_ID);
global $USER;
if(!$USER->IsAdmin())
	return;

$action = htmlspecialcharsEx($_REQUEST["action"]);
$action_button = htmlspecialcharsEx($_REQUEST["action_button"]);
$id = htmlspecialcharsEx($_REQUEST["ID"]);

if(($action == 'delete' || $action_button == 'delete') && $id > 0)
{
    global $DB;
    $strSql = "DELETE FROM yen_turbo_sets WHERE id=".mysql_real_escape_string($id);
    $dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);
}

$sTableID = "turbo_set_list";
$oSort = new CAdminSorting($sTableID, "id", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);

$dbResultList = CTurbineSet::GetList();

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage('SET')));

$lAdmin->AddHeaders(
        array(
            array(
                "id"    => "ID",
                "content"  => GetMessage('ID'),
                "sort"     => "id",
                "default"  => true,
              ),
            array(
                "id"    => "NAME",
                "content"  => GetMessage('NAME'),
                "sort"     => "NAME",
                "default"  => true,		
              ),	    
            array(
                "id"    => "IBLOCK_ID",
                "content"  => GetMessage('IBLOCK_ID'),
                "sort"     => "IBLOCK_ID",
                "default"  => true,		
              ),	    
            array(
                "id"    => "SECTION_ID",
                "content"  => GetMessage('SECTION_ID'),
                "sort"     => "SECTION_ID",
                "default"  => true,		
              ),	
            array(
                "id"    => "RENT",
                "content"  => GetMessage('RENT'),
                "sort"     => "RENT",
                "default"  => true,		
              ),    
            array(
                "id"    => "PRICE_ID",
                "content"  => GetMessage('PRICE_ID'),
                "sort"     => "PRICE_ID",
                "default"  => true,		
              ), 



        )
);

while($prof = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($prof["ID"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_turbo_set_edit.php?id=".$prof["ID"]."&action=edit&lang=".LANG.""));    
    $arActions[] = array("ICON"=>"copy", "TEXT"=>GetMessage('START'),  "ACTION"=>$lAdmin->ActionRedirect("yci_turbo_set_start.php?id=".$prof["ID"]."&lang=".LANG.""));
    $arActions[] = array("ICON"=>"copy", "TEXT"=>GetMessage('REPORT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_turbo_report.php?id=".$prof["ID"]."&lang=".LANG.""));        

	if(htmlspecialcharsEx($_REQUEST["DEBUG"] == 'Y'))
	{
		$arActions[] = array("GLOBAL_ICON"=>"adm-menu-setting", "TEXT"=>GetMessage('DELETE_ALL_PROPS'),  "ACTION"=>$lAdmin->ActionDoGroup($prof["ID"], "delete_props", 'DEBUG=Y')); 
		if(htmlspecialcharsEx($_REQUEST["action_button"] == 'delete_props'))
		{	
			$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$prof["IBLOCK_ID"], "CODE" => 'TURBO_SHOP_YANDEX_%'));
			while ($prop_fields = $properties->GetNext())
			{
				CIBlockProperty::Delete($prop_fields["ID"]);
			}
		}
	}
	
    $arSections = unserialize(base64_decode(trim($prof["SECTION_ID"])));
	$arSections_print = '';
	foreach( $arSections as $v)
		if(!empty($v))
			$arSections_print .= $v.'; ';
    $row =& $lAdmin->AddRow($prof["ID"], array(
				     "ID"=>$prof["ID"],
				     "NAME"=>$prof["NAME"],
				     "IBLOCK_ID"=>$prof["IBLOCK_ID"],
				     "SECTION_ID"=>$arSections_print,
				     "RENT"=>$prof["RENT"],
				     "PRICE_ID"=>$prof["PRICE_ID"],
				    ), "yci_turbo_set_edit.php?id=".$prof["ID"]."&action=edit&lang=".LANG."");
    $row->AddActions($arActions);
}

$aContext = array(
		array(
			"TEXT" => GetMessage("ADD"),
			"ICON" => "btn_new",
			"LINK" => "yci_turbo_set_edit.php?lang=".LANG,
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
