<?
$arResult["LIST_PAGE_URL"] = yenisite_sectionUrl($arResult["IBLOCK_SECTION_ID"], $arResult["ID"]);

function yenisite_properties_sort($arProp1, $arProp2)
{
	if ($arProp1['SORT'] == $arProp2['SORT']) {
		return 0;
	}
	return ($arProp1['SORT'] < $arProp2['SORT']) ? -1 : 1;
}

if (count($arParams["SETTINGS_HIDE"]) > 1 || (array_key_exists('0', $arParams["SETTINGS_HIDE"]) && $arParams["SETTINGS_HIDE"][0] != '0')) {
	foreach ($arResult['DISPLAY_PROPERTIES'] as $k => &$v) {
		if (substr_count($v["CODE"], "CML2_") != 0
			|| in_array($v["CODE"], $arParams["SETTINGS_HIDE"], true)
			|| in_array($v["CODE"], $arParams["PRODUCT_PROPERTIES"], true)
		) {
			unset($arResult['DISPLAY_PROPERTIES'][$k]);
		}
	}
} else {
	$arHide = array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY', 'SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');

	if (!empty($arParams['PRODUCT_PROPERTIES'])) {
		foreach ($arParams['PRODUCT_PROPERTIES'] as $prop) {
			$arHide[] = $prop;
		}
	}

	foreach ($arResult['DISPLAY_PROPERTIES'] as $k => &$v) {

		if (substr_count($v["CODE"], "CML2_") != 0
			|| in_array($v["CODE"], $arHide, true)
		) {
			unset($arResult['DISPLAY_PROPERTIES'][$k]);
		}
	}
}

/*
 * Show property type list as a reference to the filter
 */

$smartFilterInstalled = false;
$bSefMode = false;
$bKombox = false;
$arTranslitParams = false;

if( $GLOBALS['ys_options']['smart_filter_type'] == 'KOMBOX' && CModule::IncludeModule('kombox.filter'))
{
	$bSefMode = CKomboxFilter::IsSefMode($arResult['LIST_PAGE_URL']);
	$file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/kombox/filter/class.php';
	if (file_exists($file)) {
		include $file;
		$smartFilter = new CKomboxCatalogFilter();
		$smartFilterInstalled = true;
		$arTranslitParams = array("replace_space"=>"_","replace_other"=>"_");
		$bKombox = true;	
	}
}

if(!$smartFilterInstalled)
{
	$file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/bitrix/catalog.smart.filter/class.php';
	if (file_exists($file)) {
		include $file;
		$smartFilterInstalled = true;
		$smartFilter = new CBitrixCatalogSmartFilter();
	}
}

$arSmartFilterParams = array();

foreach (CIBlockSectionPropertyLink::GetArray($arResult['IBLOCK_ID']) as $PID => $arLink) {
	if ($arLink["SMART_FILTER"] !== "Y") {
		continue;
	}

	$arSmartFilterParams[] = $PID;
}

if(!$arResult['CATALOG_AVAILABLE'] && $arParams['FILTER_BY_QUANTITY'] == 'Y')
	$f_Quantity = '&f_Quantity=Y';
