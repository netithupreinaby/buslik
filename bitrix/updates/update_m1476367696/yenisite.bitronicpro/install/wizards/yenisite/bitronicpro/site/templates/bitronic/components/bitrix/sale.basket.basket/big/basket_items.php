<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $basket_set;
 $basket_set = $arParams[BASKET_PHOTO] ;
?>
			 <input type="hidden" id="BasketRefresh" name="BasketRefresh" value="" />
		  <input type="hidden" value="" name="BasketOrder"  id="basketOrderButton2">		


<?$i=0; foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) $i = $i + $arBasketItems["QUANTITY"];?>


	 

			<table>

			<?$i=0;foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems):$i++;?>
			<?			
			$arBasketItems[PRICE] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arBasketItems[PRICE_FORMATED]);
			?>
			<tr>
			  <td class="ibimg">
			 	  
			  <input type="hidden" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_<?=$i?>" value="" />
			  <?/* 
				CModule::IncludeModule('iblock');
				CModule::IncludeModule('yenisite.resizer2');
				$res1 = CIBlockElement::GetList(array(), array('ID' => $arBasketItems[PRODUCT_ID]), false, false)->Fetch();
				$res= CIBlockElement::GetProperty($res1[IBLOCK_ID], $arBasketItems[PRODUCT_ID], array("value_id" => "asc"), Array("CODE"=>"MORE_PHOTO"))->Fetch();
				$path = CFile::GetPath($res[VALUE]);
				if(!$path) $path = CFile::GetPath($res1[DETAIL_PICTURE]); */
			  ?>
			  <img src="<?=$arBasketItems['PRODUCT_PICTURE_SRC'];?>" alt="" /></td>
			  <td class="ibname"><h3><a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>"><?=$arBasketItems["NAME"] ?></a></h3>
			  
			  <?foreach($arBasketItems['PROPS'] as $prop):?>
				<b><?=$prop['NAME'];?>: <?=$prop['VALUE'];?></b>
				<br />
			  <?endforeach;?>
			  
				<!--<p>  . , ,  </p></td>-->
			  <td class="ibprice"><span class="price"><?=$arBasketItems["PRICE"];?></span></td>
			  <td class="ibcount"><input type="text" name="QUANTITY_<?=$arBasketItems["ID"]?>"  id="QUANTITY_<?=$i?>" value="<?=$arBasketItems["QUANTITY"]?>" class="txt w32" />
				<button onclick="setQuantity('#QUANTITY_<?=$i?>', '+'); return false;" class="button4">+</button> <button onclick="setQuantity('#QUANTITY_<?=$i?>', '-'); return false;" class="button5">-</button></td>
			  <td class="ibdel"><button onclick="setDelete('#DELETE_<?=$i?>'); return false;" class="button6 sym">&#206;</button></td>
			</tr>
			<?endforeach;?>
		  </table>

						
						<div class="make_order"> 
						<div class="cupon">
						
					<?if ($arParams["HIDE_COUPON"] != "Y"):?>
	
						<label><?= GetMessage("STB_COUPON_PROMT") ?></label>
						<input class="txt w180" type="text" name="COUPON" value="<?=$arResult["COUPON"]?>" size="20">
					
					<?endif;?>
                        </div><!--.cupon-->
						
						<span class="sum"><?= GetMessage("SALE_ITOGO")?>: <strong><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arResult["allSum_FORMATED"]);?></strong></span> <button onclick="$('#basket_form').attr('action', '<?=$arParams[PATH_TO_ORDER]?>').submit();  return false;" class="button3"><?echo GetMessage("SALE_ORDER")?></button></div>
		  <!--.make_order-->