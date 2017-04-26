<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->AddHeadScript('/bitrix/js/main/cphttprequest.js'); // for iblock.vote?>

<?$APPLICATION->IncludeComponent(
	"yenisite:catalog.sets",
	"",
	Array(
		'ADD2BASKET' => 'Y',
	),
	false
);?>
<?if(!function_exists('yenisite_CATALOG_AVAILABLE'))
{
	function yenisite_CATALOG_AVAILABLE ($arProduct)
	{
		if(!count($arProduct['OFFERS']))
		{
			if(($arProduct['CATALOG_QUANTITY_TRACE'] == 'Y' && $arProduct['CATALOG_QUANTITY'] > 0) 
				||	$arProduct['CATALOG_QUANTITY_TRACE'] != 'Y')
				return true;
			else
				return false;
		}
		else
		{
			foreach ($arProduct['OFFERS'] as $arOffer)
			{
				if(($arOffer['CATALOG_QUANTITY_TRACE'] == 'Y' && $arOffer['CATALOG_QUANTITY'] > 0)
					|| $arOffer['CATALOG_QUANTITY_TRACE'] != 'Y')
					return true;
			}
		}
		return false;
	}
}?>
<?
	//Reviews
	if (!$arParams["REVIEWS_DEFAULT"])			$arParams['REVIEWS_DEFAULT'] = 'TECH';
	if (!$arParams["REVIEWS_TECH_ENABLE"])		$arParams['REVIEWS_TECH_ENABLE'] = "Y";
	if (!$arParams["REVIEWS_SITE_ENABLE"])		$arParams['REVIEWS_SITE_ENABLE'] = "Y";
	$arParams["SORT_OPTIONS"] = $arParams["SORT_OPTIONS"] ? $arParams["SORT_OPTIONS"] : 50;
	$arParams["SORT_REVIEWS"] = $arParams["SORT_REVIEWS"] ? $arParams["SORT_REVIEWS"] : 100;
	$arReviews = array("TECH", "SITE", "YM","VK","FB","DQ");
	$catalog_flag = CModule::IncludeModule("catalog");
	$arReviewServices = array();
	$indexarray = array();
	foreach($arReviews as $str) {
		if($arParams["REVIEWS_".$str."_ENABLE"] == "Y" and ($catalog_flag or $str == "TECH")) {
			array_push($arReviewServices, $str);
			$sort_index = $arParams["REVIEWS_".$str."_SORT"];
			if ($str == "TECH")
				$sort_index = $arParams["SORT_OPTIONS"];
			elseif ($str == "SITE")
				$sort_index = $arParams["SORT_REVIEWS"];	
			array_push($indexarray, $sort_index);
		}
	}
	array_multisort($indexarray, $arReviewServices);
?>
<?if((!function_exists('yenisite_WEEK_COUNTER')))
{
	function yenisite_WEEK_COUNTER ($ELEMENT_ID, $IBLOCK_ID)
	{
		$ELEMENT_ID = IntVal($ELEMENT_ID) ;
		if(!is_array($_SESSION["YENISITE_WEEK_COUNTER"]))
			$_SESSION["YENISITE_WEEK_COUNTER"] = Array();
			
		if(!$ELEMENT_ID || in_array($ELEMENT_ID, $_SESSION['YENISITE_WEEK_COUNTER']))
			return false;
		
		$arSelect = Array('ID', 'IBLOCK_ID', 'PROPERTY_WEEK', 'PROPERTY_WEEK_COUNTER') ;
		$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'ID'=>$ELEMENT_ID) ;
		$dbElement = CIBlockElement::GetList(Array(), $arFilter, false, array('nCountTop'=>1), $arSelect);
		if($arElement = $dbElement->Fetch())
		{
			$days = array() ;
			$today = strtotime(date("d.m.Y"));
			
			if(!$arElement['PROPERTY_WEEK_COUNTER_VALUE'])
				$days = Array($today => 1) ;
			else
			{
				$days = unserialize($arElement["PROPERTY_WEEK_VALUE"]);
				$days[$today] = IntVal($days[$today]) ? $days[$today] + 1 : 1 ;
			}
			$first_week_day = $today - 604800; // 604800 = 60 * 60 * 24 * 7
			$week_counter = 0 ;
			foreach ($days as $day => $count)
			{
				if($day > $first_week_day)
					$week_counter += $count ;
				else
					unset ( $days[$day] ) ;
			}
			
			CIBlockElement::SetPropertyValues($ELEMENT_ID, $IBLOCK_ID, serialize($days), "WEEK");
			CIBlockElement::SetPropertyValues($ELEMENT_ID, $IBLOCK_ID, $week_counter, "WEEK_COUNTER");
			$_SESSION["YENISITE_WEEK_COUNTER"][] = $ELEMENT_ID;
		}
	}
}
?>
<div id="pic_popup"> <a href="#" class="close sym" title="<?=GetMessage('TP_POPPIC_CLOSE');?>">&#206;</a> <a href="javascript:void(0);" class="button7 sym prev" title="<?=GetMessage('TP_POPPIC_PREV');?>">&#212;</a> <a class="button8 sym next" href="javascript:void(0);" title="<?=GetMessage('TP_POPPIC_NEXT');?>">&#215;</a>
  <div class="pop_img"> <img src="#" alt="" /> </div>
  <!--.pop_img-->
  <div class="pop_descr">
	<h2></h2>
  </div><!--.pop_descr--> 
