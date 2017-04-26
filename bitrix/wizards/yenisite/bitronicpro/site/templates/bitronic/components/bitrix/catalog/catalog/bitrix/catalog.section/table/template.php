<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<? CModule::IncludeModule('yenisite.resizer2'); ?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

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

<?global $USER;
$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
?> 
<?if(intval($arResult['CATALOG']['IBLOCK_ID']) && $arResult['CATALOG']['IBLOCK_ID'] !== $arResult['IBLOCK_ID']):?>
<input type="hidden" name="ajax_iblock_id_sku" id="ajax_iblock_id_sku" value="<?=$arResult['CATALOG']['IBLOCK_ID']?>"/>
<?endif;?>

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
				
				$strMainID = $arElement['ID'];
				// FOR SKU as SelectBox
				$arItemIDs = array(
					'ID' => $this->GetEditAreaId($arElement['ID']),
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
				<?/* OFFERS MIN PRICE END */?>	
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
						//$price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
						if(!empty($price['PRINT_DISCOUNT_VALUE']))$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
					}
					if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
				}       
				$disc = 0;
				if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
					$disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
				?>
				<?$n_td = 0 ;?>
				<tr id="<?=$this->GetEditAreaId($arElement['ID']);?>">
					<td><?$n_td ++;?>
						<?//$path = CFile::GetPath($arElement['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
						<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
						<a class="table_big_img" href="<?=$arElement['DETAIL_PAGE_URL']?>" rel="<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_BIG']);?>">
							<img id="product_photo_<?=$arElement['ID'];?>" class="table_img product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['TABLE_IMG']);?>' alt='<?=$arElement['NAME']?>' />
						</a>
					</td>
					<td class="catlistname"><?$n_td ++;?>
						<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
							<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
						<?endif?>
						<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement['NAME']?></a></h3>
						
						<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
							<div class="article"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
						<?endif;?>
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
						
					<?else:?>
						<div class="ye-props">
							<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
								<?if (!empty($product_property["VALUES"])):?>
									<div class="ye-prop">
										<div><?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</div>
										<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]">
											<?foreach($product_property["VALUES"] as $k => $v):?>
												<option value="<?echo $k?>"><?echo $v?></option>
											<?endforeach;?>
										</select>
									</div>
								<?endif;?>
							<?endforeach;?>
						</div>
						
					<?endif;?>
					</td>    
					
					<td class="avilableTD"><?$n_td ++;?>
						<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
							<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
							<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
							<?else:?>
								<span id="product_have_<?=$arElement['ID'];?>" class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
							<?endif?>
						<?if(method_exists($this, 'createFrame')) $frame->end();?>
					</td>
					<td class="priceTD"><?$n_td ++;?>
						<?if($no_hide_for_order):?>
							<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
							<span class="price">
								<?=$arElement['PRICES'][$kr]['DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
								<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
									<span class="oldprice"><?=$ppr;?></span>
								<?endif?>	
							</span>
							<?if(method_exists($this, 'createFrame')) $frame->end();?>
						<?endif;?>						
					</td>
						
					
					<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>
						<td><?$n_td ++;?>
						<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && ($arParams['PRODUCT_DISPLAY_MODE'] != 'SB' || count($arElement['OFFERS_PROP']) <= 0 )):?>				
							<form name="a2b<?echo $arElement["ID"]?>" id="a2b<?echo $arElement["ID"]?>" action="<?=$arElement["DETAIL_PAGE_URL"]?>" method="post" enctype="multipart/form-data"></form>	
						<?else:?>
							<form name="a2b<?echo $arElement["ID"]?>" id="a2b<?echo $arElement["ID"]?>" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
								<input id="q<?echo $arElement["ID"]?>"  type="text" name="quantity" class="txt"  value="0" <?=($ppr <= 0)?' disabled' :''?>/>
								<input id="<?=$arItemIDs['BUY_LINK']?>" type="hidden" name="id" value="<?echo $arElement["ID"]?>">							
								<input type="hidden" name="action" value="ADD2BASKET">
							</form>
						<?endif?>
						</td>
						
						<?if(!count($arElement["OFFERS"]) || ($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && count($arElement['OFFERS_PROP']) > 0)):?>
							<?if(0 && isset($arElement['CATALOG_MEASURE_NAME'])):?>
								<td><?$n_td ++;?><div class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?=$arElement['CATALOG_MEASURE_NAME']?></div></td>
							<?endif?>
							<td><?$n_td ++;?>
								<?if($ppr > 0):?><button title="<?=GetMessage("ADD_ITEM"); ?>" onclick="setQuantityTable('#q<?echo $arElement["ID"]?>', '+', <?=$arElement['CATALOG_MEASURE_RATIO']?>); return false;" class="button4">+</button><?endif?>
							</td>
							<td><?$n_td ++;?>
								<?if($ppr > 0):?><button title="<?=GetMessage("DELETE_ITEM"); ?>" onclick="setQuantityTable('#q<?echo $arElement["ID"]?>', '-', <?=$arElement['CATALOG_MEASURE_RATIO']?>); return false;" class="button5">-</button><?endif?>
							</td>
						<?else:?>
							<td><?$n_td ++;?></td><td><?$n_td ++;?></td>
						<?endif?>
					<?else:?>
						<td colspan="3"></td>
					<?endif?>
					
					
					<? $ppr = ""; ?>
					
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
								'PICTURE' => CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['BLOCK_IMG_BIG']),
								'PICTURE_SMALL' => CResizer2Resize::ResizeGD2($path,$arParams['RESIZER_SETS']['TABLE_IMG'])
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
							'VIEW_MODE' => 'table',
						);?>	
							
						<script language="javascript" type="text/javascript">
						var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
						</script>
					<?endif;?>
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
						<?endforeach?>							
					</tbody>
				</table>
			</div>
	<?$arSet = CResizer2Set::GetByID($arParams['RESIZER_SETS']['BLOCK_IMG_BIG']);?>
	<div id="pic-table-popup" style=" width: <?=$arSet['w']?>px; height: <?=$arSet['h']?>px;  background-color: white">
		<span class='notloader ws' style="display: block; margin: 50%; position: absolute; font-size: 28px;">0</span>
		<div class="pop_img">
			<img src="#" alt="" />
		</div>
	</div><!--#pic-table-popup-->		
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