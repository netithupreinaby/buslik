<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
		  
		  <div class="tab_block no-hide">
			<ul class="ulmitem">
			 <?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
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
			$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price['PRINT_VALUE']);
			$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $price['PRINT_DISCOUNT_VALUE']);
            if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
		}       
        $disc = 0;
        if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
    ?>  
					
				<?//$path = CFile::GetPath($arElement['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
				<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
				
				 <li>
				 	<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
					<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img">
						<img src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />	
					</a>
					<div class="product_popup">
						<div class="btn_shop">
							<?if ($arParams['HIDE_BUY_IF_PROPS'] != 'Y'):?>
								<a class="button1" href="<?echo $arElement["ADD_URL"]?>" onclick="onClick2Cart();"><span><?=GetMessage('CATALOG_ADD')?></span></a>
							<?endif?>
						</div>
						<div class="compare_list">
							<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>
						</div>
					</div>
					<div class="marks">
					<?if($arElement["PROPERTIES"]["NEW"]["VALUE"]  || yenisite_date_to_time($arElement['DATE_CREATE']) > $arParams['STICKER_NEW_START_TIME']):?>
						<div class="new-label mark"  style="top: 0px;"><?=GetMessage('STICKER_NEW');?></div>
					<?endif?>
					<?if($arElement["PROPERTIES"]["HIT"]["VALUE"] || $arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] >= $arParams['STICKER_HIT']):?>
						<div class="star2-label mark" style="top: 30px;"><?=GetMessage('STICKER_HIT');?></div>
					<?endif?>
					<?if($arElement["PROPERTIES"]["SALE"]["VALUE"] || $disc > 0):?>
						<div class="per2-label mark" style="top: 60px;">
							<?if($disc>0):?>
								-<?=Round($disc)?>
							<?endif?>
						%</div>
					<?endif;?>
					<?if($arElement["PROPERTIES"]["BESTSELLER"]["VALUE"] || $arElement["PROPERTIES"]["SALE_INT"]["VALUE"] >= $arParams['STICKER_BESTSELLER']):?>
						<div class="leader-label mark" style="top:90px"><?=GetMessage('STICKER_BESTSELLER');?></div>
					<?endif?>
					</div>
					
					<div class="sl_info">
						<a class="tag" href="<?=$arElement["IBLOCK_SECTION_PAGE_URL"]?>"><?=$arElement["IBLOCK_SECTION_NAME"]?></a>
					<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
							<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
						<?endif?>
						<h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement[NAME]?></a></h3>
					  <?if($USER->IsAdmin()):?>
						<div class="debug"><?=GetMessage('FOR_ADMIN_ONLY');?><br/> <?=GetMessage('SELLERS');?> <?=$arElement["PROPERTIES"]["SALE_INT"]["VALUE"] ? IntVal($arElement["PROPERTIES"]["SALE_INT"]["VALUE"]) : 0;?> <?=GetMessage('WEEK_CNT');?> <?=$arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] ? IntVal($arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"]) : 0;?>; <?=GetMessage('NEW_DAYS');?> <?=Round((time()-yenisite_date_to_time($arElement['DATE_CREATE']))/86400);?></div>
					<?endif;?>
				<?if($no_hide_for_order):?>		  
					<span class="price">
							<?=$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]?$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]:$ppr;?>
							<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
									<span class="oldprice"><?=$ppr;?></span>
							<?endif?>	
					</span>	
				<?endif;?>	  
					  </div>
					<!--.sl_info--> 
				  </li>
				<? $ppr = ""; ?>		 
			 
			  <?endforeach?>			 
			</ul>
		  </div>