</div><!--#pic_popup-->

<?if($arParams["USE_COMPARE"]=="Y"):?>
    <?$APPLICATION->IncludeComponent(
	    "bitrix:catalog.compare.list",
	    "",
	    Array(
		    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
		    "NAME" => $arParams["COMPARE_NAME"],
		    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		    "COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
	    ),
	    $component
    );?>
<?endif?>

<?
$arSets = array();
$arSets['DETAIL_IMG_SMALL'] = $arParams['DETAIL_IMG_SMALL'];
$arSets['DETAIL_IMG_BIG'] = $arParams['DETAIL_IMG_BIG'];
$arSets['DETAIL_IMG_ICON'] = $arParams['DETAIL_IMG_ICON'];
?>

<?$ElementID=$APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	Array(
 		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
 		"PROPERTY_CODE" => $arResult['YS_SHOW_PROPERTIES'], // <-- generation in result_modifier.php
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"USE_PRODUCT_QUANTITY" => "Y",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
 		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
 		"SET_TITLE" => 'N',
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],

 		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
 		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
 		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
 		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		
		//store_bitrix12:
		"USE_STORE" =>	$arParams["USE_STORE"],
		"USE_STORE_PHONE" =>	$arParams["USE_STORE_PHONE"],
		"USE_STORE_SCHEDULE" =>	$arParams["USE_STORE_SCHEDULE"],
		"USE_MIN_AMOUNT" =>	$arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" =>	$arParams["MIN_AMOUNT"],
		"STORE_PATH" =>	$arParams["STORE_PATH"],
		"MAIN_TITLE" =>	$arParams["MAIN_TITLE"],
		
		//resizer :
		"RESIZER_SETS" => $arSets,
		"RESIZER_BOX" => $arParams["RESIZER_BOX"],
		
		"ADD_SECTIONS_CHAIN" => "N",
		"SETTINGS_HIDE" => $arParams["SETTINGS_HIDE"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],

		"HIDE_ORDER_PRICE" => $arParams["HIDE_ORDER_PRICE"],
		"FOR_ORDER_DESCRIPTION" => $arParams["FOR_ORDER_DESCRIPTION"],
		
		//stickers :
		"STICKER_NEW" => $arParams['STICKER_NEW'],
		"STICKER_NEW_DELTA_TIME" => $arResult['STICKER_NEW_DELTA_TIME'],
		"STICKER_HIT" => $arParams['STICKER_HIT'],
		"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],
		
		// complete sets
		'PROPERTY_COMPLETE_SETS' => 'COMPLETE_SETS',
		'COMPLETE_SET_PROPERTIES' => $arParams['COMPLETE_SET_PROPERTIES'],
		'COMPLETE_SET_DESCRIPTION' => $arParams['COMPLETE_SET_DESCRIPTION'],
		'COMPLETE_SET_RESIZER_SET' => $arParams['COMPLETE_SET_RESIZER_SET'],
		'COMPLETE_SET_NO_INCLUDE_PRICE' => $arParams['COMPLETE_SET_NO_INCLUDE_PRICE'] ? $arParams['COMPLETE_SET_NO_INCLUDE_PRICE'] : 'N',
		
		//announce :
		"SHOW_ANNOUNCE" => $arParams['SHOW_ANNOUNCE'],
		"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT'],
		"SHOW_SKLAD" => $arParams['SHOW_SKLAD'],
		
		"SHOW_SEOBLOCK" => $arParams['SHOW_SEOBLOCK'],
		
		"REVIEWS_SERVICES" => $arReviewServices,
		"REVIEWS_DEFAULT" => $arParams['REVIEWS_DEFAULT'],

		'STORE_CODE' => $arParams["STORE_CODE"],
	),
	$component
);?>

