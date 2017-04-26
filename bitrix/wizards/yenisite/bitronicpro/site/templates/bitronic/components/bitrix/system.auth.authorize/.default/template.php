<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
// ShowMessage($arParams["~AUTH_RESULT"]);
// ShowMessage($arResult['ERROR_MESSAGE']);
?>
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
<?
$APPLICATION->SetPageProperty("title", $APPLICATION->GetTitle());
$APPLICATION->AddChainItem($APPLICATION->GetTitle());?>

				<div class="ordering">
					<div class="registration">
						<p><?=GetMessage("AUTH_GET_CHECK_STRING")?></p>
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
                                <label><?=GetMessage("AUTH_LOGIN")?></label>                                
								<input type="text" class="txt" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<?=GetMessage("AUTH_OR")?>
								<label><?=GetMessage("AUTH_PASSWORD")?></label>  
								<input class="txt" type="password" name="USER_PASSWORD" maxlength="255" />															
                            </div><!--.form-item-->
							
							<input type="hidden" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
							<button class="button"><?=GetMessage("AUTH_AUTHORIZE")?></button>
                        </form>
					</div>
					<br/><br/>
					
<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
		<!--<noindex>-->
			<p>
				<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
			</p>
		<!--</noindex>-->
<?endif?>

<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
		<!--<noindex>-->
			<p>
				<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a><br />
				<?=GetMessage("AUTH_FIRST_ONE")?> 
			</p>
		<!--</noindex>-->
<?endif?>

					
                    <div style="clear:both;"></div>
                </div><!--.ordering-->

