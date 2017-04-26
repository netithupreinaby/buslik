<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
if(!function_exists('yenisite_CATALOG_AVAILABLE')) 
{ 
    function yenisite_CATALOG_AVAILABLE ($arProduct) 
    { 
        if(!count($arProduct['OFFERS'])) 
        { 
            if ($arProduct['CATALOG_QUANTITY_TRACE'] != 'Y') return true; 
            if ($arProduct['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true; 
            if ($arProduct['CATALOG_QUANTITY'] > 0)          return true; 
                return false; 
        } 
        else 
        { 
            if($arProduct['CATALOG_QUANTITY'] > 0) return true; 

            foreach ($arProduct['OFFERS'] as $arOffer) 
            { 
                if ($arOffer['CATALOG_QUANTITY_TRACE'] != 'Y') return true; 
                if ($arOffer['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true; 
                if ($arOffer['CATALOG_QUANTITY'] > 0)          return true; 
            } 
        } 
        return false; 
    } 
} 
$arResult['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($arResult) ;
// need move tihs source code in one file and include 
$pr = 0; $kr = 0;				
foreach($arResult['PRICES'] as $k => &$price){
	if(CModule::IncludeModule("catalog"))
	{
		$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price['PRINT_VALUE']);
		$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $price['PRINT_DISCOUNT_VALUE']);
	}
	else
	{
		$price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
		$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
	}
	if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
}

$disc = 0;
if($arResult['PRICES'][$kr]['DISCOUNT_VALUE'])
	$disc =  ($arResult["PRICES"][$kr]["VALUE"] - $arResult["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arResult["PRICES"][$kr]["VALUE"];        
?>   
<a class="ys_close_add2b" href="javascript:void(0);" onclick="yenisite_add2b_close();" title="<?=GetMessage("CLOSE")?>">&#205;</a>
<h2><?=GetMessage('PRODUCT_ADDED_TO_BASKET');?></h2>

<a href="<?=$arResult['DETAIL_PAGE_URL'];?>" class="sl_img">
	<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
		"ELEMENT" => $arResult,
		"IMAGE_SET" => 3,
		"STICKER_NEW" => $arParams["STICKER_NEW"],
		"STICKER_HIT" => $arParams["STICKER_HIT"],
		"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
		"WIDTH" => 75,
		),
		$component, array("HIDE_ICONS"=>"Y")
	);?>
<img src="<?=$arResult['IMAGE_SRC'];?>" alt="<?=$arResult['NAME'];?>">
<div class="img_popup"><div class="top_line"></div><div class="cont"></div></div>
</a>
<div class="sl_info">
	<h3><a href="<?=$arResult['DETAIL_PAGE_URL'];?>"><?=(!empty($arParams['NAME'])) ? $arParams['NAME']:$arResult['NAME'];?></a></h3>
	<span class="price">
	<?if(!empty($arParams['PRICE'])):?>
		<?=(int)$arParams['PRICE'].' <span class="rubl">'.GetMessage('RUB').'</span>'?>
	<?elseif(!$bComplete):?>
		<?=$arResult['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arResult['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
		<?if($arResult['PRICES'][$kr]['DISCOUNT_VALUE'] && $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] != $arResult['PRICES'][$kr]['VALUE']):?>
			<span class="oldprice"><?=$ppr;?></span>
		<?endif;?>
	<?else:?>
		<?$curPrice = ( $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] > 0 ) && ( $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] < $arResult['PRICES'][$kr]['VALUE'] ) ?  $arResult['PRICES'][$kr]['DISCOUNT_VALUE'] : $arResult['PRICES'][$kr]['VALUE'];?>
	<?endif; //bComplete?>
	</span>
<?if(is_array($arParams['PRODUCT_PROPS'])):?>
	<div class="ye-props">
<?foreach ($arParams['PRODUCT_PROPS'] as $prop):?>
		<p><strong><?=$prop['NAME']?>:</strong> <?=$prop['VALUE']?></p>
<?endforeach?>
	</div>
<?endif?>
	<div class="stars">

	
	
	
	
<?
//echo "<pre>";print_r($arParams);echo "</pre>";

/* 	comment because there is problem 
	with css styles of voting stars 
	in popup window, when element in put in basket
	
	but not now*/
$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_ID" => $arParams['ELEMENT_ID'],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
	"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
	"SET_STATUS_404" => $arParams["SET_STATUS_404"],
	"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
	),
	$component, array("HIDE_ICONS"=>"Y")
);/**/?>

		<span class="reply">
			<span class="ws">&#0115;</span> <?=$arResult['PROPERTIES']['FORUM_MESSAGE_CNT']['VALUE'];?><?=GetMessage('ADD2B_REVIEWS');?>
		</span><!--.reply-->
	</div><!--.stars-->
	<?if(CModule::IncludeModule("catalog")):?>
		<?if($arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
			<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> 
		<?elseif(!$arResult['CATALOG_AVAILABLE']):?>
			<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
		<?else:?>
			<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
		<?endif?>
	<?endif?>		
</div><!--.sl_info-->
<div style="clear:both;"></div>
<div class="simular">
<?//get base price for SLIDER_FILTER
if (CModule::IncludeModule("catalog"))
{
	$base_price_group = CCatalogGroup::GetBaseGroup();
	$SLIDER_FILTER['ID_BASE_PRICE'] = $base_price_group['ID'];
}
$SLIDER_FILTER['PRICE'] = $arResult['PRICES'][$base_price_group['NAME']]['VALUE'];
global $elementSection;
$elementSection = $arResult["SECTION"]["ID"];
$obCache = new CPHPCache;
$cache_id = 'yenisite_similar_product_'.$arResult['ID'];
$obCache->Clean($cache_id, "/ys_slider_filter/");
$obCache->InitCache($arParams["CACHE_TIME"]-5, $cache_id, "/ys_slider_filter/");
if($obCache->StartDataCache())
    $obCache->EndDataCache(array(
		"AR_FILTER_PRICE"    => $SLIDER_FILTER
	)) ;
unset($obCache) ;
?>
	<?
	$slider_id = "add2b_popup";
	$ElementID = $arParams['ELEMENT_ID'];
	require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/ajax/accessories.php");
	?>

	<div style="clear:both;"></div>
	<div class="popup-nav">
		<a href="javascript:void(0);" onclick="yenisite_add2b_close();" class="go_to_basket"><?=GetMessage('ADD2B_RETURN');?></a>
		<a href="/personal/basket.php" class="order"><?=GetMessage('ADD2B_GOTO_BASKET');?></a>
	</div><!--.popup-nav-->
</div><!--.simular-->