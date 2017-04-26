<?
if($_REQUEST["ys_ms_ajax_call"] === "y")
{
	define("SITE_ID", htmlspecialchars($_REQUEST["site_id"]));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}else{
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}
?>
<?

$accessories_filter = array();
global $accessories_filter;
global $ys_options;

session_start();
if($_REQUEST["ys_ms_ajax_call"] === "y")
{
	$ys_options = array();
	global $ys_options;
	$lifetime = 60*60*24;
	$slider_id=htmlspecialchars($_REQUEST["slider_id"]);
	if(!in_array($slider_id, array("detail","basket","add2b_popup")))
		$slider_id = "detail";
	if(is_array($_SESSION['slider_'.$slider_id]))
		$id_list=$_SESSION['slider_'.$slider_id]['id_list'];
}
else
{
	$lifetime = $arParams['CACHE_TIME'] ? intval($arParams['CACHE_TIME']) : 60*60*24;
	if(isset($ElementID) && !is_array($id_list))
		$id_list = array($ElementID);
	$_SESSION['slider_'.$slider_id] = array(
		'id_list'=>$id_list,
	);
	global $elementSection;
	$slider_section_id  = $elementSection ;	
	$slider_iblock_type	= $arParams["IBLOCK_TYPE"]; 
	$slider_iblock_id   = $arParams['IBLOCK_ID'] ;
	if($slider_id == "detail")
	{
		$obCache = new CPHPCache;
		$obCache->Clean("detail_similar_params_".SITE_ID."_".$id_list[0], "/");
		$obCache->Clean('yenisite_product_info_'.$id_list[0], "/ys_slider_filter/");
		unset($obCache) ;
	}
}

/*SAVE PARAM*/
$save_param = new CPHPCache();
if($save_param->InitCache($lifetime, "detail_similar_params_".SITE_ID."_".$id_list[0], "/")) :
	$vars = $save_param->GetVars();
	$ys_options = $vars["ys_options"];
	$arParams = $vars["arParams"];
	$slider_title_s = $vars["slider_title_s"];
else:
	if($_REQUEST["ys_ms_ajax_call"] === "y"):
		die("cache");
	endif;
endif;
if($save_param->StartDataCache()):
	$save_param->EndDataCache(array(
		"arParams"    => $arParams,
		"ys_options"    => $ys_options,
	)); 
endif;

$slider_show = false;

