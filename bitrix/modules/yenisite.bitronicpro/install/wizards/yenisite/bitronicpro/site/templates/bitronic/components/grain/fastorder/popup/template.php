<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if($arParams["ONLY_PROCESSING"]=="Y") return;

?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
	<!--<a class="close sym" href="javascript:void(0);" title="<?=GetMessage('CLOSE')?>">&#206;</a>-->
    <a href="javascript:void(0);" class="ys-feedback_add_popup_close">&#205;</a>
	<h2><?=GetMessage('POPUP_TITLE_IN_ONE_CLICK');?></h2>
	<div class="pad">	
		<div class="req_block">
			<span style="color: red">*</span>
				<?=GetMessage("REQUIRED");?>
		</div>
		<form action="<?=$APPLICATION->GetCurPage()?>" method="POST" id="fastorder_form">
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
			<input type="hidden" name="PRODUCT_ID" value="<?=$arParams["PRODUCT_ID"][0]?>">
			<input type="submit" style='display: none;' class='button' name="submit" value="<?=GetMessage("GRAIN_FO_SUBMIT")?>">
            <button class='button' type='submit'>
                    <span class='text show'><?=GetMessage("GRAIN_FO_SUBMIT")?></span>
                    <span class='notloader'></span>
            </button>
		</form>
<?
if($arParams["ONLY_SHOW_FORM"]!="Y")
    {

//    echo "<script>jGrowl('".$arParams["OK_TEXT"]."','ok');</script>";
	/*if($arResult["SUCCESS"]) {
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
	}*/
    
    if($arResult["SUCCESS"])
    {
        echo "<span class='success' style='display: none;'>" . $arParams['OK_TEXT'] . "</span>";
    }
    
    if(!empty($arResult["ERROR_MESSAGE"]))
	{
        foreach($arResult["ERROR_MESSAGE"] as $v)
            echo "<span class='error_message' style='display: none;'>" . $v . "</span>";
        
    }

}
?>
	</div>
<!--</div>--><!--.popup-->

<style>
a.ys-feedback_add_popup_close {
    border-bottom: medium none !important;
    font-family: WebSymbolsLigaRegular !important;
    font-size: 12px !important;
    position: absolute !important;
    right: 5px !important;
    top: 2px !important;
}
</style>

<script>
$(document).ready(function(){
if (typeof Tipped == "object")
{
    Tipped.create('.ys-feedback_add_popup_close', '<?=GetMessage('CLOSE');?>');
    $('a.ys-feedback_add_popup_close').click(function(){
            $('.qtip').remove();
            $('#mask').click();
        });
}
});
</script>
