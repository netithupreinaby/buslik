<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
$AUTH_RESULT = $APPLICATION->arAuthResult;	//Get result of all actions related to the authorization

?>
<div>
    <a class="close sym" href="javascript:void(0);" title="<?=GetMessage('CLOSE')?>">&#205;</a>
    <h2><span class="sym">&#0119;</span><?=GetMessage('AUTH');?></h2>
      <form name="form_auth" id='form_auth' method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

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
                <table><tr align="center"><td><a>&#0088;</a></td></tr></table>
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
        <div class="form-submit">
            <button class="button">
                    <span class='text show'><?=GetMessage("AUTH_AUTHORIZE")?></span>
                    <span class='notloader'></span>
            </button>
        </div>
        <!--.form-submit-->
        <!--<div class="title">   </div>
        <a href="#"><span class="ws fs20">f</span></a> <a href="#"><span class="ws fs20">v</span></a> <a href="#"><span class="ws fs20">&#0035;</span></a> <a href="#"><span class="ws fs20">g</span></a>-->	
      </form>
    <?
    if(!empty($AUTH_RESULT["MESSAGE"]))
    {
        $ERROR = preg_split("/<br>/",$AUTH_RESULT["MESSAGE"]);
        foreach($ERROR as $k=>$v)
            if(!empty($v))
                echo "<span class='error' style='display: none;'>" . $v . "</span>";
                //print_r( "<script>jGrowl('".$v."','".strtolower($AUTH_RESULT["TYPE"])."');</script>");
    }
    ?>
    
    <?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "popup", 
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
</div>



<script>
$(document).ready(function() {

	if(typeof yenisite_bs_flyObjectTo != "undefined" && typeof Tipped == "object"){
		Tipped.create(".close[title]," +
					".button-eye[title]", { skin: 'black' }
					);
    }
	$('.button-eye').mousedown(function(){
		document.getElementsByName("USER_PASSWORD")[0].setAttribute('type','text');
	});	

	$('.button-eye').mouseup(function(){
		document.getElementsByName("USER_PASSWORD")[0].setAttribute('type','password');
	});	
	
	$("#form_auth input.checkbox").uniform();
});
</script>

