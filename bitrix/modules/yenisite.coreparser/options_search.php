<?php

use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$brands = COption::GetOptionString(moduldeID, 'brands', 'N');
$exception_words = COption::GetOptionString(moduldeID, 'exception_words', '');
?>

	<tr class="heading">
		<td colspan="2"><b><?=Loc::getMessage("MAIN_OPTIONS_SEARCH")?></b></td>
	</tr>
	<tr>
		<td class="column-name"><?=Loc::getMessage('SPEC_BRANDS')?>: </td>
		<td class="column-value">
			<select name="brands">
				<option value='N'<?if($brands!='Y'):?> selected="selected"<?endif?>><?=Loc::getMessage('SPEC_BRANDS_N_TITLE')?></option>
				<option value='Y'<?if($brands=='Y'):?> selected="selected"<?endif?>><?=Loc::getMessage('SPEC_BRANDS_Y_TITLE')?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="column-name">
			<?=Loc::getMessage('SPEC_EXEPTION_WORDS')
			?><span class="required"><sup><?=\Yenisite\CoreParser\Options::addNote(Loc::getMessage('SPEC_EXEPTION_WORDS_TIP'))?></sup></span>:
		</td>
		<td class="column-value" ><textarea rows="6" cols="45" name="exception_words"><?=$exception_words?></textarea></td>
	</tr>