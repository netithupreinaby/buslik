<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

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
<input type="hidden" name="ajax_iblock_id_sku" id="ajax_iblock_id_sku" value="<?=$arResult['CATALOG']['IBLOCK_ID']?>"/>
<?endif;?>



<? 
global $ys_options;
$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
CModule::IncludeModule('yenisite.resizer2');
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<input type="hidden" name="ajax_iblock_id" id="ajax_iblock_id" value="<?=$arResult['IBLOCK_ID'];?>"/>

	<div class="catalog">
		<?if($ys_options['block_view_mode'] != 'nopopup'):?>
			<ul class="catalog-list">
		<?else:?>
			<ul class="catalog-list ulmitem">
		<?endif;?>

<?if(empty($arResult["ITEMS"]) && !count($arResult["ITEMS"]) > 0 && $_REQUEST["set_filter"]=="Y"):?>	
<p><font class="errortext"><?=GetMessage('FILTER_ERROR');?></font></p>
<?endif?>

<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>
	<?
    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
    
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
		'BUY_LINK' => 'b-'.$arElement['ID'],
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
<?$pr = 0; $kr = 0; $kk = 0;?>
<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):?>	
<?foreach($arElement["OFFERS"] as $arOffer):?>

<?
if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arOffer["CATALOG_QUANTITY"] > 0) ){
	$arElement["CATALOG_QUANTITY_TRACE"] = "N";	
}
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

