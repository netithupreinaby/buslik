<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/*for im24.ru*/
$param_file = $_SERVER["DOCUMENT_ROOT"].SITE_DIR."params_for_all_iblocks.php";
if (file_exists($param_file)) {
    require($param_file);
}
/****************/
/*--------catalog ajax*/
	?><script>if(!YS.Ajax.init_flag){YS.Ajax.Init('<?=SITE_TEMPLATE_PATH."/ajax/catalog_filter.php"?>','<?=SITE_ID?>','<?=$arParams["IBLOCK_ID"]?>');}</script><?
/*-----end catalog ajax*/

/*--------filter ajax*/

if($arParams['FILTER_BY_QUANTITY'] == 'Y' && !in_array('AVAILABLE', $arParams['KOMBOX_FIELDS'])){
	$arParams['KOMBOX_FIELDS'][] = 'AVAILABLE';
}

global $ys_options;
global $YS_AJAX_FILTER_ENABLE;
$YS_AJAX_FILTER_ENABLE = ($ys_options["smart_filter_ajax"] == "Y")?"Y":"N";

if($arParams['PRODUCT_DISPLAY_MODE'] == 'D' || !$arParams['PRODUCT_DISPLAY_MODE'])
	$arParams['PRODUCT_DISPLAY_MODE'] = $ys_options['sku_type'];
	
if(isset($ys_options)){
	$arParams['SHOW_ELEMENT'] = $ys_options['show_element'] == 'Y' ? 'Y' : 'N' ;
	//$arParams["SMART_FILTER"] = $ys_options['smart_filter'] == 'Y' ? 'Y' : 'N' ; this params delete in bitronic 1.15.0
	$arParams["SMART_FILTER"] = 'Y';
}


if($_REQUEST["clear_cache"] == "Y")
	$YS_AJAX_FILTER_ENABLE == "N";

if($arParams['FILTER_BY_QUANTITY'] == 'Y')
{
	global ${$arParams['FILTER_NAME']};
	if ($_REQUEST["f_Quantity"] == "Y" || ($APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y' && empty($_REQUEST["set_filter"]) && $_REQUEST["ajax"] !== "y"))
	{
		$arResult['q_checked'] = 'Y';
		$arParams['HIDE_NOT_AVAILABLE'] = 'N';
		// show all
		
		//${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'N';
	}
	else
	{
		$arResult['q_checked'] = 'N';
		if($ys_options["smart_filter_type"] != "KOMBOX") 
		{
			$arParams['HIDE_NOT_AVAILABLE'] = 'Y';
			${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'Y';
		}
		else 
		{
			$arParams['HIDE_NOT_AVAILABLE'] = 'N';
		}
	}
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");
}
/*-----end filter ajax*/


CModule::IncludeModule('yenisite.resizer2');

// get show params
$obCache = new CPHPCache;

$cache_id = 'ys_element_properties155_'.$arParams['IBLOCK_ID'].serialize($arParams['SETTING_HIDE']) ;

if($obCache->InitCache($arParams["CACHE_TIME"], $cache_id, "/"))
{
    $vars = $obCache->GetVars();
    $arResult['YS_SHOW_PROPERTIES'] = $vars["YS_SHOW_PROPERTIES"];
	$arParams['SETTINGS_HIDE'] = $vars["SETTINGS_HIDE"];
    unset ($vars);
}
else
{
	$system_properties = array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY', 'SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');

	if(count($arParams['SETTINGS_HIDE']))
		$arParams['SETTINGS_HIDE'] = array_merge($system_properties, $arParams['SETTINGS_HIDE']);
	else
		$arParams['SETTINGS_HIDE'] = $system_properties ;
		
	if(count($arParams['ACCESSORIES_LINK']))
		$arParams['SETTINGS_HIDE'] = array_merge($arParams['SETTINGS_HIDE'], $arParams['ACCESSORIES_LINK']);

	$dbProps = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], 'ACTIVE'=>'Y'));
	$arResult['YS_SHOW_PROPERTIES'] = array() ;
	while($arProp = $dbProps->Fetch())
		if(substr_count($arProp["CODE"], "CML2_") == 0 && !in_array($arProp["CODE"], $arParams["SETTINGS_HIDE"]) &&  strpos($arProp['CODE'], 'TURBO_') !== 0)
			$arResult['YS_SHOW_PROPERTIES'][] = $arProp['CODE'] ;
}
if($obCache->StartDataCache())
    $obCache->EndDataCache(array(
        "YS_SHOW_PROPERTIES"    => $arResult['YS_SHOW_PROPERTIES'],
		"SETTINGS_HIDE"    	=> $arParams['SETTINGS_HIDE']
        )); 
