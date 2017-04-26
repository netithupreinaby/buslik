<style type="text/css">
	.column-name {
		width: 50%;  
	}
	.column-value {
		width: 50%;
	}
	.info {
		color: #C44;
	}
</style>
<?php

use Bitrix\Main\Localization\Loc;
use Yenisite\CoreParser\Options;

global $USER;
if (!$USER->IsAdmin())
	return;

Loc::loadLanguageFile(__FILE__);

const moduldeID = 'yenisite.specifications';
CModule::IncludeModule(moduldeID);

CJSCore::RegisterExt(
	'ys_spec_options',
	array(
		'js'   => '/bitrix/js/' . moduldeID . '/options.js',
		'lang' => '/bitrix/modules/' . moduldeID . '/lang/' . LANGUAGE_ID . '/options-js.php',
		'rel'  => array('jquery'),
		)
);

CJSCore::Init('ys_spec_options');

$arParseSelectValues = array(
	1 => GetMessage("SPEC_PARSE_IMAGES"),
	2 => GetMessage("SPEC_PARSE_PROPERTIES"),
	4 => GetMessage("SPEC_PARSE_DESCRIPTION"),
	3 => GetMessage("SPEC_PARSE_IMG_n_PROP"),
	5 => GetMessage("SPEC_PARSE_IMG_n_DESCR"),
	6 => GetMessage("SPEC_PARSE_PROP_n_DESCR"),
	255 => GetMessage("SPEC_PARSE_ALL")
);

$arSaveTextSelectValues = array(
	1 => GetMessage('SPEC_SAVE_DETAIL_TEXT'),
	2 => GetMessage('SPEC_SAVE_PREVIEW_TEXT'),
	255 => GetMessage('SPEC_SAVE_BOTH')
);

	
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage(      "MAIN_TAB_SET"), "ICON" => "form_settings", "TITLE" => GetMessage(      "MAIN_TAB_TITLE_SET")),
	array("DIV" => "edit2", "TAB" => GetMessage("EXPERIMENT_TAB_SET"), "ICON" => "form_settings", "TITLE" => GetMessage("EXPERIMENT_TAB_TITLE_SET"))
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$what_to_parse = intval($_REQUEST['what_to_parse']);
$where_save_text = intval($_REQUEST['where_save_text']);

if ($REQUEST_METHOD=="POST" && strlen($_REQUEST['save'].$_REQUEST['apply'].$RestoreDefaults) > 0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption(moduldeID);
	}
	else
	{
		Options::saveConnection(moduldeID);
		Options::saveSearch(moduldeID);

		COption::SetOptionString(moduldeID, 'property_by_section',      $_REQUEST['property_by_section'], GetMessage('SPEC_PROPERTY_BY_SECTION_TIP'));
		COption::SetOptionString(moduldeID, 'group_by_section',         $_REQUEST['group_by_section'],    GetMessage('SPEC_GROUP_BY_SECTION'));
		COption::SetOptionString(moduldeID, 'yml_property',             $_REQUEST['yml_property'],        GetMessage('SPEC_YML_PROPERTY'));
		COption::SetOptionString(moduldeID, 'name_property',            $_REQUEST['name_property'],       GetMessage('SPEC_NAME_PROPERTY'));
		COption::SetOptionString(moduldeID, 'auto_detect',              $_REQUEST['auto_detect']);
		COption::SetOptionString(moduldeID, 'not_search_again',         $_REQUEST['not_search_again'],    GetMessage('SPEC_NOT_SEARCH_AGAIN'));
		COption::SetOptionString(moduldeID, 'not_search_section',       $_REQUEST['not_search_section'],  GetMessage('SPEC_NOT_SEARCH_SECTION'));
		COption::SetOptionString(moduldeID, 'only_active_props',        $_REQUEST['only_active_props'],   GetMessage('SPEC_ONLY_ACTIVE_PROPS'));
		COption::SetOptionString(moduldeID, 'use_in_smart_filter',      $_REQUEST['use_in_smart_filter'], GetMessage('SPEC_USE_IN_SMART_FILTER'));
		COption::SetOptionString(moduldeID, 'rewrite_props',            $_REQUEST['rewrite_props'],       GetMessage('SPEC_USE_IN_SMART_FILTER'));
		COption::SetOptionString(moduldeID, 'detail_picture',           $_REQUEST['detail_picture']);
		COption::SetOptionString(moduldeID, 'detail_picture_overwrite', $_REQUEST['detail_picture_overwrite']);
		COption::SetOptionString(moduldeID, 'property_prefix',          $_REQUEST['property_prefix'],     GetMessage('SPEC_PROPERTY_PREFIX'));
		COption::SetOptionString(moduldeID, 'measure_check',            $_REQUEST['measure_check']);
		COption::SetOptionString(moduldeID, 'measure_types',            $_REQUEST['measure_types']);
		COption::SetOptionString(moduldeID, 'responsibility_agreement', $_REQUEST['responsibility_agreement']);
		COption::SetOptionString(moduldeID, 'getandfill_noid',          $_REQUEST['getandfill_noid']);
		COption::SetOptionString(moduldeID, 'overwrite_text',           $_REQUEST['overwrite_text']);

		if (!array_key_exists($what_to_parse,   $arParseSelectValues))    $what_to_parse = 0;
		if (!array_key_exists($where_save_text, $arSaveTextSelectValues)) $where_save_text = 0;

		if (!empty($what_to_parse))   COption::SetOptionInt(moduldeID, 'what_to_parse',   $what_to_parse);
		if (!empty($where_save_text)) COption::SetOptionInt(moduldeID, 'where_save_text', $where_save_text);
	}

	BXClearCache(true, '/yenisite.specifications/');

	if (strlen($Update) > 0 && strlen($_REQUEST["back_url_settings"]) > 0)
	{
		LocalRedirect($_REQUEST["back_url_settings"]);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
	}
}

