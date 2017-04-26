<?php

use Bitrix\Main\Localization\Loc;
use Yenisite\CoreParser\Options;

global $USER;
if (!$USER->IsAdmin())
	return;
$MODULE_ID = "yenisite.turbo";
const moduldeID = 'yenisite.turbo';

Bitrix\Main\Loader::includeModule("iblock");
Bitrix\Main\Loader::includeModule(moduldeID);
Loc::loadLanguageFile(__FILE__);
	
$apply 				= htmlspecialcharsEx($_REQUEST["apply"]);
$save 				= htmlspecialcharsEx($_REQUEST["save"]);
$turbo_shop_name 	= htmlspecialcharsEx($_REQUEST['turbo_shop_name']);
$turbo_interval_min = htmlspecialcharsEx($_REQUEST['turbo_interval_min']);
$turbo_interval_max = htmlspecialcharsEx($_REQUEST['turbo_interval_max']);

$aTabs = array(
		array("DIV" => "options", "TAB" => GetMessage("TURBO_TAB_TITLE"), "ICON" => "catalog", "TITLE" => GetMessage("TURBO_TAB_H1")),
	);
$tabControl = new CAdminTabControl("tabControl", $aTabs);


if(check_bitrix_sessid() && ($apply||$save))
{
	Options::saveConnection(moduldeID);
	Options::saveSearch(moduldeID);

	COption::SetOptionString($MODULE_ID, 'turbo_shop_name', $turbo_shop_name);
	COption::SetOptionString($MODULE_ID, 'turbo_country', $turbo_country);
}
/*
if ($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption("yenisite.turbo");
	}
	else
	{		
		COption::SetOptionString("yenisite.turbo", 'turbo_shop_name', $_REQUEST['turbo_shop_name'], GetMessage('RESIZE_CLASS'));
		print_r($_REQUEST);
		
	}

	if (strlen($Update) > 0 && strlen($_REQUEST["back_url_settings"]) > 0)
	{
		LocalRedirect($_REQUEST["back_url_settings"]);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
	}
}
*/

$turbo_shop_name = COption::GetOptionString($MODULE_ID, 'turbo_shop_name', '');
$turbo_interval_min = COption::GetOptionString($MODULE_ID, 'turbo_interval_min', '');
$turbo_interval_max = COption::GetOptionString($MODULE_ID, 'turbo_interval_max', '');
$turbo_country = COption::GetOptionString($MODULE_ID, 'turbo_country', 'ru');
$proxy_ip = COption::GetOptionString(moduldeID, 'proxy_ip', '');
$exception_words = COption::GetOptionString(moduldeID, 'exception_words', '');

$tabControl->Begin();
?>
<?$tabControl->BeginNextTab();?>

	<!--<form method="post" name="intr_opt_form" action="/bitrix/admin/settings.php?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">-->
	<form method="post">
		<?=bitrix_sessid_post();?>

		<tr>
			<td class="column-name"><?=GetMessage('TURBO_SHOP_NAME')?>: </td>
			<td class="column-value" ><input style="width: 200px;"  type="text" name="turbo_shop_name" value="<?=htmlspecialcharsEx($turbo_shop_name);?>" /></td>
		</tr>
		
		<tr>
			<td class="column-name"><?=GetMessage('TURBO_COUNTRY')?><span class="required"><sup><?=Options::addNote(Loc::getMessage('TURBO_COUNTRY_TOOLTIP'))?></sup></span>: </td>
			<td class="column-value" >
				<select name="turbo_country">
						<!--<option value="">---</option>-->
						<option value="ru" <?=$turbo_country=='ru'?'selected="selected"':'';?> ><?=GetMessage("RU")?></option>
						<option value="ua" <?=$turbo_country=='ua'?'selected="selected"':'';?> ><?=GetMessage("UA")?></option>
						<option value="by" <?=$turbo_country=='by'?'selected="selected"':'';?> ><?=GetMessage("BY")?></option>
						<option value="kz" <?=$turbo_country=='kz'?'selected="selected"':'';?> ><?=GetMessage("KZ")?></option>
				</select>
			</td>
		</tr>
		<? include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/yenisite.coreparser/options_connection.php' ?>
		<? include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/yenisite.coreparser/options_search.php' ?>
		<? $tabControl->Buttons(array()) ?>

	</form>

<?$tabControl->End();?>
<?echo BeginNote();?>
<? foreach (Options::getNotes() as $key => $note): ?>
<span class="required"><sup><?=$key?></sup></span> <?=$note?><br>
<? endforeach ?>
<?echo EndNote();?>