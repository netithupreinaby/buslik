<?php

namespace Yenisite\Geoipstore;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CatalogTools {
	static private $_cacheDir = '/romza/geoipstore/catalog';
	static private $_cacheTime = 604800;

	/**
	 * Method returns boolean availability or null to use original value
	 * @param array|int $product - product array or product ID
	 * @param array $arStores - list of store IDs
	 * @return bool|null
	 */
	public static function getAvailableStatus($product, $arStores)
	{
		if (is_array($product)) {
			if (isset($product['CATALOG_QUANTITY']) && isset($product['CATALOG_QUANTITY_TRACE']) && isset($product['CATALOG_CAN_BUY_ZERO'])) {
				if ($product['CATALOG_QUANTITY_TRACE'] === 'N') return null;
				if ($product['CATALOG_CAN_BUY_ZERO'] === 'Y') return null;
				if ($product['CATALOG_QUANTITY'] <= 0) return false;
				if (!empty($product['IBLOCK_ID'])) {
					$arProduct = array(
						'ELEMENT_IBLOCK_ID' => $product['IBLOCK_ID'],
						'CAN_BUY_ZERO' => $product['CATALOG_CAN_BUY_ZERO'],
						'QUANTITY' => $product['CATALOG_QUANTITY'],
						'QUANTITY_TRACE' => $product['CATALOG_QUANTITY_TRACE']
					);
				}
			}
			$productId = intval($product['ID']);
		} else {
			$productId = intval($product);
		}
		if (Loader::IncludeModule("catalog")) {
			$availStatus = false;
			$obCache = new \CPHPCache();
			$cacheId = $productId.serialize($arStores);
			if ($obCache->InitCache(self::$_cacheTime, $cacheId, self::$_cacheDir))
			{
				$availStatus = $obCache->GetVars();
				$availStatus = $availStatus['STATUS'];
			}
			else
			{
				if (empty($arProduct)) {
					$arProduct = \CCatalogProduct::GetList(array(), array('ID' => $productId), false, false, array('ELEMENT_IBLOCK_ID', 'CAN_BUY_ZERO', 'QUANTITY', 'QUANTITY_TRACE'))->Fetch();
				}

				if ($arProduct['QUANTITY_TRACE'] === 'N' || $arProduct['CAN_BUY_ZERO'] == 'Y')
				{
					$availStatus = true;
				}
				elseif ($arProduct['QUANTITY'] > 0)
				{
					$rsStore = \CCatalogStoreProduct::GetList(array(), array("PRODUCT_ID" => $productId, "@STORE_ID" => $arStores, ">AMOUNT" => 0), false, false, array('PRODUCT_ID', 'STORE_ID', 'AMOUNT'));
					while ($arStore = $rsStore->Fetch()) {

						if ($arStore['AMOUNT'] > 0) {
							$availStatus = true;
							break;
						}
					}

				}

				if ($obCache->StartDataCache()) {
					if(defined("BX_COMP_MANAGED_CACHE"))
					{
						global $CACHE_MANAGER;
						$CACHE_MANAGER->StartTagCache(self::$_cacheDir);
						$CACHE_MANAGER->RegisterTag("iblock_id_".$arProduct['ELEMENT_IBLOCK_ID']);
						$CACHE_MANAGER->EndTagCache();
					}

					$obCache->EndDataCache(array('STATUS' => $availStatus));
				}
			}

			return $availStatus;
		}
		else
		{
			return null;
		}
	}

	/**
	 * method returns:
	 * array $arReturn - amount product on stores
	 * null - need use current available quantity (not need to change)
	 * @param int $productId - product identifier
	 * @param array $arStores - list of store IDs
	 * @return array|null
	 */
	public static function getStoresAmount($productId, $arStores)
	{
		if (!Loader::IncludeModule("catalog")
		||  !is_array($arStores)
		||      empty($arStores)
		) {
			return null;
		}
		$arReturn = array();
		foreach($arStores as $storeId)
		{
			$arReturn[$storeId] = 0;
		}

		$rsStore = \CCatalogStoreProduct::GetList(array(), array("PRODUCT_ID" => $productId, "@STORE_ID" => $arStores), false, false, array('STORE_ID', 'AMOUNT'));
		while ($arStore = $rsStore->Fetch()) {
			$arReturn[$arStore['STORE_ID']] = $arStore['AMOUNT'];
		}

		return $arReturn;
	}
}