<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin('');
/* <div class="column w20">
</div> */
?>
<form action="<?=$arResult["FORM_ACTION"]?>" id="send-form">
<div style='display: none;'>
<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
	<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
	<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" checked="checked" /> <?=$itemValue["NAME"]?>
	</label>
<?endforeach;?>
</div>
<label for="subscribeID"><?=GetMessage("SUBSCRIBE")?></label>
<input class="txt" id="subscribeID" type="text" name="sf_EMAIL" size="20" placeholder="<?=$arResult["EMAIL"]?$arResult["EMAIL"]:GetMessage("subscr_form_email_title")?>" title="<?=GetMessage("subscr_form_email_title")?>" />
<input type="hidden" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
<button class="button"><?=GetMessage("subscr_form_button")?></button>
</form>
