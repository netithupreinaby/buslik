<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
if ($arParams['CODE'] == 'ZIP') CUtil::InitJSCore(array('ajax'));


$main_tag_param = '';
if ($arParams['CODE'] == 'CITY') $main_tag_param = ' id="citynew" style="display: none;"';

$name_tag_param = ' style="vertical-align: middle;"';
if ($arParams['CODE'] == 'LOCATION')
	if ($arParams['EDOST_CATALOGDELIVERY'] != 'Y') $name_tag_param = ' style="padding-top: 10px;"';
	else $name_tag_param = ' style="padding-top: 5px;"';

if ($arParams['IN_TABLE'] == 'Y') {
	$main_tag = '<tr'.$main_tag_param.'>';
	$main_tag_end = '</tr>';

	$name_tag = '<td class="name" valign="top" align="right" '.$name_tag_param.'>';
	$name_tag_end = '</td>';

	$field_tag = '<td id="edost_'.$arParams['CODE'].'_field">';
	$field_tag_end = '</td>';
}
else {
	if ($main_tag_param != '') {
		$main_tag = '<span '.$main_tag_param.'>';
		$main_tag_end = '</span>';
	}
	else {
		$main_tag = '';
		$main_tag_end = '';
	}

	$name_tag = '';
	$name_tag_end = '<br>';

	$field_tag = '';
	if ($arParams['CODE'] == 'LOCATION') $field_tag_end = '<br>'; else $field_tag_end = '<br><br>';
}


$name = ($arParams["REQUIED_FORMATED"] == "Y" ? '<span class="starrequired">*</span>' : '').$arParams["NAME"].($arParams["REQUIED_FORMATED"] != "NO_CHANGE" ? ':' : '');
?>

<?=$main_tag?>
	<?=$name_tag?><?=$name?><?=$name_tag_end?>
	<?=$field_tag?>

