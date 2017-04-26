<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(CModule::IncludeModule("yenisite.market"))
{
	$arResult['READY'] = 'Y' ;
	if(is_array($arResult['ITEMS']))
	{
		foreach($arResult['ITEMS'] as $k => &$arItem)
		{
			$arItem['CAN_BUY'] = 'Y';
			$arItem['DELAY'] = 'N' ;
			$arItem['QUANTITY'] = $arItem['COUNT'] ;
			$arItem['PRICE'] = $arItem['MIN_PRICE'];
			$arItem['KEY'] =  CMarketBasket::EncodeBasketItems($arItem["ID"], $arItem["PROPERTIES"]);
			$arItem['PRODUCT_ID'] = $arItem['ID'];
		}
	}
}
?>