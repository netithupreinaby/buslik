<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('yenisite.resizer2');
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
foreach($arResult["ITEMS"] as &$item) {
	$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];
	$res = CIBlockSection::GetByID($item["IBLOCK_SECTION_ID"]);
	if($ar_res = $res->GetNext()){
		$item["IBLOCK_SECTION_NAME"] = $ar_res['NAME'];
		$item["IBLOCK_SECTION_PAGE_URL"] = $ar_res['SECTION_PAGE_URL'];
		}
	else
	{
		$ar_res = CIBlock::GetByID($item["IBLOCK_ID"])->GetNext();
		$item["IBLOCK_SECTION_NAME"] = $ar_res['NAME'];
		$item["IBLOCK_SECTION_PAGE_URL"] = $ar_res['LIST_PAGE_URL'];
	}
	if(!CModule::IncludeModule('catalog')){		
		CModule::IncludeModule('yenisite.market');
		$prices = CMarketPrice::GetItemPriceValues($item["ID"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}
}
// auto stickers
$arParams['STICKER_NEW'] = $arParams['STICKER_NEW'] ? IntVal($arParams['STICKER_NEW']) : 14 ;
$arParams['STICKER_NEW_DELTA_TIME']  = 86400 * $arParams['STICKER_NEW'] ; // 86400 - 1 day in seconds
$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'] ;
$arParams['STICKER_HIT'] = 3; //$arParams['STICKER_HIT'] ? IntVal($arParams['STICKER_HIT']) : 100 ;
$arParams['STICKER_BESTSELLER'] = $arParams['STICKER_BESTSELLER'] ? IntVal($arParams['STICKER_BESTSELLER']) : 3 ;

$arParams['IMAGE_SET'] = $arParams['IMAGE_SET'] ? IntVal($arParams['IMAGE_SET']) : 3;
$arParams['IMAGE_SET_BIG'] = $arParams['IMAGE_SET_BIG'] ? IntVal($arParams['IMAGE_SET_BIG']) : 4;

$arAjaxParamsCode = array('ACTION_VARIABLE','PRODUCT_ID_VARIABLE', 'USE_PRODUCT_QUANTITY', 'QUANTITY_FLOAT', 'PRODUCT_QUANTITY_VARIABLE', 'PRODUCT_PROPERTIES', 'PRODUCT_PROPS_VARIABLE', 'OFFERS_CART_PROPERTIES');
$arAjaxParams = array() ;
$curAjaxParams = COption::GetOptionString("yenisite.bitronicpro", "ajaxParams_main_page", '');
if($curAjaxParams)
{
	$curAjaxParams = unserialize($curAjaxParams) ;
}
$save_new_params = false;
foreach ($arAjaxParamsCode as $code)
{
	if($curAjaxParams[$code] != $arParams[$code])
		$save_new_params = true;
	
	$arAjaxParams[$code] = $arParams[$code];
}
if(count($arAjaxParams) && $save_new_params)
{
	$serAjaxParams = serialize($arAjaxParams);
	COption::SetOptionString("yenisite.bitronicpro", "ajaxParams_main_page", $serAjaxParams);
}

?>
