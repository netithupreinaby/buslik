<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if(empty($arResult["ITEMS"])) return 0;?>

<?
// FOR SKU as SelectBox
$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS']))
{
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		ob_start();?>
		<div id="#ITEM#_prop_<?=$arProp['ID'];?>_cont" class="ye-prop">
			<div class="bx_item_section_name_gray"><?=htmlspecialcharsex($arProp['NAME']); ?>:</div>
			<select 
				id="#ITEM#_prop_<?=$arProp['ID'];?>_list" 
				class="selectBox toggle-list" 
				name="<?=$arParams["PRODUCT_PROPS_VARIABLE"]?>[<?=$pid?>]" 
				<?//onchange="onSelectChange(this);"?>
			>
				<?foreach($arProp['VALUES'] as $arOneValue):?>
					<option 
						data-treevalue="<?=$arProp['ID'].'_'.$arOneValue['ID']; ?>"
						data-onevalue="<?=$arOneValue['ID']; ?>"
						value="<?=$arOneValue['ID']?>" ><?=htmlspecialcharsex($arOneValue['NAME'])?>
					</option>
				<?endforeach;?>
			</select>
		</div><?
		
		$arSkuTemplate[$arProp['CODE']] = ob_get_contents();
		ob_end_clean();
	}
	unset($arProp);
}
?>
	<div class="catalog-list-light">
		<?$n_cols = 0; 
		$n_img = 100;
		$first_group = current($arResult['ITEMS']) ;?>
		<table class="ys_sets_table">
			<thead>
				<tr>
					<th><?=GetMessage('COL_NAME_PHOTO');?><?$n_cols ++;?></th>
					<?foreach($arParams['PROPERTIES'] as $prop_code):
						if($prop_code):?>
						<th><?=$first_group[0]['DISPLAY_PROPERTIES'][$prop_code]['NAME'];?><?$n_cols ++;?></th>
						<?endif;
					endforeach;?>
					<th><?=GetMessage('COL_NAME_NAME');?><?$n_cols ++;?></th>
					<?if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB'):?>
						<th><?=GetMessage('COL_NAME_SKU');?><?$n_cols ++;?></th>
					<?endif;?>
					<?if($arParams['DESCRIPTION'] == 'PREVIEW_TEXT' || $arParams['DESCRIPTION'] == 'DETAIL_TEXT'):?>
						<th><?=GetMessage('COL_NAME_DESCR');?><?$n_cols ++;?></th>
					<?endif;?>
					<th><?=GetMessage('COL_NAME_PRICE');?><?$n_cols ++;?></th>
					<th><?=GetMessage('COL_NAME_BUY');?><?$n_cols ++;?></th>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult['ITEMS'] as $group_id => $arGroup):?>
					<?if(count($arGroup)):?>
						<tr>
							<td class="ys_sets_groups" colspan="<?=$n_cols;?>"><?=$arResult['GROUPS'][$group_id]['NAME'];?></td>
						</tr>
						<?foreach($arGroup as $arItem):?>
							<?$strMainID = $arItem['ID'];
							// FOR SKU as SelectBox
							$arItemIDs = array(
								'ID' => 'set-'.$strMainID,
								'PICT' => 'product_photo_'.$arItem['ID'],
								'SECOND_PICT' => $strMainID.'_secondpict',

								'QUANTITY' => $strMainID.'_quantity',
								'QUANTITY_DOWN' => $strMainID.'_quant_down',
								'QUANTITY_UP' => $strMainID.'_quant_up',
								'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
								'BUY_LINK' => 'BUY_'.$arItem['ID'],
								'SUBSCRIBE_LINK' => $strMainID.'_subscribe',

								'PRICE' => 'product_price_'.$arItem['ID'],
								'DSC_PERC' => $strMainID.'_dsc_perc',
								'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

								'PROP_DIV' => $strMainID.'_sku_tree',
								'PROP' => $strMainID.'_prop_',
								'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
								'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
							);?>
						<tr id=<?=$arItemIDs['ID']?>>
							<td class="ys_sets_photo_col">
								<?if($arItem['PHOTO_SRC']):?>
									<a class="ico_<?=$n_img++;?>" rel="tag" href="<?=$arItem['PHOTO_SRC']['BIG'];?>">
										<img class="product_photo" alt="<?=$arItem['NAME'];?>" src="<?=$arItem['PHOTO_SRC']['SMALL'];?>">
									</a>
								<?endif;?>
							</td>
							<?foreach($arParams['PROPERTIES'] as $prop_code):
								if($prop_code):?>
								<td class="ys_sets_prop_col">
									<?=$arItem['DISPLAY_PROPERTIES'][$prop_code]['VALUE'];?>
								</td>
								<?endif;
							endforeach;?>
							<td class="ys_sets_name_col">
								<?if($arItem['YENISITE_SET']['hide_link']):?>
									<?=$arItem['NAME'];?>
								<?else:?>
									<a class="complect_set_product" href="<?=$arItem['DETAIL_PAGE_URL'];?>" title="<?=$arItem['NAME'];?>"><?=$arItem['NAME'];?></a>
								<?endif;?>
							</td>
							<?// FOR SKU as SelectBox
							if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB'):?>
							<td class="ys_sets_sku_col">
								<div class="props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
									<?foreach ($arSkuTemplate as $code => $strTemplate)
									{
										if (!isset($arItem['OFFERS_PROP'][$code]))
											continue;
										echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate);
									}?>
								</div>	
							</td>
							<?endif;?>
							
							<?if($arParams['DESCRIPTION'] == 'PREVIEW_TEXT' || $arParams['DESCRIPTION'] == 'DETAIL_TEXT'):?>
								<td class="ys_sets_description_col">
									<?=$arItem[$arParams['DESCRIPTION']];?>
								</td>
							<?endif;?>
							<td class="priceTD">
								<span class="price">
									<?if(count($arItem["OFFERS"])<=0 || ($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && count($arItem['OFFERS_PROP'])>0 )):?>
										<?
										$arItem['PRICE']['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem['PRICE']['PRINT_VALUE']);
										
										if($arItem['PRICE']['PRINT_DISCOUNT_VALUE']):?>
											<?$arItem['PRICE']['PRINT_DISCOUNT_VALUE'] =str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem['PRICE']['PRINT_DISCOUNT_VALUE']);?>
											<?=$arItem['PRICE']['PRINT_DISCOUNT_VALUE'];?>
											<span class="oldprice"><?=$arItem['PRICE']['PRINT_VALUE'];?></span>
											<input type="hidden" class="ys_complete_set_price" id="PRICE_<?=$arItem['ID'];?>" value="<?=$arItem['PRICE']['DISCOUNT_VALUE'];?>"/>
										<?else:?>
											<?=$arItem['PRICE']['PRINT_VALUE'];?>
											<input type="hidden" class="ys_complete_set_price" id="PRICE_<?=$arItem['ID'];?>" value="<?=$arItem['PRICE']['VALUE'];?>"/>
										<?endif;?>
									<?endif;?>
								</span>
							</td>
							<td class="ys_sets_check_buy">
								<?if(count($arItem["OFFERS"])<=0 || ($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && count($arItem['OFFERS_PROP'])>0 )):?>
									<input type="checkbox" class="checkbox ys_complete_set_buy" id="BUY_<?=$arItem['ID'];?>"<?=$arItem['YENISITE_SET']['buy_checked'] ? ' checked' : '';?>/>
								<?endif;?>
							</td>
							
							
							<?if(count($arResult['SKU_PROPS']) && count($arItem['JS_OFFERS']) && count($arItem['OFFERS_SELECTED'])):
							
								$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $arItem['ID']);

								$arSkuProps = array();
								
								foreach ($arResult['SKU_PROPS'] as $arOneProp)
								{
									if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
										continue;
									$arSkuProps[] = array(
										'ID' => $arOneProp['ID'],
										'SHOW_MODE' => $arOneProp['SHOW_MODE'],
										'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
									);
								}
								
								$arJSParams = array(
									'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
									'SHOW_ABSENT' => true,
									'SHOW_OLD_PRICE' => ($arItem['PRICES'][$kr]['DISCOUNT_VALUE'] && $arItem['PRICES'][$kr]['DISCOUNT_VALUE'] != $arItem['PRICES'][$kr]['VALUE']),
									'DEFAULT_PICTURE' => array(
										'PICTURE' => $arItem['PHOTO_SRC']['BIG'],
										'PICTURE_SMALL' => $arItem['PHOTO_SRC']['SMALL']
									),
									'VISUAL' => array(
										'ID' => $arItemIDs['ID'],
										'TREE_ID' => $arItemIDs['PROP_DIV'],
										'TREE_ITEM_ID' => $arItemIDs['PROP'],
										'BUY_ID' => $arItemIDs['BUY_LINK'],
									),
									'PRODUCT' => array(
										'ID' => $arItem['ID'],
										'NAME' => $arItem['~NAME']
									),
									'OFFERS' => $arItem['JS_OFFERS'],
									'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
									'TREE_PROPS' => $arSkuProps,
									'VIEW_MODE' => 'table',
								);?>	
									
								<script language="javascript" type="text/javascript">
									var <? echo $strObName; ?> = new JCCatalogSets(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
								</script>
							<?endif;?>
						</tr>
						<?endforeach;?>
					<?endif;?>
				<?endforeach;?>
				<tr>
					<td class="ys_sets_result" colspan="<?=$n_cols-2;?>"><?=GetMessage('ALL_SUM');?> </td><td class="priceTD"><span class="price"><span id="allSum"><?=str_replace(GetMessage('RUB_REPLACE'),'</span> <span class="rubl">'.GetMessage('RUB').'</span>', $arResult['allSum_FORMAT']);?></span>
					<input type="hidden" id="allSum_val" value="<?=$arResult['allSum'];?>"/>
					</td><td></td>
				</tr>
			</tbody>
		</table>
	</div>
<script>
function yenisite_number_format_sets (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

$(document).ready(function(){
	//$("#ys_top_price").html('<span class="allSumMain"><?=str_replace(GetMessage('RUB_REPLACE'), '</span><span class="rubl">'.GetMessage('RUB').'</span>', $arResult['allSum_FORMAT']);?></span>') ;
	$(".ys_complete_set_buy").change(function(){
		var sum = Number($('#allSum_val').val());
		var link_add2basket = $('#add2basket').attr('href') ;
		var product_id = $(this).attr('id').replace('BUY_', '') ;
		if($(this).prop("checked") == 1){
			if(!(link_add2basket.indexOf('&id['+product_id+']='+product_id) + 1)) { // [] to ['+product_id+']  , modify by Ivan, 09.10.2013, for ajax add to basket,
				link_add2basket += '&id['+product_id+']='+product_id ;  // [] to ['+product_id+']  , modify by Ivan, 09.10.2013, for ajax add to basket,
			}
			sum += Number($('#PRICE_'+product_id).val()) ;
		}
		else{
			link_add2basket = link_add2basket.replace('&id['+product_id+']='+product_id, '') ;  // [] to ['+product_id+']  , modify by Ivan, 09.10.2013, for ajax add to basket,
			sum -= Number($('#PRICE_'+product_id).val()) ;
		}
		$('#add2basket').attr('href', link_add2basket);
		$('#add2basket2').attr('href', link_add2basket);
		$('#allSum_val').val(sum);
		var new_sum = yenisite_number_format_sets(sum, 0, '.', ' ') ;
		if($('#allSum').next('.rubl').size() <= 0)
		{
			var pricePrintTemplate = $('#allSum').html().replace(/[\s,.]/g, '');
			new_sum = pricePrintTemplate.replace(/\d+/g , new_sum);
		}
		$('#allSum').html(new_sum + " ");
		if ( $('.allSumMain:first').length )
			$('.allSumMain:first').html(new_sum + " ");
	});
	
	$(".ys_sets_photo_col a").click(function(){
		$('#mask, #pic_popup').show();
		$('.pop_img img').attr('src', $(this).attr('href'));
		$('.pop_descr').html($(this).find('img').attr('alt'));

		ico = parseInt($(this).attr('class').replace('ico_',''));
		
		return false;
	});
});
</script>