<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!($arParams['HIDE_ORDER_PRICE'] =='Y' && $arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y')):?>
<span class="price" id="ys_top_price">
	
	
	<span class="oldprice"></span>
</span>
<?endif;
$strMainID = $arResult['ID'];
$arItemIDs = array(
	'ID' => 'container',
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'BUY_LINK' => $arResult['bComplete'] ? 'add2basket' : 'b-'.$strMainID,
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
	'BUY_ID_SET' => 'add2basket2',
);

?>
<?if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])):
	$arSkuProps = array();
?>
<div class="props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);

?>
	<div id="<?=$arItemIDs['PROP']?><?=$arProp['ID'];?>_cont">
		<div class="bx_item_section_name_gray"><?=htmlspecialcharsex($arProp['NAME']); ?>:</div>
		<select 
			id="<?=$arItemIDs['PROP']?><?=$arProp['ID'];?>_list" 
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
	</div>
<?
	}
	unset($arProp);
?>
</div>

<?if(!($arParams['HIDE_ORDER_PRICE'] =='Y' && $arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y')):?>
<a 	class="button2 ajax_add2basket 
		<?if($prop_flag):?>ajax_add2basket_prop<?endif;?>" 
	id="<?=$arItemIDs['BUY_LINK']?>" 
	href="<?=$arResult["ADD_URL"]?>"  rel="nofollow"> 
	<span><?=GetMessage('CATALOG_ADD_TO_BASKET')?></span>
</a>
<?endif;

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $arResult['ID']);
$arJSParams = array(
	'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
	'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'SHOW_ADD_BASKET_BTN' => false,
	'SHOW_BUY_BTN' => true,
	'SHOW_ABSENT' => true,
	'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
	'SHOW_OFFER_GROUP' => !!$arResult['OFFER_GROUP'],

	'SHOW_OLD_PRICE' => ($arResult['PRICES'][$kr]['DISCOUNT_VALUE'] && $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] != $arResult['PRICES'][$kr]['VALUE']),
	'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),

	'VISUAL' => array(
		'ID' => $arItemIDs['ID'],
		'PICT_ID' => $arItemIDs['PICT'],
		'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
		'PRICE_ID' => $arItemIDs['PRICE'],
		'TREE_ID' => $arItemIDs['PROP_DIV'],
		'TREE_ITEM_ID' => $arItemIDs['PROP'],
		'BUY_ID' => $arItemIDs['BUY_LINK'],
		'BUY_ID_SET' => $arItemIDs['BUY_ID_SET'],
		'DSC_PERC' => $arItemIDs['DSC_PERC'],
		'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
		'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
		'OFFER_GROUP' => $arItemIDs['OFFER_GROUP'],
	),
	'PRODUCT' => array(
		'ID' => $arResult['ID'],
		'NAME' => $arResult['~NAME']
	),
	'OFFERS' => $arResult['JS_OFFERS'],
	'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
	'TREE_PROPS' => $arSkuProps,
	'AJAX_RELOAD_PICTURE' => $arResult['boolShowPicture'] ? false : true,
);
?>
<input type="hidden" name="ajax_iblock_id_sku" id="ajax_iblock_id_sku" value="<?=$arResult['OFFERS_IBLOCK']?>"/>
<input type="hidden" name="ys-request-uri" value="<?=$_SERVER['REQUEST_URI']?>" />

<script language="javascript" type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>	

<?endif;?>