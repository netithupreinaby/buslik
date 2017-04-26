<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$sef = COption::GetOptionString("yenisite.bitronicpro", "sef_mode");

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
foreach($arResult["ITEMS"] as &$item) {
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item) ;
    if($item["DETAIL_PICTURE"]["ID"])
    	$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];
	if(!CModule::IncludeModule('catalog')){
		CModule::IncludeModule('yenisite.market');
		$prices = CMarketPrice::GetItemPriceValues($item["ID"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}

	if($sef == "Y" && !isset($arResult["URL2MAIN"]))
	{
		$url = $item["BUY_URL"];
		$tmpAr = explode('/', $url);
		$tmpAr = array_slice($tmpAr, 0, 2);
		$url = implode('/', $tmpAr);
		$arResult["URL2MAIN"] = $url.'/';
		unset($tmpAr);
	}
}

// Page navigation
if($sef == "Y")
{	
	preg_match_all('/href="(.+)">/', $arResult["NAV_STRING"], $matches);

	foreach($matches[1] as $key => $match)
	{
		$matchTmp = preg_replace('/page-(\d+)(.+)PAGEN_1=(\d+)/', 'page-$3/', $match);

		$arResult["NAV_STRING"] = str_replace($match, $matchTmp, $arResult["NAV_STRING"]);
	}

	preg_match_all('/href="(.+)">/', $arResult["NAV_STRING"], $matches);

	foreach($matches[1] as $key => $match)
	{
		preg_match_all('/PAGEN_1=(\d+)/', $match, $pages);

		$tmpAr = explode('/', $match);
		$tmpAr = array_slice($tmpAr, 0, count($tmpAr) - 1);
		$tmpStr = implode("/", $tmpAr);
		if(!empty($pages[1][0]))
		{
			$tmpStr .= '/'.'page-'.$pages[1][0].'/';

			$arResult["NAV_STRING"] = str_replace($match, $tmpStr, $arResult["NAV_STRING"]);
		}
	}

	preg_match_all('/<a href="(.+)">(\d+)/', $arResult["NAV_STRING"], $matches);

	foreach($matches[1] as $key => $match)
	{
		$tmpAr = explode('/', $match);

		$tmpAr[count($tmpAr) - 2] = "page-".$matches[2][$key];
		$tmpStr = implode("/", $tmpAr);

		$arResult["NAV_STRING"] = str_replace($match, $tmpStr, $arResult["NAV_STRING"]);

	}
}

$arParams['STICKER_NEW'] = $arParams['STICKER_NEW'] ? IntVal($arParams['STICKER_NEW']) : 14 ;
$arParams['STICKER_NEW_DELTA_TIME']  = 86400 * $arParams['STICKER_NEW'] ; // 86400 - 1 day in seconds
$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'] ;
?>
