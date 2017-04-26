<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && count($arResult["OFFERS"]) > 0):?>
<?$n_cols = 0;
$offers_iblock = false ;
?>
<div class="filter"></div>
<div class="catalog<?=$bComplete ? ' catalog_with_sets' : '';?>">
	<div class="catalog-list-light">
		<table>
			<tbody>	
				<tr>
					<td><?$n_cols++;?><!-- photo --></td> 
					<td  class="catlistname"><?$n_cols++;?></td>
					<?foreach($arParams["OFFERS_PROPERTY_CODE"] as $pid):?>
					<td>
						<?$n_cols++;?>
						<?
						$prop = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arResult["OFFERS"][0]["IBLOCK_ID"], "CODE" => $pid))->Fetch();
						if($prop["NAME"])
							echo $prop["NAME"];
						?>
					</td>
					<?endforeach?>
					<?if($arParams['USE_STORE'] == 'Y' && count($arResult['OFFERS'][0]['STORES'])):?>
						<?foreach($arResult['OFFERS'][0]['STORES'] as $arStore):?>
							<td class="stores" title="<?=$arStore['TITLE'];?><?=$arStore['ADDRESS'] ? ', '.$arStore['ADDRESS'] : '';?><?=$arStore['PHONE'] ? ', '.$arStore['PHONE'] : '';?><?=$arStore['SCHEDULE'] ? ', '.$arStore['SCHEDULE'] : '';?>"><?=substr($arStore['TITLE'], 0, 1);?></td>
						<?endforeach;?>
						<td colspan="5" class="this_store">&larr; <?=$arParams['MAIN_TITLE'];?><?$n_cols += 5?></td>
					<?else:?>
						<td><?$n_cols++;?><!-- quanity --></td>
						<td><?$n_cols++;?><!-- "+" --></td>
						<td><?$n_cols++;?><!-- "-" --></td>
						<td class="add_basket"><?$n_cols++;?></td>
					<?endif;?>
					
				</tr>
				<?foreach($arResult["OFFERS"] as $arOffer):
					$ppr = false;
					if(!$offers_iblock)
						$offers_iblock = $arOffer['IBLOCK_ID'];
					//get base price for SLIDER_FILTER (with SKU)
					$SLIDER_FILTER['PRICE'] += $arOffer['PRICES'][$base_price_group['NAME']]['VALUE'];

					//$path = $arOffer["PREVIEW_PICTURE"] > 0 ? $arOffer["PREVIEW_PICTURE"] : $arOffer["DETAIL_PICTURE"];
					//if($path) $path = CFile::GetPath($path);
					$path = CFile::GetPath(yenisite_GetPicSrc($arOffer));
				?>	
				<tr>
					<td>
					<?if($path):?>
						<img src="<?=CResizer2Resize::ResizeGD2($path, $arParams[RESIZER_SETS][DETAIL_IMG_ICON]);?>" alt="<?=$arOffer["NAME"]?>"/>
					<?endif?>
					</td>
					<td  class="catlistname">
						<b><?=$arOffer["NAME"]?></b>
					</td>                    
					<?foreach($arParams["OFFERS_PROPERTY_CODE"] as $pid):
						$arProperty = $arOffer["DISPLAY_PROPERTIES"][$pid];
					?>
					<td>
					<?
						if(is_array($arProperty["DISPLAY_VALUE"]))
							echo "<b>".strip_tags(implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]))."</b>";
						else
							echo "<b>".strip_tags($arProperty["DISPLAY_VALUE"])."</b>";
					?>
					</td>
					<?endforeach?>
					<?foreach($arOffer['STORES'] as $arStore):?>
						<td class="stores" title="<?=$arStore['YS_AMOUNT_TITLE']?>">
							<div class="product_amount">
								<div <?=$arStore['YS_AMOUNT_INDICATOR'] == 3 ? ' class="available"': '';?>></div>
								<div <?=$arStore['YS_AMOUNT_INDICATOR'] >= 2 ? ' class="available"': '';?>></div>
								<div <?=$arStore['YS_AMOUNT_INDICATOR'] >= 1 ? ' class="available"': '';?>></div>
							</div>
						</td>
					<?endforeach;?>																						
					<td class="priceTD">
					<?
						$pr = 0; $kr = 0;
						//foreach($arOffer[PRICES] as $k => $price)
							//if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
						
						foreach($arOffer['PRICES'] as $k => &$price){
							$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '</span><span class="rubl">'.GetMessage('RUB').'</span>',  '<span class="allSumMain">'.$price['PRINT_VALUE']);
							$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '</span><span class="rubl">'.GetMessage('RUB').'</span>',  '<span class="allSumMain">'.$price['PRINT_DISCOUNT_VALUE']);
							if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
						}			
					
						$disc = 0;
						if($arOffer['PRICES'][$kr]['DISCOUNT_VALUE'])
							$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
					?>   		
						<span class="price">
							<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
								<?=$arOffer['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arOffer['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
											<?if($arOffer['PRICES'][$kr]['DISCOUNT_VALUE'] && $arOffer['PRICES'][$kr]['DISCOUNT_VALUE'] != $arOffer['PRICES'][$kr]['VALUE']):?>
										<span class="oldprice"><?=$ppr;?></span>
								<?endif?>	
							<?if(method_exists($this, 'createFrame')) $frame->end();?>
						</span>
					</td>
					<?if($arOffer['CATALOG_QUANTITY_TRACE']=="N"||$arOffer['CATALOG_CAN_BUY_ZERO']=='Y'||$arOffer['CATALOG_QUANTITY']>0)://fix(02.12.2013)?>
					<td>
						<form name="a2b<?=$arOffer["ID"]?>" id="a2b<?=$arOffer["ID"]?>" action="#" method="post" enctype="multipart/form-data">
							<input id="q<?=$arOffer["ID"]?>" name="quantity" class="txt" value="1" type="text">
							<input name="id" value="<?=$arOffer["ID"]?>" type="hidden">							
							<input name="action" value="ADD2BASKET" type="hidden">
						</form>	
					</td>
					<td>
						<button onclick="setQuantityTable('#q<?=$arOffer["ID"]?>', '+', <?=$arOffer['CATALOG_MEASURE_RATIO']?>); return false;" class="button4">+</button>
					</td>
					<td>
						<button onclick="setQuantityTable('#q<?=$arOffer["ID"]?>', '-', <?=$arOffer['CATALOG_MEASURE_RATIO']?>); return false;" class="button5">-</button>
					</td>
					<td class="add_basket">
						<button id ="b-<?=$arOffer["ID"];?>" <?/*onclick="document.forms['a2b<?=$arOffer["ID"]?>'].submit(); return false;"*/?> class="button2 ajax_add2basket_q <?if($pr <= 0):?>button_in_basket<?endif;?> sku_button"><span></span></button>
					</td>
					<?endif;?>
				</tr>
				<?/* if(count($arOffer['STORES'])):?>
					<tr>
						<td colspan="<?=$n_cols;?>" class="sku_stores"></td>
					</tr>
				<?endif; */?>
				<?endforeach?>		
			</tbody>
		</table><br/><br/>
		<?if($arParams['USE_STORE'] == 'Y' && count($arResult['OFFERS'][0]['STORES'])):?>
			<div class="stores_list">
				<h3><?=$arParams['MAIN_TITLE'];?>:</h3>
				<?foreach($arResult['OFFERS'][0]['STORES'] as $arStore):?>
					<p><b><?=substr($arStore['TITLE'], 0, 1);?></b><?=substr($arStore['TITLE'], 1);?><?=$arStore['ADDRESS'] ? ', '.$arStore['ADDRESS'] : '';?><?=$arStore['PHONE'] ? ', '.$arStore['PHONE'] : '';?><?=$arStore['SCHEDULE'] ? ', '.$arStore['SCHEDULE'] : '';?></p>
				<?endforeach;?>
			</div>
		<?endif;?>
	</div>
</div>
<hidden name="ajax_iblock_id_sku" id="ajax_iblock_id_sku" value="<?=$offers_iblock;?>"/>
<?
if(count($arResult["OFFERS"])>1):
	//arithmetic average from SKU base prices
	$SLIDER_FILTER['PRICE'] = $SLIDER_FILTER['PRICE']/count($arResult["OFFERS"]);
endif;
?>
<?endif?>