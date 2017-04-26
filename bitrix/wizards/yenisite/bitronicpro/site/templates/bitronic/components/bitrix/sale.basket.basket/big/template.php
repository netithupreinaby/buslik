<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?if(file_exists($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/bitrix/sale.basket.basket/ajax.php'))$APPLICATION->AddHeadScript($templateFolder."/script_ajax.js");?>
<?/*?>
		<div id="container">
			<article class="no_border">
            	<div class="crumbs">
                	<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(),	false );?>
                </div><!--.crumbs-->
                <h1><?=$APPLICATION->GetTitle();?></h1>
<?*/?>
				<div class="user_basket">
					<div class="f_loader"></div>
<?
if (StrLen($arResult["ERROR_MESSAGE"])<=0)
{
	?>
	<form method="post" id='basket_form' action="<?=POST_FORM_ACTION_URI?>" name="basket_form">
		<input type="hidden" id="BasketRefresh" name="BasketRefresh" value="" />
		<?
		foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
		{
			$arHeaders[] = $arHeader["id"];
		}
		?>
		<input type="hidden" id="coupon_approved" value="N" />
		<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
		<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
		<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
		<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
		<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
		<?
		
		?>
		<div class="basket_basket_items" style="<?if ($arResult["ShowReady"]!="Y") echo 'display:none';?>">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?> 
		</div>
		
		<div class="basket_delayed_items" style="<?if ($arResult["ShowDelay"]!="Y") echo 'display:none';?>">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");?> 
		</div>


		<div class="basket_not_available_items" style="<?if ($arResult["ShowNotAvail"]!="Y") echo 'display:none';?>">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_notavail.php");?> 
		</div>
	</form>
	<?
}
else
	echo "<b>".GetMessage('EMPTY')."</b>";
?>

                 
                </div><!--.user_basket-->
<?/*?>
	  </article><!-- #content-->
  </div><!-- #container-->
<?*/?>