<?
if ($arParams['CODE'] == 'ZIP') {
?>
		<input style="vertical-align: middle; width: 60px;" type="text" maxlength="6" size="6" value="<?=$arParams["VALUE"]?>" name="<?=$arParams["FIELD_NAME"]?>" id="<?=$arParams["FIELD_NAME"]?>" <?=($arParams['ZIP_CHECKING'] == 'Y' ? "onfocus=\"edost_CheckZIP('start'); edost_zip_timer = window.setInterval('edost_CheckZIP()',200);\" onblur=\"edost_zip_timer = window.clearInterval(edost_zip_timer);\"" : "")?>>
		<span id="zip_info" name="zip_info" style="padding: 0px 0px 0px 5px; vertical-align: middle; display: inline;"></span>
		<input id="zip_input" value="<?=$arParams["FIELD_NAME"]?>" type="hidden">
		<input id="zip_value" value="<?=$arParams["VALUE"]?>" type="hidden">
<?
}
else if ($arParams['CODE'] == 'CITY') {
	if ($arParams["VALUE"] == '&nbsp;') $arParams["VALUE"] = '';
?>
		<input type="text" maxlength="250" size="20" value="<?=$arParams["VALUE"]?>" name="<?=$arParams["FIELD_NAME"]?>" id="<?=$arParams["FIELD_NAME"]?>" style="width:300px;">
		<input id="city_input" value="<?=$arParams["FIELD_NAME"]?>" type="hidden">
<?
}
else if ($arParams['CODE'] == "LOCATION") {
?>

<script language=javascript>
	function edost_SetHTML(name, c, s) {
		var E = document.getElementById(name);
		if (E) {
			if (c == 'loading') E.innerHTML = '<img style="vertical-align: middle;" src="<?=$arResult["component_path"]?>/images/loading_small.gif" border="0" height="20" width="20"><font color="#888888"><b> ' + s + '</b></font>';
			else if (c == 'error') E.innerHTML = '<font style="font-size: 11px; color: #FF0000;"><b>' + s + '</b></font>';
			else if (c == 'ok') E.innerHTML = '<font color="#00AA00"><b>' + s + '</b></font>';
<? if ($arResult['zip_help']['link'] != '') { ?>
			else if (name == 'zip_info' && edost_zip_status != 'disable') E.innerHTML = '<a href="<?=$arResult['zip_help']['link']?>" target="_blank" style="cursor: pointer; font-size: 11px; text-decoration: none;"><?=$arResult['zip_help']['name']?></a>';
<? } ?>
			else E.innerHTML = '';

			if (name == 'location_info') E.style.display = 'block';
		}
	}
</script>

<?=$arResult["JS"]?>

<?
	if (count($arResult["cityUp"]) > 0 && ($arParams['CITY_NOW_ID'] <= 0 || $arResult["cityUpActive"])) {
?>
		<style>
			input.edostu { height: 21px; cursor: pointer; vertical-align: middle; padding: 0px; margin: 0px;}

			p.edostu { height: 21px;  cursor: pointer; display: inline; padding: 1px; margin: 0px; font-family: Arial; font-size: 13px;}
			p.edostu2 { color: #AAAAAA; }
			p.edostu3 { font-weight: bold; }

			p.edostub { height: 21px;  cursor: pointer; display: inline; padding: 1px; margin: 0px; color: #000099; font-family: Arial; font-size: 13px;}
			p.edostub2 { color: #8888AA; }
		</style>

		<table cellpadding="2" cellspacing="0" border="0">
<?
		$TR_open = false;
		$n = 0;
		foreach ($arResult["cityUp"] as $ar) if (($arResult["cityUpActive"] && $ar['ID'] == $arParams['CITY_NOW_ID']) || $arResult["cityUpActive"] != true) {
			$n++;

			if ($n & 1) {
?>
			<tr>
<?
				$TR_open = true;
			}

			if ($ar['ID'] > 0) {
?>
				<td width="15">
					<input name="edostUp" id="edostUp<?=$n?>" value="<?=$ar['ID']?>" type="radio" <?=($arResult["cityUpActive"] ? 'checked="checked"' : '')?> class="edostu" onclick="edost_SetUp(this.value);">
				</td>
				<td>
					<label for="edostUp<?=$n?>">
						<p class="edostu<?=($arResult["cityUpActive"] ? ' edostu3' : '')?>" id="edostUpName<?=$n?>"><?=$ar['name']?></p>
					</label>
				</td>
<?
			}
			else {
?>
				<td width="15"></td><td></td>
<?
			}

			if ($n & 1) {
?>
				<td width="30"></td>
<?			}
			else {
?>
   	    	</tr>
<?
				$TR_open = false;
			}
		}
		if ($TR_open) {
?>
			</tr>
<?
		}
?>
			<tr>
				<td width="15">
					<input name="edostUp" id="edostUp0" value="-1" type="radio" class="edostu" onclick="edost_SetUp(this.value);">
				</td>
				<td>
					<label for="edostUp0">
						<p class="edostub" id="edostUpName0"><?=$arResult["select"]?></p>
					</label>
				</td>
			</tr>
		</table>
<?
	}
?>
		<span id="location_1" style="display: none;"></span>
		<span id="location_2" style="display: none; padding-top: 3px;"></span>
		<span id="location_3" style="display: none; padding-top: 3px;"></span>
		<span id="location_info" style="display: none; padding-top: 3px;"></span>
		<input id="location_set" value="<?=$arResult['set_location']?>" type="hidden">
		<input name="<?=$arParams["FIELD_NAME"]?>" id="<?=$arParams["FIELD_NAME"]?>" value="<?=$arParams['CITY_NOW_ID']?>" type="hidden">
		<input id="location_input" value="<?=$arParams["FIELD_NAME"]?>" type="hidden">
		<input id="location_value" value="<?=$arParams['CITY_NOW_ID']?>" type="hidden">
<?
}

if ($arParams['EDOST_CATALOGDELIVERY'] != 'Y' && (($arParams['CODE'] == 'CITY' && $arParams['CITY_ACTIVE'] == 'Y') || ($arParams['CODE'] == "LOCATION" && $arParams['CITY_ACTIVE'] != 'Y'))) {
?>
<script language=javascript>
	edost_SetLocation(-2);
	edost_SetHTML('zip_info', '');
</script>
<?
}
?>

	<?=$field_tag_end?>
<?=$main_tag_end?>