<?
    /*foreach($arElement[PRICES] as $k => $price)
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
				// $price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
				if(!empty($price['PRINT_DISCOUNT_VALUE']))$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
			}
            if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }		
		}  
		unset($price);
        $disc = 0;
        if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
            $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];  

        //for buy with props
        $prop_flag = false;
		if($arParams['PRODUCT_DISPLAY_MODE'] != 'SB') {
			foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property){
				if (!empty($product_property["VALUES"])) {
					$prop_flag = true;
					break;
				}
			}
		}
		
		if(($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0) || $prop_flag)
			$with_sku_class = 'class="with_sku"';
		else	
			$with_sku_class = '';
			//$path = CFile::GetPath($arElement[PROPERTIES][MORE_PHOTO][VALUE][0]);
    ?>
	<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
		<li id="<?=$this->GetEditAreaId($arElement['ID']);?>" <?=$with_sku_class?>>
		<div id="<?=$arElement['ID'];?>">
			<?if($ys_options['block_view_mode'] != 'nopopup'):?>
				<div class="item-popup">
					<div class="pop_pic"></div>
					<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
					<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
						<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
							"CATALOG" => "Y",
							"ELEMENT" => $arElement,
							"IMAGE_SET" => $arParams['RESIZER_SETS']['BLOCK_IMG_BIG'],
							"STICKER_NEW" => $arParams["STICKER_NEW"],
							"STICKER_HIT" => $arParams["STICKER_HIT"],
							"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
							"WIDTH" => 75,
							),
							$component, array("HIDE_ICONS"=>"Y")
						);?>
						<img id="product_photo_<?=$arElement['ID'];?>" class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_BIG']);?>' alt='<?=$arElement['NAME']?>' />
					</a>
					
					<div class="sl_info">
						<!--<a href="#" class="tag"></a>-->
						<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
							<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
						<?endif?>
						
						<h3><a id="product_name_<?=$arElement['ID'];?>" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement['NAME']?></a></h3>
						
						<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
							<div class="article"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
						<?endif;?>
						
						<?if($no_hide_for_order):?>
							<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
							<span id="product_price_<?=$arElement['ID'];?>" class="price">
									<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
									<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
											<span class="oldprice"><?=$ppr;?></span>
									<?endif?>	
							</span>
							<?if(method_exists($this, 'createFrame')) $frame->end();?>
						<?endif;?>
						
						<div class="stars">
							<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
								<?
								$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
									"IBLOCK_TYPE" => $arElement['IBLOCK_TYPE'],
									"IBLOCK_ID" => $arElement['IBLOCK_ID'],
									"ELEMENT_ID" => $arElement['ID'],
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
									"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
									"SET_STATUS_404" => $arParams["IBLOCK_SET_STATUS_404"],
									"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
									),
									$component, array("HIDE_ICONS"=>"Y")
								);?>
							<?if(method_exists($this, 'createFrame')) $frame->end();?>
							
							<span class="reply">
								<span class="ws">&#0115;</span> <?=GetMessage('CATALOG_REVIEW')?> 
								<?=$arElement['PROPERTIES']['FORUM_MESSAGE_CNT']['VALUE'];?>
							</span><!--.reply-->
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
								<?if($prop_flag):?>
								<div class="ye-props">
									<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
										<?if (!empty($product_property["VALUES"])):?>
											<div><?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</div>
											<?if($arElement["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
											&& $arElement["PROPERTIES"][$pid]["LIST_TYPE"] == "C"):?>
												<?foreach($product_property["VALUES"] as $k => $v):?>
													<?// start modify by Ivan, 09.10.2013 ---->?>
													<div><label><input type="radio" class="radio" onclick="onRadioPropChange(this)" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" value="<?echo $k?>"><?echo $v?></label></div>
													<?// <---- end modify by Ivan, 09.10.2013?>
												<?endforeach;?>
											<?else:?>
												<div>
													<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="onSelectChange(this);">
													<option value="0"><?=GetMessage('CHOOSE')?></option>
														<?foreach($product_property["VALUES"] as $k => $v):?>
															<option value="<?echo $k?>"><?echo $v?></option>
														<?endforeach;?>
													</select>
												</div>
											<?endif;?>
										<?endif;?>
									<?endforeach;?>
								</div>
								<?else:?>
									<?$prop_flag = (count($arElement['OFFERS_PROP']) > 0 ) ? true : false; // FOR SKU as SelectBox?>
								<?endif;?>
								<a class="button2 ajax_add2basket <?if($ppr <= 0 || $prop_flag):?>button_in_basket<?endif;?> <?if($prop_flag):?>ajax_add2basket_prop<?endif;?>" id="b-<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"<?/*onclick="onClick2Cart();*/?> rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
							<?endif?>
						<?endif?>
						
						<!--<a href="#" class="added">  </a>-->
						<div class="compare_list">
							<a href="<?echo $arElement["COMPARE_URL"]?>"  rel="nofollow"><span class="ws">&#193;</span><span id="c-<?=$arElement['ID'];?>"><?echo GetMessage("CATALOG_COMPARE")?></span></a>
						</div>
					<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>	
						<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
							<span id="product_have_<?=$arElement['ID'];?>" class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
						<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
							<span id="product_have_<?=$arElement['ID'];?>" class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
						<?else:?>
							<span id="product_have_<?=$arElement['ID'];?>" class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
						<?endif?>
					<?if(method_exists($this, 'createFrame')) $frame->end();?>
					</div><!--.sl_info-->								
				</div><!--.item-popup-->
			
				<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
					<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
						"CATALOG" => "Y",
						"ELEMENT" => $arElement,
						"IMAGE_SET" => $arParams['RESIZER_SETS']['BLOCK_IMG_SMALL'],
						"STICKER_NEW" => $arParams["STICKER_NEW"],
						"STICKER_HIT" => $arParams["STICKER_HIT"],
						"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
						"WIDTH" => 75,
						),
						$component, array("HIDE_ICONS"=>"Y")
					);?>
					<img class="product_photo" src="<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_SMALL']);?>" alt="<?=$arElement['NAME']?>" />
				</a>
			
			<?else:?>
				<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
				<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
					<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
						"CATALOG" => "Y",
						"ELEMENT" => $arElement,
						"IMAGE_SET" => $arParams['RESIZER_SETS']['BLOCK_IMG_SMALL'],
						"STICKER_NEW" => $arParams["STICKER_NEW"],
						"STICKER_HIT" => $arParams["STICKER_HIT"],
						"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
						"WIDTH" => 75,
						),
						$component, array("HIDE_ICONS"=>"Y")
					);?>
					<img id="product_photo_<?=$arElement['ID'];?>" class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_SMALL']);?>' alt='<?=$arElement['NAME']?>' />	
				</a>
				
				<div class="product_popup">
					<div class="btn_shop">
						<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>
							<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && ($arParams['PRODUCT_DISPLAY_MODE'] != 'SB' || count($arElement['OFFERS_PROP']) <= 0 )):?>
								<a class="button1" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
							<?else:?>
								<a class="button1 ajax_add2basket<?if($ppr <= 0 || $prop_flag):?> button_in_basket<?if($prop_flag):?> ajax_add2basket_prop<?endif?><?endif?>" id="b-<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>"<?/* onclick="onClick2Cart();"*/?> rel="nofollow"><span><?=GetMessage('CATALOG_ADD')?></span></a>
							<?endif;?>
						<?endif;?>
					</div>
					<div class="compare_list">
						<a class="" href="<?echo $arElement["COMPARE_URL"]?>"  rel="nofollow"><span class="ws">&#193;</span><span class="frame_add_compare" id="c-<?=$arElement['ID'];?>"><?echo GetMessage("CATALOG_COMPARE")?></span></a>
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
				<!--<a href="#" class="tag"></a>-->
				
				<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
					<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
				<?endif?>
				
				<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement['NAME']?></a></h3>
				
				<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
					<div class="article article_block"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
				<?endif;?>
				
				<?// FOR SKU as SelectBox
				if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && $ys_options['block_view_mode'] == 'nopopup'):?>
					<div class="ye-props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
						<?foreach ($arSkuTemplate as $code => $strTemplate)
						{
							if (!isset($arElement['OFFERS_PROP'][$code]))
								continue;
							echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate);
						}?>
					</div>
				<?endif?>

				<?// for buy with props
				if ($ys_options['block_view_mode'] == 'nopopup' && $prop_flag):?>
					<div class="ye-props bx_catalog_item_scu">
						<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
							<?if (!empty($product_property["VALUES"])):?>
								<div><?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</div>
								<div>
									<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="onSelectChange(this);">
										<option value="0"><?=GetMessage('CHOOSE')?></option>
										<?foreach($product_property["VALUES"] as $k => $v):?>
											<option value="<?echo $k?>"><?echo $v?></option>
										<?endforeach;?>
									</select>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				<?endif?>
							
				<?if($no_hide_for_order):?>
					<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
					
						<span class="price">
							<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
							<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
									<span class="oldprice"><?=$ppr;?></span>
							<?endif?>	
						</span>
					<?if(method_exists($this, 'createFrame')) $frame->end();?>
				<?endif;?>
				
				<?if($ys_options['block_view_mode'] == 'nopopup'):?>
					<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
						<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
							<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
						<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
							<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
						<?else:?>
							<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
						<?endif?>
					<?if(method_exists($this, 'createFrame')) $frame->end();?>
				<?endif;?>
			</div><!--.sl_info-->
		</div>
		</li>
	<? $ppr = ""; ?>				
	<?
	if(count($arResult['SKU_PROPS']) && count($arElement['JS_OFFERS']) && count($arElement['OFFERS_SELECTED'])):
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
				'PICTURE' => CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_BIG']),
				'PICTURE_SMALL' => CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_SMALL'])
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
			'VIEW_MODE' => 'block',
		);?>	
			
		<script language="javascript" type="text/javascript">
		var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
		</script>	
	<?endif;?>
