<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
			 <input type="hidden" id="BasketRefresh" name="BasketRefresh" value="" />
			  <input type="hidden" value="" name="BasketOrder"  id="basketOrderButton2">		

<?$i=0; foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) $i = $i + $arBasketItems["QUANTITY"];?>

<div class='node'>
	
	  	<a href="#" class="count_link"><?=GetMessage("IN_BASKET");?></a> <strong><?=$i?></strong> <?=GetMessage("ITEMS");?> <br /><?=GetMessage("SUMM");?> <strong><?=number_format($arResult["allSum"], 0, '.', ' ');?></strong> <b class='rubl'><?=GetMessage('RUB');?></b> <span></span>
</div>		
		<div class="basket-popup closed" id="bag-popup">
		<div class="rasp"></div>
		  <table>
			<tr>
			  <td class="t_photo"><?=GetMessage("SALE_PHOTO");?></th>
			  <td class="t_name"><?=GetMessage("SALE_NAME");?></th>
			  <td class="t_price"><?=GetMessage("SALE_PRICE");?></th>
			  <td class="t_count"><?=GetMessage("SALE_QUANTITY");?></th>
			  <td class="t_delete"><?=GetMessage("SALE_DELETE");?></th>
			</tr>
			</table>
			<div class="bask_wr">
			<table>

			<?$i=0;foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems):$i++;?>
			<tr>
			  <td class="t_photo">
			 	  
			  <input type="hidden" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_<?=$i?>" value="" />
			  <?
				CModule::IncludeModule('iblock');
				CModule::IncludeModule('yenisite.resizer2');
				$res1 = CIBlockElement::GetList(array(), array('ID' => $arBasketItems[PRODUCT_ID]), false, false)->Fetch();
				$res= CIBlockElement::GetProperty($res1[IBLOCK_ID], $arBasketItems[PRODUCT_ID], array("value_id" => "asc"), Array("CODE"=>"MORE_PHOTO"))->Fetch();
				$path = CFile::GetPath($res[VALUE]);
				if(!$path) $path = CFile::GetPath($res1[DETAIL_PICTURE]);
			  ?>
			  <img src="<?=CResizer2Resize::ResizeGD2($path, $arParams[BASKET_PHOTO]);?>" alt="" /></td>
			  <td class="t_name"><h3><a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>"><?=$arBasketItems["NAME"] ?></a></h3>
				<!--<p>  . , ,  </p></td>-->
			  <td class="t_price"><span class="price"><?=number_format($arBasketItems["PRICE"], 0, '.', ' ');?> <span class="rubl"><?=GetMessage('RUB');?></span></span></td>
			  <td class="t_count"><input type="text" name="QUANTITY_<?=$arBasketItems["ID"]?>"  id="QUANTITY_<?=$i?>" value="<?=$arBasketItems["QUANTITY"]?>" class="txt w32" />
				<button onclick="setQuantity('#QUANTITY_<?=$i?>', '+'); return false;" class="button4">+</button> <button onclick="setQuantity('#QUANTITY_<?=$i?>', '-'); return false;" class="button5">-</button></td>
			  <td class="t_delete"><button onclick="setDelete('#DELETE_<?=$i?>'); return false;" class="button6 sym">&#206;</button></td>
			</tr>
			<?endforeach;?>
		  </table>
		  	</div><!--.bask_wr-->
			
		  <div class="make_order"> <span class="sum"><?= GetMessage("SALE_ITOGO")?>: <strong><?=number_format($arResult["allSum"], 0, '.', ' ');?><span class="rubl noabs"><?=GetMessage('RUB');?></span></strong></span> <button onclick="$('#basket_form').attr('action', '<?=$arParams[PATH_TO_ORDER]?>').submit();  return false;" class="button3"><?echo GetMessage("SALE_ORDER")?></button></div>
		  <!--.make_order-->
		  <div class="pbot"></div>
		</div>
		<!--.basket-popup-->