<?yenisite_WEEK_COUNTER($ElementID, $arParams['IBLOCK_ID']);?>
<div class="item_detail">
	<?global $APPLICATION; $APPLICATION->ShowViewContent('DETAIL_TOP');?>
		
	<?foreach($arReviewServices as $str) { ?>
		<?if($str == "TECH"):?>
				<div id="item-tech" class="tab_block" <?if($str == $arParams["REVIEWS_DEFAULT"]):?>style="display: block;"<?endif?>>
					<?$APPLICATION->ShowViewContent('item-tech');?>
				</div>
		<?elseif($str == "SITE"):?>
			<div id="item-replies" class="tab_block" <?if($str == $arParams["REVIEWS_DEFAULT"]):?>style="display: block;"<?endif?>>
				<?$APPLICATION->IncludeComponent(
					"bitrix:forum.topic.reviews",
					"reviews",
					Array(
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
						"USE_CAPTCHA" => $arParams["USE_CAPTCHA"],
						"PATH_TO_SMILE" => $arParams["PATH_TO_SMILE"],
						"FORUM_ID" => $arParams["FORUM_ID"],
						"URL_TEMPLATES_READ" => $arParams["URL_TEMPLATES_READ"],
						"SHOW_LINK_TO_FORUM" => $arParams["SHOW_LINK_TO_FORUM"],
						"ELEMENT_ID" => $ElementID,
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"AJAX_POST" => $arParams["REVIEW_AJAX_POST"],
						"POST_FIRST_MESSAGE" => $arParams["POST_FIRST_MESSAGE"],
						"URL_TEMPLATES_DETAIL" => $arParams["POST_FIRST_MESSAGE"]==="Y"? $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"] :"",
					),
					$component
				);?>
			</div>	
		<?elseif($str == "YM"):?>
			<div id="item-replies-ym" class="tab_block" <?if($str == $arParams["REVIEWS_DEFAULT"]):?>style="display: block;"<?endif?>>
				<?if(CModule::IncludeModule("yenisite.review")):?>
					<?$APPLICATION->IncludeComponent("yenisite:yandex.market_review", ".default", array(
					"ELEMENT_ID" => $ElementID,
					),
					false
				);?>
				<?endif;?>
			</div>
		<?else:		//vk, fb, dq?>
			<div id="item-replies-<?=strtolower($str);?>" class="tab_block" <?if($str == $arParams["REVIEWS_DEFAULT"]):?>style="display: block;"<?endif?>>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/reviews/".strtolower($str).".php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
			</div>
		<?endif?>
		
	<?}?>	
	
	
	</div><!--.item-tabs--><?/* open in template.php */?>
</div><!--.item_detail-->
<?$APPLICATION->ShowViewContent('seoblock');?>
<? $this->SetViewTarget('SLIDER'); ?>

