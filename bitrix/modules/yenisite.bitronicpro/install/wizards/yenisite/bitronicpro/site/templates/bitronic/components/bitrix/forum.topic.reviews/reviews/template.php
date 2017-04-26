<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';

if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}

if(method_exists($this, 'createFrame')) $this->createFrame('reply')->begin(GetMessage('COMPOSITE_LOADING'));
CUtil::InitJSCore(array('ajax', 'fx')); 
// ************************* Input params***************************************************************
$arParams["SHOW_LINK_TO_FORUM"] = ($arParams["SHOW_LINK_TO_FORUM"] == "N" ? "N" : "Y");
$arParams["FILES_COUNT"] = intVal(intVal($arParams["FILES_COUNT"]) > 0 ? $arParams["FILES_COUNT"] : 1);
$arParams["IMAGE_SIZE"] = (intVal($arParams["IMAGE_SIZE"]) > 0 ? $arParams["IMAGE_SIZE"] : 100);
if (LANGUAGE_ID == 'ru'):
	$path = str_replace(array("\\", "//"), "/", dirname(__FILE__)."/ru/script.php");
	include($path);
endif;

// *************************/Input params***************************************************************
?>

<?	/*	----NEW ERROR MESSAGE----	*/
if (!empty($arResult["ERROR_MESSAGE"]))
	print_r( "<script>jGrowl('".$arResult["ERROR_MESSAGE"]."','error');</script>");
if (!empty($arResult["OK_MESSAGE"]))
	print_r( "<script>jGrowl('".$arResult["OK_MESSAGE"]."','ok');</script>");
?>
<!-- 	OLD ERROR MESSAGE (04.03.2013)	
<div class="reviews-note-box reviews-note-error">
	<div class="reviews-note-box-text"><b><?=ShowError($arResult["ERROR_MESSAGE"], "reviews-note-error");?></b></div>
</div>-->


									<div class="reply-form">
                                        <div class="req_block">
                                            <span class="req">*</span> &mdash; <?=GetMessage('REQUIRE');?> 
                                        </div><!--.req_block-->
										<form name="REPLIER<?=$arParams["form_index"]?>" id="REPLIER<?=$arParams["form_index"]?>" action="<?=POST_FORM_ACTION_URI?>#postform"<?
											?> method="POST" enctype="multipart/form-data" onsubmit="return ValidateForm(this, '<?=$arParams["AJAX_TYPE"]?>', '<?=$arParams["AJAX_POST"]?>', '<?=$arParams["PREORDER"]?>');"<?
											?> class="reviews-form">
											<input type="hidden" name="back_page" value="<?=$arResult["CURRENT_PAGE"]?>" />
											<input type="hidden" name="ELEMENT_ID" value="<?=$arParams["ELEMENT_ID"]?>" />
											<input type="hidden" name="SECTION_ID" value="<?=$arResult["ELEMENT_REAL"]["IBLOCK_SECTION_ID"]?>" />
											<input type="hidden" name="save_product_review" value="Y" />
											<input type="hidden" name="preview_comment" value="N" />
											<?//=bitrix_sessid_post()?>
											<input type="hidden" name="sessid"  value="<?=bitrix_sessid()?>" />
                                        <h2><?=GetMessage('ADD_REVIEW');?></h2>
										
<?
/* GUEST PANEL */
if (!$arResult["IS_AUTHORIZED"]):
?>

										<div class="form-item">
                                            <label><?=GetMessage("OPINIONS_NAME")?>:<span class="req">*</span></label>

											<input class="txt w280" name="REVIEW_AUTHOR" id="REVIEW_AUTHOR<?=$arParams["form_index"]?>" size="30" type="text" value="<?=$arResult["REVIEW_AUTHOR"]?>" <?if(!empty($tabIndex)):?> tabindex="<?=$tabIndex++;?>" <?endif;?> />                                           

                                        </div><!--.form-item-->

 <?		
	if ($arResult["FORUM"]["ASK_GUEST_EMAIL"]=="Y"):
?>

										<div class="form-item">
                                            <label><?=GetMessage("OPINIONS_EMAIL")?> <span class="req">*</span>:</label>                                            

											<input class="txt w280" type="text" name="REVIEW_EMAIL" id="REVIEW_EMAIL<?=$arParams["form_index"]?>" size="30" value="<?=$arResult["REVIEW_EMAIL"]?>" <?if(!empty($tabIndex)):?> tabindex="<?=$tabIndex++;?>" <?endif;?> />

                                        </div><!--.form-item-->
<?
	endif;
endif;
?>										

                                        <div class="form-item">
                                            <label><?=GetMessage('REVIEW_TEXT');?>:</label>
                                            <textarea name="REVIEW_TEXT" class="reply-text" id="itsalltext_generated_id__1"></textarea><?/*<img alt="It's All Text!" src="chrome://itsalltext/locale/gumdrop.png" title="It's All Text!" style="cursor: pointer ! important; display: none ! important; position: absolute ! important; padding: 0pt ! important; margin: 0pt ! important; border: medium none ! important; width: 28px ! important; height: 14px ! important; opacity: 0.0152174 ! important;"> */?>
                                        </div><!--.form-item-->
                                        <div class="form-item captcha">
                                            <!--<label>SLovo sprava</label>
                                            <input type="text" class="txt w100">
                                            <span class="captcha_img"><img alt="captcha" src="/bitrix/templates/bitronic/static/tmp/captcha.png"></span>-->
											
<?

/* CAPTHCA */
if (strLen($arResult["CAPTCHA_CODE"]) > 0):
?>
		
				<input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
			
				<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?><span class="reviews-required-field">*</span></label>

				<input id="captcha_word" class="txt w100" type="text" size="30" name="captcha_word" <?if(!empty($tabIndex)):?> tabindex="<?=$tabIndex++;?>" <?endif;?> autocomplete="off" />
		
			<span class="captcha_img">
				<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" alt="<?=GetMessage("F_CAPTCHA_TITLE")?>" />
			</span>
		
<?
endif;	
?>										
											
											
                                        </div><!--.form-item-->
                                        <div class="form-submit">				

                                            <span class="submit"><input class="button" name="send_button" type="submit" value="<?=GetMessage("OPINIONS_SEND")?>" <?if(!empty($tabIndex)):?> tabindex="<?=$tabIndex++;?>" <?endif;?> onclick="this.form.preview_comment.value = 'N';" /></span>
                                        </div><!--.form-submit-->
                                    </form>
                                    </div><!--.reply-form-->

<?
$iCount = 0;
foreach ($arResult["MESSAGES"] as $res):
	$iCount++;
?>

								<div class="reply-item">
                                    <span class="date"><?=$res["POST_DATE"]?></span>
                                    <h3><?=$res["AUTHOR_NAME"]?></h3>
                                    <p><?=$res["POST_MESSAGE_TEXT"]?></p>
                                </div><!--.reply-item-->

<?
endforeach;
if (strlen($arResult["NAV_STRING"]) > 0 && $arResult["NAV_RESULT"]->NavPageCount > 1):
?>
<div class="reviews-navigation-box reviews-navigation-bottom">
	<div class="reviews-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="reviews-clear-float"></div>
</div>
<?
endif;
?>