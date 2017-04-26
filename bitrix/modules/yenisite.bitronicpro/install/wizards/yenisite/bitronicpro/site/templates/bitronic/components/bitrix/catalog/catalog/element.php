<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->AddHeadScript('/bitrix/js/main/cphttprequest.js'); // for iblock.vote?>
<?if ($arParams["SLIDER_USE_MOUSEWHEEL"] != 'N' && CModule::IncludeModule('yenisite.mainspec'))
$APPLICATION->AddHeadScript('/bitrix/components/yenisite/main_spec/templates/.default/plugins/jquery.mousewheel.js');
?>
<?global $ys_options;

if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}

if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?$APPLICATION->IncludeComponent(
	"yenisite:catalog.sets",
	"",
	Array(
		'ADD2BASKET' => 'Y',
	),
	false
);?>
<?
	//Tabs
	if (!$arParams["TABS_DEFAULT"])			$arParams['TABS_DEFAULT'] = 'TECH';
	if (!$arParams["TABS_TECH_ENABLE"])		$arParams['TABS_TECH_ENABLE'] = 'Y';
	if (!$arParams["TABS_TECH_SORT"])		$arParams['TABS_TECH_SORT'] = 100;
	if (!$arParams["TABS_REVIEWS_ENABLE"])	$arParams['TABS_REVIEWS_ENABLE'] = 'Y';
	if (!$arParams["TABS_REVIEWS_SORT"])		$arParams['TABS_REVIEWS_SORT'] = 200;
	$arTabs = array("TECH", "REVIEWS","VIDEO", "MANUAL");
	$arTabList = array();
	$indexarray = array();
	foreach($arTabs as $str) {
		if($arParams["TABS_".$str."_ENABLE"] == "Y") {
			$sort_index = $arParams["TABS_".$str."_SORT"];
			array_push($arTabList, $str);
			array_push($indexarray, $sort_index);
		}
	}
	array_multisort($indexarray, $arTabList);	



	//Reviews
	if (!$arParams["REVIEWS_SITE_ENABLE"])		$arParams['REVIEWS_SITE_ENABLE'] = "Y";
	$arParams["SORT_REVIEWS"] = $arParams["SORT_REVIEWS"] ? $arParams["SORT_REVIEWS"] : 100;
	$arReviews = array("SITE", "YM2", "YM", "MR","VK","FB","DQ");
	$catalog_flag = CModule::IncludeModule("catalog");
	$arReviewServices = array();
	$indexarray = array();
	foreach($arReviews as $str) {
		if($arParams["REVIEWS_".$str."_ENABLE"] == "Y" and ($catalog_flag or $str != "SITE")) {
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

<div id="pic_popup"> <a href="javascript:void(0);" class="close sym" title="<?=GetMessage('TP_POPPIC_CLOSE');?>">&#206;</a> <a href="javascript:void(0);" class="button7 sym prev" title="<?=GetMessage('TP_POPPIC_PREV');?>">&#212;</a> <a class="button8 sym next" href="javascript:void(0);" title="<?=GetMessage('TP_POPPIC_NEXT');?>">&#215;</a>
  <div class="pop_img"><div id="l-popup" ><span class='notloader ws' style="display: none; left: 45%;top: 50%; position: absolute; font-size: 98px;">0</span></div> <img src="/upload/resizer2/no_photo.gif" alt="" /> </div>
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
<?
$review_tab = false;
foreach($_REQUEST as $key=>$val)
{
	if(preg_match('/^PAGEN_/',$key) && intval($val)>0){
		$review_tab = true;
		break;
    }
}
if(in_array("REVIEWS",$arTabList) && (is_numeric($_REQUEST['review_page']) || $review_tab)) $arParams["TABS_DEFAULT"]="REVIEWS";?>
<?
//need to clear cache on smart_filter_type change
$smartFilterType = $ys_options['smart_filter_type'];
if (CModule::IncludeModule('kombox.filter') && $smartFilterType == 'KOMBOX') {
	if (CKomboxFilter::IsSefMode($APPLICATION->GetCurPage(false))) {
		$smartFilterType .= '_SEF';
	}
}
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
		
		"TABLIST" => $arTabList,
		"TABS_DEFAULT" => $arParams['TABS_DEFAULT'],
		
		'STORE_CODE' => $arParams["STORE_CODE"],
		//vkredit:
		"VKREDIT_BUTTON_ON" => $arParams['VKREDIT_BUTTON_ON'],
		
		'FILTER_TYPE' => $smartFilterType, //fix to clear cache on filter type change
		'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
		
		'VIEW_PHOTO' => $ys_options['view_photo'],
		
		// selectBox SKU
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		//for Parent photo by SKU
		"PARENT_PHOTO_SKU" => $arParams['PARENT_PHOTO_SKU'],
	),
	$component
);?>
<? /* ==================== kypi v 1 click START */ ?>
<?if(CModule::IncludeModule('grain.fastorder')):?>
 <div id="ys-fastorder-popup" class="popup"></div>