<?
global $SLIDER_FILTER ;
if($arParams['ACCESSORIES_ON'] == 'Y')
{
	$APPLICATION->IncludeComponent("yenisite:catalog.accessories", "", array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $ElementID,
		"ACCESSORIES_LINK" => $arParams["ACCESSORIES_LINK"],
		"ACCESSORIES_PROPS" => $arParams["ACCESSORIES_PROPS"],
		"FILTER_NAME" => "SLIDER_FILTER",
		"PAGE_ELEMENT_COUNT" => $arParams["ACCESSORIES_PAGE_ELEMENT_COUNT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		),
		$component
	);
	
	$slider_show  		= count($SLIDER_FILTER['ID']) ; 
	if($arParams['FILTER_BY_QUANTITY'] == 'Y')
		$SLIDER_FILTER['>CATALOG_QUANTITY'] = 0;
	$slider_title 		= GetMessage('ACCESSORIES');
	$slider_iblock_type	= '' ; 
	$slider_iblock_id	= '' ;
	$slider_section_id  = '' ;
}
else
{
	$SLIDER_FILTER 		= array("!ID" => $ElementID);
	if($arParams['FILTER_BY_QUANTITY'] == 'Y')
		$SLIDER_FILTER['>CATALOG_QUANTITY'] = 0;
	//FILTER BY ELEMENTS' BASE PRICE/////////////
	$obCache = new CPHPCache; 
	if($obCache->InitCache($arParams["CACHE_TIME"]-5, 'yenisite_similar_product_'.$ElementID, "/ys_slider_filter/")) 
	{
		$vars = $obCache->GetVars();
		$BASE_PRICE = $vars["AR_FILTER_PRICE"];
		if($BASE_PRICE['PRICE'] > 0)
		{
			$SLIDER_FILTER['>=CATALOG_PRICE_'.$BASE_PRICE['ID_BASE_PRICE']] = $BASE_PRICE['PRICE']*0.8;
			$SLIDER_FILTER['<=CATALOG_PRICE_'.$BASE_PRICE['ID_BASE_PRICE']] = $BASE_PRICE['PRICE']*1.2;
			$SLIDER_FILTER["OFFERS"]=array();
		}
	}
	unset($obCache) ;	
	/////////////////////////////////////////////
	$slider_show 		= true ;
	$slider_title 		= GetMessage('MORE_ITEMS');
	$slider_iblock_type	= $arParams["IBLOCK_TYPE"]; 
	$slider_iblock_id   = $arParams['IBLOCK_ID'] ;
	global $elementSection;
	$slider_section_id  = $elementSection ;
}
if($slider_show):?>
			<?$APPLICATION->IncludeComponent("yenisite:catalog.section.all", "detail_spec", array(
				"BLOCK_VIEW_MODE" => $ys_options["block_view_mode"],
				"SLIDER_TITLE" => $slider_title,
				"IBLOCK_TYPE" => $slider_iblock_type,
				"IBLOCK_ID" => $slider_iblock_id,
				"IMAGE_SET" => "3",	//
				"SECTION_ID" => $slider_section_id,
				"SECTION_CODE" => "",
				"SECTION_USER_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"ELEMENT_SORT_FIELD" => "rand",
				"ELEMENT_SORT_ORDER" => "asc",
				"FILTER_NAME" => "SLIDER_FILTER",
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"PAGE_ELEMENT_COUNT" => "30",
				"LINE_ELEMENT_COUNT" => "3",
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "MORE_PHOTO",
					2 => "",
				),
				"SECTION_URL" => "",
				"DETAIL_URL" => "",
				"BASKET_URL" => "/personal/basket.php",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRODUCT_QUANTITY_VARIABLE" => "quantity",
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"ADD_SECTIONS_CHAIN" => "N",
				"DISPLAY_COMPARE" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"CACHE_FILTER" => "Y",
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => "N",
				"SHOW_PRICE_COUNT" => "1",
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRODUCT_PROPERTIES" => array(
				),
				"INCLUDE_SUBSECTION" => "Y",
				"USE_PRODUCT_QUANTITY" => "N",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "Y",
				"PAGER_TEMPLATE" => "",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "Y",
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"AJAX_OPTION_ADDITIONAL" => "",

				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"HIDE_ORDER_PRICE" => $arParams["HIDE_ORDER_PRICE"],
				"ELEMENT_ID" => $ElementID,

				'HIDE_BUY_IF_PROPS' => $arParams['HIDE_BUY_IF_PROPS'],
				"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT']
				),
				$component
			);?>
<?endif;?>

<? $this->EndViewTarget(); ?>
<?
$arParams['DETAIL_META_H1_FORCE'] = $arParams['DETAIL_META_H1_FORCE'] ? $arParams['DETAIL_META_H1_FORCE'] : 'H1' ;
$arParams['DETAIL_META_TITLE_PROP_FORCE'] = $arParams['DETAIL_META_TITLE_PROP_FORCE'] ? $arParams['DETAIL_META_TITLE_PROP_FORCE'] : 'TITLE' ;
$arParams['DETAIL_META_KEYWORDS_FORCE'] = $arParams['DETAIL_META_KEYWORDS_FORCE'] ? $arParams['DETAIL_META_KEYWORDS_FORCE'] : 'KEYWORDS' ;
$arParams['DETAIL_META_DESCRIPTION_FORCE'] = $arParams['DETAIL_META_DESCRIPTION_FORCE'] ? $arParams['DETAIL_META_DESCRIPTION_FORCE'] : 'DESCRIPTION' ;
$APPLICATION->IncludeComponent(
	"yenisite:catalog.detail_meta",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $ElementID,
		"META_SPLITTER" => $arParams['DETAIL_META_SPLITTER'],
		"META_TITLE" => $arParams['DETAIL_META_H1'],
		"META_TITLE_FORCE" => $arParams['DETAIL_META_H1_FORCE'],
		"META_TITLE2" => $arParams['DETAIL_META_TITLE_PROP'],
		"META_TITLE2_FORCE" => $arParams['DETAIL_META_TITLE_PROP_FORCE'],
		"META_KEYWORDS" => $arParams['DETAIL_META_KEYWORDS'],
		"META_KEYWORDS_FORCE" => $arParams['DETAIL_META_KEYWORDS_FORCE'],
		"META_DESCRIPTION" => $arParams['DETAIL_META_DESCRIPTION'],
		"META_DESCRIPTION_FORCE" => $arParams['DETAIL_META_DESCRIPTION_FORCE'],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	),
	$component
);?>
