<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

CJSCore::RegisterExt('script_fastorder', array(	
'js' 	=> SITE_TEMPLATE_PATH.'/components/grain/fastorder/bitronic/script_fastorder.js',
'lang' 	=> SITE_TEMPLATE_PATH.'/components/grain/fastorder/bitronic/lang/'.LANGUAGE_ID.'/template.php',
));

CJSCore::Init(array('script_fastorder'));

if($arParams["ONLY_SHOW_FORM"]!="Y") {

	if($arResult["SUCCESS"]) {
	    LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["HASH"], Array("success")));
	} elseif($_REQUEST["success"] == $arResult["HASH"]) {
		//echo $arParams["OK_TEXT"];
		print_r( "<script>jGrowl('".$arParams["OK_TEXT"]."','ok');</script>");
	}
	
	if(!empty($arResult["ERROR_MESSAGE"]))
	{
		foreach($arResult["ERROR_MESSAGE"] as $v)
			print_r( "<script>jGrowl('".$v."','error');</script>");
			//ShowError($v);
	}

}

if($arParams["ONLY_PROCESSING"]=="Y") return;

?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="popup" id="add-popup-buy1click" style="display:none;">
	<a class="close sym" href="javascript:void(0);">&#206;</a>
	<h2><?=GetMessage('POPUP_TITLE_IN_ONE_CLICK');?></h2>
	<div class="pad">	
		<form id='form_fastorder' action="<?=$APPLICATION->GetCurPage()?>" method="POST">
			<?=bitrix_sessid_post();?>
			<?if(in_array("NAME",$arParams["SHOW_FIELDS"])):?>
				<div class="grain-fo-name">
					<div class="grain-fo-text">
						<?=GetMessage("GRAIN_FO_NAME")?><?if(in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="grain-fo-req">*</span><?endif?>
					</div>
					<input type="text" class="txt" name="FASTORDER_NAME" value="<?=$arResult["NAME"]?>">
				</div>
			<?endif?>
			<?if(in_array("EMAIL",$arParams["SHOW_FIELDS"])):?>
				<div class="grain-fo-email">
					<div class="grain-fo-text">
						<?=GetMessage("GRAIN_FO_EMAIL")?><?if(in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?><span class="grain-fo-req">*</span><?endif?>
					</div>
					<input type="text" class="txt" name="FASTORDER_EMAIL" value="<?=$arResult["EMAIL"]?>">
				</div>
			<?endif?>
			<?if(in_array("PHONE",$arParams["SHOW_FIELDS"])):?>
				<div class="grain-fo-phone">
					<div class="grain-fo-text">
						<?=GetMessage("GRAIN_FO_PHONE")?><?if(in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?><span class="grain-fo-req">*</span><?endif?>
					</div>
					<input type="text" class="txt" name="FASTORDER_PHONE" value="<?=$arResult["PHONE"]?>">
				</div>
			<?endif?>

			<?if($arParams["USE_CAPTCHA"] == "Y"):?>
			<div class="grain-fo-captcha">
				<div class="grain-fo-text"><?=GetMessage("GRAIN_FO_CAPTCHA")?></div>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
				<div class="grain-fo-text"><?=GetMessage("GRAIN_FO_CAPTCHA_CODE")?><span class="grain-fo-req">*</span></div>
				<input type="text" class="txt" name="captcha_word" size="30" maxlength="50" value="">
			</div>
			<?endif;?>
			<input type="hidden" name="HASH" value="<?=$arResult["HASH"]?>">
			<input type="submit" class='button' name="submit_fastorder" value="<?=GetMessage("GRAIN_FO_SUBMIT")?>">
		</form>
	</div>
</div><!--.popup-->
