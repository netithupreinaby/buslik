<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->AddChainItem(GetMessage('AUTH_REGISTER'));
?><?
$AUTH_RESULT = $APPLICATION->arAuthResult;	//Get result of all actions related to the authorization
if(!empty($AUTH_RESULT["MESSAGE"]))
{
	$ERROR = preg_split("/<br>/",$AUTH_RESULT["MESSAGE"]);
	foreach($ERROR as $k=>$v)
		if(!empty($v))
			print_r( "<script>jGrowl('".$v."','".strtolower($AUTH_RESULT["TYPE"])."');</script>");
}
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>



                <?$APPLICATION->SetPageProperty("title", GetMessage("AUTH_REGISTER"));?>
				<? //ShowMessage($arParams["~AUTH_RESULT"]);?>
				<div class="ordering">
					<div class="registration">
						<!--.req_block-->
                        <form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
							<input type="hidden" name="AUTH_FORM" value="Y" />
							<input type="hidden" name="TYPE" value="REGISTRATION" />
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_LOGIN_MIN")?>:<span class="req">*</span></label>
                                <input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" class="txt">
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_PASSWORD_REQ")?>:<span class="req">*</span></label>
                                <input name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" type="password" class="txt">
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_CONFIRM")?>:<span class="req">*</span></label>
                                <input name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" type="password" class="txt">
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_EMAIL")?>:<span class="req">*</span></label>
                                <input name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" type="text" class="txt">
                            </div><!--.form-item-->                         
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_NAME")?>:</label>
                                <input name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" type="text" class="txt">
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_LAST_NAME")?>:</label>
                                <input name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" type="text" class="txt">
                            </div><!--.form-item-->  

							<div class="form-item">
								<label><input name="SUBSCRIBE" class="checkbox" checked="checked"  value="Y" type="checkbox"><?=GetMessage("AUTH_SUBSCRIBE")?></label>
							</div>							


<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
		
		<div class="form-item captcha">
			<label><?=GetMessage("CAPTCHA_REGF_TITLE")?></label>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />			 
				<input class="txt w100" type="text" name="captcha_word" maxlength="50" value="" />
				<span class="captcha_img"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></span>
		</div><!--.form-item-->
		<?
	}
	/* CAPTCHA */
	?>
	<p><span class="req">*</span> &mdash; <?=GetMessage('REQ');?></p>
<input type="hidden" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
                            <button class="button"><?=GetMessage("AUTH_REGISTER")?></button>
                        </form>
					</div>
                    <div style="clear:both;"></div>
                </div><!--.ordering-->
