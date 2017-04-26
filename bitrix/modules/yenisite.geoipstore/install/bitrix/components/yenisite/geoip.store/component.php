<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 3600;
}

// if we on subdomain - change active store ID
$nextItem = CYSGeoIPStore::getItemFromHttpHost();
if ($nextItem) {
	$id = $nextItem['ID'];

	if ($id != CYSGeoIPStore::GetActiveItemId()) {
		CYSGeoIPStore::SetActiveItem($id);
	}
}

if (CModule::IncludeModule("statistic") && CModule::IncludeModule('sale') && CModule::IncludeModule('yenisite.geoipstore') && CModule::IncludeModule("catalog")) {
	$activeItem = (!empty($nextItem)) ? $nextItem : array('ID' => CYSGeoIPStore::GetActiveItemId(true));
	$activeItem = (intval($activeItem['ID']) > 0 && $arParams['ONLY_GEOIP'] != 'Y') ? $activeItem : CYSGeoIPStore::GetByCurrentLocation($activeItem);

	CYSGeoIPStore::SetActiveItem($activeItem['ID']);
	CYSGeoIPStore::checkDomain($activeItem['ID']);
		
	$cacheId = (!empty($domain)) ? $domain : '';
	$cacheId .= '_'.$activeItem['ID'];
	if ($this->StartResultCache(false, $cacheId)) {
		// Build item list
		$dbRes = CYSGeoIPStore::GetList('item', array(), array('SITE_ID' => SITE_ID));
		while ($arRes = $dbRes->GetNext()) {
			$dbRs = CYSGeoIPStore::GetList('store', array(), array('ITEM_ID' => $arRes['ID']));
			while($ar = $dbRs->GetNext()) {
				$arStRes = CCatalogStore::GetList(array(), array('ID' => $ar['STORE_ID'],'SITE_ID' => SITE_ID));
				if(!$arSt = $arStRes->Fetch())
					continue;
				$tmpAr['STORE_ID'] = $ar['STORE_ID'];

				$tmpAr['TITLE'] = $arSt['TITLE'];
				$arRes['STORES'][] = $tmpAr;
			}
			if(empty($arRes['STORES']))
				continue;
			$arLocation = CSaleLocation::GetByID($arRes['LOCATION_ID_DELIVERY']);
			
			$arRes['CITY_DEL_NAME'] = $arLocation['CITY_NAME_LANG'];
			$arResult['ITEMS'][] = $arRes;
		}
		// ------

		// ------
		$activeItem = CYSGeoIPStore::GetByID($activeItem['ID']);
		$arResult['ACTIVE_ITEM_ID'] = $activeItem['ID'];
		$arLocation = CSaleLocation::GetByID($activeItem['LOCATION_ID_DELIVERY']);
		$arResult['CITY'] = $arLocation['CITY_NAME_LANG'];

		$this->IncludeComponentTemplate();
	}

	CYSGeoIPStore::InitPublicScript();

	// Get prices and stores for location
	if (empty($id)) $id = CYSGeoIPStore::GetActiveItemId();

	$arReturn = array(
		'PRICE_CODE' => NULL,
		'STORES' => NULL,
		'ITEM' => NULL,
		'INCLUDE_POSTFIX' => NULL,
		'CURRENCY_ID' => NULL
	);
	$obCache = new CPHPCache;
	if ($obCache->InitCache(2764800, 'ys_geoip_store_price_' . $id, '/ys_geoip_store')) {
		$arReturn = $obCache->GetVars();
	} elseif ($obCache->StartDataCache()) {
		if (empty($id)) {
			$arItem = CYSGeoIPStore::GetDefaultItem()->Fetch();
			$id = $arItem['ID'];
		}
		if (!empty($id)) {
			$dbRes = CYSGeoIPStore::GetList('price', array(), array('ITEM_ID' => $id));
			while ($ar = $dbRes->Fetch()) {
				$prices[] = $ar['PRICE_CODE'];
			}
			$dbRes = CYSGeoIPStore::GetList('store', array(), array('ITEM_ID' => $id));
			while ($ar = $dbRes->Fetch()) {
				$stores[] = $ar['STORE_ID'];
			}
		}
		$arReturn['PRICES'] = $prices;
		$arReturn['STORES'] = $stores;
		$arReturn['ITEM'] = CYSGeoIPStore::GetActiveItem();
		$postfix = $id;
		if('Y' == $arReturn['ITEM']['DEFAULT']) {
			$postfix = '';
		}
		$arReturn['INCLUDE_POSTFIX'] = $postfix;
		$obCache->EndDataCache($arReturn);
	}
	if ($arParams['DETERMINE_CURRENCY'] !== 'N') {
		$arReturn['CURRENCY_ID'] = CYSGeoIPStore::GetCurrencyByCurrentLocation();
		$_SESSION['GEOIP_CURRENCY_ID'] = $arReturn['CURRENCY_ID'];
	}
	return $arReturn;
}
?>