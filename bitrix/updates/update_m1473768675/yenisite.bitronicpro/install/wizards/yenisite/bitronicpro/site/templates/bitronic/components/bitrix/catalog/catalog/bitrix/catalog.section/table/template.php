<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? CModule::IncludeModule('yenisite.resizer2'); ?>
<?global $USER;?> 
<input type="hidden" name="ajax_iblock_id" id="ajax_iblock_id" value="<?=$arResult['IBLOCK_ID'];?>"/>

<div class="catalog">
	<div class="catalog-list-light">
		<table>
			<tbody>						
			<?if(empty($arResult["ITEMS"]) && !count($arResult["ITEMS"]) > 0 && $_REQUEST["set_filter"]=="Y"):?>	
				<p><font class="errortext"><?=GetMessage('FILTER_ERROR');?></font></p>
			<?endif?>
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
				<?$n_td = 0 ;?>
				<tr id="<?=$this->GetEditAreaId($arElement['ID']);?>">
					<td><?$n_td ++;?>
						<?$path = CFile::GetPath($arElement['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
						<a class="table_big_img" href="<?=$arElement['DETAIL_PAGE_URL']?>" rel="<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_BIG']);?>">
							<img id="product_photo_<?=$arElement['ID'];?>" class="table_img" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['TABLE_IMG']);?>' alt='<?=$arElement['NAME']?>' />
						</a>
					</td>
					<td class="catlistname"><?$n_td ++;?>
						<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
							<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
						<?endif?>
						<h3><a href="<?=$arElement[DETAIL_PAGE_URL]?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement[NAME]?></a></h3>
					</td>                                    
						<td class="avilableTD"><?$n_td ++;?>
							<?if(!$arElement['CATALOG_AVAILABLE']):?>
								<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
							<?else:?>
								<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
							<?endif?>
						</td>
						<td class="priceTD"><?$n_td ++;?>
							<?if($no_hide_for_order):?>
								<span class="price">
										<?=$arElement[PRICES][$kr][DISCOUNT_VALUE]?$arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE]:$ppr;?>
										<?if($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]):?>
												<span class="oldprice"><?=$ppr;?></span>
										<?endif?>	
								</span>
								
							<?endif;?>						
						</td>
						
						<td><?$n_td ++;?>
		<?if(($arElement["CATALOG_QUANTITY_TRACE"] != "Y" || $arElement["CATALOG_QUANTITY"] != 0) && $no_hide_for_order):?>
			<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>								
						<form name="a2b<?echo $arElement["ID"]?>" id="a2b<?echo $arElement["ID"]?>" action="<?=$arElement["DETAIL_PAGE_URL"]?>" method="post" enctype="multipart/form-data">										
						</form>	
			<?else:?>
						<form name="a2b<?echo $arElement["ID"]?>" id="a2b<?echo $arElement["ID"]?>" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
							<input id="q<?echo $arElement["ID"]?>"  type="text" name="quantity" class="txt"  value="1" />
							<input type="hidden" name="id" value="<?echo $arElement["ID"]?>">							
							<input type="hidden" name="action" value="ADD2BASKET">
						</form>	
						
			<?endif?>
						</td>
						<?if(!count($arElement["OFFERS"])):?>
						<td><?$n_td ++;?>
							<button title="<?=GetMessage("ADD_ITEM"); ?>" onclick="setQuantityTable('#q<?echo $arElement["ID"]?>', '+'); return false;" class="button4">+</button>
						</td>
						<td><?$n_td ++;?>
							<button title="<?=GetMessage("DELETE_ITEM"); ?>" onclick="setQuantityTable('#q<?echo $arElement["ID"]?>', '-'); return false;" class="button5">-</button>
						</td>
						<?else:?>
						<td><?$n_td ++;?></td><td><?$n_td ++;?></td>
						<?endif?>
						<td class="add_basket">
							<?$n_td ++;?>
							<button class="ajax_add2basket_q <?if($ppr <= 0):?>button_in_basket<?endif;?> button2" id="<?echo 'b'.$i.'-'.$arElement["ID"]?>" <?/*onclick="if ( onClick2Cart(this) ) { document.forms['a2b<?echo $arElement["ID"]?>'].submit(); return false; }" on*/?>><span></span></button>
						</td>
		<?endif?>
				</tr>
				
				<?if($USER->IsAdmin()):?>
				<tr>
					<td colspan="<?=$n_td;?>">
						<div class="debug">
							<p><?=GetMessage('CATALOG_ADMIN_INFO');?></p>
							<?
							echo GetMessage('CATALOG_SALE_EXTERNAL').$arElement["PROPERTIES"]["SALE_EXT"]["VALUE"];
							echo '; '.GetMessage('CATALOG_SALE_INTERNAL').$arElement["PROPERTIES"]["SALE_INT"]["VALUE"];
							if($arElement["SORT"])
								echo '; '.GetMessage('CATALOG_INDEX_SORT').$arElement["SORT"];
							echo '; '.GetMessage('CATALOG_WEEK_COUNTER').$arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"];
							echo ';<br/>'.GetMessage('CATALOG_WEEK');
							$week = array();
							$week = unserialize($arElement["PROPERTIES"]["WEEK"]["VALUE"]);
							foreach($week as $key=>$count)
								echo ' ['.date("d.m.Y", $key).']  '.$count.';';	
							?>
						</div>
					</td>
				</tr>
				<?endif;?>
				
<?/*
							<tr>
								<td colspan="5">
								<?if (empty($arElement["OFFERS"]) && ($arElement["CATALOG_QUANTITY_TRACE"] != "Y" || $arElement["CATALOG_QUANTITY"] != 0) && $no_hide_for_order):?>
									<span class="ye-props">
											<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
											<?$i++;?>
												<span class="ye-prop">
												<?if (!empty($product_property["VALUES"])):?>
													<span><?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</span>
													<?if($arElement["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
														&& $arElement["PROPERTIES"][$pid]["LIST_TYPE"] == "C"):?>
														<?foreach($product_property["VALUES"] as $k => $v):?>
															<div><label><input type="radio" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"checked"'?>><?echo $v?></label></div>
														<?endforeach;?>
													<?else:?>
														<select id="<?echo 's'.$i.'-'.$arElement["ID"]?>" class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="onSelectChange(this);">
														<option value="0"><?=GetMessage('CHOOSE')?></option>
															<?foreach($product_property["VALUES"] as $k => $v):?>
																<option value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"selected"'?>><?echo $v?></option>
															<?endforeach;?>
														</select>
													<?endif;?>
												<?endif;?>
												</span>
											<?endforeach;?>
										</span>
										
									<?endif;?>
								</td>
							</tr>
*/?>
							<? $ppr = ""; ?>			
						<?endforeach?>							
					</tbody>
				</table>
			</div>
			
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
								<?endif?>

									<option value='20' <?=$arParams[PAGE_ELEMENT_COUNT] == 20?"selected='selected'":""; ?>>20</option>
									<option value='40' <?=$arParams[PAGE_ELEMENT_COUNT] == 40?"selected='selected'":""; ?>>40</option>
									<option value='60' <?=$arParams[PAGE_ELEMENT_COUNT] == 60?"selected='selected'":""; ?>>60</option>
								</select>
							</form>
					</div><!--.show_filter-->
					
					<?=$arResult["NAV_STRING"]?>
				</div><!--.pager-block-->
<div id="pic_popup_table"><div class="pop_img"><img src="#" alt="" /></div></div><!--#pic_popup-->