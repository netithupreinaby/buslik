<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? CModule::IncludeModule('yenisite.resizer2'); ?>
				<div class="catalog">
					<ul class="catalog-list-view">
<?if(empty($arResult["ITEMS"]) && !count($arResult["ITEMS"]) > 0 && $_REQUEST["set_filter"]=="Y"):?>	
<p><font class="errortext"><?=GetMessage('FILTER_ERROR');?></font></p>
<?endif?>

<input type="hidden" name="ajax_iblock_id" id="ajax_iblock_id" value="<?=$arResult['IBLOCK_ID'];?>"/>

<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
	<?
    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
    ?>
<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
<?/* OFFERS MIN PRICE START */?>			 
<?$pr = 0; $kr = 0; $kk = 0;?>
<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):?>	
<?$arElement["CATALOG_QUANTITY_TRACE"] = "Y";?>
<?foreach($arElement["OFFERS"] as $arOffer):?>

<?
$arElement["CATALOG_QUANTITY_TRACE"] = "Y";
if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] > 0) )
	$arElement["CATALOG_QUANTITY_TRACE"] = "N";
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
<?/* OFFERS MIN PRICE END */?>	
	
	
	<?
	/*
        $pr = 0; $kr = 0;
        foreach($arElement[PRICES] as $k => $price)
            if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
        $price = $arElement[PRICES][$kr][VALUE];
        $disc = 0;
        if($arElement[PRICES][$kr][DISCOUNT_VALUE])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
	*/
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
	
	
						<li id="<?=$this->GetEditAreaId($arElement['ID']);?>">
							<div class="item_links">
				<?if($no_hide_for_order):?>
					<span class="price">
							<?=$arElement[PRICES][$kr][DISCOUNT_VALUE]?$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]:$ppr;?>
							<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
									<span class="oldprice"><?=$ppr;?></span>
							<?endif?>	
					</span>
				<?endif;?>
							<?if($arElement['CATALOG_AVAILABLE']  && $no_hide_for_order):?>
									<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>										
										<a class="button2" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
									<?else:?>
										<div class="ye-props">
											<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
												<?if (!empty($product_property["VALUES"])):?>
													<div><?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</div>
													<?if($arElement["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
														&& $arElement["PROPERTIES"][$pid]["LIST_TYPE"] == "C"):?>
														<?foreach($product_property["VALUES"] as $k => $v):?>
															<div><label><input type="radio" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"checked"'?>><?echo $v?></label></div>
														<?endforeach;?>
													<?else:?>
														<div>
														<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="onSelectChange(this);">
														<option value="0"><?=GetMessage('CHOOSE')?></option>
															<?foreach($product_property["VALUES"] as $k => $v):?>
																<option value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"selected"'?>><?echo $v?></option>
															<?endforeach;?>
														</select>
														</div>
													<?endif;?>
												<?endif;?>
											<?endforeach;?>
										</div>
										<a class="button2 ajax_add2basket <?if($ppr <= 0):?>button_in_basket<?endif;?>" id="b-<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>" <?/*onclick="onClick2Cart(this);"*/?>><span><?=GetMessage('CATALOG_ADD')?></span>
										</a>
									<?endif?>
							<?endif?>				
									<div class="compare_list">
										
										<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span id="c-<?=$arElement['ID'];?>"><?echo GetMessage("CATALOG_COMPARE")?></span></a>
									</div>
							</div>
							<div class="item-pic">
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
							    <?$path = CFile::GetPath($arElement[PROPERTIES][MORE_PHOTO][VALUE][0]);?>
								<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img"><img id="product_photo_<?=$arElement['ID'];?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams[RESIZER_SETS][LIST_IMG]);?>' alt='<?=$arElement[NAME]?>' /></a>
							</div>
							
							<div class="item_descr">
							<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
								<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
							<?endif?>
							<h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement[NAME]?></a></h3>
							<p><?=$arElement[PREVIEW_TEXT]?><br/>
                                                             <?foreach($arElement["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
								<b><?=$arProperty["NAME"]?></b>: <?
										if(is_array($arProperty["DISPLAY_VALUE"])):
											echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
										elseif($pid=="MANUAL"):
											?><a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a><?
										else:
											echo $arProperty["DISPLAY_VALUE"];?>
										<?endif?>
                                                                <br/>
								<?endforeach?>
                                                           </p>

							<div class="item_info">
									<div class="stars">
<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
	"IBLOCK_TYPE" => $arElement[IBLOCK_TYPE],
	"IBLOCK_ID" => $arElement[IBLOCK_ID],
	"ELEMENT_ID" => $arElement[ID],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
	"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
	"SET_STATUS_404" => $arParams["IBLOCK_SET_STATUS_404"],
	"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
	),
	$component
);?>
										
										<span class="reply">
											<span class="ws">&#0115;</span> <?=GetMessage('CATALOG_REVIEW')?> <?=$arElement[PROPERTIES][FORUM_MESSAGE_CNT][VALUE];?>
										</span><!--.reply-->
									</div><!--.stars-->
									<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
										<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
									<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
										<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
									<?else:?>
										<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
									<?endif?>
								</div><!--.item_info-->
							
							</div>
							<div style="clear:both;"></div>
							<?
							global $USER;
							if($USER->IsAdmin())
							{?>
								<div class="debug">
									<p><?=GetMessage('CATALOG_ADMIN_INFO');?></p>
									<?
									echo GetMessage('CATALOG_SALE_EXTERNAL').$arElement["PROPERTIES"]["SALE_EXT"]["VALUE"];
									echo '; '.GetMessage('CATALOG_SALE_INTERNAL').$arElement["PROPERTIES"]["SALE_INT"]["VALUE"];
									if($arElement['SORT'])
										echo '; '.GetMessage('CATALOG_INDEX_SORT').$arElement["SORT"];
									echo '; '.GetMessage('CATALOG_WEEK_COUNTER').$arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"];
									echo ';<br/>'.GetMessage('CATALOG_WEEK');
									$week = array();
									$week = unserialize($arElement["PROPERTIES"]["WEEK"]["VALUE"]);
									foreach($week as $key=>$count)
										echo ' ['.date("d.m.Y", $key).']  '.$count.';';	
									?>
								</div>
							<?
							}
							?>
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

								<?if($ys_options["sef"] = "Y"):?>
									<select class="selectBox" name='page_count' onchange="setPageCount(this.value);">
								<?else:?>
									<select class="selectBox" name='page_count' onchange="document.forms['pagecount'].submit();">
								<?endif;?>

									<option value='20' <?=$arParams[PAGE_ELEMENT_COUNT] == 20?"selected='selected'":""; ?>>20</option>
									<option value='40' <?=$arParams[PAGE_ELEMENT_COUNT] == 40?"selected='selected'":""; ?>>40</option>
									<option value='60' <?=$arParams[PAGE_ELEMENT_COUNT] == 60?"selected='selected'":""; ?>>60</option>
								</select>
							</form>
					</div><!--.show_filter-->
					
					<?=$arResult["NAV_STRING"]?>
				</div><!--.pager-block-->