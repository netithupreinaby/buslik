<?if(!check_bitrix_sessid()) return false;
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("MOD_INST_ERR"), "DETAILS"=>$errors, "HTML"=>true));
	?>
	<form action="<?echo $APPLICATION->GetCurPage()?>">
		<input type="hidden" name="lang" value="<?echo LANGUAGE_ID; ?>">
		<input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
	<form>