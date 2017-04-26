<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


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

$arElement = $arParams["ELEMENT"];
if(!is_array($arElement)) return;

//discount
$pr = 0;
$kr = 0;
$kk = 0; 
$disc = 0;
if (is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):
	foreach ($arElement["OFFERS"] as $arOffer):
		foreach ($arOffer[PRICES] as $k => $price) {
			if ($price[VALUE] < $pr || $pr == 0) {
				$pr = $price[VALUE];
				$kr = $k;
				$arElement[PRICES][$kr] = $arOffer[PRICES][$kr];
			}
		}
		$price = $arOffer[PRICES][$kr][VALUE];
		if ($arOffer[PRICES][$kr][DISCOUNT_VALUE]) {
			$disc = ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"]) * 100 / $arOffer["PRICES"][$kr]["VALUE"];
		}
	endforeach;
endif;

$params = array();
$params['STICKER_NEW'] = $arParams['STICKER_NEW'];
$params['STICKER_HIT'] = $arParams['STICKER_HIT'];
$params['STICKER_BESTSELLER'] = $arParams['STICKER_BESTSELLER'];

/*if($arElement["IBLOCK_ID"])
{
	if($arParams["CATALOG"] == "Y")
	{
		$save_param = new CPHPCache();
		if($save_param->InitCache(86400*14, SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/"))
			if($params != $save_param->GetVars())
				CPHPCache::Clean(SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/");
		if($save_param->StartDataCache()):
			$save_param->EndDataCache($params);
		endif;
		unset($save_param);
	}else{
		$save_param = new CPHPCache();
		if($save_param->InitCache(86400*14, SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/"))
		{
			$params = $save_param->GetVars();
		}
		unset($save_param);
		if(!is_array($params)) return;
	}
}*/

$params['STICKER_NEW'] = $params['STICKER_NEW'] ? IntVal($params['STICKER_NEW']) : 14 ;
$params['STICKER_NEW_DELTA_TIME']  = 86400 * $params['STICKER_NEW'] ; // 86400 - 1 day in seconds
$params['STICKER_NEW_START_TIME'] = time() - $params['STICKER_NEW_DELTA_TIME'] ;
$params['STICKER_HIT'] = $params['STICKER_HIT'] ? IntVal($params['STICKER_HIT']) : 100 ;
$params['STICKER_BESTSELLER'] = $params['STICKER_BESTSELLER'] ? IntVal($params['STICKER_BESTSELLER']) : 3 ;

$arResult = array();

if($arElement["PROPERTIES"]["NEW"]["VALUE"] || yenisite_date_to_time($arElement['DATE_CREATE']) > $params['STICKER_NEW_START_TIME'])
	$arResult["NEW"] = true;
if($arElement["PROPERTIES"]["HIT"]["VALUE"] || $arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] >= $params['STICKER_HIT'])
	$arResult["HIT"] = true;
if($arElement["PROPERTIES"]["SALE"]["VALUE"] || $disc > 0)
{
	$arResult["SALE"] = true;
	$arResult["SALE_DISC"] = $disc;
}
if($arElement["PROPERTIES"]["BESTSELLER"]["VALUE"] || $arElement["PROPERTIES"]["SALE_INT"]["VALUE"] >= $params['STICKER_BESTSELLER'])
	$arResult["BESTSELLER"] = true;
	
$this->IncludeComponentTemplate();


?>