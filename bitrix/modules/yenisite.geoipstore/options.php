<?
$MODULE_ID = "yenisite.geoipstore";

CModule::IncludeModule("catalog");
CModule::IncludeModule($MODULE_ID);

IncludeModuleLangFile(__FILE__);

global $USER;
if(!$USER->IsAdmin())
	return;

$apply	= htmlspecialcharsEx($_REQUEST["apply"]);
$save 	= htmlspecialcharsEx($_REQUEST["save"]);
$item_def 	 = htmlspecialcharsEx($_REQUEST["item_default"]);
$store_def 	 = htmlspecialcharsEx($_REQUEST["store_default"]);
$is_redirect = htmlspecialcharsEx($_REQUEST["is_redirect"]);

$aTabs = array(
	array("DIV" => "options", "TAB" => GetMessage("OPTIONS"), "ICON" => "catalog", "TITLE" => ""),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if(check_bitrix_sessid() && ($apply||$save))
{
	CYSGeoIPStore::SetDefaultItem($item_def);
	//CYSGeoIPStore::SetDefaultStore($item_def, $store_def);

	COption::SetOptionString($MODULE_ID, 'is_redirect', $is_redirect);
}

$tabControl->Begin();
?>
<?$tabControl->BeginNextTab();?>

<form method="post">
	<?=bitrix_sessid_post();?>
	<tr>
		<td class="column-name"><?=GetMessage("ITEM_DEFAULT")?>:</td>
		<td class="column-value">
			<?$arDef = CYSGeoIPStore::GetDefaultItem()->Fetch();?>
			<?$dbRes = CYSGeoIPStore::GetList();?>
			<select name="item_default">
				<?while($arRes = $dbRes->GetNext()):?>
					<option value="<?=$arRes['ID']?>" <?if($arRes['ID'] == $arDef['ID']) echo 'selected="selected"';?>>
						<?=$arRes['NAME'];?>
					</option>
				<?endwhile?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("REDIRECT")?>:</td>
		<td class="column-value">
			<?$redirect = COption::GetOptionString($MODULE_ID, 'is_redirect');?>
			<input type="checkbox" name="is_redirect" value="Y" <?if($redirect == "Y") echo 'checked="checked"';?> />
		</td>
	</tr>

	<?$tabControl->Buttons(array());?>
</form>

<?
$tabControl->End();
?>