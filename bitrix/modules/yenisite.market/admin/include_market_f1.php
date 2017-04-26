<?
define("ADMIN_MODULE_NAME", "yenisite.market");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
IncludeModuleLangFile(__FILE__);
if (!CModule::IncludeModule("iblock")){
	echo '<b>'.GetMessage('IBLOCKS_NOT_INSTALL')."</b>";
}

if (!CModule::IncludeModule("yenisite.market")){
	die('<b>'.GetMessage('MARKET_NOT_INSTALL')."</b>");
}

global $USER;
if(!$USER->IsAdmin())
	return;




$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("YEN_TAB_1_NAME"), "TITLE" => GetMessage("YEN_TAB_1"))
	
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

                    
<?
echo GetMessage("TEXT");
?>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>