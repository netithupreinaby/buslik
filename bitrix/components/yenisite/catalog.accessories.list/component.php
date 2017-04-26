<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(strlen($arParams["FILTER_NAME"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
$FILTER_NAME = $arParams["FILTER_NAME"];

global $$FILTER_NAME;
if(!isset($$FILTER_NAME))
	$$FILTER_NAME = array();
$arResult['ALL_ID_ARRAY_1'] = array () ;
$arResult['ALL_ID_ARRAY_2'] = array () ;

foreach($arParams['ID_LIST'] as $id)
{
	$obCache = new CPHPCache ;

	if( $obCache -> InitCache('36000', "yenisite_accessories_{$id}", '/yenisite_accessories/') )
	{
		$arCacheResult = $obCache -> GetVars() ;
		if (is_array($arCacheResult['ACCESSORIES_ID']))
		{
			foreach ($arCacheResult['ACCESSORIES_ID'] as $accessoires_id => $arAccessories)
			{
				if($accessoires_id && $arAccessories == 1 && !in_array($accessories_id, $arResult['ALL_ID_ARRAY_1']))
				{
					$key2 = array_search($accessoires_id, $arResult['ALL_ID_ARRAY_2']) ;
					if($key2) unset($arResult['ALL_ID_ARRAY_2'][$key2]) ;
					$arResult['ALL_ID_ARRAY_1'][] = $accessoires_id ; 
				}
				elseif($accessoires_id && $arAccessories == 2 && !in_array($accessories_id, $arResult['ALL_ID_ARRAY_1']) && !in_array($accessories_id, $arResult['ALL_ID_ARRAY_2']))
					$arResult['ALL_ID_ARRAY_2'][] = $accessoires_id ; 
			}
		}
	}

	unset($obCache) ;
}
if(count($arResult['ALL_ID_ARRAY_1']) || count($arResult['ALL_ID_ARRAY_2']))
	$$FILTER_NAME = array('ID' => array_merge($arResult['ALL_ID_ARRAY_1'], $arResult['ALL_ID_ARRAY_2'])) ;
?>
