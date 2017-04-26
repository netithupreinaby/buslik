<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
global $ys_options;
global $APPLICATION;
if(in_array($_REQUEST["page_count"], array(20, 40, 60))){
	$page_count = htmlspecialchars($_REQUEST["page_count"]);
	$APPLICATION->set_cookie("page_count", $page_count);
}
else
	$page_count = $APPLICATION->get_cookie("page_count")?$APPLICATION->get_cookie("page_count"):20;
	
$order = $_REQUEST['order'] ? htmlspecialchars($_REQUEST['order']) : $APPLICATION->get_cookie("order");
$by = $_REQUEST['by'] ? htmlspecialchars($_REQUEST['by']) : $APPLICATION->get_cookie("by");

// If params is exist ( asc?clear_cache=Y )
if(strpos($by, '?') !== false)
{
	$tmp = explode('?', $by);
	$by = $tmp[0];
}

if(!$order)
{
	if($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'PRICE')
		$order = $arParams['LIST_PRICE_SORT'] ;
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'NAME')
		$order = 'NAME' ;
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_EXT')
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_SALE_EXT' ;
	}
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_INT')
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_SALE_INT' ;
	}
	else
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_WEEK_COUNTER' ;
	}
}
$order = $order == 'SHOW_COUNTER' ? 'PROPERTY_WEEK_COUNTER' : $order ; // it's for compatibility with < 1.3.8 version. Kill this str 03.2013
$by = $by ? $by : $arParams['DEFAULT_ELEMENT_SORT_ORDER'];

if(!in_array($order, array($arParams['LIST_PRICE_SORT'], 'NAME', 'PROPERTY_SALE_EXT', 'PROPERTY_SALE_INT', 'SORT', 'PROPERTY_WEEK_COUNTER', 'SHOW_COUNTER')) ||
	!in_array($by, array('asc', 'desc', 'ASC', 'DESC')))
{
	define("ERROR_404", 1);
}
else
{
	$APPLICATION->set_cookie("order", $order);
	$APPLICATION->set_cookie("by", $by);
}
?>

<?if($ys_options["sef"] == "Y"):?>
	<input type="hidden" name="ys-sef" value="Y" />
<?endif;?>

<form name="sort_form">
        <input id='order_field' type='hidden' value='<?=$order?>' name='order' />
        <input id='by_field' type='hidden' value='<?=$by?>' name='by' />
        <div class="filter">
	        <div class="f_label"><?=GetMessage('SORT');?>:</div>
	        <div class="f_price">
		        <span class="lab <?=($order==$arParams['LIST_PRICE_SORT'])?"active":"";?>"><?=GetMessage('PO_PRICE');?></span>
		        <?if($ys_options["sef"] == "Y"):?>
		        	<button class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-<?=$arParams['LIST_PRICE_SORT']?>-desc/?>" style="display: none;">&#123;</a>
		        	
		        	<button class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-<?=$arParams['LIST_PRICE_SORT']?>-asc/?>" style="display: none;">&#125;</a>
		        <?else:?>
		        	<button onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'DESC'); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && $by=='DESC')?"active":"";?>">&#123;</button>
		        	<button onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'ASC'); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && $by=='ASC')?"active":"";?>">&#125;</button>
	        	<?endif;?>
	        </div><!---.f_price-->
	        <div class="f_name">
		        <span class="lab <?=($order=='NAME')?"active":"";?>"><?=GetMessage('PO_NAME');?></span>
		        <?if($ys_options["sef"] == "Y"):?>
		        	<button class="button11 sym <?=($order=="NAME" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-NAME-desc/" style="display: none;">&#123;</a>
		        	
		        	<button class="button11 sym <?=($order=="NAME" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-NAME-asc/" style="display: none;">&#125;</a>
		        <?else:?>
		        	<button onclick="setSortFields('NAME', 'DESC'); return false;" class="button11 sym <?=($order=="NAME" && $by=='DESC')?"active":"";?>">&#123;</button>
		        	<button onclick="setSortFields('NAME', 'ASC'); return false;" class="button11 sym <?=($order=="NAME" && $by=='ASC')?"active":"";?>">&#125;</button>
	        	<?endif;?>
	        </div><!---.f_name-->
	        <div class="f_pop">
		        <span class="lab  <?=($order=='PROPERTY_WEEK_COUNTER')?"active":"";?>"><?=GetMessage('PO_POPULAR');?></span>
		        <?if($ys_options["sef"] == "Y"):?>
		        	<button class="button11 sym <?=($order=="PROPERTY_WEEK_COUNTER" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-PROPERTY_WEEK_COUNTER-desc/" style="display: none;">&#123;</a>
		        	
		        	<button class="button11 sym <?=($order=="PROPERTY_WEEK_COUNTER" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-PROPERTY_WEEK_COUNTER-asc/" style="display: none;">&#125;</a>
		         <?else:?>
		        	<button onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'DESC'); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && $by=='DESC')?"active":"";?>">&#123;</button>
		        	<button onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'ASC'); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && $by=='ASC')?"active":"";?>">&#125;</button>
	        	<?endif;?>
	        </div><!---.f_pop-->
			<div class="f_sales">
		        <span class="lab  <?=($order=='PROPERTY_SALE_INT')?"active":"";?>"><?=GetMessage('PO_SALE');?></span>
		        <?if($ys_options["sef"] == "Y"):?>
		        	<button class="button11 sym <?=($order=="PROPERTY_SALE_INT" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-PROPERTY_SALE_INT-desc/" style="display: none;">&#123;</a>
		        	
		        	<button class="button11 sym <?=($order=="PROPERTY_SALE_INT" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
		        	<a href="<?=$arResult["URL2MAIN"]?>sort-PROPERTY_SALE_INT-asc/" style="display: none;">&#125;</a>
		        <?else:?>
		        	<button onclick="setSortFields('PROPERTY_SALE_INT', 'DESC'); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && $by=='DESC')?"active":"";?>">&#123;</button>
		        	<button onclick="setSortFields('PROPERTY_SALE_INT', 'ASC'); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && $by=='ASC')?"active":"";?>">&#125;</button>
	        	<?endif;?>
	        </div><!---.f_sales-->

	        <!--<form name="view_form">-->

	        <?if($pagen>0):?>
				<input id='PAGEN_field' type='hidden' value='<?=$pagen?>' name='<?=$pagen_key?>' />
			<?endif;?>			
	        
	        <!--</form>-->

        </div><!--.filter-->
    </form>
	<div class="catalog">
		<?if($ys_options['block_view_mode'] != 'nopopup'):?>
			<ul class="catalog-list">
		<?else:?>
			<ul class="catalog-list ulmitem">
		<?endif;?>
		

