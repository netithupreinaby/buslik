<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// function use in catalog.element  and .section template. /!\ dublicate in yenisite.catalog.section.all /!\
if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date); 
		list($day, $month, $year) = explode(".", $date); 
		list($hour, $minute, $second) = explode(":", $time); 
		return mktime($hour, $minute, $second, $month, $day, $year); 
	}
}
// function use in catalog.section templates
if(!function_exists('yenisite_CATALOG_AVAILABLE'))
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
			if($arProduct['CATALOG_QUANTITY'] > 0)
				return true;
				
			foreach ($arProduct['OFFERS'] as $arOffer)
			{
				if(($arOffer['CATALOG_QUANTITY_TRACE'] == 'Y' && $arOffer['CATALOG_QUANTITY'] > 0)
					|| $arOffer['CATALOG_QUANTITY_TRACE'] != 'Y')
					return true;
			}
		}
		return false;
	}
}
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
	$system_properties = array('SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');

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

// get description
$obCache = new CPHPCache;
$arResult['IBLOCK_DESCRIPTION'] = false;
$arResult['IBLOCK_SECTION'] = $arResult["VARIABLES"]["SECTION_ID"] ? $arResult["VARIABLES"]["SECTION_ID"] : $arResult["VARIABLES"]["SECTION_CODE"] ;
if($arParams['SECTION_SHOW_DESCRIPTION'] == 'Y' && $arParams['IBLOCK_ID'])
{
	$cache_id = 'ys_seo_descriptions2_'.$arParams['IBLOCK_ID'].$arResult['IBLOCK_SECTION'] ;
	if($obCache->InitCache($arParams["CACHE_TIME"], $cache_id, "/"))
	{
		$vars = $obCache->GetVars();
		$arResult['SEO_DESCRIPTION'] = $vars['SEO_DESCRIPTION'];
		unset ($vars);
	}
	else
	{
		if($arResult['IBLOCK_SECTION'])
		{
			if($arResult["VARIABLES"]["SECTION_ID"])
				$arFilter['ID'] = $arResult["VARIABLES"]["SECTION_ID"] ;
			elseif($arResult["VARIABLES"]["SECTION_CODE"])
				$arFilter['CODE'] = $arResult["VARIABLES"]["SECTION_CODE"] ;
			$arSection = CIBlockSection::GetList(array(), $arFilter, false, Array('DESCRIPTION'))->GetNext();
			$arResult['SEO_DESCRIPTION'] = $arSection['DESCRIPTION'] ;
		}
		else
		{
			$arIBlock = CIBlock::GetByID($arParams['IBLOCK_ID']) -> GetNext();
			$arResult['SEO_DESCRIPTION'] = $arIBlock['DESCRIPTION'] ;
		}
	}
	if($obCache->StartDataCache())
		$obCache->EndDataCache(array(
			"SEO_DESCRIPTION"    => $arResult['SEO_DESCRIPTION'],
			)); 
	unset($obCache) ;
}

if(!$arParams['ELEMENT_ID']) // for section and sections.php
{
	$arParams['DEFAULT_ELEMENT_SORT_BY'] = $arParams['DEFAULT_ELEMENT_SORT_BY'] ? $arParams['DEFAULT_ELEMENT_SORT_BY'] : 'PROPERTY_WEEK_COUNTER' ; 
	$arParams['DEFAULT_ELEMENT_SORT_ORDER'] = $arParams['DEFAULT_ELEMENT_SORT_ORDER'] ? $arParams['DEFAULT_ELEMENT_SORT_ORDER'] : 'DESC' ;
	$arParams['LIST_PRICE_SORT'] = $arParams['LIST_PRICE_SORT'] ? htmlspecialchars($arParams['LIST_PRICE_SORT']) : 'CATALOG_PRICE_1' ;
}

