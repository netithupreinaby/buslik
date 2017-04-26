<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="mfeedback">
<?if(!empty($arResult["ERROR_MESSAGE"]))
{
	//$this->SetViewTarget('errors');		OLD ERROR MESSAGE (04.03.2013)
	//echo GetMessage('ERROR');
	//$this->EndViewTarget();
	foreach($arResult["ERROR_MESSAGE"] as $v)
		if(!empty($v))
			print_r( "<script>jGrowl('".$v."','error');</script>");
}
if(!empty($arResult["OK_MESSAGE"]))
{
	print_r( "<script>jGrowl('".$arResult["OK_MESSAGE"]."','ok');</script>");
}

?>

<form action="<?=$APPLICATION->GetCurPage()?>" method="POST">
<?=bitrix_sessid_post()?>
<input type='hidden' name='error' value='Y' />
	<div class="mf-name">
		<div class="mf-text">
			<label><?=GetMessage("MFT_NAME")?></label><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<input style="width: 400px;" type="text" class="txt" name="user_name" value="<?=$arResult["AUTHOR_NAME"]?>">
	</div>
	<!--<div class="mf-email">
		<div class="mf-text">
			<label><?=GetMessage("MFT_EMAIL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<input class="txt" style="width: 400px;" type="text" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>">

	</div>-->
	<div class="mf-message">
		<div class="mf-text">
			<label><?=GetMessage("MFT_EMAIL")?></label><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<!--<textarea style="width: 400px;" class="txt" name="MESSAGE" rows="5" cols="40"><?=$arResult["MESSAGE"]?></textarea>-->
		<input class="txt" style="width: 400px;" type="text"  name="MESSAGE"   value="<?=$arResult["MESSAGE"]?>">
	</div>

	<?if($arParams["USE_CAPTCHA"] == "Y"):?>
	<div class="mf-captcha">
		<div class="mf-text"><?=GetMessage("MFT_CAPTCHA")?></div>
		<input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
		<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
		<div class="mf-text"><?=GetMessage("MFT_CAPTCHA_CODE")?><span class="mf-req">*</span></div>
		<input class="txt" type="text" name="captcha_word" size="30" maxlength="50" value="">
	</div>
	<?endif;?>
	
	<input class='button' type="submit" name="submit" value="<?=GetMessage("MFT_SUBMIT")?>">
</form>
</div>