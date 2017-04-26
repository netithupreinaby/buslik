<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<br /><br />
<h2><?= GetMessage("SALE_OTLOG_TITLE")?></h2>
<?$i=0; foreach($arResult["ITEMS"]["DelDelCanBuy"] as $arBasketItems) $i = $i + $arBasketItems["QUANTITY"];?>


	 

			<table id="delayed_items">

			<?$i=0;foreach($arResult["ITEMS"]["DelDelCanBuy"] as $arBasketItems):$i++;?>
			<?			
			$arBasketItems['PRICE'] = str_replace('.','',str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arBasketItems['PRICE_FORMATED']));
			?>
			<tr id="<?=$arBasketItems["ID"]?>">
			  <td class="ibimg">
				<input type="hidden" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_D_<?=$arBasketItems["ID"]?>" value="" />
			  <input type="hidden" name="DELAY_<?=$arBasketItems["ID"] ?>" id="UN_DELAY_<?=$arBasketItems["ID"]?>" value="Y" />
			  
			  <img src="<?=$arBasketItems['PRODUCT_PICTURE_SRC'];?>" alt="" /></td>
			  <td class="ibname"><h3><a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>"><?=$arBasketItems["NAME"] ?></a></h3>
			  
			 <?foreach($arBasketItems['PROPS'] as $prop):?>
				<?if (is_array($arBasketItems["SKU_DATA"]))
				{
					$bSkip = false;
					foreach ($arBasketItems["SKU_DATA"] as $propId => $arProp)
					{
						if ($arProp["CODE"] == $prop["CODE"])
						{
							$bSkip = true;
							break;
						}
					}
					if ($bSkip)
						continue;
				}?>
				<b><?=$prop['NAME'];?>: <?=$prop['VALUE'];?></b>
				<br />
			  <?endforeach;?>
			  <?if (is_array($arBasketItems["SKU_DATA"]) && !empty($arBasketItems["SKU_DATA"])):?>
					<div class="ye-props bx_catalog_item_scu" id="<? echo $arBasketItems["ID"]; ?>_sku">
						<?foreach ($arBasketItems["SKU_DATA"] as $propId => $arProp):?>
							
						<div id="<?=$arProp['CODE'];?>_cont" class="ye-prop">
							<div class="bx_item_section_name_gray"><?=htmlspecialcharsex($arProp['NAME']); ?>:</div>
							<select 
								id="prop_<?=$arProp['CODE'];?>_<?=$arBasketItems["ID"]?>" 
								class="selectBox toggle-list" 
								<?//onchange="onSelectChange(this);"?>
							>
								<?foreach($arProp['VALUES'] as $arOneValue):?>
									<?
									$selected = "";
									foreach ($arBasketItems["PROPS"] as $arItemProp):
										if ($arItemProp["CODE"] == $arBasketItems["SKU_DATA"][$propId]["CODE"])
										{
											if ($arItemProp["VALUE"] == $arOneValue["NAME"] || $arItemProp["VALUE"] == $arOneValue["XML_ID"])
												$selected = "selected";
											}
									endforeach;
									?>
									<option 
										data-value-id="<?=$arOneValue["XML_ID"]?>"
										data-element="<?=$arBasketItems["ID"]?>"
										data-property="<?=$arProp["CODE"]?>"
										value="<?=$arOneValue['NAME']?>" 
										<?=$selected?>
										><?=htmlspecialcharsex($arOneValue['NAME'])?></option>
								<?endforeach;?>
							</select>
						</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			  
				<!--<p>  . , ,  </p></td>-->
			  <td class="ibprice"><span class="price"><?=$arBasketItems["PRICE"];?></span></td>
			  <td class="ibcount"><input disabled="disabled" type="text" name="QUANTITY_<?=$arBasketItems["ID"]?>"  id="QUANTITY_<?=$arBasketItems["ID"]?>" value="<?=$arBasketItems["QUANTITY"]?>" class="txt w32" />
				<button style="display: none;" onclick="setQuantity('#QUANTITY_<?=$arBasketItems["ID"]?>', '+'); return false;" class="button4">+</button> 
				<button style="display: none;" onclick="setQuantity('#QUANTITY_<?=$arBasketItems["ID"]?>', '-'); return false;" class="button5">-</button></td>
			  <td class="ibdel delay"> <button onclick="setDelay('#UN_DELAY_<?=$arBasketItems["ID"]?>', 'N'); return false;" class="button6 sym" title="<?=GetMessage('SALE_OTLOG_OFF')?>">&#0125;</button></td>
			  <td class="ibdel delete"><button onclick="setDelete('#DELETE_D_<?=$arBasketItems["ID"]?>'); return false;" class="button6 sym" title="<?=GetMessage('SALE_DELETE')?>">&#206;</button></td>
			  
			</tr>
			<?endforeach;?>
</table>
<?