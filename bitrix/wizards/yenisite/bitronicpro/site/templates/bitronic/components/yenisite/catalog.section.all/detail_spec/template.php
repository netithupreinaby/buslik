<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?global $ys_options;?>
<?if(!count($arResult["ITEMS"])) return;?>

<?
// FOR SKU as SelectBox
$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS']))
{
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		ob_start();?>
		<div id="#ITEM#_prop_<?=$arProp['ID'];?>_cont">
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

if(intval($arResult['CATALOG']['IBLOCK_ID']) && $arResult['CATALOG']['IBLOCK_ID'] !== $arResult['IBLOCK_ID']):?>
<input type="hidden" name="ajax_iblock_id_sku_similar" id="ajax_iblock_id_sku_similar" value="<?=$arResult['CATALOG']['IBLOCK_ID']?>"/>
<?endif;?>


<div id="slider" class="detail_slider_2">
	<div class="sl_title"><?=GetMessage($arParams['SLIDER_TITLE']);?></div>
	<input type="hidden" name="count" value="<?=$arResult["COUNT"];?>" />
	<div class="slider">
	<button id="button7" onClick="javascript:void(0);" class="button7 sym">&#212;</button>
	<button id="button8" onClick="javascript:void(0);" class="button8 sym">&#215;</button>
		<div class="sl_wrapper">
<div class="no-hide">
	<?if($ys_options['block_view_mode'] != 'nopopup'):?>
		<ul>
	<?else:?>
		<ul class="ulmitem">
	<?endif;?>
	<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>

	<?$itemid = "ys-ms-".$arElement['IBLOCK_ID']."-".$arElement['ID'];?>
	<?
	$strMainID = $arElement['ID'];
	// FOR SKU as SelectBox
	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => 'product_photo_'.$arElement['ID'],
		'SECOND_PICT' => $strMainID.'_secondpict',

		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $itemid,
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',

		'PRICE' => 'product_price_'.$arElement['ID'],
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
	);
	?>

	
	<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
	<?/* OFFERS MIN PRICE START */?>			 
	<?$pr = 0; $kr = 0; $kk = 0;?>
	<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0)
	{
		foreach($arElement["OFFERS"] as $arOffer)
		{
			$arElement["CATALOG_QUANTITY_TRACE"] = "Y";
			if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] > 0) )
				$arElement["CATALOG_QUANTITY_TRACE"] = "N";

			foreach($arOffer['PRICES'] as $k => $price)
			{
				if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $kr = $k;  $arElement['PRICES'][$kr] = $arOffer['PRICES'][$kr]; }
			}			
			$price = $arOffer['PRICES'][$kr]['VALUE'];				
			$disc = 0;				
			if($arOffer['PRICES'][$kr]['DISCOUNT_VALUE'])
				$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
		}
	}?>  
	<?/* OFFERS MIN PRICE END */?>	

	<?
    // $pr = 0; $kr = 0;				
    foreach($arElement['PRICES'] as $k => &$price){
	if(CModule::IncludeModule("catalog"))
	{
		$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price['PRINT_VALUE']);
		$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $price['PRINT_DISCOUNT_VALUE']);
	}
	// else
	// {
		// $price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
		// $price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
	// }
        if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
	}       
	unset($price);
    $disc = 0;
    if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
        $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
    ?>     	
	
	<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
	<li id="<?=$arItemIDs['ID']?>">
		<input type="hidden" name="ajax_iblock_id_<?=$ys_n_for_ajax;?>" id="ajax_iblock_id_<?=$ys_n_for_ajax;?>" value="<?=$arElement['IBLOCK_ID'];?>" />
		<input type="hidden" name="iNumPage" value="<?=$_REQUEST["iNumPage"]?IntVal($_REQUEST["iNumPage"]):1?>" />
		<?if($ys_options['block_view_mode'] != 'nopopup'):?>
			<div class="item-popup"> 
				
				<div class="pop_pic">
					<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
					<a class="sl_img" href="<?=$arElement['DETAIL_PAGE_URL']?>">
						<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
							"ELEMENT" => $arElement,
							"IMAGE_SET" => $arParams['BLOCK_IMG_BIG'],
							"STICKER_NEW" => $arParams["STICKER_NEW"],
							"STICKER_HIT" => $arParams["STICKER_HIT"],
							"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
							"WIDTH" => 75,
							),
							$component, array("HIDE_ICONS"=>"Y")
						);?>
						<img id="product_photo_407" class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['BLOCK_IMG_BIG']);?>' alt='<?=$arElement['NAME']?>' />
					</a>
				</div>
					
				<div class="sl_info">
					<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></h3>
					<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
						<div class="article"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
					<?endif;?>
					<?if($no_hide_for_order):?>
						<span class="price">
								<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
								<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
										<span class="oldprice"><?=$ppr;?></span>
								<?endif?>	
						</span>	
					<?endif;?>
					
					<div class="stars">	
						<?
						$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
							"IBLOCK_TYPE" => $arElement["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arElement["IBLOCK_ID"],
							"ELEMENT_ID" => $arElement["ID"],
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
							"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
							"SET_STATUS_404" => "N",
							"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
							),
							$component, 
							array("HIDE_ICONS"=>"Y")
						);?>					
						
						<span class="reply"> <span class="ws">&#0115;</span> <?=GetMessage('REVIEW');?> <?=$arElement['PROPERTIES']['FORUM_MESSAGE_CNT']['VALUE'];?></span><!--.reply--> 
					</div><!--.stars-->
				
					<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>
						<?// FOR SKU as SelectBox
							if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB'):?>
								<div class="ye-props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
									<?foreach ($arSkuTemplate as $code => $strTemplate)
									{
										if (!isset($arElement['OFFERS_PROP'][$code]))
											continue;
										echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate);
									}?>
								</div>
						<?endif?>	
					
					
						<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && ($arParams['PRODUCT_DISPLAY_MODE'] != 'SB' || count($arElement['OFFERS_PROP']) <= 0 )):?>				
							<a class="button2" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
						<?else:?>
							<?$prop_flag = (count($arElement['OFFERS_PROP']) > 0 ) ? true : false; // FOR SKU as SelectBox?>
							<?if($arParams['HIDE_BUY_IF_PROPS'] != 'Y' || 
									count($arParams['PRODUCT_PROPERTIES'])<=0 ||
									(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && $arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
							):?>
								<a id="<?=$itemid;?>" class="button2 add2basket <?if($ppr <= 0):?>button_in_basket<?endif;?> ajax_a2b_<?=$arElement['ID'];?> <?if($prop_flag):?>ajax_add2basket_prop<?endif;?>" href="<?echo $arElement["ADD_URL"]?>" rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
							<?endif;?>
						<?endif?>
					<?endif?>
					
					<?/*<div class="compare_list">
						<?/*<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span id="c-<?=$ys_n_for_ajax;?>"><?echo GetMessage("CATALOG_COMPARE")?></span></a>*//*?>
					</div>*/?><!--.compare_list--> 
					
					<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
						<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
					<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
						<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
					<?else:?>
						<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
					<?endif?>
				</div><!--.sl_info--> 
			</div><!--.item-popup--> 
			
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
				<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
					"ELEMENT" => $arElement,
					"IMAGE_SET" =>  $arParams['BLOCK_IMG_SMALL'],
					"STICKER_NEW" => $arParams["STICKER_NEW"],
					"STICKER_HIT" => $arParams["STICKER_HIT"],
					"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
					"WIDTH" => 75,
					),
					$component, array("HIDE_ICONS"=>"Y")
				);?>
				<img class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path, $arParams['BLOCK_IMG_SMALL']);?>' alt='<?=$arElement['NAME']?>' />					
			</a>
		<?else:?>
			<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
				<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
					"ELEMENT" => $arElement,
					"IMAGE_SET" => $arParams['BLOCK_IMG_SMALL'],
					"STICKER_NEW" => $arParams["STICKER_NEW"],
					"STICKER_HIT" => $arParams["STICKER_HIT"],
					"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
					"WIDTH" => 75,
					),
					$component, array("HIDE_ICONS"=>"Y")
				);?>
				<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['BLOCK_IMG_SMALL']);?>' alt='<?=$arElement['NAME']?>' />	
			</a>
			
			<div class="product_popup">
				<div class="btn_shop">
					<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>
						<a class="button1" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
					<?else:?>
						<a id="<?=$itemid;?>" class="button1 add2basket <?if($ppr <= 0):?>button_in_basket<?endif;?>  ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"<?/* onclick="onClick2Cart();"*/?> rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
					<?endif;?>
				</div>
				<div class="compare_list">
					<?/* <a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span class="frame_add_compare" id="c-<?=$ys_n_for_ajax;?>"><?echo GetMessage("CATALOG_COMPARE")?></span></a> */?>
				</div>
			</div>
		<?endif;?>
		
		<?if($ys_options['block_view_mode'] == 'nopopup'):?>
			<div class="marks">
		<?endif;?>
					
		<?if($ys_options['block_view_mode'] == 'nopopup'):?>
			</div>
		<?endif;?>
		
		<div class="sl_info">
			<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></h3>
			<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
				<div class="article article_block"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
			<?endif;?>
			
			<?if($no_hide_for_order):?>	  
				<span class="price">
					<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
					<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
						<span class="oldprice"><?=$ppr;?></span>
					<?endif?>	
				</span>	
			<?endif;?>	

			<?if($ys_options['block_view_mode'] == 'nopopup'):?>
				<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
					<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
				<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
					<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
				<?else:?>
					<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
				<?endif?>	
			<?endif;?>
		</div><!--.sl_info--> 
		
		<?if(count($arResult['SKU_PROPS']) && count($arElement['JS_OFFERS']) && count($arElement['OFFERS_SELECTED'])):
			
			$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $arElement['ID']);

			$arSkuProps = array();
		
			foreach ($arResult['SKU_PROPS'] as $arOneProp)
			{
				if (!isset($arElement['OFFERS_PROP'][$arOneProp['CODE']]))
					continue;
				$arSkuProps[] = array(
					'ID' => $arOneProp['ID'],
					'SHOW_MODE' => $arOneProp['SHOW_MODE'],
					'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
				);
			}
			
			$arJSParams = array(
				'PRODUCT_TYPE' => $arElement['CATALOG_TYPE'],
				'SHOW_ABSENT' => true,
				'SHOW_OLD_PRICE' => ($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']),
				'DEFAULT_PICTURE' => array(
					'PICTURE' => CResizer2Resize::ResizeGD2($path,$arParams['BLOCK_IMG_BIG']),
					'PICTURE_SMALL' => CResizer2Resize::ResizeGD2($path,$arParams['BLOCK_IMG_SMALL'])
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
					'TREE_ID' => $arItemIDs['PROP_DIV'],
					'TREE_ITEM_ID' => $arItemIDs['PROP'],
					'BUY_ID' => $arItemIDs['BUY_LINK'],
				),
				'PRODUCT' => array(
					'ID' => $arElement['ID'],
					'NAME' => $arElement['~NAME']
				),
				'OFFERS' => $arElement['JS_OFFERS'],
				'OFFER_SELECTED' => $arElement['OFFERS_SELECTED'],
				'TREE_PROPS' => $arSkuProps,
			);?>
			
			<script language="javascript" type="text/javascript">
			var <? echo $strObName; ?> = new JCCatalogSectionAll(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
			</script>
		<?endif;?>
	</li>
	<? $ppr = ""; ?>
			
	<?endforeach;?>			 
	</ul>
	<div class="slider1 slider1__detail">
		<div class="slider1_BG"></div>
		<div class="ui-slider-handle"></div>
	</div> 
</div>
<div class="clear"></div>
</div><!--.sl_wrapper-->
</div><!--.slider-->
<div class="clear"></div>
</div><!--#slider-->
<?/*COption::SetOptionString(CYSBitronicSettings::getModuleId(), "ys_n_for_ajax", $ys_n_for_ajax );*/?>