<?endif;?>
<? /* ==================== kypi v 1 click END */ ?>
<? /* ==================== kypiVkredit START */ ?>
<?if(CModule::IncludeModule('tcsbank.kupivkredit') && $arParams["VKREDIT_BUTTON_ON"]=='Y' ):?>
	<? $APPLICATION->AddHeadScript('https://kupivkredit-test-fe.tcsbank.ru/widget/vkredit-bitrix.js'); ?> <script type="text/javascript"> var vbb = new VkreditBitrixButton({ bitrixButtonId:'b-<?=$ElementID;?>', paymentId:'ID_PAY_SYSTEM_ID_<?=$arParams['VKREDIT_PAYMENT'];?>' }); </script>
<?endif;?>
<? /* ==================== kypiVkredit END  ==== here and in file no_sku.php*/ ?>
<?yenisite_WEEK_COUNTER($ElementID, $arParams['IBLOCK_ID']);?>
<div class="item_detail">
	<?global $APPLICATION; $APPLICATION->ShowViewContent('DETAIL_TOP');?>
		
	<?foreach($arTabList as $tab) { ?>
		<?if($tab == "TECH"):?>
			<div id="item-tech" class="tab_block" <?if($tab == $arParams["TABS_DEFAULT"]):?>style="display: block;"<?endif;?>>
				<?$APPLICATION->ShowViewContent('item-tech');?>
			</div>
		<?elseif($tab == "REVIEWS"):?>
			<div id="item-replies" class="tab_block" <?if($tab == $arParams["TABS_DEFAULT"]):?>style="display: block;"<?endif;?>>
				<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
				<?foreach($arReviewServices as $str) { ?>
					<?if($str == "SITE" && CModule::IncludeModule("catalog")):?>
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
								"PAGE_NAVIGATION_TEMPLATE" => "bitronic_not_sef",
								"PREORDER" => (!empty($arParams["PREORDER"])) ? $arParams["PREORDER"] : "N",
								"URL_TEMPLATES_DETAIL" => $arParams["POST_FIRST_MESSAGE"]==="Y"? $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"] :"",
							),
							$component
						);?>
					<?elseif($str == "YM"):?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/reviews/ym.php", "EDIT_TEMPLATE" => "include_areas_template.php", "ELEMENT_ID" => $ElementID), false, array("HIDE_ICONS"=>"Y"));?>
					<?elseif($str == "YM2"): //$arResult["PROPERTIES"] set in catalog.element component_epilog.php?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/reviews/ym2.php", "EDIT_TEMPLATE" => "include_areas_template.php", "ELEMENT_ID" => $arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"]), false, array("HIDE_ICONS"=>"Y"));?>
					<?elseif($str == "MR"): //$arResult["PROPERTIES"] set in catalog.element component_epilog.php?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/reviews/mr.php", "EDIT_TEMPLATE" => "include_areas_template.php", "ELEMENT_ID" => $arResult["PROPERTIES"]["MAILRU_ID"]["VALUE"]), false, array("HIDE_ICONS"=>"Y"));?>
					<?else:		//vk, fb, dq?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/reviews/".strtolower($str).".php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					<?endif;?>
				<?}?>
				<?if(method_exists($this, 'createFrame')) $frame->end();?>
			</div>
		<?elseif($tab == "VIDEO"):?>
			<div id="item-video" class="tab_block" <?if($tab == $arParams["TABS_DEFAULT"]):?>style="display: block;"<?endif;?>>
				<?$APPLICATION->ShowViewContent('item-video');?>
			</div>
		<?elseif($tab == "MANUAL"):?>
			<div id="item-manual" class="tab_block" <?if($tab == $arParams["TABS_DEFAULT"]):?>style="display: block;"<?endif;?>>
				<?$APPLICATION->ShowViewContent('item-manual');?>
			</div>
		<?endif;?>
	<?}?>
	
	
	</div><!--.item-tabs--><?/* open in template.php */?>
</div><!--.item_detail-->
<?$APPLICATION->ShowViewContent('seoblock');?>
<? $this->SetViewTarget('SLIDER'); ?>

<? //ACCESSORIES
$APPLICATION->IncludeComponent("yenisite:catalog.accessories", "", array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_ID" => $ElementID,
	"ACCESSORIES_LINK" => $arParams["ACCESSORIES_LINK"],
	"ACCESSORIES_PROPS" => $arParams["ACCESSORIES_PROPS"],
	"FILTER_NAME" => "accessories_filter",
	"PAGE_ELEMENT_COUNT" => $arParams["ACCESSORIES_PAGE_ELEMENT_COUNT"],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	),
	$component
);
$slider_id = "detail";
require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/ajax/accessories.php");
?>

<? $this->EndViewTarget(); ?>
<?
if(CModule::IncludeModule("yenisite.meta"))
{
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
);
}?>