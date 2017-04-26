<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

?>

	<form action="<?=$APPLICATION->GetCurPage(); ?>">
		<? echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" => Loc::getMessage("MOD_UNINST_IMPOSSIBLE"), "DETAILS"=>$errors, "HTML"=>true)); ?>

		<input type="hidden" name="lang" value="<?=LANG; ?>">
		<input type="submit" name="inst" value="<?=GetMessage('MOD_UNINST_BACK'); ?>">
	<form>