<?endforeach;?>						
</ul>
	<div style="clear:both;"></div>
</div><!--.cataog-->
	
	<div class="pager-block">
		<div class="show_filter">
			<?if($arParams["FILTER_BY_QUANTITY"] == 'Y'
			&& !((isset($_REQUEST["set_filter"]) && $_REQUEST["f_Quantity"] == "Y") || $_REQUEST['available'] == "no" || strpos($_SERVER['REQUEST_URI'], '/available-no/') !== false)
			&& (($APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'N' && $_REQUEST["ys_filter_ajax"] !== "y") || $arParams['q_checked'] == 'N')):?>
				<?
				if (CModule::IncludeModule('kombox.filter') && $ys_options['smart_filter_type'] == 'KOMBOX') {
					if (CKomboxFilter::IsSefMode($APPLICATION->GetCurPage(false))) {
						$href = $APPLICATION->GetCurPage(false) . 'filter/available-no/';
					} else {
						$href = preg_replace( '/&?arrFilter_AVAILABLE_\d+=\S*(?:&)?/', '', $APPLICATION->GetCurPageParam("available=no", array("del_filter","set_filter", "available", "bitrix_include_areas", "clear_cache")));
					}
				} else {
					$href = preg_replace( '/&?arrFilter_AVAILABLE_\d+=\S*(?:&)?/', '', $APPLICATION->GetCurPageParam("set_filter=y&f_Quantity=Y", array("del_filter","set_filter", "f_Quantity", "bitrix_include_areas", "clear_cache")));
				}
				?>
				<div class="ys_no_available_link"><a href="<?=$href?>" title="<?=GetMessage('ALL_PRODUCTS_LINK_TITLE');?>"><?=GetMessage('ALL_PRODUCTS_LINK');?></a></div>
			<?elseif($arParams["FILTER_BY_QUANTITY"] == 'Y' && 
				((isset($_REQUEST["set_filter"]) && $_REQUEST["f_Quantity"] == "Y") || $_REQUEST['available'] == 'no' ||
				(strpos($_SERVER['REQUEST_URI'], '/available-no/') !== false) || 
				($APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y' && $_REQUEST["ys_filter_ajax"] !== "y") ||
				$arParams['q_checked'] == 'Y')
			):?>
				<?
				if (CModule::IncludeModule('kombox.filter') && $ys_options['smart_filter_type'] == 'KOMBOX') {
					if (CKomboxFilter::IsSefMode($APPLICATION->GetCurPage(false))) {
						$href = $APPLICATION->GetCurPage(false);
					} else {
						$href = preg_replace( '/&?arrFilter_AVAILABLE_\d+=\S*(?:&)?/', '', $APPLICATION->GetCurPageParam("", array("del_filter","set_filter", "available", "bitrix_include_areas", "clear_cache")));
					}
				} else {
					$href = preg_replace( '/&?arrFilter_AVAILABLE_\d+=\S*(?:&)?/', '', $APPLICATION->GetCurPageParam("set_filter=y&f_Quantity=N", array("del_filter","set_filter", "f_Quantity", "bitrix_include_areas", "clear_cache")));
				}
				?>
				<div class="ys_no_available_link"><a href="<?=$href?>" title="<?=GetMessage('AVAILABLE_PRODUCTS_LINK');?>"><?=GetMessage('AVAILABLE_PRODUCTS_LINK');?></a></div>
			<?endif;?>
	
			<form name='pagecount' method="post">
				<?echo GetMessage("PAGE_COUNT")?>:

				<?//if($sef == "Y"):?>
					<select class="selectBox" name='page_count' onchange="setPageCount(this.value, <?=($sef == "Y")?'true':'false'?>);">
				<?/*else:?>
				    <select class="selectBox" name='page_count' onchange="document.forms['pagecount'].submit();">
				<?endif*/?>
						<option value='20' <?=$arParams['PAGE_ELEMENT_COUNT'] == 20?"selected='selected'":""; ?>>20</option>
						<option value='40' <?=$arParams['PAGE_ELEMENT_COUNT'] == 40?"selected='selected'":""; ?>>40</option>
						<option value='60' <?=$arParams['PAGE_ELEMENT_COUNT'] == 60?"selected='selected'":""; ?>>60</option>
					</select>
			</form>
		</div><!--.show_filter-->
		
		<?=$arResult["NAV_STRING"]?>
	</div><!--.pager-block-->