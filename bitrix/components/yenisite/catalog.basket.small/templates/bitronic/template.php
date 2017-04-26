<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if($arParams['IT_IS_AJAX_CALL'] != 'Y'):?>
<div class="yen-bs-box">
<?endif;?>
	<div class="yen-bs-count yen-bs-up">
		<div class="yen-bs-node">
		<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
			<a href="javascript:void(0);" class="yen-bs-count_link" title="<?=GetMessage('YS_BS_IN_BASKET');?>"><?=GetMessage('YS_BS_IN_BASKET');?></a>
			<strong id="YS_BS_COUNT_PRODUCT"><?=$arResult['NUM_PRODUCTS'];?></strong><span id="YS_BS_COUNT_STRING"><?=yenisite_declOfNum($arResult['NUM_PRODUCTS'], array(GetMessage('YS_BS_PRODUCT1'), GetMessage('YS_BS_PRODUCT2'), GetMessage('YS_BS_PRODUCT0')));?></span>
			<?=GetMessage('YS_BS_IN_SUMM');?><strong id="YS_BS_TOTALSUM_TOP"><?=$arResult['allSum_FORMAT'];?></strong>
			<b class="yen-bs-rubl"><?=$arResult['CURRENCY'];?></b><img class="yen-bs-icon" src="<?=$arParams['BASKET_ICON'];?>" title="<?=GetMessage('YS_BS_IN_BASKET');?>" alt="<?=GetMessage('YS_BS_BASKET');?>"/>
		<?if(method_exists($this, 'createFrame')) $frame->end();?>
		</div>
		<div class="yen-bs-popup <?=$arParams['YS_BS_OPENED'] !='Y' ? 'yen-bs-closed' : 'yen-bs-opened';?>" id="yen-bs-bag-popup">
			<div class="yen-bs-rasp"></div>
			<a class="yen-bs-close" onclick="yenisite_bs_close()" title="<?=GetMessage('YS_BS_CLOSE');?>"><?=GetMessage("YS_BS_CLOSE_SYM_NEW");?></a>
			<table>
				<tbody>
					<tr>
						<td class="yen-bs-t_photo"><?=GetMessage('YS_BS_TH_PHOTO');?></td>
						<td class="yen-bs-t_name"><?=GetMessage('YS_BS_TH_NAME');?></td>
						<td class="yen-bs-t_price"><?=GetMessage('YS_BS_TH_PRICE');?></td>
						<td class="yen-bs-t_count"><?=GetMessage('YS_BS_TH_COUNT');?></td>
						<td class="yen-bs-t_delete"><?=GetMessage('YS_BS_TH_DELETE');?></td>
					</tr>
				</tbody>
			</table>
			<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
			<?if ($arResult["READY"]=="Y"):?>			
				<div class="yen-bs-bask_wr">
					<table>
						<tbody>
							<?foreach ($arResult['ITEMS'] as $i=>$arItem)
							{
							
								if($arItem['DELAY']=='N' && $arItem['CAN_BUY']=='Y')
								{?>								
								<tr id="YS_BS_ROW_<?=$i;?>">
									<td class="yen-bs-t_photo">
										<a href="<?=$arItem["DETAIL_PAGE_URL"];?>" title="<?=$arItem['NAME'];?>">
										<img src="<?=$arItem['PRODUCT_PICTURE_SRC'] ? $arItem['PRODUCT_PICTURE_SRC'] : $arParams['PATH_TO_NO_PHOTO'];?>" alt="<?=$arItem['NAME'];?>">
										</a>
									</td>
									<td class="yen-bs-t_name">
										<h3>
											<a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
												<?=$arItem['NAME'];?>
											</a>
										</h3>
<?
										foreach ($arItem['PRODUCT_PROPERTIES'] as $arProp):
?>
										<div><strong><?=$arProp['NAME']?>: <?=$arProp['VALUE']?></strong></div>
<?
										endforeach;
?>
									</td>
									<td class="yen-bs-t_price">
										<span class="yen-bs-price">
											<?=$arItem["PRICE"];?><span class="yen-bs-rubl"><?=$arResult['CURRENCY'];?></span>
											<input type="hidden" name="YS_BS_PRICE_<?=$arItem['ID'];?>" id="YS_BS_PRICE_<?=$i;?>" value="<?=$arItem["PRICE"];?>"/>
										</span>
									</td>
									
									<td class="yen-bs-t_count">
										<input type="hidden" name="key" id="YS_BS_KEY_<?=$i;?>" value="<?=$arItem["KEY"];?>" >
										<?if($arParams['CHANGE_QUANTITY'] != 'Y'):?>
										<input type="hidden" name="old_q" id="YS_BS_OLD_Q_<?=$i;?>" value="<?=$arItem["QUANTITY"];?>">
										<input autocomplete="off" type="text" name="YS_BS_QUANTITY_<?=$arItem['ID'];?>" id="YS_BS_QUANTITY_<?=$i;?>" value="<?=$arItem["QUANTITY"];?>" class="yen-bs-txt yen-bs-w32" onchange="yen_setQuantity('<?=$i;?>', 'c'); return false;">
										
										<button onclick="yen_setQuantity('<?=$i;?>', 'p'); return false;" class="yen-bs-button4" title="<?=GetMessage('YS_BS_BUTTON_PLUS');?>">+</button> <button onclick="yen_setQuantity('<?=$i;?>', 'm'); return false;" class="yen-bs-button5" title="<?=GetMessage('YS_BS_BUTTON_MINUS');?>">-</button>
										<?else:?>
											<input type="text" name="YS_BS_QUANTITY_<?=$arItem['ID'];?>" id="YS_BS_QUANTITY_<?=$i;?>" disabled="disabled" value="<?=$arItem["QUANTITY"];?>" class="yen-bs-txt yen-bs-w32">
										<?endif;?>
									</td>
									<td class="yen-bs-t_delete">
										<button onclick="yen_setQuantity('<?=$i;?>', 'd'); return false;" class="yen-ys-button6 yen-bs-sym" title="<?=GetMessage('YS_BS_BUTTON_DEL');?>"><?=GetMessage("YS_BS_DELETE_NEW");?></button>
										<input type="hidden" name="yen-bs-eid" class="yen-bs-eid" value="<?=$arItem['ID'];?>"/>
									</td>
								</tr>
								<?
								}
							}?>
						</tbody>
					</table>
				</div><!--/bask_wr-->
			<?endif;?>
			<input type="hidden" name="all_sum" id="yen-bs-all-sum" value="<?=$arResult['allSum'];?>"/>
			<div class="yen-bs-make_order"><span class="yen-bs-sum"><?=GetMessage('YS_BS_IN_TOTAL');?><strong id="YS_BS_TOTALSUM"><?=$arResult['allSum_FORMAT'];?></strong> <strong><span class="yen-bs-rubl yen-bs-noabs"><?=$arResult['CURRENCY'];?></span></strong></span>				
				<button onclick="$('#yen-bs-basket_form').submit();  return false;" class="yen-bs-button3"><?=GetMessage('YS_BS_ORDER');?></button>
				<form id="yen-bs-basket_form" method="get" action="<?=$arParams["BASKET_URL"]?>"></form>
			</div> <!--/make_order-->
			<div class="yen-bs-pbot"></div>
			<?if(method_exists($this, 'createFrame')) $frame->end();?>
		</div> <!-- /basket-popup -->
	</div> <!-- /yen-bs-count -->	
<?if($arParams['IT_IS_AJAX_CALL'] != 'Y'):?>
</div> <!-- /yen-bs-box -->
<?endif;?>
<!-- end basket small -->
