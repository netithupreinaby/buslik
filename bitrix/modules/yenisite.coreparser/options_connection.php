<?php

use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$proxy_ip = COption::GetOptionString(moduldeID, 'proxy_ip', '');
$proxy_retries = COption::GetOptionInt(moduldeID, 'proxy_retries', 5);
$proxy_timeout = COption::GetOptionInt(moduldeID, 'proxy_timeout', 5);

$interval_min = COption::GetOptionString(moduldeID, 'interval_min', '12');
$interval_max = COption::GetOptionString(moduldeID, 'interval_max', '18');
?>

	<tr class="heading">
		<td colspan="2"><b><?=Loc::getMessage("MAIN_OPTIONS_CONNECTION")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=Loc::getMessage('SPEC_PROXY_IP')?>: </td>
		<td class="column-value" ><textarea rows="5" cols="45" name="proxy_ip"><?=$proxy_ip?></textarea></td>
	</tr>
	<tr>
		<td class="column-name"><?=Loc::getMessage('SPEC_PROXY_RETRIES')?></td>
		<td class="column-value" ><input type="text" name="proxy_retries" maxlength="2" value="<?=htmlspecialchars($proxy_retries)?>"></td>
	</tr>
	<tr>
		<td class="column-name"><?=Loc::getMessage('SPEC_PROXY_TIMEOUT')?></td>
		<td class="column-value" ><input type="text" name="proxy_timeout" maxlength="2" value="<?=htmlspecialchars($proxy_timeout)?>"></td>
	</tr>
	<tr>
		<td class="column-name"><?=Loc::getMessage('SPEC_TIMEOUT_INTERVAL')?>: </td>
		<td class="column-value" >
			<input type="text" name="interval_min" size="1" maxlength='2' value="<?=htmlspecialchars($interval_min);?>" />
			-
			<input type="text" name="interval_max" size="1" maxlength='2' value="<?=htmlspecialchars($interval_max);?>" />
		</td>
	</tr>