foreach ($arResult['DISPLAY_PROPERTIES'] as $k => $arProperty) {
	$withSmartFilter = in_array($arProperty['ID'], $arSmartFilterParams) && $smartFilterInstalled;
	if ($arProperty['PROPERTY_TYPE'] == 'L') {
		if ($arProperty['MULTIPLE'] == 'Y') {
			if(!is_array($arProperty['DISPLAY_VALUE'])) {
				$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = $arProperty['DISPLAY_VALUE'] = array($arProperty['DISPLAY_VALUE']);
			}
			foreach ($arProperty['DISPLAY_VALUE'] as $ik => $iv) {
				if (!$withSmartFilter) {
					continue;
				}
				if ($withSmartFilter) {
					$smartFilter->fillItemValues($arProperty, $arProperty['VALUE_ENUM_ID'][$ik]);
					$curValue = array_pop($arProperty['VALUES']);
				}
				if($bSefMode && $withSmartFilter)
				{
					$arProperty["CODE_ALT"] = ToLower(CUtil::translit($arProperty["CODE"], "ru", $arTranslitParams));
					$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'][$ik] = '<a href="' . $arResult['LIST_PAGE_URL'] . 'filter/' . $smartFilter->getUrlParam($arProperty, array($curValue["CONTROL_NAME_ALT"])) . '">' . $arProperty['DISPLAY_VALUE'][$ik] . '</a>';
				}
				elseif($bKombox)
				{
					$arProperty["CODE_ALT"] = ToLower(CUtil::translit($arProperty["CODE"], "ru", $arTranslitParams));
					$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = '<a href="' . $arResult['LIST_PAGE_URL'] . '?set_filter=Y' .$f_Quantity. '&arrFilter_pf[' . $arProperty['CODE'] . '][]=' . $arProperty['VALUE_ENUM_ID'] . ($withSmartFilter ? '&' .  $arProperty["CODE_ALT"] . '=' . $curValue['CONTROL_NAME_ALT'] : '') . '">' . $arProperty['DISPLAY_VALUE'] . '</a>';
				}
				else
				{
					$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'][$ik] = '<a href="' . $arResult['LIST_PAGE_URL'] . '?set_filter=Y' .$f_Quantity. '&arrFilter_pf[' . $arProperty['CODE'] . '][]=' . $arProperty['VALUE_ENUM_ID'][$ik] . ($withSmartFilter ? '&arrFilter' . $curValue['CONTROL_NAME'] . '=Y' : '') . '">' . $arProperty['DISPLAY_VALUE'][$ik] . '</a>';
				}
			}
			if(is_array($arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE']))
				$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = join(' / ', $arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE']);
		} else {
			if ($withSmartFilter) {
				$smartFilter->fillItemValues($arProperty, $arProperty['VALUE_ENUM_ID']);
				$curValue = array_pop($arProperty['VALUES']);
			}
			if (!$withSmartFilter) {
				continue;
			}

			if($bSefMode && $withSmartFilter)
			{
				$arProperty["CODE_ALT"] = ToLower(CUtil::translit($arProperty["CODE"], "ru", $arTranslitParams));
				$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = '<a href="' . $arResult['LIST_PAGE_URL'] . 'filter/' . $smartFilter->getUrlParam($arProperty, array($curValue["CONTROL_NAME_ALT"])) . '">' . $arProperty['DISPLAY_VALUE'] . '</a>';
			}
			elseif($bKombox)
			{
				$arProperty["CODE_ALT"] = ToLower(CUtil::translit($arProperty["CODE"], "ru", $arTranslitParams));
				$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = '<a href="' . $arResult['LIST_PAGE_URL'] . '?set_filter=Y' .$f_Quantity. '&arrFilter_pf[' . $arProperty['CODE'] . '][]=' . $arProperty['VALUE_ENUM_ID'] . ($withSmartFilter ? '&' .  $arProperty["CODE_ALT"] . '=' . $curValue['CONTROL_NAME_ALT'] : '') . '">' . $arProperty['DISPLAY_VALUE'] . '</a>';
			}
			else
			{
				$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = '<a href="' . $arResult['LIST_PAGE_URL'] . '?set_filter=Y' .$f_Quantity. '&arrFilter_pf[' . $arProperty['CODE'] . '][]=' . $arProperty['VALUE_ENUM_ID'] . ($withSmartFilter ? '&arrFilter' . $curValue['CONTROL_NAME'] . '=Y' : '') . '">' . $arProperty['DISPLAY_VALUE'] . '</a>';
			}
		}
	}
	if ($arProperty['PROPERTY_TYPE'] == 'N' && !is_array($arProperty['DISPLAY_VALUE']))
		$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'] = (float)$arResult['DISPLAY_PROPERTIES'][$k]['DISPLAY_VALUE'];
}


/**
 *
 */
usort($arResult['DISPLAY_PROPERTIES'], 'yenisite_properties_sort');
reset($arResult['DISPLAY_PROPERTIES']);

$arAdditionalProps = array('HEIGHT', 'LENGTH', 'WIDTH' , 'WEIGHT');
foreach($arAdditionalProps as $additionalProp)
{
	if(intval($arResult['CATALOG_'.$additionalProp]) <= 0)
		continue;
	$key = array_key_exists('CATALOG_'.$additionalProp ,$arResult['DISPLAY_PROPERTIES']) ? md5('CATALOG_'.$additionalProp) : 'CATALOG_'.$additionalProp;
	
	array_unshift($arResult['DISPLAY_PROPERTIES'], array(
		'ID' => $key,
		'NAME' => GetMessage('CATALOG_'.$additionalProp),
		'PROPERTY_TYPE' => 'S',
		'DISPLAY_VALUE' => $arResult['CATALOG_'.$additionalProp]
	));
}
/*
	I don't know what it means!
	
while (list($key, $value) = each($a)) {
	echo "$key: $value";
}
*/
$arResult['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($arResult);
if ($arResult["DETAIL_PICTURE"]["ID"]) {
	array_unshift($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"], $arResult["DETAIL_PICTURE"]["ID"]);
}

if (!CModule::IncludeModule('catalog')) {
	CModule::IncludeModule('yenisite.market');
	$prices = CMarketPrice::GetItemPriceValues($arResult["ID"], $arResult['PRICES']);
	if(count($prices)>0)
		unset ($arResult["PRICES"]);
	foreach ($prices as $k => $pr) {
		$arResult["PRICES"][$k]["VALUE"] = $pr;
		$arResult["PRICES"][$k]["PRINT_VALUE"] = $pr . " <span class='rubl'>" . GetMessage('RUB') . "</span>";
	}
}
$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'];

if (is_array($arResult['PROPERTIES'][$arParams['PROPERTY_COMPLETE_SETS']]['VALUE'])) {
	$str_id = "&amp;id[0]={$arResult['ID']}"; // add [0] , modify by Ivan, 09.10.2013, for ajax add complete set to basket
	foreach ($arResult['PROPERTIES'][$arParams['PROPERTY_COMPLETE_SETS']]['~VALUE'] as $jsonValue) {
		$arValue = json_decode($jsonValue, true);
		if ($arValue['element_id']) {
			$arResult['bComplete'] = true;
		}
		if ($arValue['buy_checked'] == 1 && $arValue['iblock_id'] > 0) {
			$str_id .= "&amp;id[{$arValue['element_id']}]={$arValue['element_id']}"; // add ['element_id'] , modify by Ivan, 09.10.2013, for ajax add complete set to basket
		}
	}
	$arResult["ADD_URL"] = str_replace('ADD2BASKET&amp;', 'ADD2BASKET_SET', $arResult['ADD_URL']);
	$arResult["ADD_URL"] = str_replace($arParams['PRODUCT_ID_VARIABLE'] . '=' . $arResult['ID'], $str_id, $arResult['ADD_URL']);
}
// stores for offers
if ($arParams['USE_STORE'] == 'Y' && CModule::IncludeModule('catalog') && count($arResult['OFFERS'])) {
	$arParams['YS_STORES_MUCH_AMOUNT'] = $arParams['YS_STORES_MUCH_AMOUNT'] ? IntVal($arParams['YS_STORES_MUCH_AMOUNT']) : 15;
	$arParams['MIN_AMOUNT'] = $arParams['MIN_AMOUNT'] ? IntVal($arParams['MIN_AMOUNT']) : 7;
	foreach ($arResult['OFFERS'] as &$arOffer) {
		$arOffer['STORES'] = array();
		$arFilter = Array("PRODUCT_ID" => $arOffer['ID']);
		$arSelectFields = Array(
			"ID",
			"ACTIVE",
			"TITLE",
			"ADDRESS",
			"DESCRIPTION",
			"GPS_N",
			"GPS_S",
			"IMAGE_ID",
			"DATE_CREATE",
			"DATE_MODIFY",
			"USER_ID",
			"XML_ID",
			"PRODUCT_AMOUNT"
		);
		if ($arParams['USE_STORE_PHONE'] == 'Y') {
			$arSelectFields[] = 'PHONE';
		}
		if ($arParams['USE_STORE_SCHEDULE'] == 'Y') {
			$arSelectFields[] = 'SCHEDULE';
		}
		$dbStores = CCatalogStore::GetList(Array(), $arFilter, false, false, $arSelectFields);
		while ($arStore = $dbStores->GetNext()) {
			if ($arStore['PRODUCT_AMOUNT'] >= $arParams['YS_STORES_MUCH_AMOUNT']) {
				$store_amount_title = GetMessage('STORES_MUCH_AMOUNT');
				$store_amount_indicator = 3;
			} elseif ($arStore['PRODUCT_AMOUNT'] >= $arParams['MIN_AMOUNT']) {
				$store_amount_title = GetMessage('STORES_ENOUGH_AMOUNT');
				$store_amount_indicator = 2;
			} elseif ($arStore['PRODUCT_AMOUNT'] > 0) {
				$store_amount_title = GetMessage('STORES_LITTLE_AMOUNT');
				$store_amount_indicator = 1;
			} else {
				$store_amount_title = GetMessage('STORES_NO_AMOUNT');
				$store_amount_indicator = 0;
			}
			$arStore['YS_AMOUNT_TITLE'] = $store_amount_title;
			$arStore['YS_AMOUNT_INDICATOR'] = $store_amount_indicator;
			$arOffer['STORES'][] = $arStore;
		}


	}
	unset($arOffer);
}

$arParams['PROPERTY_COMPLETE_SET'] = $arParams['PROPERTY_COMPLETE_SETS'] ? $arParams['PROPERTY_COMPLETE_SETS'] : 'COMPLETE_SETS';
$arParams['COMPLETE_SETS_PROPERTIES'] = is_array($arParams['COMPLETE_SETS_PROPERTIES']) ? $arParams['COMPLETE_SETS_PROPERTIES'] : array();
$arParams['COMPLETE_SET_DESCRIPTION'] = $arParams['COMPLETE_SET_DESCRIPTION'] ? $arParams['COMPLETE_SET_DESCRIPTION'] : 'PREVIEW_TEXT';

$arParams['COMPLETE_SET_RESIZER_SET'] = $arParams['COMPLETE_SET_RESIZER_SET'] ? IntVal($arParams['COMPLETE_SET_RESIZER_SET']) : 5;


// FOR SKU as SeclectBox
$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

if ($arResult['MODULES']['catalog'])
{
	if (!$boolConvert)
		$strBaseCurrency = CCurrency::GetBaseCurrency();

	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);

	if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']))
	{
		$arSKUPropList = CIBlockPriceTools::getTreeProperties(
			$arSKU,
			$arParams['OFFER_TREE_PROPS'],
			array(
				'PICT' => $arEmptyPreview,
				'NAME' => '-'
			)
		);
		$arSKUPropIDs = array_keys($arSKUPropList);
	}
}

if ($arResult['MODULES']['catalog'])
{
	$arResult['CATALOG'] = true;
	if (!isset($arResult['CATALOG_TYPE']))
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
	if (
		(CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arResult['CATALOG_TYPE'])
		&& !empty($arResult['OFFERS'])
	)
	{
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
	}
	switch ($arResult['CATALOG_TYPE'])
	{
		case CCatalogProduct::TYPE_SET:
			$arResult['OFFERS'] = array();
			$arResult['CATALOG_MEASURE_RATIO'] = 1;
			$arResult['CATALOG_QUANTITY'] = 0;
			$arResult['CHECK_QUANTITY'] = false;
			break;
		case CCatalogProduct::TYPE_SKU:
			break;
		case CCatalogProduct::TYPE_PRODUCT:
		default:
			$arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
			break;
	}
}
else
{
	$arResult['CATALOG_TYPE'] = 0;
	$arResult['OFFERS'] = array();
}
$arResult['boolShowPicture'] = $arParams['PRODUCT_DISPLAY_MODE'] != 'SB'|| count($arSKUPropList)<=0 || count($arResult['OFFERS'])<=0 || (isset($_POST["ys_filter_ajax"]) && $_POST["ys_filter_ajax"] === "y");

if ($arResult['CATALOG'] && isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$boolSKUDisplayProps = false;

	$arResultSKUPropIDs = array();
	$arFilterProp = array();
	$arNeedValues = array();
	foreach ($arResult['OFFERS'] as &$arOffer)
	{
		foreach ($arSKUPropIDs as &$strOneCode)
		{
			if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
			{
				$arResultSKUPropIDs[$strOneCode] = true;
				if (!isset($arFilterProp[$strOneCode]))
					$arFilterProp[$strOneCode] = $arSKUPropList[$strOneCode];
			}
		}
		unset($strOneCode);
	}
	unset($arOffer);

	CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
	$arSKUPropIDs = array_keys($arSKUPropList);
	$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);


	$arMatrixFields = $arSKUPropKeys;
	$arMatrix = array();

	$arNewOffers = array();

	$arIDS = array();
	$arOfferSet = array();
	$arResult['OFFER_GROUP'] = false;
	$arResult['OFFERS_PROP'] = false;

	$arDouble = array();
	foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
	{
		$arOffer['ID'] = intval($arOffer['ID']);
		if (isset($arDouble[$arOffer['ID']]))
			continue;
		$arIDS[] = $arOffer['ID'];
		$boolSKUDisplayProperties = false;
		$arOffer['OFFER_GROUP'] = false;
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

		CIBlockPriceTools::setRatioMinPrice($arOffer);

		$arOffer['MORE_PHOTO'] = array();
		$arOffer['MORE_PHOTO_COUNT'] = 0;
		$offerSlider = CIBlockPriceTools::getSliderForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP'], 'Y' == $arParams['ADD_DETAIL_TO_SLIDER']);
		if (empty($offerSlider))
		{
			$offerSlider = $productSlider;
		}
		$arOffer['MORE_PHOTO'] = $offerSlider;
		$arOffer['MORE_PHOTO_COUNT'] = count($offerSlider);

		$boolSKUDisplayProps = CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']);

		$arDouble[$arOffer['ID']] = true;
		$arNewOffers[$keyOffer] = $arOffer;
	}
	$arResult['OFFERS'] = $arNewOffers;
	$arResult['SHOW_OFFERS_PROPS'] = $boolSKUDisplayProps;

	$arUsedFields = array();
	$arSortFields = array();

	foreach ($arSKUPropIDs as $propkey => $strOneCode)
	{
		$boolExist = $arMatrixFields[$strOneCode];
		foreach ($arMatrix as $keyOffer => $arRow)
		{
			if ($boolExist)
			{
				if (!isset($arResult['OFFERS'][$keyOffer]['TREE']))
					$arResult['OFFERS'][$keyOffer]['TREE'] = array();
				$arResult['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
				$arResult['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
				$arUsedFields[$strOneCode] = true;
				$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
			}
			else
			{
				unset($arMatrix[$keyOffer][$strOneCode]);
			}
		}
	}
	$arResult['OFFERS_PROP'] = $arUsedFields;
	$arResult['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

	\Bitrix\Main\Type\Collection::sortByColumn($arResult['OFFERS'], $arSortFields);

	if (!empty($arIDS) && CBXFeatures::IsFeatureEnabled('CatCompleteSet'))
	{
		$rsSets = CCatalogProductSet::getList(
			array(),
			array(
				'@OWNER_ID' => $arIDS,
				'=SET_ID' => 0,
				'=TYPE' => CCatalogProductSet::TYPE_GROUP
			),
			false,
			false,
			array('ID', 'OWNER_ID')
		);
		while ($arSet = $rsSets->Fetch())
		{
			$arOfferSet[$arSet['OWNER_ID']] = true;
			$arResult['OFFER_GROUP'] = true;
		}
	}

	$arMatrix = array();
	$intSelected = -1;
	$arResult['MIN_PRICE'] = false;
	foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
	{
		if (empty($arResult['MIN_PRICE']) && $arOffer['CAN_BUY'])
		{
			$intSelected = $keyOffer;
			$arResult['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
		}
		$arSKUProps = false;
		if (!empty($arOffer['DISPLAY_PROPERTIES']))
		{
			$boolSKUDisplayProps = true;
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
		if (isset($arOfferSet[$arOffer['ID']]))
		{
			$arOffer['OFFER_GROUP'] = true;
			$arResult['OFFERS'][$keyOffer]['OFFER_GROUP'] = true;
		}
		reset($arOffer['MORE_PHOTO']);
		$firstPhoto = current($arOffer['MORE_PHOTO']);
		$arOneRow = array(
			'ID' => $arOffer['ID'],
			'NAME' => $arOffer['~NAME'],
			'TREE' => $arOffer['TREE'],
			'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
			'DISPLAY_PROPERTIES' => $arSKUProps,
			'PREVIEW_PICTURE' => $firstPhoto,
			'DETAIL_PICTURE' => $firstPhoto,
			'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
			'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
			'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
			'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
			'OFFER_GROUP' => $arOffer['OFFER_GROUP'],
			'CAN_BUY' => $arOffer['CAN_BUY'],
			'SLIDER' => $arOffer['MORE_PHOTO'],
			'SLIDER_COUNT' => $arOffer['MORE_PHOTO_COUNT'],
			'BUY_URL' => $arOffer['~BUY_URL']
		);
		$arMatrix[$keyOffer] = $arOneRow;
	}
	if (-1 == $intSelected)
		$intSelected = 0;
	$arResult['JS_OFFERS'] = $arMatrix;
	$arResult['OFFERS_SELECTED'] = $intSelected;

	$arResult['OFFERS_IBLOCK'] = $arSKU['IBLOCK_ID'];
}

if (method_exists('CIBlockPriceTools', 'setRatioMinPrice') && $arResult['MODULES']['catalog'] && $arResult['CATALOG'] && CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'])
{
	CIBlockPriceTools::setRatioMinPrice($arResult, true);
}

$arResult['SKU_PROPS'] = $arSKUPropList;
$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

// GET PHOTO FOR SKU FROM PARENT ELEMENT
$parentId = (isset($arResult["PROPERTIES"]["CML2_LINK"]["VALUE"]) ? $arResult["PROPERTIES"]["CML2_LINK"]["VALUE"] : false);
//Check for Photo (product or SKU)
$isProductPhoto = ((!is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) || count($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) <= 0) && intval($arResult['DETAIL_PICTURE']) <= 0 && intval($arResult['PREVIEW_PICTURE']) <= 0) ? false : true;
if ($isProductPhoto)
{
	$arResult['BIG_PHOTO'] = CFile::GetPath(yenisite_GetPicSrc($arResult));
}
if(intval($parentId) > 0 && ( (!$isProductPhoto) || ($arParams['PARENT_PHOTO_SKU'] == 'Y')))
{
	$arTmpPhotoValue = array();
	if (($arParams['PARENT_PHOTO_SKU'] == 'Y') && $isProductPhoto)
	{
		$arTmpPhotoValue = $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'];
		if ($arResult['DETAIL_PICTURE'] > 0) $arTmpPhotoValue[] = $arResult['DETAIL_PICTURE']['ID'];
	}
	
	$arParent = CIBlockElement::GetByID($parentId)->GetNext();
	$dbParentPhoto = CIBlockElement::GetProperty($arParent['IBLOCK_ID'], $arParent['ID'], array(), array('CODE' => 'MORE_PHOTO'));
	while ($arPhoto = $dbParentPhoto->Fetch())
	{
		$arParent['PROPERTIES']['MORE_PHOTO']['VALUE'][] = $arPhoto['VALUE'];
	}
	$arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] = array();
	if(intval($arParent['DETAIL_PICTURE']) > 0)
	{
		$arResult['PROPERTIES']['MORE_PHOTO']['VALUE'][] = $arParent['DETAIL_PICTURE']['ID'];
	}
	if(count($arParent['PROPERTIES']['MORE_PHOTO']['VALUE']) > 0)
	{
		$arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] = array_merge($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'], $arParent['PROPERTIES']['MORE_PHOTO']['VALUE']);
	}
	
	if (!empty($arTmpPhotoValue))
	{
		foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $photo)
		{
			$arTmpPhotoValue[] = $photo;
		}
		$arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] = array();
		$arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] = $arTmpPhotoValue;
	}

	if (empty($arResult['BIG_PHOTO']))
	{
		$arResult['BIG_PHOTO'] = CFile::GetPath(yenisite_GetPicSrc($arResult));
	}
}
?>