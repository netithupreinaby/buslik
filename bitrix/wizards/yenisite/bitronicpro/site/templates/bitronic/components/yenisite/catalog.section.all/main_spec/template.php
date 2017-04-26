<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?global $ys_options;?>
<?global $ys_n_for_ajax;?>
<div class="tab_block no-hide">
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
	<?if($ys_options['block_view_mode'] != 'nopopup'):?>
		<ul>
	<?else:?>
		<ul class="ulmitem">
	<?endif;?>
	<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		<?$ys_n_for_ajax++;?>
		<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
		<?/* OFFERS MIN PRICE START */?>			 
		<?$pr = 0; $kr = 0; $kk = 0;?>
		<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0)
		{
			foreach($arElement["OFFERS"] as $arOffer)
			{
				$arElement["CATALOG_QUANTITY_TRACE"] = "Y";
				if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] > 0) )
					$arElement["CATALOG_QUANTITY_TRACE"] = "N";

				foreach($arOffer[PRICES] as $k => $price)
					if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k;  $arElement[PRICES][$kr] = $arOffer[PRICES][$kr]; }				
				$price = $arOffer[PRICES][$kr][VALUE];				
				$disc = 0;				
				if($arOffer[PRICES][$kr][DISCOUNT_VALUE])
					$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
			}
		}?>
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
            if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
		}       
        $disc = 0;
        if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
		?>  	
		<?//$path = CFile::GetPath($arElement['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
		<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
		<li>
			<hidden name="ajax_iblock_id_<?=$ys_n_for_ajax;?>" id="ajax_iblock_id_<?=$ys_n_for_ajax;?>" value="<?=$arElement['IBLOCK_ID'];?>"/>
			<?if($ys_options['block_view_mode'] != 'nopopup'):?>
				<div class="item-popup"> 
					<div class="pop_pic">
						<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
						<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET_BIG']);?>' alt='<?=$arElement['NAME']?>' />
						<?if($arElement["PROPERTIES"]["NEW"]["VALUE"] || yenisite_date_to_time($arElement['DATE_CREATE']) > $arParams['STICKER_NEW_START_TIME']):?>
							<div class="new-label mark"><?=GetMessage('STICKER_NEW');?></div>
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
						<?if($no_hide_for_order):?>		
							<span class="price">
									<?=$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]?$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]:$ppr;?>
									<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
											<span class="oldprice"><?=$ppr;?></span>
									<?endif?>	
							</span>	
						<?endif;?>		
						<div class="stars">	
<?
$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
	"IBLOCK_TYPE" => $arElement["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arElement["IBLOCK_ID"],
	"ELEMENT_ID" => $arElement["ID"],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
	"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
	"SET_STATUS_404" => $arParams["IBLOCK_SET_STATUS_404"],
	"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
	),
	$component, array("HIDE_ICONS"=>"Y")
);?>					
						</div>
						<!--.stars--> 
								<?if(($arElement["CATALOG_QUANTITY_TRACE"] != "Y" || $arElement["CATALOG_QUANTITY"] != 0) && $no_hide_for_order):?>
									<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>										

												<a class="button2" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
												<?else:?>
													<?if ($arParams['HIDE_BUY_IF_PROPS'] != 'Y'):?>
														<a id="mb-<?=$ys_n_for_ajax;?>" class="button2 ajax_add2basket_main <?if($ppr <= 0):?>button_in_basket<?endif;?> ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
													<?endif;?>
									<?endif?>
								<?endif?>
						<div class="compare_list"></div><!--.compare_list--> 
						<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
							<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
						<?elseif($arElement["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] == 0):?>
							<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
						<?else:?>
							<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
						<?endif?>
					
					</div>
				  <!--.sl_info--> 
				</div>
				<!--.item-popup-->
				<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img">
					<img src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />	
				</a>
			<?else:?>
				<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
				<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img">
					<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />	
				</a>
				<div class="product_popup">
					<div class="btn_shop">
						<?if(($arElement["CATALOG_QUANTITY_TRACE"] != "Y" || $arElement["CATALOG_QUANTITY"] != 0) && $no_hide_for_order):?>
							<a id="mb-<?=$ys_n_for_ajax;?>" class="button1 ajax_add2basket_main <?if($ppr <= 0):?>button_in_basket<?endif;?> ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"<?/* onclick="onClick2Cart();"*/?>><span><?=GetMessage('CATALOG_ADD')?></span></a>
						<?endif;?>
					</div>
					<div class="compare_list">
				<?/*		<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>*/?>
					</div>
				</div>
			<?endif;?>
			<?if($ys_options['block_view_mode'] == 'nopopup'):?>
				<div class="marks">
			<?endif;?>
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
			<?if($ys_options['block_view_mode'] == 'nopopup'):?>
				</div>
			<?endif;?>
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
		<?if($ys_options['block_view_mode'] == 'nopopup'):?>
			<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
				<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
			<?elseif($arElement["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] == 0):?>
				<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
			<?else:?>
				<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
			<?endif?>	
		<?endif;?>		
			  </div>
			<!--.sl_info--> 
		  </li>
				<? $ppr = ""; ?>		 
			 
			  <?endforeach?>			 
			</ul>
		  </div>
<?COption::SetOptionString(CYSBitronicSettings::getModuleId(), "ys_n_for_ajax", $ys_n_for_ajax );?>
