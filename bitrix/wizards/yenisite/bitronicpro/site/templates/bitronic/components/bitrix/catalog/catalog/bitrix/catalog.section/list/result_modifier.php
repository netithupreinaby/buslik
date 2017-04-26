<?
$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);

// FOR SKU as SelectBox
if (!empty($arResult['ITEMS']))
{
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
	if ($arResult['MODULES']['catalog'])
	{
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();
		
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
		if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']) && 'SB' == $arParams['PRODUCT_DISPLAY_MODE'])
		{
			$arSKUPropList = CIBlockPriceTools::getTreeProperties(
				$arSKU,
				$arParams['OFFER_TREE_PROPS'],
				array(
					'PICT' => $arEmptyPreview,
					//'NAME' => '-'
				)
			);
				$arNeedValues = array();
				
			CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
			$arSKUPropIDs = array_keys($arSKUPropList);
			
			if (empty($arSKUPropIDs))
				$arParams['PRODUCT_DISPLAY_MODE'] = 'N';
			else
				$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
		}
	}
	
	$arResult['SKU_PROPS'] = $arSKUPropList;
}


foreach($arResult["ITEMS"] as &$item) {
	// AVAILABLE control
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item);
    if($item["DETAIL_PICTURE"]["ID"])
    	$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];	
	
	if(!CModule::IncludeModule('catalog')){		
		CModule::IncludeModule('yenisite.market');
		$prices = CMarketPrice::GetItemPriceValues($item["ID"], $arResult['PRICES']);
		if(count($prices)>0)
			unset ($item["PRICES"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}

	if($sef == "Y") {
		preg_match('/SECTION_ID=(\d+)&/', $item["DETAIL_PAGE_URL"], $match);

		if (is_set($match[1])) {
			$item["DETAIL_PAGE_URL"] = yenisite_sectionUrl($match[1], $item["ID"]) . $item["CODE"] . '.html';
		} else {
			$arTmp = explode('/', $item["DETAIL_PAGE_URL"]);
			$arTmp = array_slice($arTmp, 0, 3);
			$str = implode('/', $arTmp);
			$str .= '/';
			$item["DETAIL_PAGE_URL"] = $str . $item["CODE"] . '.html';
		}
	}
	
	// FOR SKU as SelectBox
	if ($arResult['MODULES']['catalog'])
	{
		$item['CATALOG'] = true;
		if (!isset($item['CATALOG_TYPE']))
			$item['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
		if (
			(CCatalogProduct::TYPE_PRODUCT == $item['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $item['CATALOG_TYPE'])
			&& !empty($item['OFFERS'])
		)
		{
			$item['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
		}
		switch ($item['CATALOG_TYPE'])
		{
			case CCatalogProduct::TYPE_SET:
				$item['OFFERS'] = array();
				$item['CATALOG_MEASURE_RATIO'] = 1;
				$item['CATALOG_QUANTITY'] = 0;
				$item['CHECK_QUANTITY'] = false;
				break;
			case CCatalogProduct::TYPE_SKU:
				break;
			case CCatalogProduct::TYPE_PRODUCT:
			default:
				$item['CHECK_QUANTITY'] = ('Y' == $item['CATALOG_QUANTITY_TRACE'] && 'N' == $item['CATALOG_CAN_BUY_ZERO']);
				break;
		}
	}
	else
	{
		$item['CATALOG_TYPE'] = 0;
		$item['OFFERS'] = array();
	}
	// FOR SKU as SelectBox
	if (isset($item['OFFERS']) && !empty($item['OFFERS']))
	{
		if ('SB' == $arParams['PRODUCT_DISPLAY_MODE'])
		{
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$boolSKUDisplayProperties = false;
			$item['OFFERS_PROP'] = false;

			$arDouble = array();
			foreach ($item['OFFERS'] as $keyOffer => $arOffer)
			{
				$arOffer['ID'] = intval($arOffer['ID']);
				if (isset($arDouble[$arOffer['ID']]))
					continue;
				$arRow = array();
				foreach ($arSKUPropIDs as $propkey => $strOneCode)
				{
					$arCell = array(
						'VALUE' => 0,
						'SORT' => PHP_INT_MAX,
						'NA' => true
					);
					if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
					{
						$arMatrixFields[$strOneCode] = true;
						$arCell['NA'] = false;
						if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
						{
							$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
							$arCell['VALUE'] = $intValue;
						}
						elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
						}
						elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
						}
						$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
					}
					$arRow[$strOneCode] = $arCell;
				}
				$arMatrix[$keyOffer] = $arRow;
				
				// $offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP']);
				$offerPictures['PICT']['SRC'] = CFile::GetPath(yenisite_GetPicSrc($arOffer));
				$arOffer['OWNER_PICT'] = empty($offerPictures['PICT']['SRC']);
				$arOffer['PREVIEW_PICTURE'] = false;
				$arOffer['PREVIEW_PICTURE_SECOND'] = false;
				$arOffer['SECOND_PICT'] = true;
				if (!$arOffer['OWNER_PICT'])
				{
					if (empty($offerPictures['SECOND_PICT']))
						$offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
					$arOffer['PREVIEW_PICTURE'] = CResizer2Resize::ResizeGD2($offerPictures['PICT']['SRC'],$arParams['RESIZER_SETS']['LIST_IMG']);
				}
				$arDouble[$arOffer['ID']] = true;
				$arNewOffers[$keyOffer] = $arOffer;
			}
			
			$item['OFFERS'] = $arNewOffers;
			$arUsedFields = array();
			$arSortFields = array();
	
			foreach ($arSKUPropIDs as $propkey => $strOneCode)
			{
				$boolExist = $arMatrixFields[$strOneCode];
				foreach ($arMatrix as $keyOffer => $arRow)
				{
					if ($boolExist)
					{
						if (!isset($item['OFFERS'][$keyOffer]['TREE']))
							$item['OFFERS'][$keyOffer]['TREE'] = array();
						$item['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
						$item['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
						$arUsedFields[$strOneCode] = true;
						$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
					}
					else
					{
						unset($arMatrix[$keyOffer][$strOneCode]);
					}
				}
			}
			$item['OFFERS_PROP'] = $arUsedFields;
			$item['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

			\Bitrix\Main\Type\Collection::sortByColumn($item['OFFERS'], $arSortFields);

			$arMatrix = array();
			$intSelected = -1;
			$item['MIN_PRICE'] = false;
			foreach ($item['OFFERS'] as $keyOffer => $arOffer)
			{
				if (empty($item['MIN_PRICE']) && $arOffer['CAN_BUY'])
				{
					$intSelected = $keyOffer;
					$item['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
				}
				$arSKUProps = false;
				if (!empty($arOffer['DISPLAY_PROPERTIES']))
				{
					$boolSKUDisplayProperties = true;
					$arSKUProps = array();
					foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp)
					{
						if ('F' == $arOneProp['PROPERTY_TYPE'])
							continue;
						$arSKUProps[] = array(
							'NAME' => $arOneProp['NAME'],
							'VALUE' => $arOneProp['DISPLAY_VALUE']
						);
					}
					unset($arOneProp);
				}

				$arOneRow = array(
					'ID' => $arOffer['ID'],
					'NAME' => $arOffer['~NAME'],
					'TREE' => $arOffer['TREE'],
					'DISPLAY_PROPERTIES' => $arSKUProps,
					'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
					'SECOND_PICT' => $arOffer['SECOND_PICT'],
					'OWNER_PICT' => $arOffer['OWNER_PICT'],
					'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
					'PREVIEW_PICTURE_SECOND' => $arOffer['PREVIEW_PICTURE_SECOND'],
					'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
					'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
					'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
					'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
					'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
					'CAN_BUY' => $arOffer['CAN_BUY'],
					'BUY_URL' => $arOffer['~BUY_URL'],
					'ADD_URL' => $arOffer['~ADD_URL'],
				);
				$arMatrix[$keyOffer] = $arOneRow;
			}
			if (-1 == $intSelected)
				$intSelected = 0;
			if (!$arMatrix[$intSelected]['OWNER_PICT'])
			{
				$item['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
				$item['PREVIEW_PICTURE_SECOND'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SECOND'];
			}
			$item['JS_OFFERS'] = $arMatrix;
			$item['OFFERS_SELECTED'] = $intSelected;
			$item['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
		}
		else
		{
			$item['MIN_PRICE'] = CIBlockPriceTools::getMinPriceFromOffers(
				$item['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
			);
		}
	}
}

$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'] ;
?>