<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!IntVal($arParams["COMPARE_IMG"]))
	$arParams["COMPARE_IMG"] = 3;?>
<?$arHideField = array('DETAIL_PICTURE','PREVIEW_PICTURE');?>

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<div class="catalog-compare-result">
	<input type="hidden" name="ajax_iblock_id" id="ajax_iblock_id" value="<?=$arParams['IBLOCK_ID'];?>"/>
<a name="compare_table"></a>
	<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
		<input type="hidden" name="action" value="ADD_FEATURE" />
		<input id='compare_var_input' type="hidden" name="" value="" />
	<noindex><p>
	<?if($arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow" onclick="addProp('DIFFERENT' , 'N',event); return false;"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a><?
	else:
		?><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?><?
	endif
	?>&nbsp;|&nbsp;<?
	if(!$arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow" onclick="addProp('DIFFERENT' , 'Y',event); return false;"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a><?
	else:
		?><?=GetMessage("CATALOG_ONLY_DIFFERENT")?><?
	endif?>
	</p></noindex>
	<?if(!empty($arResult["DELETED_PROPERTIES"]) || !empty($arResult["DELETED_OFFER_FIELDS"]) || !empty($arResult["DELETED_OFFER_PROPS"])):?>
		<noindex><p>
		
		<?=GetMessage("CATALOG_REMOVED_FEATURES")?>:
		<?foreach($arResult["DELETED_PROPERTIES"] as $arProperty):?>
			<a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=ADD_FEATURE&pr_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow" onclick="addProp('pr_code' , '<?=$arProperty["CODE"]?>',event); return false;" ><?=$arProperty["NAME"]?></a>
		<?endforeach?>
		<?foreach($arResult["DELETED_OFFER_FIELDS"] as $code):?>
			<a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=ADD_FEATURE&of_code=".$code,array("op_code","of_code","pr_code","action")))?>" rel="nofollow" onclick="addProp('of_code' , '<?=$arProperty["CODE"]?>',event); return false;"><?=GetMessage("IBLOCK_FIELD_".$code)?></a>
		<?endforeach?>
		<?foreach($arResult["DELETED_OFFER_PROPERTIES"] as $arProperty):?>
			<a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=ADD_FEATURE&op_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow" onclick="addProp('op_code' , '<?=$arProperty["CODE"]?>',event); return false;"><?=$arProperty["NAME"]?></a>
		<?endforeach?>
		</p></noindex>
	<?endif?>
	</form>
	<?if(count($arResult["SHOW_PROPERTIES"])>0):?>
		<p>
		
			<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
			<div class='remblock'>
			<b><?=GetMessage("CATALOG_REMOVE_FEATURES")?>:</b><br /><br />
			<?foreach($arResult["SHOW_PROPERTIES"] as $arProperty):?>
				<label class='tabletd'><div class="checker minpad"><input class="checkbox" type="checkbox" name="pr_code[]" value="<?=$arProperty["CODE"]?>" /></div><?=$arProperty["NAME"]?></label>
			<?endforeach?>
			<?foreach($arResult["SHOW_OFFER_FIELDS"] as $code):?>
				<label><div class="checker minpad"><input type="checkbox" class="checkbox"  name="of_code[]" value="<?=$code?>" /></div><?=GetMessage("IBLOCK_FIELD_".$code)?></label><br/>
				<br />
			<?endforeach?>
			<?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $arProperty):?>
			<label><div class="checker minpad"><input class="checkbox"  type="checkbox" name="op_code[]" value="<?=$arProperty["CODE"]?>" /></div><?=$arProperty["NAME"]?></label><br/>
				<br />
			<?endforeach?>
			<br/>
			<br style="clear: both;">
		</div>
		<br/>

			
			<input type="hidden" name="action" value="DELETE_FEATURE" />
			<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
			<input class='button' type="submit" value="<?=GetMessage("CATALOG_REMOVE_FEATURES")?>">
			</form>
		</p>
	<?endif?>
<br />
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<table class="data-table" cellspacing="0" cellpadding="0" border="0">
		<thead>
		<tr>
			<td valign="top">&nbsp;</td>
			<?foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top" width="<?=round(100/count($arResult["ITEMS"]))?>%">
					<input type="checkbox" class="checkbox" name="ID[]" value="<?=$arElement["ID"]?>" />
				</td>
			<?endforeach?>
		</tr>
		<?foreach($arResult["ITEMS"][0]["FIELDS"] as $code=>$field):?>
			<?if(in_array($code,$arHideField)) continue;?>
		<tr>
			<th valign="top" nowrap><?=GetMessage("IBLOCK_FIELD_".$code)?></th>
			<?foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top">
					<?switch($code):
						case "NAME":							
								global $basket_set;
								$path = CFile::GetPath(yenisite_GetPicSrc($arElement));
					?>		
					<div>
							<span class="stick_img">
								<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
									"CATALOG" => "Y",
									"ELEMENT" => $arElement,
									"IMAGE_SET" => $arParams["COMPARE_IMG"],
									"STICKER_NEW" => $arParams["STICKER_NEW"],
									"STICKER_HIT" => $arParams["STICKER_HIT"],
									"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
									"WIDTH" => 75,
									),
									$component, array("HIDE_ICONS"=>"Y")
								);?>
								<img id="product_photo_<?=$arElement["ID"]?>" src="<?=CResizer2Resize::ResizeGD2($path, $arParams["COMPARE_IMG"]);?>" alt="" />
							</span>
							</div>
							<br/>
							<a id="product_detail_<?=$arElement['ID'];?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>" data-el-code="<?=$arElement['CODE']?>"><?=$arElement[$code]?></a>
							
							<?
							if($arElement["CAN_BUY"] || $arElement['CATALOG_AVAILABLE']):
								if($arElement["OFFERS_EXIST"]):
									?><div class="ys-compare-basket-button" ><a class="button2" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage("CATALOG_COMPARE_BUY"); ?></span></a></div><?
								else:
									?><div class="ys-compare-basket-button" ><a class="button2 ajax_add2basket" id="b-<?=$arElement['ID'];?>" href="<?=$arElement["ADD_URL"]?>" rel="nofollow"><span><?=GetMessage("CATALOG_COMPARE_BUY"); ?></span></a></div><?
								endif;
							/*elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):
								?><br /><?=GetMessage("CATALOG_NOT_AVAILABLE")?><?*/
							endif;?>
							
							<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
								<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
								<?else:?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
								<?endif;
							break;
						case "PREVIEW_PICTURE":
						case "DETAIL_PICTURE":
							if(is_array($arElement["FIELDS"][$code])):?>
								<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img border="0" src="<?=$arElement["FIELDS"][$code]["SRC"]?>" width="<?=$arElement["FIELDS"][$code]["WIDTH"]?>" height="<?=$arElement["FIELDS"][$code]["HEIGHT"]?>" alt="<?=$arElement["FIELDS"][$code]["ALT"]?>" /></a>
							<?endif;
							break;
						default:
							echo $arElement["FIELDS"][$code];
							break;
					endswitch;
					?>
				</td>
			<?endforeach?>
		</tr>
		<?endforeach;?>
		</thead>
		<?foreach($arResult["ITEMS"][0]["PRICES"] as $code=>$arPrice):?>
			<?if($arPrice["CAN_ACCESS"]):?>
			<tr>
				<th valign="top" nowrap><?=$arResult["PRICES"][$code]["TITLE"]?></th>
				<?foreach($arResult["ITEMS"] as $arElement):?>
					<td valign="top">
						<?if($arElement["PRICES"][$code]["CAN_ACCESS"]):?>
							<b><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arElement["PRICES"][$code]["PRINT_VALUE"])?></b>
						<?endif;?>
					</td>
				<?endforeach?>
			</tr>
			<?endif;?>
		<?endforeach;?>
		<?foreach($arResult["SHOW_PROPERTIES"] as $code=>$arProperty):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
				
				    
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=$arProperty["NAME"]?>&nbsp;</th>
					<?foreach($arResult["ITEMS"] as $arElement):?>
					
