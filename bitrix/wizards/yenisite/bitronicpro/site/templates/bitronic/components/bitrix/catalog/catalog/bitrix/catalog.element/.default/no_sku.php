<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
<span class="price" id="ys_top_price">
<?if(!$bComplete):?>
	<?=$arResult['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arResult['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
	<?if($arResult['PRICES'][$kr]['DISCOUNT_VALUE'] && $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] != $arResult['PRICES'][$kr]['VALUE']):?>
		<span class="oldprice"><?=$ppr;?></span>
	<?endif;?>
<?else:?>
	<?$curPrice = ( $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] > 0 ) && ( $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] < $arResult['PRICES'][$kr]['VALUE'] ) ?  $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] : $arResult['PRICES'][$kr]['VALUE'];?>
<?endif; //bComplete?>
</span>
<?if(method_exists($this, 'createFrame')) $frame->end();?>

<?if (0 && isset($arResult['CATALOG_MEASURE_NAME'])):?>
<div class="ys-item-measure"><?=GetMessage('CATALOG_FOR_MEASURE', array('#NAME#' => $arResult['CATALOG_MEASURE_NAME']))?></div>
<?endif?>

<? /* ==================== edost START */ ?>
<?if(CModule::IncludeModule('edost.catalogdelivery')):
	if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));
    $delivery_img = $APPLICATION->IncludeComponent(
      'edost:catalogdelivery',
      'new',
      array(
        "INFO" => "",  // detail info in header of popup window
        "SHOW_QTY" => "Y",  // "Y" - show quantity
        "SHOW_ADD_CART" => "Y",  // "Y" - show checkbox "consider basket"
        "SHOW_BUTTON" => "Y",  // "Y" - button Close & Recount
        "FRAME_X" => "650",  // width of window
        "FRAME_Y" => "450",  // height of window

       // "SET_JQUERY" => "Y",  //"Y" - include jQuery from template of this component
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
      ),
      false
    );
	
	if(method_exists($this, 'createFrame')) $frame->end();
?>
	<div id="edostDelivery" >
	  <a href="#" onclick="javascript: edost_catalogdelivery_show('<?=$arResult["ID"]?>','<?=$arResult["NAME"]?>'); return false;"  rel="nofollow">
	  <img src="<?=$delivery_img?>" width="15" height="15" />
	  <p><?=GetMessage('CATALOG_DELIVERY_EDOST_BUTTON')?></p>
	  </a>
	</div>
<?endif;?>
<? /* ==================== edost END */ ?>
<?//get base price for SLIDER_FILTER (without SKU)
$SLIDER_FILTER['PRICE'] = $arResult['PRICES'][$base_price_group['NAME']]['VALUE'];
?>
<?if($arResult['CATALOG_AVAILABLE']):?>

	<div class="props">
				<?$prop_flag = false;?>
				<?foreach($arResult["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
				<?if (!empty($product_property["VALUES"])):?>
					<?$prop_flag = true;?>
					<div>
					<?echo $arResult["PROPERTIES"][$pid]["NAME"]?>:
					</div>
					<?if(
						$arResult["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
						&& $arResult["PROPERTIES"][$pid]["LIST_TYPE"] == "C"
					):?>
						<?foreach($product_property["VALUES"] as $k => $v):?>
							<?// start modify by Ivan, 09.10.2013 [2] ---->?>
								<label><input type="radio" class="radio" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" value="<?echo $k?>" onchange="onRadioPropChange(this);"><?echo $v?></label><br>
							<?// <---- end modify by Ivan, 09.10.2013 [2]?>
						<?endforeach;?>
					<?else:?>
						<div class="ye-select">
						<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="onSelectChange(this);">
							<option value="0"><?=GetMessage('CHOOSE')?></option>
							<?foreach($product_property["VALUES"] as $k => $v):?>
								<option value="<?echo $k?>"><?echo $v?></option>
							<?endforeach;?>
						</select>
						</div>
					<?endif;?>
					
				
				<?endif;?>
				<?endforeach;?>
				
	</div>
	<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));?>
	<a class="button2 ajax_add2basket <?if($pr <= 0 || $prop_flag):?>button_in_basket<?endif;?> <?if($prop_flag):?>ajax_add2basket_prop<?endif;?>" id="<?=$arResult['bComplete'] ? 'add2basket' : "b-{$arResult['ID']}";?>" href="<?=$arResult["ADD_URL"]?>"<?/* onclick="onClick2Cart(this);"*/?>  rel="nofollow"> <?// modify by Ivan, 09.10.2013, for ajax add complete set to basket?>
	<span><?=GetMessage('CATALOG_ADD_TO_BASKET')?></span></a>
	<?if(method_exists($this, 'createFrame')) $frame->end();?>
		<? /* ==================== v 1 Click START */ ?>
		<?if(CModule::IncludeModule('grain.fastorder') && !$prop_flag ):?>
			<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));?>
			<a class="button2 button_v1click <?if($pr <= 0):?>button_in_basket<?endif;?>" href="#" id="<?=$arResult['ID'];?>"  rel="nofollow">
			<span><?=GetMessage('CATALOG_ADD_TO_BASKET_IN_ONE_CLICK')?></span></a>
			<?if(method_exists($this, 'createFrame')) $frame->end();?>
		<?endif;?>
		<? /* ==================== v 1 Click END */ ?>
		<? /* ==================== kypiVkredit START */ ?>
		<?if(CModule::IncludeModule('tcsbank.kupivkredit') && $arParams["VKREDIT_BUTTON_ON"]=='Y' ):?>
			<div id="creditButtonId">
				<img src = "https://www.kupivkredit.ru/main/getBanner/?type=11&width=142"/>
			</div>
			<!--<a id="creditButtonId" class="button2 button_vkredit" href="#">
			<span><?=GetMessage('CATALOG_ADD_TO_BASKET_VKREDIT')?></span></a>-->
		<?endif;?>
		<? /* ==================== kypiVkredit END */ ?>
<?endif?>