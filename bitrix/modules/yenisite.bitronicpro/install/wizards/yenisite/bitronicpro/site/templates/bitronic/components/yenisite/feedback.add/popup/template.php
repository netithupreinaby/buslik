<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin(''); ?>
<div>
    <a href="javascript:void(0);" class="ys-feedback_add_popup_close">&#205;</a>
    <form method='POST' id="ys-guestbook" name="guestbook" action="<?=$APPLICATION->GetCurPageParam()?>" enctype="multipart/form-data">
            <?foreach($arResult['FIELDS'] as $arItem):?>
                <? if ($arItem['PROPERTY_TYPE'] == 'E'): ?>
                    <?=$arItem['HTML'];?>
                <? endif; ?>
            <?endforeach;?>
                <div id="ys-feedback_add">
                    <div class="req_block">
                    <span style="color: red">*</span>
                        <?=GetMessage("REQUIRED");?>
                    </div>
                    <div><h2><?=$arResult['ELEMENT_ADD'];?></h2></div>
                    
                    <? if (!empty($arResult['SECTIONS_SELECT'])): ?>
                        <?= GetMessage('SECTION_SELECT') . ': ' . $arResult['SECTIONS_SELECT']; ?>
                    <? endif; ?>
                    
                    <?foreach($arResult['FIELDS'] as $arItem):?>
                        <? if (!empty($arItem['HTML']) && $arItem['PROPERTY_TYPE'] != 'E'): ?>
                            <div class='field'>
                                <label><?=$arItem['NAME'];?>:<?=($arItem['IS_REQUIRED'] == 'Y')?'<span style="color: red">*</span>':'';?></label>
                                <?=$arItem['HTML'];?>
                            </div>
                        <? endif; ?>
                    <?endforeach;?>
                    
                    <? if ($arParams['TEXT_SHOW'] == 'Y'): ?>
                        <div class='field'>
                            <label><?=GetMessage("MESSAGE");?><?=($arParams['TEXT_REQUIRED'] == 'Y')? '<span style="color: red">*</span>': '' ?>:</label>
                            <textarea name='<?=$arResult['CODE'];?>[text]'><?=$arResult['DATA']['text'];?></textarea>
                        </div>
                    <? endif; ?>
                    <?if(!empty($arResult["CAPTCHA_CODE"])):?>
                        <div class="ys-captcha">
                                <label><?=GetMessage("CAPTCHA_TITLE")?>:</label>
                                <img alt="<?=GetMessage("CAPTCHA_ALT")?>" src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" />
                                <br>
                                <input class="txt" type="text" name="captcha_word" /><br />
                                <input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
                        </div>
                    <?endif;?>
                    <br/>
                    <div><button class='button2'><?=($arParams['FORM'] == "CALLBACK_PHONE")?GetMessage("CALLBACK_PHONE_SUBMIT"):GetMessage("SEND");?></button></div>
                </div>
    </form>
</div>

<script> 
    $(document).ready(function(){ 
if (typeof Tipped == "object") 
{ 
Tipped.create('.ys-feedback_add_popup_close', '<?=GetMessage('CLOSE');?>'); 
    }    
        $('a.ys-feedback_add_popup_close').click(function(){ 
            $('.qtip').remove(); 
            $('#mask').click(); 
        }); 
       $('form#ys-guestbook:visible input:text:first').focus(); 
 
 
    }); 
</script>

<? if(!empty($arResult['ERROR'])): ?>
<script>
    $(document).ready(function(){
        $.jGrowl("<?=$arResult['ERROR'];?>", {themeState: 'error', position: 'bottom-left', header: '<?=GetMessage("ERROR");?>'});
    });
</script>
<? endif; ?>

<? if ($arResult['SUCCESS'] === TRUE): ?>

<? if(!empty($arResult['SUCCESS_TEXT'])): ?>
<script>
    $(document).ready(function(){
        $.jGrowl("<?=$arResult['SUCCESS_TEXT'];?>", {themeState: 'ok', position: 'bottom-left', header: ''});
    });
</script>
<? endif; ?>
<?php //if(isset($_REQUEST['AJAX_CALL']) && 'Y' == $_REQUEST['AJAX_CALL']): ?>
<script>
    if ($('#mask').length > 0)
    {
        $('#mask').click();
    }
</script>
<?php //endif; ?>

<? endif; ?>