unset($obCache) ;

// modify compare fields
$arCompareFields = array('DETAIL_PICTURE','PREVIEW_PICTURE');
foreach($arCompareFields as $field)
	if(!in_array($field,$arParams["COMPARE_FIELD_CODE"]))
		$arParams["COMPARE_FIELD_CODE"][] = $field;
		
// get description
$obCache = new CPHPCache;
$arResult['IBLOCK_DESCRIPTION'] = false;
$arResult['IBLOCK_SECTION'] = $arResult["VARIABLES"]["SECTION_ID"] ? $arResult["VARIABLES"]["SECTION_ID"] : $arResult["VARIABLES"]["SECTION_CODE"] ;
if($arParams['SECTION_SHOW_DESCRIPTION'] == 'Y' && $arParams['IBLOCK_ID'])
{
	$cache_id = 'ys_seo_descriptions3_'.$arParams['IBLOCK_ID'].$arResult['IBLOCK_SECTION'] ;
	if($obCache->InitCache($arParams["CACHE_TIME"], $cache_id, "/"))
	{
		$vars = $obCache->GetVars();
		$arResult['SEO_DESCRIPTION'] = $vars['SEO_DESCRIPTION'];
		$arResult['SEO_DESCRIPTION_IMG'] = $vars['SEO_DESCRIPTION_IMG'];
		unset ($vars);
	}
	else
	{
		if($arResult['IBLOCK_SECTION'])
		{
			if(intval($arResult["VARIABLES"]["SECTION_ID"])>0)
				$arFilter['ID'] = $arResult["VARIABLES"]["SECTION_ID"] ;
			elseif($arResult["VARIABLES"]["SECTION_CODE"])
				$arFilter['CODE'] = $arResult["VARIABLES"]["SECTION_CODE"] ;
			$arFilter['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
			$arSection = CIBlockSection::GetList(array(), $arFilter, false, Array('DESCRIPTION','PICTURE'))->GetNext();
			$arResult['SEO_DESCRIPTION'] = $arSection['DESCRIPTION'] ;
			$arResult['SEO_DESCRIPTION_IMG'] = CFile::GetPath($arSection['PICTURE']) ;
		}
		else
		{
			$arIBlock = CIBlock::GetByID($arParams['IBLOCK_ID']) -> GetNext();
				echo '<pre style="display:none;">'; print_r($arIBlock); echo '</pre>';
			$arResult['SEO_DESCRIPTION'] = $arIBlock['DESCRIPTION'] ;
			$arResult['SEO_DESCRIPTION_IMG'] = CFile::GetPath($arIBlock['PICTURE']) ;
		}
	}
	if($obCache->StartDataCache())
		$obCache->EndDataCache(array(
			"SEO_DESCRIPTION"    => $arResult['SEO_DESCRIPTION'],
			"SEO_DESCRIPTION_IMG" => $arResult['SEO_DESCRIPTION_IMG'],
			)); 
	unset($obCache) ;
}

if(!$arParams['ELEMENT_ID']) // for section and sections.php
{
	$arParams['DEFAULT_ELEMENT_SORT_BY'] = $arParams['DEFAULT_ELEMENT_SORT_BY'] ? $arParams['DEFAULT_ELEMENT_SORT_BY'] : 'PROPERTY_WEEK_COUNTER' ; 
	$arParams['DEFAULT_ELEMENT_SORT_ORDER'] = $arParams['DEFAULT_ELEMENT_SORT_ORDER'] ? $arParams['DEFAULT_ELEMENT_SORT_ORDER'] : 'DESC' ;
	$defPrice = CModule::IncludeModule("catalog") ? 'CATALOG_PRICE_1' : 'PROPERTY_PRICE_BASE';
	$arParams['LIST_PRICE_SORT'] = $arParams['LIST_PRICE_SORT'] ? htmlspecialchars($arParams['LIST_PRICE_SORT']) : $defPrice ;
}

if(strlen($arParams["FILTER_NAME"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
// auto stickers
$arParams['STICKER_NEW'] = $arParams['STICKER_NEW'] ? IntVal($arParams['STICKER_NEW']) : 14 ;
$arResult['STICKER_NEW_DELTA_TIME']  = 86400 * $arParams['STICKER_NEW'] ; // 86400 - 1 day in seconds
$arParams['STICKER_HIT'] = $arParams['STICKER_HIT'] ? IntVal($arParams['STICKER_HIT']) : 100 ;
$arParams['STICKER_BESTSELLER'] = $arParams['STICKER_BESTSELLER'] ? IntVal($arParams['STICKER_BESTSELLER']) : 3 ;

//META
if(CModule::IncludeModule("yenisite.meta"))
{
$arParams["SECTION_META_H1"] = $arParams["SECTION_META_H1"] ? $arParams["SECTION_META_H1"] : "#NAME#" ;
$arParams["SECTION_META_TITLE_PROP"] = $arParams["SECTION_META_TITLE_PROP"] ? $arParams["SECTION_META_TITLE_PROP"] : GetMessage("META_TITLE_PROP_BUY")."#NAME#" ;
$arParams["SECTION_META_KEYWORDS"] = $arParams["SECTION_META_KEYWORDS"] ? $arParams["SECTION_META_KEYWORDS"] : "#NAME#" ;
$arParams["SECTION_META_DESCRIPTION"] = $arParams["SECTION_META_DESCRIPTION"] ? $arParams["SECTION_META_DESCRIPTION"] : "#IBLOCK_NAME# #NAME#" ;
$arParams["DETAIL_META_H1"] = $arParams["DETAIL_META_H1"] ? $arParams["DETAIL_META_H1"] : "#NAME#" ;
$arParams["DETAIL_META_TITLE_PROP"] = $arParams["DETAIL_META_TITLE_PROP"] ? $arParams["DETAIL_META_TITLE_PROP"] : GetMessage("META_TITLE_PROP_BUY")."#NAME#" ;
$arParams["DETAIL_META_KEYWORDS"] = $arParams["DETAIL_META_KEYWORDS"] ? $arParams["DETAIL_META_KEYWORDS"] : "#NAME#, #PROPERTY_PRODUCER#" ;
$arParams["DETAIL_META_DESCRIPTION"] = $arParams["DETAIL_META_DESCRIPTION"] ? $arParams["DETAIL_META_DESCRIPTION"] : "#IBLOCK_NAME# #SECTION_NAME# #NAME#" ;
}
$arParams["DEFAULT_VIEW"] = in_array($arParams["DEFAULT_VIEW"], array("block", "list", "table")) ? $arParams["DEFAULT_VIEW"] : 'block' ;

global $FILTER_BY_QUANTITY;
$FILTER_BY_QUANTITY = $arParams['FILTER_BY_QUANTITY'];

$arParams['SECTION_SHOW_DESCRIPTION_DOWN'] = $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] ? $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] : 'N';

$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
if ($sef == "Y") {
	$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);
	if ($arch == 'multi') {
	
		$tmpAr = explode('/', $arResult["URL_TEMPLATES"]["section"]);
		$tmpAr = array_slice($tmpAr, 0, 3);
		$url = implode('/', $tmpAr);

		$arResult["IBLOCK_URL"] = $url.'/';
		$arResult["FOLDER"] = (!empty($arResult["IBLOCK_SECTION"])) ? $url.'/'.$arResult["IBLOCK_SECTION"] : $url;
	} else {
		//print_r($arResult);

		$tmpAr = explode('/', $arResult["URL_TEMPLATES"]["section"]);
		// $tmpAr2 = explode('?', $arResult['VARIABLES']['SECTION_CODE']);

		/*$secCode = '';
		if (count($tmpAr2) > 0) {
			$secCode = $tmpAr2[0];
		} else if (!empty($arResult['VARIABLES']['SECTION_CODE'])) {
			$secCode = $arResult['VARIABLES']['SECTION_CODE'];
		} else {*/
			$secCode = $tmpAr[2];
		// }

		$arResult["FOLDER"] = '/'.$tmpAr[1].'/'.$secCode;
	}

	if($arParams['FOLDER'] && $YS_AJAX_FILTER_ENABLE == "Y")
		$arResult["FOLDER"] = $arParams['FOLDER'];
}

$arAjaxParamsCode = array('IBLOCK_ID', 'ACTION_VARIABLE','PRODUCT_ID_VARIABLE', 'USE_PRODUCT_QUANTITY', 'QUANTITY_FLOAT', 'PRODUCT_QUANTITY_VARIABLE', 'PRODUCT_PROPERTIES', 'PRODUCT_PROPS_VARIABLE', 'OFFERS_CART_PROPERTIES');
if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && !in_array('OFFER_TREE_PROPS',$arAjaxParamsCode))
	$arAjaxParamsCode[] = 'OFFER_TREE_PROPS';
	
