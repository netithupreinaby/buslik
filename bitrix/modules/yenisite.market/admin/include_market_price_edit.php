<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.market");

global $USER;
if(!$USER->IsAdmin())
	return;

$action = htmlspecialcharsEx($_REQUEST["action"]);
$id = htmlspecialcharsEx($_REQUEST["ID"]);
$name = htmlspecialcharsEx($_REQUEST["name"]);
$code = htmlspecialcharsEx($_REQUEST["code"]);
$base = htmlspecialcharsEx($_REQUEST["base"]);
$apply = htmlspecialcharsEx($_REQUEST["apply"]);
$save = htmlspecialcharsEx($_REQUEST["save"]);
if(is_array($_REQUEST["groups"]))
    $group = $_REQUEST["groups"];

if($action=='edit' && $id>0 && ($apply || $save))
{
    CMarketPrice::Update($id, $name, $code, $base, $group);    
}

if($action=='add'&& ($apply || $save))
{
    CMarketPrice::Add($name, $code, $base, $group);

}

if($save) Localredirect("/bitrix/admin/yci_market_price.php?lang=".LANG."");


$aMenu = array();

$aMenu[] = array(
		"TEXT" => GetMessage("LIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/yci_market_price.php?lang=".LANG."",
            );


$aMenu[] = array(
		"TEXT" => GetMessage("ADD"),
		"ICON" => "btn_new",
		"LINK" => "/bitrix/admin/yci_market_price_edit.php?lang=".LANG."&action=add",
            );


$context = new CAdminContextMenu($aMenu);
$context->Show();


$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("EDITING"), "ICON" => "catalog", "TITLE" => "")
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();



$tabControl->BeginNextTab();
?>
<form method="POST">
<?if($id):?>
<?
    $res = CMarketPrice::GetByID($id);
    $price = $res->GetNext();
    $groups = CMarketPrice::GetPriceGroup($id);    
?>

                <tr>
			<td width="40%">ID:</td>
			<td width="60%"><?echo $id ?></td>
		</tr>
<?endif?>
                
<?
$dballgroups = CGroup::GetList(($by="sort"), ($order="desc"), array());
?>
                <tr>
			<td width="40%"><?=GetMessage("BASE")?>:</td>
			<td width="60%"><input type="checkbox" value="Y" <?=($price["base"]=='Y')?"checked='checked'":"";?> name="base"/></td>
		</tr>

                <tr>
			<td width="40%"><?=GetMessage("NAME")?>:</td>
			<td width="60%"><input type="text" value="<?=$price["name"]?>" name="name"/></td>
		</tr>


                <tr>
			<td width="40%"><?=GetMessage("CODE")?>:</td>
			<td width="60%"><input type="text" value="<?=$price["code"]?>" name="code"/></td>
		</tr>
                <tr>
			<td width="40%"><?=GetMessage("GROUPS")?>:</td>
			<td width="60%">
                            <select name="groups[]" multiple style="height: 150px;">
                                <? while($allgroups = $dballgroups->GetNext()): ?>
                                <? $selected = in_array($allgroups["ID"],$groups)?"selected='selected'":""; ?>
                                <option value="<?=$allgroups["ID"]?>" <?=$selected?> ><?=$allgroups["NAME"]?></option>
                                <? endwhile;?>
                            </select>                            
                        </td>
		</tr>


<?
$tabControl->Buttons(
                    array(
                        "back_url" => "/bitrix/admin/yci_market_price.php?lang=".LANG.""
                    )
	);
?>
</form>

<?
$tabControl->End();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>