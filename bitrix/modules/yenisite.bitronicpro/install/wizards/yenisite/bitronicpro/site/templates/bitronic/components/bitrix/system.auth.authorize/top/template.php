<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
$AUTH_RESULT = $APPLICATION->arAuthResult;	//Get result of all actions related to the authorization
if(!empty($AUTH_RESULT["MESSAGE"]))
{
	$ERROR = preg_split("/<br>/",$AUTH_RESULT["MESSAGE"]);
	foreach($ERROR as $k=>$v)
		if(!empty($v))
			print_r( "<script>jGrowl('".$v."','".strtolower($AUTH_RESULT["TYPE"])."');</script>");
}
?>
<h2><span class="sym">&#0119;</span><?=GetMessage('AUTH');?></h2>
  <form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
		<?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>
	<div class="form-item">
	  <label><?=GetMessage("AUTH_LOGIN")?>:</label>
	  <input class="txt" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
	</div>
	<!--.form-item-->
	<div class="form-item">
	  <label><?=GetMessage("AUTH_PASSWORD")?>:</label>
	  <div class="password-txt">
		  <input class="txt" type="password" name="USER_PASSWORD" maxlength="255" />
		  <div class="button-eye sym" title="<?=GetMessage("EYE_TIP")?>">
			<table><tr align="center"><td>&#0088;</td></tr></table>
		  </div>
	  </div>
	</div>
	<!--.form-item-->
	<div class="form-item">
		<a href="/auth/?forgot_password=yes" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
	</div>
	<!--.form-item-->
	<div class="form-item"><a href="/auth/?register=yes" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a> </div>
	<!--.form-item-->
	<div class="checkbox-item">
	
		<input class="checkbox" type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" /><label for="USER_REMEMBER"> <?=GetMessage("AUTH_REMEMBER_ME")?></label>

	</div>
	
		<?if($arResult["CAPTCHA_CODE"]):?>
					<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
					<label><?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<label><br/>
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />					
					<input class="txt" type="text" name="captcha_word" maxlength="50" value=""  /><br/><br/>
		<?endif;?>

	
	
	<!--.checkbox-item-->
	<div class="form-submit"><button class="button"><?=GetMessage("AUTH_AUTHORIZE")?></button></div>
	<!--.form-submit-->
	<!--<div class="title">   </div>
	<a href="#"><span class="ws fs20">f</span></a> <a href="#"><span class="ws fs20">v</span></a> <a href="#"><span class="ws fs20">&#0035;</span></a> <a href="#"><span class="ws fs20">g</span></a>-->	
  </form>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"CURRENT_SERVICE"=>$arResult["CURRENT_SERVICE"],
		"AUTH_URL"=>$arResult["AUTH_URL"],
		"POST"=>$arResult["POST"],
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>