if(strlen($arParams["FILTER_NAME"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
// auto stickers
$arParams['STICKER_NEW'] = $arParams['STICKER_NEW'] ? IntVal($arParams['STICKER_NEW']) : 14 ;
$arResult['STICKER_NEW_DELTA_TIME']  = 86400 * $arParams['STICKER_NEW'] ; // 86400 - 1 day in seconds
$arParams['STICKER_HIT'] = $arParams['STICKER_HIT'] ? IntVal($arParams['STICKER_HIT']) : 100 ;
$arParams['STICKER_BESTSELLER'] = $arParams['STICKER_BESTSELLER'] ? IntVal($arParams['STICKER_BESTSELLER']) : 3 ;

$arParams["SECTION_META_H1"] = $arParams["SECTION_META_H1"] ? $arParams["SECTION_META_H1"] : "#NAME#" ;
$arParams["SECTION_META_TITLE_PROP"] = $arParams["SECTION_META_TITLE_PROP"] ? $arParams["SECTION_META_TITLE_PROP"] : GetMessage("META_TITLE_PROP_BUY")."#NAME#" ;
$arParams["SECTION_META_KEYWORDS"] = $arParams["SECTION_META_KEYWORDS"] ? $arParams["SECTION_META_KEYWORDS"] : "#NAME#" ;
$arParams["SECTION_META_DESCRIPTION"] = $arParams["SECTION_META_DESCRIPTION"] ? $arParams["SECTION_META_DESCRIPTION"] : "#IBLOCK_NAME# #NAME#" ;
$arParams["DETAIL_META_H1"] = $arParams["DETAIL_META_H1"] ? $arParams["DETAIL_META_H1"] : "#NAME#" ;
$arParams["DETAIL_META_TITLE_PROP"] = $arParams["DETAIL_META_TITLE_PROP"] ? $arParams["DETAIL_META_TITLE_PROP"] : GetMessage("META_TITLE_PROP_BUY")."#NAME#" ;
$arParams["DETAIL_META_KEYWORDS"] = $arParams["DETAIL_META_KEYWORDS"] ? $arParams["DETAIL_META_KEYWORDS"] : "#NAME#, #PROPERTY_PRODUCER#" ;
$arParams["DETAIL_META_DESCRIPTION"] = $arParams["DETAIL_META_DESCRIPTION"] ? $arParams["DETAIL_META_DESCRIPTION"] : "#IBLOCK_NAME# #SECTION_NAME# #NAME#" ;

$arParams["DEFAULT_VIEW"] = in_array($arParams["DEFAULT_VIEW"], array("block", "list", "table")) ? $arParams["DEFAULT_VIEW"] : 'block' ;

global $ys_options;

$arParams['SHOW_ELEMENT'] = $ys_options['show_element'] == 'Y' ? 'Y' : 'N' ;

$arParams["SMART_FILTER"] = $ys_options['smart_filter'] == 'Y' ? 'Y' : 'N' ;

global $FILTER_BY_QUANTITY;
$FILTER_BY_QUANTITY = $arParams['FILTER_BY_QUANTITY'];

$arParams['SECTION_SHOW_DESCRIPTION_DOWN'] = $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] ? $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] : 'N';

if ($ys_options['sef'] == "Y") {
	$tmpAr = explode('/', $arResult["URL_TEMPLATES"]["section"]);
	$tmpAr = array_slice($tmpAr, 0, 3);
	$url = implode('/', $tmpAr);

	$arResult["IBLOCK_URL"] = $url.'/';

	$arResult["FOLDER"] = (!empty($arResult["IBLOCK_SECTION"])) ? $url.'/'.$arResult["IBLOCK_SECTION"] : $url;

	/*if(empty($_SESSION[$arParams["COMPARE_NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]))
	{
		$_SESSION[$arParams["COMPARE_NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]['tmp'] = array('tmp' => 'tmp');
	}*/
}

$arAjaxParamsCode = array('IBLOCK_ID', 'ACTION_VARIABLE','PRODUCT_ID_VARIABLE', 'USE_PRODUCT_QUANTITY', 'QUANTITY_FLOAT', 'PRODUCT_QUANTITY_VARIABLE', 'PRODUCT_PROPERTIES', 'PRODUCT_PROPS_VARIABLE', 'OFFERS_CART_PROPERTIES');
$arAjaxParams = array() ;
$curAjaxParams = COption::GetOptionString("yenisite.bitronicpro", "ajaxParams_{$arParams['IBLOCK_ID']}", '');
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
	COption::SetOptionString("yenisite.bitronicpro", "ajaxParams_{$arParams['IBLOCK_ID']}", $serAjaxParams);
}

$arSets = array();
$arSets['DETAIL_IMG_SMALL'] = $arParams['DETAIL_IMG_SMALL'];
$arSets['DETAIL_IMG_BIG'] = $arParams['DETAIL_IMG_BIG'];
$arSets['DETAIL_IMG_ICON'] = $arParams['DETAIL_IMG_ICON'];
$arParams['RESIZER_SETS'] = $arSets ;

$arADD2BParamsCode = array('IBLOCK_TYPE', 'IBLOCK_ID', 'RESIZER_SETS', 'PRICE_CODE', 'CACHE_TYPE', 'CACHE_TIME', 'CACHE_GROUPS', 'PRICE_VAT_INCLUDE', 'PRICE_VAT_SHOW_VALUE', 'CONVERT_CURRENCY', 'CURRENCY_ID','IBLOCK_MAX_VOTE','IBLOCK_VOTE_NAMES','DISPLAY_AS_RATING');
$arADD2BParams = array();
$curADD2BParams = COption::GetOptionString("yenisite.bitronicpro", "ADD2BParams_{$arParams['IBLOCK_ID']}", '');
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
	COption::SetOptionString("yenisite.bitronicpro", "ADD2BParams_{$arParams['IBLOCK_ID']}", $serAjaxParams);
}

?>