<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>


		  <div class="tab_block">
			<ul>
			 <?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
			 <?
				$pr = 0; $kr = 0;
				foreach($arElement[PRICES] as $k => $price)
					if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
				$price = $arElement[PRICES][$kr][VALUE];
				$disc = 0;
				if($arElement[PRICES][$kr][DISCOUNT_VALUE])
					$disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
					
				$path = CFile::GetPath($arElement[PROPERTIES][MORE_PHOTO][VALUE][0]);?>
			 
				 <li>
					<div class="item-popup"> 
					
					<div class="pop_pic">
						<a class="sl_link" href="<?=$arElement[DETAIL_PAGE_URL]?>"></a>
						<img src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />
						
							<?if($arElement["PROPERTIES"]["NEW"]["VALUE"]):?>
							<div class="new-label mark">NEW</div>
							<?endif?>
							<?if($arElement["PROPERTIES"]["HIT"]["VALUE"]):?>
							<div class="star-label mark" style="top: 30px;"><span class="ws">R</span></div>
							<?endif?>
							<?if($disc>0):?>
							<div class="per-label mark" style="top: 60px;">-<?=IntVal($disc)?>%</div>
							<?else:?>
								<?if ($arElement["PROPERTIES"]["SALE"]["VALUE"] == "Y"):?>
									<div class="per2-label mark" style="top: 60px;">%</div>
								<?endif;?>
							<?endif?>

						</div>
					
					
				
					
					
					
					  <div class="sl_info">
						<h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$arElement[NAME]?></a></h3>
						
									<span class="price"><?=$arElement[PRICES][$kr][DISCOUNT_VALUE]?$arElement[PRICES][$kr][DISCOUNT_VALUE]:$pr;?><span class="rubl"><?=GetMessage('RUB');?></span> 
										<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
									<span class="oldprice"><?=$pr;?><span class="rubl"><?=GetMessage('RUB');?></span></span>
									<?endif?>									
									</span>
						
						<div class="stars"> <span class="fivestar"> <a href="#"></a> <a href="#"></a> <a href="#"></a> <a href="#"></a> <a href="#"></a> </span> <span class="reply"> <span class="ws">&#0115;</span> <?=GetMessage('REVIEW');?> <?=$arElement[PROPERTIES][FORUM_MESSAGE_CNT][VALUE];?></span><!--.reply--> 
						</div>
						<!--.stars--> 
						<a href="<?echo $arElement["ADD_URL"]?>"><button class="button2"><span><?=GetMessage('CATALOG_ADD')?></span></button></a>
						<div class="compare_list">
										<span class="ws">&#193;</span>
										<a href="<?echo $arElement["COMPARE_URL"]?>"><?echo GetMessage("CATALOG_COMPARE")?></a>
						</div>
						<!--.compare_list--> 
									<?if(!$arElement['CATALOG_AVAILABLE']):?>
										<span class="have"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
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
						<?if($arElement["PROPERTIES"]["NEW"]["VALUE"]):?>
							<div class="new-label mark"  style="top: 0px;">NEW</div>
							<?endif?>
							<?if($arElement["PROPERTIES"]["HIT"]["VALUE"]):?>
							<div class="star-label mark" style="top: 30px;"><span class="ws">R</span></div>
							<?endif?>
							<?if($disc>0):?>
							<div class="per-label mark" style="top: 60px;">-<?=IntVal($disc)?>%</div>
							<?else:?>
								<?if ($arElement["PROPERTIES"]["SALE"]["VALUE"] == "Y"):?>
									<div class="per2-label mark" style="top: 60px;">%</div>
								<?endif;?>
							<?endif?>
					
					<div class="sl_info">
					  <h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$arElement[NAME]?></a></h3>
					  
						<span class="price"><?=$arElement[PRICES][$kr][DISCOUNT_VALUE]?$arElement[PRICES][$kr][DISCOUNT_VALUE]:$pr;?><span class="rubl"><?=GetMessage('RUB');?></span> 
										<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
									<span class="oldprice"><?=$pr;?><span class="rubl"><?=GetMessage('RUB');?></span></span>
									<?endif?>									
						</span>
					  
					  </div>
					<!--.sl_info--> 
				  </li>
			 
			 
			  <?endforeach?>			 
			</ul>
		  </div>

		  
<?return?>		  





<div id="tv">


	  <ul class="tv_menu tab_nav">
	  <?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		<li><a href="#tab-<?=$cell?>" class="active"><span><?=$arElement["NAME"]?></span></a></li>
		<?endforeach?>
	  </ul>
	  <div class="tv_wrapper">
	    <?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
	    
<?$pr = 0; $kr = 0; $kk = 0;?>
<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>	
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
	    
	    
	    
			<div class="tv_tab" id="tab-<?=$cell?>">
			  <div class="tv_img">
<?$path = CFile::GetPath($arElement[PROPERTIES][MORE_PHOTO][VALUE][0]);?>
<img alt="" src="<?=CResizer2Resize::ResizeGD2($path, $arParams[IMAGE_SET]);?>">
<!--<img src="/bitrix/templates/bitronic/static/tmp/tv_tab.jpg" alt="" />-->
</div>
			  <!--.tv_img-->
			  <div class="title"><?=$arElement["NAME"]?></div>
			  <p><?=$arElement["PREVIEW_TEXT"]?></p>
			  
			  	<?
        $pr = 0; $kr = 0;
        foreach($arElement[PRICES] as $k => $price)
            if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
        $price = $arElement[PRICES][$kr][VALUE];
        $disc = 0;
        if($arElement[PRICES][$kr][DISCOUNT_VALUE])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
    ?>   
			  

				
				<span class="price"><strong><?=$arElement[PRICES][$kr][DISCOUNT_VALUE]?$arElement[PRICES][$kr][DISCOUNT_VALUE]:$pr;?><span class="rubl"><?=GetMessage('RUB');?></span></strong>
				<?if($arElement[PRICES][$kr][DISCOUNT_VALUE]):?>
						<span class="oldprice"><?=$pr;?><span class="rubl"><?=GetMessage('RUB');?></span></span>
				<?endif?>									
				</span>
				
			  
			  
			  <div class="more"> <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=GetMessage('PODROBNEE')?></a>
				<div style="clear:both;"></div>
			  </div>
			  <!--.more--> 
			</div>
		<?endforeach?>
	  </div>
	  <!--.tv_wrapper-->
	  <div class="tv_pager">
		<ul class="tab_nav">
		<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		  <li><a href="#tab-<?=$cell?>" class="active"> </a></li>
		 <?endforeach?> 
		</ul>
	  </div>
	  <!--.tv_pager--> 
	</div>
	<!--#tv-->