$arAjaxParams = array() ;
$curAjaxParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_{$arParams['IBLOCK_ID']}", '');
if ($curAjaxParams) {
	$curAjaxParams = unserialize($curAjaxParams) ;
}
$save_new_params = false;
foreach ($arAjaxParamsCode as $code) {
	if($curAjaxParams[$code] != $arParams[$code])
		$save_new_params = true;
	
	$arAjaxParams[$code] = $arParams[$code];
}
if (count($arAjaxParams) && $save_new_params) {
	$serAjaxParams = serialize($arAjaxParams);
	COption::SetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_{$arParams['IBLOCK_ID']}", $serAjaxParams);
}

$arSets = array();
$arSets['DETAIL_IMG_SMALL'] = $arParams['DETAIL_IMG_SMALL'];
$arSets['DETAIL_IMG_BIG'] = $arParams['DETAIL_IMG_BIG'];
$arSets['DETAIL_IMG_ICON'] = $arParams['DETAIL_IMG_ICON'];
$arParams['RESIZER_SETS'] = $arSets ;

$arADD2BParamsCode = array('IBLOCK_TYPE', 'IBLOCK_ID', 'RESIZER_SETS', 'PRICE_CODE', 'CACHE_TYPE', 'CACHE_TIME', 'CACHE_GROUPS', 'PRICE_VAT_INCLUDE', 'PRICE_VAT_SHOW_VALUE', 'CONVERT_CURRENCY', 'CURRENCY_ID','IBLOCK_MAX_VOTE','IBLOCK_VOTE_NAMES','DISPLAY_AS_RATING');
$arADD2BParams = array();
$curADD2BParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ADD2BParams_{$arParams['IBLOCK_ID']}", '');
if ($curADD2BParams) {
	$curADD2BParams = unserialize($curADD2BParams) ;
}
$save_new_params = false;
foreach ($arADD2BParamsCode as $code) {
	if($curADD2BParams[$code] != $arParams[$code])
		$save_new_params = true;
	
	$arADD2BParams[$code] = $arParams[$code];
}
if (count($arADD2BParams) && $save_new_params) {
	$serAjaxParams = serialize($arADD2BParams);
	COption::SetOptionString(CYSBitronicSettings::getModuleId(), "ADD2BParams_{$arParams['IBLOCK_ID']}", $serAjaxParams);
}

