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
foreach($arResult["ITEMS"] as &$item) {
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
		
}
?>