$property_by_section = COption::GetOptionString(moduldeID, 'property_by_section', '');
$group_by_section = COption::GetOptionString(moduldeID, 'group_by_section', '');
$what_to_parse = COption::GetOptionInt(moduldeID, 'what_to_parse', 255);
$where_save_text = COption::GetOptionInt(moduldeID, 'where_save_text', 255);
$yml_property = COption::GetOptionString(moduldeID, 'yml_property', 'TURBO_YANDEX_LINK');
$name_property = COption::GetOptionString(moduldeID, 'name_property', '');
$auto_detect = COption::GetOptionString(moduldeID, 'auto_detect', 'N');
$not_search_again = COption::GetOptionString(moduldeID, 'not_search_again', '');
$not_search_section = COption::GetOptionString(moduldeID, 'not_search_section', '');
$only_active_props = COption::GetOptionString(moduldeID, 'only_active_props', '');
$use_in_smart_filter = COption::GetOptionString(moduldeID, 'use_in_smart_filter', 'Y');
$rewrite_props = COption::GetOptionString(moduldeID, 'rewrite_props', 'N');
$detail_picture = COption::GetOptionString(moduldeID, 'detail_picture', 'Y');
$detail_picture_overwrite = COption::GetOptionString(moduldeID, 'detail_picture_overwrite', 'N');
$getandfill_noid = COption::GetOptionString(moduldeID, 'getandfill_noid', 'N');
$overwrite_text = COption::GetOptionString(moduldeID, 'overwrite_text', 'N');

$property_prefix = COption::GetOptionString(moduldeID, 'property_prefix', '');

$measure_check = COption::GetOptionString(moduldeID, 'measure_check', '');
$measure_types = COption::GetOptionString(moduldeID, 'measure_types', '');

$responsibility_agreement = COption::GetOptionString(moduldeID, 'responsibility_agreement', '');
?>