// fill offers properties is set PRODUCT_DISPLAY_MODE = SB
if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
{
	foreach($arParams['OFFER_TREE_PROPS'] as $tree_prop)
	{
		if(!in_array($tree_prop, $arParams['LIST_OFFERS_PROPERTY_CODE']))
			$arParams['LIST_OFFERS_PROPERTY_CODE'][] = $tree_prop;
		if(!in_array($tree_prop, $arParams['DETAIL_OFFERS_PROPERTY_CODE']))
			$arParams['DETAIL_OFFERS_PROPERTY_CODE'][] = $tree_prop;
	}
	$arParams['LIST_OFFERS_LIMIT'] = 0;
}
// hack for fill $arResult["ITEMS"][]["PROPERTIES"] in component bitrix:catalog.section
if(is_array($arParams['LIST_PROPERTY_CODE']))
	foreach($arParams['LIST_PROPERTY_CODE'] as $k => $v)
		if($v==="")
			unset($arParams["LIST_PROPERTY_CODE"][$k]);
if(empty($arParams["LIST_PROPERTY_CODE"])) $arParams["LIST_PROPERTY_CODE"] = array(true);

if($_REQUEST["clear_cache"] == "Y")
	CPHPCache::Clean(SITE_ID."_".$arParams["IBLOCK_ID"], "/ys_filter_ajax_params/");
if(/*$_REQUEST["clear_cache"] != "Y" &&*/ $_REQUEST["ys_filter_ajax"] !== "y")
{	//FOR FILTER AJAX
	$arParams['YS_SHOW_PROPERTIES'] = $arResult['YS_SHOW_PROPERTIES'];
	$save_param = new CPHPCache();
	if($save_param->InitCache(86400*14, SITE_ID."_".$arParams["IBLOCK_ID"], "/ys_filter_ajax_params/"))
		if($arParams != $save_param->GetVars())
			CPHPCache::Clean(SITE_ID."_".$arParams["IBLOCK_ID"], "/ys_filter_ajax_params/");
	if($save_param->StartDataCache()):
		$save_param->EndDataCache($arParams);
	endif;
	unset($save_param);
}
?>