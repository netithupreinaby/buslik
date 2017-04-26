<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();} //use in sku.php and no_sku.php?>

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?$bComplete = $arResult['bComplete'];?>
<?if(strlen($arResult["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
	<div class="article article_detail"><?=GetMessage('ARTICLE_CODE');?><?=$arResult["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
<?endif;?>
<div id="ys-price_lower-popup" class="popup"></div>
<div id="ys-found_cheap-popup" class="popup"></div>
<div id="ys-element_exist-popup" class="popup"></div>

<input type="hidden" name="ajax_iblock_id" id="ajax_iblock_id" value="<?=$arResult['IBLOCK_ID'];?>"/>
<?if(!$bComplete || $arParams['COMPLETE_SET_NO_INCLUDE_PRICE'] != 'Y'):?>
	<div class="item_detail_options">
		<?
if(defined('ERROR_404') && ERROR_404 == 'Y');?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
		<div class="stars"> 
		<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
	"IBLOCK_TYPE" => $arResult["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arResult["IBLOCK_ID"],
	"ELEMENT_ID" => $arResult['ID'],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
	"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
	"SET_STATUS_404" => $arParams["IBLOCK_SET_STATUS_404"],
	"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
	),
	$component, array("HIDE_ICONS"=>"Y")
);?>
        </div><!--.stars--> 
<?if(method_exists($this, 'createFrame')) $frame->end();?>	<?
        $pr = 0; $kr = 0;
        foreach($arResult['PRICES'] as $k => &$price){
			if(CModule::IncludeModule("catalog"))
			{
				if(stripos($price['PRINT_VALUE'], GetMessage('RUB_REPLACE')))
					$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '</span><span class="rubl">'.GetMessage('RUB').'</span>',  '<span class="allSumMain">'.$price['PRINT_VALUE']);
				else
					$price['PRINT_VALUE'] = '<span class="allSumMain">'.$price['PRINT_VALUE'].'</span>';
				if(stripos($price['PRINT_DISCOUNT_VALUE'], GetMessage('RUB_REPLACE')))
					$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '</span><span class="rubl">'.GetMessage('RUB').'</span>', '<span class="allSumMain">'.$price['PRINT_DISCOUNT_VALUE']);
				else
					$price['PRINT_DISCOUNT_VALUE'] = '<span class="allSumMain">'.$price['PRINT_DISCOUNT_VALUE'].'</span>';
			}
			else
			{
				//$price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
				if(!empty($price['PRINT_DISCOUNT_VALUE'])) $price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
			}
            if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }
		}
		unset($price);
    ?>
	<?
	if (CModule::IncludeModule("catalog"))
	{
		$base_price_group = CCatalogGroup::GetBaseGroup();
		$SLIDER_FILTER['PRICE'] = 0;
		$SLIDER_FILTER['ID_BASE_PRICE'] = $base_price_group['ID'];
	}
	?>
	<?if((!is_array($arResult["OFFERS"]) || empty($arResult["OFFERS"]) || !count($arResult["OFFERS"])) && !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'))
		include ('no_sku.php');?>
	<?if(is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && count($arResult["OFFERS"]) > 0 && $arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
		include ('sku_sb.php') ;?>
		
		<?//if(CModule::IncludeModule("catalog"))://ybrat' yslovie dlya kioska(17.12.2012)?>
		<div class="compare_list">
			<a href="?action=ADD_TO_COMPARE_LIST&amp;id=<?=$arResult[ID]?>" rel="nofollow"><span class="ws">&#193;</span><span id="c-<?=$arResult['ID'];?>"><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></span></a>
		</div><!--.compare_list-->
                
        <div class="ys-feedback-popup">
            <a id="ys-want_low_price" href="<?=$arResult[ID]?>" rel="nofollow"><span class="ws">&#0042;</span>&nbsp;<span id="pl-<?=$arResult['ID'];?>"><?=GetMessage('WANT_LOW_PRICE')?></span></a>
		</div><!--.price_lower-->

        <div class="ys-feedback-popup">
			<a id="ys-found_cheap" href="<?=$arResult[ID]?>" rel="nofollow"><span class="ws">@</span>&nbsp;<span id="pl-<?=$arResult['ID'];?>"><?=GetMessage('FOUND_CHEAP')?></span></a>
		</div><!--.price_lower-->
                
        <?if(CModule::IncludeModule("catalog")):?>
            <? //if($arResult['CATALOG_CAN_BUY_ZERO'] == 'N' && $arResult['CATALOG_NEGATIVE_AMOUNT_TRACE'] == 'N' && $arResult['CATALOG_QUANTITY'] == 0):?>
            <? if(!$arResult['CATALOG_AVAILABLE']):?>
                <div class="ys-feedback-popup">
                    <a id="ys-when_element_exist" href="<?=$arResult[ID]?>" rel="nofollow"><span class="ws">&#0056;</span>&nbsp;<span id="pl-<?=$arResult['ID'];?>"><?=GetMessage('WHEN_ELEMENT_EXIST')?></span></a>
                </div><!--.element_exist-->
            <?endif;?>
        <?endif;?>
                
		<?//endif?>
		<?if(CModule::IncludeModule("catalog")):?>
			<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
				<?if($arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
					<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
				<?elseif(!$arResult['CATALOG_AVAILABLE']):?>
					<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
				<?else:?>
					<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
				<?endif?>
			<?if(method_exists($this, 'createFrame')) $frame->end();?>
		<?endif?>
	   <!-- <div class="have">   </div>-->
	   <?if($arParams["USE_STORE"] == "Y" && !count($arResult['OFFERS']) && CModule::IncludeModule("catalog") && $arResult['ID']):?>
	   <div>
		<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "store", array(
				"PER_PAGE" => "10",
				"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
				"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
				"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
				"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
				"ELEMENT_ID" => $arResult['ID'],
				"STORE_PATH"  =>  $arParams["STORE_PATH"],
				"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				'STORE_CODE' => $arParams["STORE_CODE"],
			),
			false
			);?>
			<?if(method_exists($this, 'createFrame')) $frame->end();?>
		</div>
		<?endif?>
		<!--<div class="call_me">
			<span class="ws">@</span> <a href="#">    </a>
		</div>--><!--.call_me-->
		<?if($arParams['FOR_ORDER_DESCRIPTION'] && $arResult['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
			<div class="for_order_desc"><?=htmlspecialchars($arParams['FOR_ORDER_DESCRIPTION']);?></div>
		<?endif;?>
	</div><!--.item_detail_options-->
	
	<?if(CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && intval($arResult["PROPERTIES"]["SERVICE"]["VALUE"])>0):?>
		<?$res = CIBlockElement::GetList(array(), array("ID" => $arResult["PROPERTIES"]["SERVICE"]["VALUE"]));?>
		<?if($res->SelectedRowsCount() > 0):?>
		<div class="item_detail_options services">
			<h3><?=GetMessage('SERVICE_TITLE');?></h3>
			<form id="options-form">
				<?while($service = $res->GetNext()):
					$priceService = CPrice::GetBasePrice($service['ID']);?>
					<?if(CModule::IncludeModule("currency") && !empty($priceService) && $arResult['PRICES'][$kr]['CURRENCY'] != $priceService['CURRENCY']) 
					{ 
						$priceService['PRICE'] = CCurrencyRates::ConvertCurrency($priceService['PRICE'], $priceService['CURRENCY'], $arResult['PRICES'][$kr]['CURRENCY']);
						$priceService['CURRENCY'] = $arResult['PRICES'][$kr]['CURRENCY'];
					}?>
					<div class="form-item">
						<label>	<input name="service" type="checkbox" class="checkbox" value="<?=$service['ID']?>"/><?=$service['NAME']?></label>
						<?if($priceService):?>
							<input name="servicePrice_<?=$service['ID']?>" type="hidden" value="<?=$priceService['PRICE']?>"/>
							<span class="opt">+<?=(float)$priceService['PRICE']?> <span class="rubl"><?=GetMessage($priceService['CURRENCY'])?></span></span>
						<?endif;?>
					</div><!--.form-item-->
				<?endwhile;?>
			</form>
		</div><!--.item_detail_options services-->
		<?endif;?>
	<?endif;?>
<?endif; //bComplete?>

<?$this->SetViewTarget('DETAIL_TOP');?>
<div class="item_detail_pictures">
<div class="f_loader"></div>
<div class="item_detail_pictures_cont" style="<?if(!$arResult['boolShowPicture']):?>display:none<?endif?>">
<?if($arParams['RESIZER_BOX']=='Y' || $arParams['VIEW_PHOTO']=='popup'):?>
	<div class="item_detail_pic<?=$bComplete ? ' pic_with_sets':'';?>">
	<?//$path = CFile::GetPath($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
	<?$path = $arResult['BIG_PHOTO'];?>
	<?if($arParams['RESIZER_BOX']!='Y' && $arParams['VIEW_PHOTO']=='popup'):?>
		<div class='photoID'><a class='ico_0' rel="tag" href="<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_BIG']);?>">
	<?endif?>
	<span class="stick_img">
		<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
			"CATALOG" => "Y",
			"ELEMENT" => $arResult,
			"IMAGE_SET" => $arParams['RESIZER_SETS']['DETAIL_IMG_SMALL'],
			"STICK_SCALE" => 0.2,
			"STICKER_NEW" => $arParams["STICKER_NEW"],
			"STICKER_HIT" => $arParams["STICKER_HIT"],
			"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
			"WIDTH" => 75,
		),
		$component,
		array('HIDE_ICONS' => 'Y')
		);?>
		<img id="product_photo_<?=$arResult['ID'];?>" alt="<?=$arResult['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0]?$arResult['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0]:$arResult['NAME']?>" src="<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_SMALL']);?>"/>
	</span>
			
	<?if($arParams['RESIZER_BOX']!='Y' && $arParams['VIEW_PHOTO']=='popup'):?>
		</a></div>
	<?endif?>

	<?if((count($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) > 1) && ($arParams['RESIZER_BOX']!='Y') && $arParams['VIEW_PHOTO']=='popup'):?>
		<div class="item_gal" style="display: inline-block">
		<?$i=0;foreach($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $pic): ?>
		<?$path = CFile::GetPath($pic);?>
		<a class='ico_<?=$i?><?=($i==0)?' act':''?>' rel="tag" href="<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_BIG']);?>"data-image="<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_SMALL']);?>" ><img alt="<?=$arResult['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][$i]?$arResult['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][$i]:$arResult['NAME']?>" src="<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_ICON']);?>"></a>

		<?$i++; endforeach?>
		<img src='<?=CResizer2Resize::ResizeGD2($path, $arParams['RESIZER_SETS']['DETAIL_IMG_BIG']);?>' alt="" style='display: none;' />
		<div style="clear:left;"></div>
		</div><!--.item_gal-->
	<?endif?>
	</div><!--.item_detail_pic-->
	<?if($arParams['RESIZER_BOX']=='Y'):?>
		<div id="ys-resizer-placeholder"></div>
	<?endif?>
<?elseif($arParams['VIEW_PHOTO']=='zoom'):?>
	<?$APPLICATION->IncludeComponent(
		"yenisite:resizer2.box", // template in /bitrix/templates/bitronic_XXX/components/yenisite/resizer2.box/
		"bitronic_jQZoom",
		Array(
			"SET_DETAIL" => $arParams['RESIZER_SETS']['DETAIL_IMG_SMALL'],
			"SET_BIG_DETAIL" => $arParams['RESIZER_SETS']['DETAIL_IMG_BIG'],
			"SET_SMALL_DETAIL" => $arParams['RESIZER_SETS']['DETAIL_IMG_ICON'],
			"SHOW_TITLE" => "N",
			"ZOOM_TYPE" => "reverse",
			"SHOW_EFFECT" => "show",
			"HIDE_EFFECT" => "hide",
			"SHOW_DELAY_DETAIL" => "400",
			"HIDE_DELAY_DETAIL" => "400",
			"ZOOM_SPEED_IN" => "500",
			"ZOOM_SPEED_OUT" => "700",
			"IBLOCK_TYPE" => $arResult["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arResult["IBLOCK_ID"],
			"ELEMENT_ID" => $arResult["ID"],
			"PROPERTY_CODE" => "MORE_PHOTO",
			"DROP_PREVIEW_DETAIL" => "N",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"RESULT_ELEMENT" => $arResult,
			"STICKER_NEW" => $arParams["STICKER_NEW"],
			"STICKER_HIT" => $arParams["STICKER_HIT"],
			"STICKER_BESTSELLER" => $arParams["STICKER_BESTSELLER"],
		),
	$component
);?>
<?endif?>
</div><!--.item_detail_pictures_cont-->
</div><!--.item_detail_pictures-->
<?if(CModule::IncludeModule('yenisite.bitronic3dmodel')):?>
	<?if($arResult["PROPERTIES"]["ID_3D_MODEL"]["VALUE"]):?>
		<?$APPLICATION->IncludeComponent("yenisite:bitronic.3Dmodel", ".default", array(
				"BUT_OR_PLAY" => "BUTTON",
				"ID" => $arResult["PROPERTIES"]["ID_3D_MODEL"]["VALUE"],
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "360000",
				"FULLSCREEN" => "N",
				"ZOOM" => "N",
				"ANAGLYPH" => "N",
				"AUTOPLAY" => "Y",
				"SIZE" => "MIDDLE",
				"HEIGHT" => "",
				"WIDTH" => "",
				"DESIGN" => "1",
				"BUTTON_TEXT" => "",
				"FULLSCREEN2" => "N",
				"ZOOM2" => "N",
				"ANAGLYPH2" => "N",
				"BORDER" => "",
				"BORDER_COLOR" => "",
				"BACKGROUND_COLOR" => "",
				"SHADOW_COLOR" => "",
				"OPACITY_BORDER" => "",
				"OPACITY_BACKGROUND" => "",
				"OPACITY_SHADOW" => ""
			),
			false
		);?>
	<?endif;?>
<?endif;?><?

//Bitrix sets?>
<div class="clear_l bx_md">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && $arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFERS'] as $arOffer)
		{
			if (!$arOffer['OFFER_GROUP'])
				continue;
?>
	<div id="<? echo $arItemIDs['OFFER_GROUP'].$arOffer['ID']; ?>" style="display: none;">
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
 <?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	"bitronic_detail",
	array(
		"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
		"ELEMENT_ID" => $arOffer['ID'],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
	if(method_exists($this, 'createFrame')) $frame->end();
?>
	</div>
<?
		}
	}
}
elseif (empty($arResult['OFFERS']))
{
	if ($arResult['MODULES']['catalog'])
	{
if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin('');
?> <?if($arResult['CATALOG_AVAILABLE']):?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	"bitronic_detail",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?endif?><?
		if(method_exists($this, 'createFrame')) $frame->end();
	}
}
?>
</div>
<?if(is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arParams['PRODUCT_DISPLAY_MODE'] != 'SB')
	include ('sku.php') ;?>
				<?if($arParams['SHOW_ANNOUNCE']=='Y'):?>
					<p<?=$bComplete ? ' class="desc_with_sets"' : '';?>><?=$arResult['PREVIEW_TEXT']?></p>
				<?endif;?>
                    <p<?=$bComplete ? ' class="desc_with_sets"' : '';?>><?=$arResult['DETAIL_TEXT']?></p>
                    <div class="social_block">
						<div class="asd_share">
						<?$APPLICATION->IncludeComponent(
							"bitrix:asd.share.buttons",
							"bitronic",
							Array(
								"ASD_ID" => $arResult["ID"],
								"ASD_TITLE" => $arResult["NAME"],
								"ASD_URL" => "http://".$_SERVER["SERVER_NAME"].$arResult["DETAIL_PAGE_URL"],
								"ASD_PICTURE" => "http://".$_SERVER["SERVER_NAME"].$arResult["MORE_PHOTO"]["0"]["SRC"],
								"ASD_TEXT" => $arResult["DETAIL_TEXT"],
								"ASD_LINK_TITLE" => GetMessage('ASD_LINK_TITLE_VAL'),
								"ASD_SITE_NAME" => "",
								"ASD_INCLUDE_SCRIPTS" => ""
							),
							false
						);?>
						</div>
				<?/*<?// comment in 1.3.96 - error in zakazniki services.?>
				<span class="add_to_wishlist"><span class="ws">N</span>
					<script type="text/javascript">
							document.write(
								Zakazniki.Like({
									'name':'<?=$arResult[NAME]?>',
									'link':'<?=$arResult[DETAIL_PAGE_URL]?>',
									'image':'<?=CResizer2Resize::ResizeGD2($path, $arParams[RESIZER_SETS][DETAIL_IMG_SMALL]);?>',
									'price':'<?=$pr?>'                
									
								})
							);
					</script>
				</span>
			*/?>
                    </div><!--.social-->
					<?//$this->SetViewTarget('COMPLETE_SET');?>
					<?if($bComplete):?>
					<div class="filter"></div>
					<div class="catalog">
						<?$APPLICATION->IncludeComponent(
							"yenisite:catalog.sets",
							"",
							Array(
								'ID' => $arResult['ID'],
								'IBLOCK_ID' 	=> $arResult['IBLOCK_ID'],
								'PROPERTY_CODE' => $arParams['PROPERTY_COMPLETE_SETS'],
								'PROPERTIES'	=> $arParams['COMPLETE_SET_PROPERTIES'],
								'DESCRIPTION' 	=> $arParams['COMPLETE_SET_DESCRIPTION'],
								'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
								'CURRENCY_ID' 	=> $arParams['CURRENCY_ID'],
								'PRICE_CODE' 	=> 	$arParams['PRICE_CODE'],
								'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
								'ADD2BASKET'	=> 'N',
								'RESIZER2_SET' 	=> $arParams['COMPLETE_SET_RESIZER_SET'],
								'INCLUDE_OWNER_PRICE_VALUE' => $arParams['COMPLETE_SET_NO_INCLUDE_PRICE'] != 'Y' ? $curPrice : false,
								
								'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
								// sku
								'OFFERS_SORT_FIELD' 	=> 	$arParams['OFFERS_SORT_FIELD'],
								'OFFERS_SORT_ORDER' 	=> 	$arParams['OFFERS_SORT_ORDER'],
								'OFFERS_SORT_FIELD2' 	=> 	$arParams['OFFERS_SORT_FIELD2'],
								'OFFERS_SORT_ORDER2' 	=> 	$arParams['OFFERS_SORT_ORDER2'],
								'OFFERS_FIELD_CODE' 	=> 	$arParams['OFFERS_FIELD_CODE'],
								'OFFERS_PROPERTY_CODE' 	=> 	$arParams['OFFERS_PROPERTY_CODE'],
								'OFFERS_LIMIT' 	=> 	$arParams['OFFERS_LIMIT'],
								
								//sku SelectBox
								'PRODUCT_DISPLAY_MODE' 	=> 	$arParams['PRODUCT_DISPLAY_MODE'],
								'OFFER_TREE_PROPS' 	=> 	$arParams['OFFER_TREE_PROPS'],
							),
							$component
						);?>
						<a class="button2 button2_with_sets ajax_add2basket" id="add2basket2" href = "<?=$arResult["ADD_URL"]?>" rel="nofollow"<?/* onclick="onClick2Cart(this);" */?>> <?// modify by Ivan, 09.10.2013, for ajax add complete set to basket?>
						<span><?=GetMessage('CATALOG_ADD_TO_BASKET')?></span></a>
					</div><!-- .catalog -->
					<?endif;?>
				<?//$this->EndViewTarget();?>
                    <div class="item_tabs<?=$bComplete ? ' item_tabs_with_set':'';?>"> <?/* closed in element.php*/?>
                        <ul class="slider_cat">
								<?
									$arTabs = $arParams["TABLIST"];
									foreach($arTabs as $str) {
										$tab_text = 'TABS-'.$str;
								?>
									<li <?if($str == $arParams["TABS_DEFAULT"]):?>class="active"<?endif?>><span class="slider_cat_wr"><a href="javascript:void(0);"><?=GetMessage($tab_text);?></a><i class="cl"></i><i class="cr"></i></span></li>
								<?}?>
						</ul>
<?$this->EndViewTarget();?>
<? $this->SetViewTarget('item-tech'); ?>
				<? if (CModule::IncludeModule('yenisite.infoblockpropsplus')): ?>
					<? $APPLICATION->IncludeComponent('yenisite:ipep.props_groups', 'bitronic', array('DISPLAY_PROPERTIES' => $arResult['DISPLAY_PROPERTIES'], 'IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SHOW_PROPERTY_VALUE_DESCRIPTION' => 'Y'),$component) ?>
				<? else: ?>
					<table>
						<tbody>

						<?if(count($arResult["DISPLAY_PROPERTIES"])>0):?>
						<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
							<tr>
								<td><?=$arProperty["NAME"]?></td>
								<td>
								<?
								if ($arProperty['PROPERTY_TYPE'] == 'L'):
									if (is_array($arProperty['DISPLAY_VALUE'])):
										foreach ($arProperty['DISPLAY_VALUE'] as $n => $value):
											echo $n > 0 ? ', ' : '';
											echo $arProperty['DISPLAY_VALUE'][$n];
										endforeach; else:
										echo $arProperty['DISPLAY_VALUE'];
									endif;
								else:
									if (is_array($arProperty["DISPLAY_VALUE"]) && $arProperty['PROPERTY_TYPE'] != 'F'):
										foreach ($arProperty["DISPLAY_VALUE"] as &$p) {
											if (substr_count($p, "a href") > 0) {
												$p = strip_tags($p);
											}
										}
										echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); elseif ($pid && $pid == "MANUAL"):
										?>
										<a href="<?= $arProperty["VALUE"] ?>" rel="nofollow"><?= GetMessage("CATALOG_DOWNLOAD") ?></a><? elseif ($arProperty['PROPERTY_TYPE'] == 'F'):
										if ($arProperty['MULTIPLE'] == 'Y'):
											if (is_array($arProperty['DISPLAY_VALUE'])):
												foreach ($arProperty['DISPLAY_VALUE'] as $n => $value):
													echo $n > 0 ? ', ' : '';
													echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'][$n] . '</a>', $value);
												endforeach; else:
												echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'][0] . '</a>', $arProperty['DISPLAY_VALUE']);
											endif; else:
											echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'] . '</a>', $arProperty['DISPLAY_VALUE']);
										endif; else:
										if (substr_count($arProperty["DISPLAY_VALUE"], "a href") > 0) {
											$arProperty["DISPLAY_VALUE"] = strip_tags($arProperty["DISPLAY_VALUE"]);
										}
										echo $arProperty["DISPLAY_VALUE"];
									endif;
								endif;
								?>
								</td>
							</tr>
						<?endforeach?>
						<?else:?>
						<tr>
							<td>
						<p class="errortext"><?=GetMessage("CATALOG_ERROR")?></p>
							</td>
						</tr>
						<?endif?>
					</tbody></table>
				<?endif;?>
<?$this->EndViewTarget();?>
<? $this->SetViewTarget('item-video'); ?>
	<?if(is_array($arResult["PROPERTIES"]['VIDEO']['VALUE'])>0):?>
		<?foreach ($arResult["PROPERTIES"]['VIDEO']['VALUE'] as $key => $value):?>
			<br />
			<?if(isset($value['path']) && is_array($value)):?>
				<?$APPLICATION->IncludeComponent("bitrix:player","",Array(
					"PATH" => $value['path'],
					"PROVIDER" => "",
					"WIDTH" => "560",
					"HEIGHT" => "315",
					"AUTOSTART" => "N",
					"REPEAT" => "none",
					"VOLUME" => "90",
					"ADVANCED_MODE_SETTINGS" => "N",
					"PLAYER_TYPE" => "auto",
					"USE_PLAYLIST" => "N",
					"STREAMER" => "",
					"PREVIEW" => "",
					"FILE_TITLE" => "",
					"FILE_DURATION" => "",
					"FILE_AUTHOR" => "",
					"FILE_DATE" => "",
					"FILE_DESCRIPTION" => "",
					"MUTE" => "N",
					"PLUGINS" => array(
						0 => "",
						1 => "",
					),
					"ADDITIONAL_FLASHVARS" => "",
					"PLAYER_ID" => "",
					"BUFFER_LENGTH" => "10",
					"ALLOW_SWF" => "N",
					"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
					"SKIN" => "",
					"CONTROLBAR" => "bottom",
					"WMODE" => "opaque",
					"LOGO" => "",
					"LOGO_LINK" => "",
					"LOGO_POSITION" => "none"
				), $component);?>			
			<?else:?>
				<?$APPLICATION->IncludeComponent("bitrix:player","",Array(
					"PLAYER_TYPE" => "flv",
					"PROVIDER" => "youtube",
					"PATH" =>  $value,
					"WIDTH" => "560",
					"HEIGHT" => "315",
				), $component);?>
			<?endif;?>
		<?endforeach;?>
	<?endif;?>
<?$this->EndViewTarget();?>
<?
?>
<? $this->SetViewTarget('item-manual'); ?>
	<?if(is_array($arResult["PROPERTIES"]['MANUAL']['VALUE'])):?>
		<?$arMimeFile = array(
			"TEXT" => array(
				"application/vnd.oasis.opendocument.text",
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"application/octet-stream", // all MS Office file
				"application/pdf",
			),
			"EXCEL" => array(
				"application/vnd.oasis.opendocument.spreadsheet",
				"application/vnd.ms-excel",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			),
		);?>
		<div>
		<?foreach ($arResult["PROPERTIES"]['MANUAL']['VALUE'] as $key => $value):?>
			<?
			$arFile = CFile::GetFileArray($value);
			
			if(in_array($arFile['CONTENT_TYPE'],$arMimeFile['TEXT']))
				$ico = "&#0109;";
			elseif(in_array($arFile['CONTENT_TYPE'],$arMimeFile['EXCEL']))
				$ico = "&#0105;";
			else
				$ico = "&#0061;";
			?>
			<p><span class="ws"><?=$ico?></span><?=(!empty($arFile['DESCRIPTION'])) ? $arFile['DESCRIPTION'] : $arFile['ORIGINAL_NAME']?> <?="(".round($arFile['FILE_SIZE']/1024,1)." ".GetMessage('YS_KB').")"?> <a target="_blank" href="<?=htmlspecialcharsbx($arFile["SRC"])?>"><?=GetMessage('CATALOG_DOWNLOAD')?></a></p>
		<?endforeach;?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>
<? $this->SetViewTarget('seoblock'); ?>
<?
if ($arParams['SHOW_SEOBLOCK'] == 'Y')
{
	$res = CIBlock::GetByID($arParams["IBLOCK_ID"]);
			 $ar_res = $res->GetNext();


	if(file_exists($_SERVER["DOCUMENT_ROOT"]. $ar_res["LIST_PAGE_URL"]. "/gen_seo.php"))
	{
		  require_once($_SERVER["DOCUMENT_ROOT"]. $ar_res["LIST_PAGE_URL"]. "/gen_seo.php");
		  global $GEN_SEO;
	}
	else
		  $GEN_SEO = GetMessage('SEO_TEXT');


	$GEN_SEO=str_replace("[SITE]", $_SERVER["HTTP_HOST"], $GEN_SEO);
	echo '<div id="pblock">'. strtr($GEN_SEO, Array("[NAME]"=>$arResult["NAME"], "[TIP]"=>$ar_res["ELEMENT_NAME"])). "</div>";
}
?>
<?$this->EndViewTarget();?>
<?$this->SetViewTarget('DETAIL_PIC');?>
          	<a class="back" <?if(strlen($arResult[LIST_PAGE_URL])>0):?> href="<?=$arResult[LIST_PAGE_URL]?>"<?else:
				$path=$APPLICATION->GetCurUri();
				$pos = strrpos($path, '/');
				if ($pos) 	$path=substr($path, 0, $pos + 1);
				?> href="<?=$path?>"<?endif;?>><?=GetMessage('BACK_TO_CATALOG');?></a>

<?$this->EndViewTarget();?>
<?
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