<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?=LANGUAGE_ID?>" id="ys_spec_options_form">
<?=bitrix_sessid_post();?>
<? $tabControl->Begin() ?>
<? $tabControl->BeginNextTab() ?>
<? include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/yenisite.coreparser/options_connection.php' ?>
<? include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/yenisite.coreparser/options_search.php' ?>

	<tr>
		<td class="column-name"><?=GetMessage('SPEC_YML_PROPERTY')?>: </td>
		<td class="column-value" ><input type="text" name="yml_property" value="<?=(htmlspecialchars($yml_property)?:'')?>"></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_NAME_PROPERTY')?>: </td>
		<td class="column-value" ><input type="text" name="name_property" value="<?=(htmlspecialchars($name_property)?:'')?>"></td>
	</tr>
	<tr>
		<td class="column-name">
			<?=GetMessage('SPEC_NOT_SEARCH_AGAIN')?><span class="required"><sup><?=Options::addNote(Loc::getMessage('SPEC_NOT_SEARCH_AGAIN_TIP'))?></sup></span>:
		</td>
		<td class="column-value" ><input type="checkbox" name="not_search_again" value="Y"<?if($not_search_again=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_NOT_SEARCH_SECTION')?>: </td>
		<td class="column-value" ><input type="checkbox" name="not_search_section" value="Y"<?if($not_search_section=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_AUTO_DETECT')?>: </td>
		<td class="column-value" ><input type="checkbox" name="auto_detect" value="Y"<?if($auto_detect=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_GETANDFILL_NOID')?>: </td>
		<td class="column-value" ><input type="checkbox" name="getandfill_noid" value="Y"<?if($getandfill_noid=="Y")echo" checked";?> /></td>
	</tr>

	<tr class="heading">
		<td colspan="2"><b><?=GetMessage("MAIN_OPTIONS_PARSER")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_WHAT_TO_PARSE')?>: </td>
		<td class="column-value"><select name="what_to_parse"><?
		foreach ($arParseSelectValues as $key => $value):?>

			<option value="<?=$key?>"<?if($key==$what_to_parse):?> selected="selected"<?endif?>><?=$value?></option><?
		endforeach?>

		</select></td>
	</tr>

	<tr class="heading">
		<td colspan="2"><b><?=GetMessage("MAIN_OPTIONS_SAVE_PROPS")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_PROPERTY_BY_SECTION')?><span class="required"><sup><?=Options::addNote(Loc::getMessage('SPEC_PROPERTY_BY_SECTION_TIP'))?></sup></span>: </td>
		<td class="column-value" ><input type="checkbox" name="property_by_section" value="Y"<?if($property_by_section=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_GROUP_BY_SECTION')?>: </td>
		<td class="column-value" ><input type="checkbox" name="group_by_section" value="Y"<?if($group_by_section=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_ONLY_ACTIVE_PROPS')?><span class="required"><sup><?=Options::addNote(Loc::getMessage('SPEC_ONLY_ACTIVE_PROPS_TIP'))?></sup></span>: </td>
		<td class="column-value" ><input type="checkbox" name="only_active_props" value="Y"<?if($only_active_props=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_USE_IN_SMART_FILTER')?>: </td>
		<td class="column-value" ><input type="checkbox" name="use_in_smart_filter" value="Y"<?if($use_in_smart_filter=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_REWRITE_PROPS')?>: </td>
		<td class="column-value" ><input type="checkbox" name="rewrite_props" value="Y"<?if($rewrite_props=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_PROPERTY_PREFIX')?>: </td>
		<td class="column-value" ><input type="text" name="property_prefix" value="<?=$property_prefix?>"></td>
	</tr>

	<tr class="heading">
		<td colspan="2"><b><?=GetMessage("MAIN_OPTIONS_SAVE_IMAGES")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_DETAIL_PICTURE')?>: </td>
		<td class="column-value" ><input type="checkbox" name="detail_picture" value="Y"<?if($detail_picture=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_DETAIL_PICTURE_OVERWRITE')?>: </td>
		<td class="column-value" ><input type="checkbox" name="detail_picture_overwrite" value="Y"<?if($detail_picture_overwrite=="Y")echo" checked";?> /></td>
	</tr>

	<tr class="heading">
		<td colspan="2"><b><?=GetMessage("MAIN_OPTIONS_SAVE_TEXT")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_WHERE_TO_SAVE')?>: </td>
		<td class="column-value"><select name="where_save_text"><?
		foreach ($arSaveTextSelectValues as $key => $value):?>

			<option value="<?=$key?>"<?if($key==$where_save_text):?> selected="selected"<?endif?>><?=$value?></option><?
		endforeach?>

		</select></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_OVERWRITE_TEXT')?>: </td>
		<td class="column-value" ><input type="checkbox" name="overwrite_text" value="Y"<?if($overwrite_text=="Y")echo" checked";?> /></td>
	</tr>

<?$tabControl->BeginNextTab();?>

	<tr>
		<td class="column-name"><?=GetMessage('SPEC_MEASURE_CHECK')?>: </td>
		<td class="column-value" ><input type="checkbox" name="measure_check" value="Y"<?if($measure_check=="Y")echo" checked";?> /></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage('SPEC_MEASURE_TYPES')?>: </td>
		<td class="column-value" ><textarea rows="10" cols="45" name="measure_types"><?=$measure_types?></textarea></td>
	</tr>

<?$tabControl->Buttons(array());?>
<?$tabControl->End();?>

<?echo BeginNote();?>
<input type="checkbox" name="responsibility_agreement" value="Y"<?if($responsibility_agreement=="Y")echo" checked"?>/>
<span class="required"><?=GetMessage('ys_spec_notify')?></span><br><br>
<? foreach (Options::getNotes() as $key => $note): ?>
<span class="required"><sup><?=$key?></sup></span> <?=$note?><br>
<? endforeach ?>
<?echo EndNote();?>
</form>