<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(count($arResult["ITEMS"]) > 0):?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div id="basket_slider" >
	<button id="button7" onClick="javascript:void(0);" class="button7 sym">&#212;</button> 
	<button id="button8" onClick="javascript:void(0);" class="button8 sym">&#215;</button>
	<h2><?=GetMessage($arParams['SLIDER_TITLE']);?></h2>
	<input type="hidden" name="count" value="<?=$arResult["COUNT"];?>" />
	<div class="overflow"> 
	<ul>
	<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
		<?$itemid = "ys-ms-".$arElement['IBLOCK_ID']."-".$arElement['ID'];?>
	<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
	<?/* OFFERS MIN PRICE START */?>			 
	<?$pr = 0; $kr = 0; $kk = 0;?>
	<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):?>	
	<?foreach($arElement["OFFERS"] as $arOffer):?>


	<?
	$arElement["CATALOG_QUANTITY_TRACE"] = "Y";
	if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] > 0) )
		$arElement["CATALOG_QUANTITY_TRACE"] = "N";
	?>


		<?
			foreach($arOffer[PRICES] as $k => $price)
				if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k;  $arElement[PRICES][$kr] = $arOffer[PRICES][$kr]; }				
					$price = $arOffer[PRICES][$kr][VALUE];				
					$disc = 0;				
				if($arOffer[PRICES][$kr][DISCOUNT_VALUE])
					$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
		?>  
	<?endforeach?>
	<?endif?>	

	<?$pr = 0; $kr = 0; $kk = 0;?>
	<?/* OFFERS MIN PRICE END */?>	
	<?
        $pr = 0; $kr = 0;				
        foreach($arElement[PRICES] as $k => &$price){
			if(CModule::IncludeModule("catalog"))
			{
				$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price['PRINT_VALUE']);
				$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $price['PRINT_DISCOUNT_VALUE']);
			}
			else
			{
				$price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
				$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
			}
            if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $ppr = $price[PRINT_VALUE];  $kr = $k; }			
		}       
        $disc = 0;
        if($arElement[PRICES][$kr][DISCOUNT_VALUE])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
    ?> 
		<li class="sim-item">
			<input type="hidden" name="iNumPage" value="<?=$_REQUEST["iNumPage"]?IntVal($_REQUEST["iNumPage"]):1?>" />
			<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sim-item-img">
				<img src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>'>
			</a>
			<h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$arElement[NAME]?></a></h3>
			<div class="sim-price">
				<?if($no_hide_for_order):?>	
					<span class="price">
						<?=$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]?$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]:$ppr;?>
						<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
								<span class="oldprice"><?=$ppr;?></span>
						<?endif?>	
					</span>
				<?endif;?>	
			</div><!--.sim-price-->
			<button id="<?=$itemid;?>" class="button2 add2basket <?if($ppr <= 0):?>button_in_basket<?endif;?> ajax_a2b_<?=$arElement['ID'];?>" href="<?=$arElement["ADD_URL"];?>"><span><?=GetMessage('CATALOG_ADD')?></span></button>
		</li><!--.sim-item-->
	<?endforeach;?>
	</ul>
	</div>
</div>
<?endif;?>