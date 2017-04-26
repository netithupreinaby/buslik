<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
$APPLICATION->SetPageProperty("title", $APPLICATION->GetTitle());
$APPLICATION->AddChainItem($APPLICATION->GetTitle());

//ShowMessage($arParams["~AUTH_RESULT"]);

?>

				<div class="ordering">
					<div class="registration">
						<p><?=GetMessage("AUTH_GET_CHECK_STRING")?></p>
						<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
							<input type="hidden" name="AUTH_FORM" value="Y">
							<input type="hidden" name="TYPE" value="SEND_PWD">
                            <div class="form-item">
                                <label><?=GetMessage("AUTH_LOGIN")?></label>                                
								<input type="text" class="txt" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<br /><?=GetMessage("AUTH_OR")?>
								<label><?=GetMessage("AUTH_EMAIL")?></label>  
								<input type="text" class="txt" name="USER_EMAIL" maxlength="255" />																
                            </div><!--.form-item-->
							<input type="hidden" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
							<button class="button"><?=GetMessage("AUTH_SEND")?></button>
                        </form>
					</div>
                    <div style="clear:both;"></div>
                </div><!--.ordering-->