<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
	<?
    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
    ?>
<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
<?$pr = 0; $kr = 0; $kk = 0;?>
<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):?>	
<?foreach($arElement["OFFERS"] as $arOffer):?>

<?
if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arOffer["CATALOG_QUANTITY"] > 0) ){
	$arElement["CATALOG_QUANTITY_TRACE"] = "N";	
}
?>
<?
        foreach($arOffer['PRICES'] as $k => $price)
            if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $kr = $k;  $arElement['PRICES'][$kr] = $arOffer['PRICES'][$kr]; }				
				$price = $arOffer['PRICES'][$kr]['VALUE'];				
				$disc = 0;				
			if($arOffer['PRICES'][$kr]['DISCOUNT_VALUE'])
				$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
    ?>  
<?endforeach?>
<?endif?>	
<?$pr = 0; $kr = 0; $kk = 0;?>

<?
    // foreach($arElement[PRICES] as $k => $price)
            // if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
        // $price = $arElement[PRICES][$kr][VALUE];
        // $disc = 0;
        // if($arElement[PRICES][$kr][DISCOUNT_VALUE])
            // $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
	
?>

		<?
        $pr = 0; $kr = 0;				
        foreach($arElement['PRICES'] as $k => &$price){
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
					<li id="<?=$this->GetEditAreaId($arElement['ID']);?>">
						<hidden name="ajax_iblock_id_<?=$ys_n_for_ajax;?>" id="ajax_iblock_id_<?=$ys_n_for_ajax;?>" value="<?=$arElement['IBLOCK_ID'];?>"/>
						<?if($ys_options['block_view_mode'] != 'nopopup'):?>
							<div class="item-popup">
								<div class="pop_pic">
								<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
								<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img">
									<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />
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
								</a>
								</div>	
									<div class="sl_info">
										<!--<a href="#" class="tag"></a>-->
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
											<span class="fivestar">
												<a href="#"></a>
												<a href="#"></a>
												<a href="#"></a>
												<a href="#"></a>
												<a href="#"></a>
											</span>
											<span class="reply">
												<span class="ws">&#0115;</span> <?=GetMessage('CATALOG_REVIEW')?> 
												<?=$arElement[PROPERTIES][FORUM_MESSAGE_CNT][VALUE];?>
											</span><!--.reply-->
										</div><!--.stars-->
									<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>	
										<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>										
										<a class="button2" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
										<?else:?>
											<?if ($arParams['HIDE_BUY_IF_PROPS'] != 'Y'):?>
												<a id="mb-<?=$ys_n_for_ajax;?>" class="button2 ajax_add2basket_main ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"  rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
											<?endif;?>
										<?endif?>
									<?endif?>
										<!--<a href="#" class="added">  </a>-->
										
										<div class="compare_list">
											<?/*
											<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>
											*/?>
										</div>
										<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
											<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
										<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
											<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
										<?else:?>
											<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
										<?endif?>
									</div><!--.sl_info-->								
							</div><!--.item-popup-->
							<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img"><img src="<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>" alt="<?=$arElement['NAME']?>" /></a>
							<?else:?>
								<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
								<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img">
									<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams[IMAGE_SET]);?>' alt='<?=$arElement[NAME]?>' />	
								</a>
								<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>	
								<div class="product_popup">
									<div class="btn_shop">
										<a id="mb-<?=$ys_n_for_ajax;?>" class="button1 ajax_add2basket_main <?if($ppr <= 0):?>button_in_basket<?endif;?> ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>" <?/* onclick="onClick2Cart();" */?>  rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
									</div>
									<div class="compare_list">
										<?/*
										<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>
										*/?>
									</div>
								</div>
								<?endif;?>
							<?endif;?>
							
							<?if($ys_options['block_view_mode'] == 'nopopup'):?>
								<div class="marks">
							<?endif;?>
							
							<?if($arElement["PROPERTIES"]["NEW"]["VALUE"] || (yenisite_date_to_time($arElement['DATE_CREATE'])> $arParams['STICKER_NEW_START_TIME'])):?>
								<div class="new-label mark"><?=GetMessage('STICKER_NEW');?></div>
							<?endif?>
							<?if($arElement["PROPERTIES"]["HIT"]["VALUE"] || $arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] >= $arParams['STICKER_HIT']):?>
								<div class="star2-label mark"><?=GetMessage('STICKER_HIT');?></div>
							<?endif?>
							
							<?if($disc>0):?> 
							<div class="per2-label mark" style="top: 60px;">-<?=Round($disc)?>%</div>
						<?else:?>
								<?if ($arElement["PROPERTIES"]["SALE"]["VALUE"]):?>
								<div class="per2-label mark" style="top: 60px;">%</div>
							<?endif;?>
						<?endif?>
							<?if($arElement["PROPERTIES"]["BESTSELLER"]["VALUE"] || $arElement["PROPERTIES"]["SALE_INT"]["VALUE"] >= $arParams['STICKER_BESTSELLER']):?>
								<div class="leader-label mark"><?=GetMessage('STICKER_BESTSELLER');?></div>
							<?endif?>
						<?if($ys_options['block_view_mode'] == 'nopopup'):?>
							</div>
						<?endif;?>
							<div class="sl_info">
								<!--<a href="#" class="tag"></a>-->
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
				<?if($ys_options['block_view_mode'] == 'nopopup'):?>
					<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
						<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
					<?elseif($arElement["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] == 0):?>
						<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
					<?else:?>
						<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
					<?endif?>	
				<?endif;?>
							</div><!--.sl_info-->
						</li>
<? $ppr = ""; ?>						
						
						<?endforeach;?>						
						</ul>
					<div style="clear:both;"></div>
				</div><!--.cataog-->
			<div class="pager-block">
					<div class="show_filter">
							<?if($arParams["FILTER_BY_QUANTITY"] == 'Y'):?>
								<div class="ys_no_available_link"><a href="?set_filter=Y" title="<?=GetMessage('ALL_PRODUCTS_LINK_TITLE');?>"><?=GetMessage('ALL_PRODUCTS_LINK');?></a></div>
							<?endif;?>
							<form name='pagecount' method="post">
								<?echo GetMessage("PAGE_COUNT")?>:
								<select class="selectBox" name='page_count' onchange="document.forms['pagecount'].submit();">
									<option value='20' <?=$arParams[PAGE_ELEMENT_COUNT] == 20?"selected='selected'":""; ?>>20</option>
									<option value='40' <?=$arParams[PAGE_ELEMENT_COUNT] == 40?"selected='selected'":""; ?>>40</option>
									<option value='60' <?=$arParams[PAGE_ELEMENT_COUNT] == 60?"selected='selected'":""; ?>>60</option>
								</select>
							</form>
					</div><!--.show_filter-->
					
					<?=$arResult["NAV_STRING"]?>
				</div><!--.pager-block-->