if($arParams['ACCESSORIES_ON'] == 'Y' || $slider_id == "basket")
{
	$APPLICATION->IncludeComponent("yenisite:catalog.accessories.list", "", array(
		"ID_LIST" => $id_list,
		"FILTER_NAME" => "accessories_filter",
		"PAGE_ELEMENT_COUNT" => "10",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400"
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);

	$slider_show  		= (count($accessories_filter['ID']) > 0)  ? true : false; 

	if($arParams['FILTER_BY_QUANTITY'] == 'Y')
		$accessories_filter['CATALOG_AVAILABLE'] = 'Y';
	$slider_iblock_type	= '' ; 
	$slider_iblock_id	= '' ;
	$slider_section_id  = '' ;
	$slider_title 		= ($slider_id == "basket") ? 'ACCESSORIES_2':'ACCESSORIES';
}

if(($slider_id == "basket" && !$slider_show) || ($slider_id != "basket" && $arParams['ACCESSORIES_ON'] != 'Y'))
{
	
	if(count($id_list)>1)
		$accessories_filter = array( 'LOGIC' => 'OR');
	else
		$accessories_filter = array();
	
	foreach($id_list as $key => $value)
	{
		$add_filter = array("!ID" => $id_list[$key]);
		if($arParams['FILTER_BY_QUANTITY'] == 'Y')
			$add_filter['CATALOG_AVAILABLE'] = 'Y';
		//FILTER BY ELEMENTS' BASE PRICE/////////////
		$obCache = new CPHPCache; 
		if($obCache->InitCache($arParams["CACHE_TIME"]-5, 'yenisite_similar_product_'.$id_list[$key], "/ys_slider_filter/")) 
		{
			$vars = $obCache->GetVars();
			$BASE_PRICE = $vars["AR_FILTER_PRICE"];
			if($BASE_PRICE['PRICE'] > 0)
			{
				$add_filter['>=CATALOG_PRICE_'.$BASE_PRICE['ID_BASE_PRICE']] = $BASE_PRICE['PRICE']*0.8;
				$add_filter['<=CATALOG_PRICE_'.$BASE_PRICE['ID_BASE_PRICE']] = $BASE_PRICE['PRICE']*1.2;
				$add_filter["OFFERS"]=array();
			}
		}
		unset($obCache);	

		$obCache = new CPHPCache; 
		if($obCache->InitCache($arParams["CACHE_TIME"]-5, 'yenisite_product_info_'.$id_list[$key], "/ys_slider_filter/")) 
		{
			$vars = $obCache->GetVars();
			$slider_iblock_type = $vars["IBLOCK_TYPE"];
			$slider_iblock_id = $vars["IBLOCK_ID"];
			$slider_section_id = $vars["SEC_ID"];
			$slider_show 		= true ;
		}else{
			if($slider_iblock_id)
				$slider_show = true ;
			else
				continue;
		}
		if($obCache->StartDataCache()):
			$obCache->EndDataCache(array(
				"IBLOCK_TYPE"    => $slider_iblock_type,
				"IBLOCK_ID"    => $slider_iblock_id,
				"SEC_ID"    => $slider_section_id,
			)); 
		endif;		
		unset($obCache);		
		
		$add_filter["IBLOCK_TYPE"] = $slider_iblock_type;
		$add_filter["IBLOCK_ID"] = $slider_iblock_id;
		$add_filter["SECTION_ID"] = $slider_section_id;
		
		
		$accessories_filter[] = $add_filter;
	}
	/////////////////////////////////////////////
	
	$slider_title 		= 'MORE_ITEMS';
	$slider_iblock_type	= '' ; 
	$slider_iblock_id	= '' ;
	$slider_section_id  = '' ;
}
if($slider_id == "detail" && $arParams['ACCESSORIES_ON'] != 'Y' && $arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && count($arParams['OFFER_TREE_PROPS']))
{
	$slider_iblock_id = $arParams["IBLOCK_ID"];
}
	$accessories_filter = array($accessories_filter);
$similar_params = array(
	"IS_YS_MS" => "Y",
	"SITE_DIR" => SITE_DIR,
	"slider_id" => $slider_id,
	"BLOCK_VIEW_MODE" => $ys_options["block_view_mode"],
	"SLIDER_TITLE" => $slider_title,
	"IBLOCK_TYPE" => $slider_iblock_type,
	"IBLOCK_ID" => $slider_iblock_id,
	"IMAGE_SET" =>  $arParams['TABLE_IMG'] ? $arParams['TABLE_IMG'] : 5,
	"BLOCK_IMG_BIG" =>  $arParams['BLOCK_IMG_BIG'] ? $arParams['BLOCK_IMG_BIG'] : 4,
	"BLOCK_IMG_SMALL" =>  $arParams['BLOCK_IMG_SMALL'] ? $arParams['BLOCK_IMG_SMALL'] : 3,
	"SECTION_ID" => $slider_section_id,
	"SECTION_CODE" => "",
	"SECTION_USER_FIELDS" => array(
		0 => "",
		1 => "",
	),
	
	"FILTER_NAME" => "accessories_filter",
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
	"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
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
	/*"ELEMENT_ID" => $id_list[0],*/

	'HIDE_BUY_IF_PROPS' => $arParams['HIDE_BUY_IF_PROPS'],
	"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT'],
	
	// selectBox SKU
	'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
);
if($arParams['ACCESSORIES_ON'] != 'Y')
{
	$similar_params["ELEMENT_SORT_FIELD"] = "rand";
	$similar_params["ELEMENT_SORT_ORDER"] = "asc";
}
	
if($slider_show):?>
	<?$APPLICATION->IncludeComponent("yenisite:catalog.section.all", $slider_id == "add2b_popup" ? "basket_access" : "detail_spec", $similar_params, $component, array("HIDE_ICONS" => "Y"));?>
<?endif;?>


<?if($_REQUEST["ys_ms_ajax_call"] === "y")
{require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");}
else{
		/*$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/similar-items.js");*/
		?><script type="text/javascript" src="<?=SITE_TEMPLATE_PATH."/static/js/similar-items.js";?>"></script><?
		$similar_ajax_path = SITE_TEMPLATE_PATH."/ajax/accessories.php";
?>
		<script type="text/javascript">
				ys_slider_JSInit('<?=$similar_ajax_path;?>', '<?=SITE_ID;?>', '<?=$slider_id?>', '<?=$_SERVER["PHP_SELF"] ? $_SERVER["PHP_SELF"] : "/";?>');
		</script>
<?}?>