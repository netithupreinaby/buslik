<?
$sef = COption::GetOptionString("yenisite.bitronicpro", "sef_mode");

foreach($arResult["ITEMS"] as &$item) {
	// AVAILABLE control
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item);
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

	if($sef == "Y")
	{
		$url = $item["DETAIL_PAGE_URL"];

		$tmpAr = explode('/', $url);

		$tmpAr = array_slice($tmpAr, 1, 2);

		$url = implode('/', $tmpAr);

		$item["DETAIL_PAGE_URL"] = '/'.$url.'/'.$item["CODE"].'.html';

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

$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'] ;
?>
