<?
$module_id = "sebekon.deliveryprice";

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
IncludeModuleLangFile(__FILE__);

$ADV_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($ADV_RIGHT>="R") :


$arAllOptions = array(
	array("DP_DECIMAL_POINT", 			GetMessage("sebekon_DP_DECIMAL_POINT"), 		Array("text", 30)),
	array("DP_THOUSAND_POINT", 			GetMessage("sebekon_DP_THOUSAND_POINT"),		Array("text", 30)),
	array("DP_DECIMAL_CNT", 			GetMessage("sebekon_DP_DECIMAL_CNT"), 			Array("text", 30)),
	array("DP_DEC_CNT", 				GetMessage("sebekon_DP_DEC_CNT"), 				Array("text", 30)),
	array("DP_ORDER_PAGE", 				GetMessage("sebekon_DP_ORDER_PAGE"), 			Array("text", 30)),
	array("DP_DISABLE_JQUERY_ADMIN",	GetMessage("sebekon_DP_DISABLE_JQUERY_ADMIN"),	Array("checkbox", 30)),
	array("DP_DISABLE_JQUERY",			GetMessage("sebekon_DP_DISABLE_JQUERY"),		Array("checkbox", 30)),
	array("DP_DISABLE_BOOTSRAP",		GetMessage("sebekon_DP_DISABLE_BOOTSRAP"),		Array("checkbox", 30)),
	array("DP_DISABLE_BOOTSRAP_MODAL",	GetMessage("sebekon_DP_DISABLE_BOOTSRAP_MODAL"),		Array("checkbox", 30)),
	array("DP_DISABLE_BOOTSRAP_TOOLTIP",GetMessage("sebekon_DP_DISABLE_BOOTSRAP_TOOLTIP"),		Array("checkbox", 30)),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "ad_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET"))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply)>0 && $ADV_RIGHT>="W" && check_bitrix_sessid())
{
	for($i=0; $i<count($arAllOptions); $i++)
	{
		$name=$arAllOptions[$i][0];
		$val=$$name;
		if($arAllOptions[$i][2][0]=="checkbox" && $val!="Y") $val="N";
		COption::SetOptionString($module_id, $name, $val);
	}

	$Update = $Update.$Apply;
	ob_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	ob_end_clean();

	if($Apply == '' && $_REQUEST["back_url_settings"] <> '')
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}
?>
<?
$tabControl->Begin();
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsEx($mid)?>&lang=<?=LANGUAGE_ID?>">
<?
$tabControl->BeginNextTab();
?>
	<?
	for($i=0; $i<count($arAllOptions); $i++):
		$Option = $arAllOptions[$i];
		$val = COption::GetOptionString($module_id, $Option[0]);
		$type = $Option[2];
	?>
		<tr>
			<td valign="top" width="50%">
				<?	
				if($type[0]=="checkbox")
					echo "<label for=\"".htmlspecialcharsEx($Option[0])."\">".htmlspecialcharsEx($Option[1])."</label>";
				else
					echo htmlspecialcharsEx($Option[1]);?>
			</td>
			<td valign="top" width="50%">
				<?if($type[0]=="checkbox"):?>
					<input type="checkbox" name="<?echo htmlspecialcharsEx($Option[0])?>" id="<?echo htmlspecialcharsEx($Option[0])?>" value="Y"<?if($val=="Y")echo " checked";?>>
				<?elseif($type[0]=="text"):?>
					<input type="text" size="<?echo intval($type[1])?>" maxlength="255" value="<?echo htmlspecialcharsEx($val)?>" name="<?echo htmlspecialcharsEx($Option[0])?>">
					<?if (strlen($Option[3])>0):?>
						&nbsp;<label for="<?echo htmlspecialcharsEx($Option[0])?>_clear"><?=GetMessage("AD_CLEAR")?>:</label><input type="checkbox" name="<?echo htmlspecialcharsEx($Option[0])?>_clear" id="<?echo htmlspecialcharsEx($Option[0])?>_clear" value="Y">
					<?endif;?>						
				<?elseif($type[0]=="textarea"):?>
					<textarea rows="<?echo intval($type[1])?>" cols="<?echo intval($type[2])?>" name="<?echo htmlspecialcharsEx($Option[0])?>"><?echo htmlspecialcharsEx($val)?></textarea>
				<?endif?>
			</td>
		</tr>
	<?
	endfor;
	?>

<?$tabControl->Buttons();?>
<script type="text/javascript">
function RestoreDefaults()
{
	if(confirm('<?echo AddSlashes(GetMessage("SEBEKON_DELIVERYPRICE_MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
		window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?=LANGUAGE_ID?>&mid=<?echo urlencode($mid)?>&<?echo bitrix_sessid_get()?>";
}
</script>
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
	<input type="submit" name="Update" value="<?=GetMessage("sebekon_DP_MAIN_SAVE")?>" title="<?=GetMessage("sebekon_DP_MAIN_SAVE")?>"<?if ($ADV_RIGHT<"W") echo " disabled" ?>>
	<?endif?>
	<input type="submit" name="Apply" value="<?=GetMessage("sebekon_DP_MAIN_OPT_APPLY")?>" title="<?=GetMessage("sebekon_DP_MAIN_OPT_APPLY")?>"<?if ($ADV_RIGHT<"W") echo " disabled" ?>>
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=GetMessage("sebekon_DP_MAIN_OPT_CANCEL")?>" title="<?=GetMessage("sebekon_DP_MAIN_OPT_CANCEL")?>" onclick="window.location='<?echo htmlspecialcharsEx(CUtil::JSEscape($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsEx($_REQUEST["back_url_settings"])?>">
	<?endif?>
	<input type="button" title="<?echo GetMessage("SEBEKON_DELIVERYPRICE_MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>"<?if ($ADV_RIGHT<"W") echo " disabled" ?>>
	<?=bitrix_sessid_post();?>

<?$tabControl->End();?>
</form>
<?endif;?>