<?    if(substr_count($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"], "a href") > 0) 
        $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = strip_tags($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
?>					
					
						<?if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?else:?>	

						<th valign="top">&nbsp;
							<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</th>
						<?endif?>
					<?endforeach?>
				</tr>
			<?endif?>
		<?endforeach;?>
		<?foreach($arResult["SHOW_OFFER_FIELDS"] as $code):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$Value = $arElement["OFFER_FIELDS"][$code];
				if(is_array($Value))
				{
					sort($Value);
					$Value = implode(" / ", $Value);
				}
				$arCompare[] = $Value;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=GetMessage("IBLOCK_FIELD_".$code)?>&nbsp;</th>
					<?foreach($arResult["ITEMS"] as $arElement):?>
						<?if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
						</td>
						<?else:?>
						<th valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
						</th>
						<?endif?>
					<?endforeach?>
				</tr>
			<?endif?>
		<?endforeach;?>
		<?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=$arProperty["NAME"]?>&nbsp;</th>
					<?foreach($arResult["ITEMS"] as $arElement):?>
						<?if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?else:?>
						<th valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</th>
						<?endif?>
					<?endforeach?>
				</tr>
			<?endif?>
		<?endforeach;?>
	</table>
	<br />
	<input class='button' type="submit" value="<?=GetMessage("CATALOG_REMOVE_PRODUCTS")?>" />
	<input type="hidden" name="action" value="DELETE_FROM_COMPARE_RESULT" />
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
</form>
<br />
<?if(count($arResult["ITEMS_TO_ADD"])>0):?>
<p>
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
	<input type="hidden" name="action" value="ADD_TO_COMPARE_RESULT" />
	<select name="id">
	<?foreach($arResult["ITEMS_TO_ADD"] as $ID=>$NAME):?>
		<option value="<?=$ID?>"><?=$NAME?></option>
	<?endforeach?>
	</select>
	<input class='button' type="submit" value="<?=GetMessage("CATALOG_ADD_TO_COMPARE_LIST")?>" />
</form>
</p>
<?endif?>
</div>
