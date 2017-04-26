<?
use Bitrix\Main\Localization\Loc;

/**
* Yenisite GeoIP Store class
*
* @author Pavel Ivanov
* @Skype: hardyjazz
* @email: pavel@yenisite.ru
*/
class CYSGeoIPStore
{
	/**
	* Items table name
	*/
	const tblItems = 'ys_geoip_store_items';

	/**
	* Places table name
	*/
	const tblPlaces = 'ys_geoip_store_places';

	/**
	* Prices table name
	*/
	const tblPrices = 'ys_geoip_store_price_types';

	/**
	* Stores table name
	*/
	const tblStores = 'ys_geoip_store_stores';

	/**
	* Module ID
	*/
	const moduldeID = 'yenisite.geoipstore';

	/**
	* cacheID
	*/
	const cacheID = 'ys_geoip_store';

	/**
	* cacheTime
	*/
	const cacheTime = 2764800;

	/**
	* cacheDir
	*/
	const cacheDir = '/ys_geoip_store';

	public static $activeID;

	/**
	* OnEpilog event handler
	*/
	public static function OnEpilog()
	{
		if (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true) {
			return;
		}
		if ($GLOBALS['APPLICATION']->GetCurPage() != '/bitrix/admin/settings.php') {
			return;
		}

		CJSCore::RegisterExt(
			'ysgeoip',
			array(
				'js'   => '/bitrix/js/' . self::moduldeID . '/ajax.js',
				'css'  => '/bitrix/js/' . self::moduldeID . '/styles.css',
				'rel'  => array('jquery'),
			)
		);

		CJSCore::Init('ysgeoip');
	}

	public static function GetOptimalPrice($intProductID, $quantity = 1, $arUserGroups = array(), $renewal = "N", $arPricesNeed = array(), $siteID = false, $arDiscountCoupons = false) {

		global $APPLICATION;

		$intProductID = (int)$intProductID;
		if ($intProductID <= 0) {
			$APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_PRODUCT_ID_ABSENT"), "NO_PRODUCT_ID");
			return false;
		}

		$quantity = (float)$quantity;
		if ($quantity <= 0) {
			$APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_QUANTITY_ABSENT"), "NO_QUANTITY");
			return false;
		}

		if (!is_array($arUserGroups) && (int)$arUserGroups . '|' == (string)$arUserGroups . '|')
			$arUserGroups = array((int)$arUserGroups);

		if (!is_array($arUserGroups))
			$arUserGroups = array();

		if (!in_array(2, $arUserGroups))
			$arUserGroups[] = 2;

		$renewal = ($renewal == 'Y' ? 'Y' : 'N');

		if ($siteID === false)
			$siteID = SITE_ID;

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$resultCurrency = CCurrency::GetBaseCurrency();
		if (empty($resultCurrency)) {
			$APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_NO_BASE_CURRENCY"), "NO_BASE_CURRENCY");
			return false;
		}
		if (CCatalogProduct::getUsedCurrency() !== null)
			$resultCurrency = CCatalogProduct::getUsedCurrency();

		$intIBlockID = (int)CIBlockElement::GetIBlockByID($intProductID);
		if ($intIBlockID <= 0) {
			$APPLICATION->ThrowException(
				Loc::getMessage(
					'BT_MOD_CATALOG_PROD_ERR_ELEMENT_ID_NOT_FOUND',
					array('#ID#' => $intProductID)
				),
				'NO_ELEMENT'
			);
			return false;
		}

		if (!isset($arPricesNeed) || !is_array($arPricesNeed)) {
			$arPricesNeed = array('1');
		}

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$dbPriceList = CPrice::GetListEx(
			array(),
			array(
				"PRODUCT_ID" => $intProductID,
				"CATALOG_GROUP_ID" => $arPricesNeed,
				"GROUP_GROUP_ID" => $arUserGroups,
				"GROUP_BUY" => "Y",
				"+<=QUANTITY_FROM" => $quantity,
				"+>=QUANTITY_TO" => $quantity
			),
			false,
			false,
			array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY")
		);
		while ($arPriceList = $dbPriceList->Fetch()) {
			$arPriceList['ELEMENT_IBLOCK_ID'] = $intIBlockID;
			$arPrices[] = $arPriceList;
		}
		unset($arPriceList, $dbPriceList);

		if (empty($arPrices))
			return false;

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rsVAT = CCatalogProduct::GetVATInfo($intProductID);
		if ($arVAT = $rsVAT->Fetch())
			$arVAT['RATE'] = (float)$arVAT['RATE'] * 0.01;
		else
			$arVAT = array('RATE' => 0.0, 'VAT_INCLUDED' => 'N');

		if (CCatalogProduct::getUseDiscount()) {
			if ($arDiscountCoupons === false)
				$arDiscountCoupons = CCatalogDiscountCoupon::GetCoupons();
		}

		//		$boolDiscountVat = ('N' != COption::GetOptionString('catalog', 'discount_vat', 'Y'));
		$boolDiscountVat = true;

		$minPrice = false;
		$basePrice = false;
		$arMinPrice = array();
		$arMinDiscounts = array();

		foreach ($arPrices as &$arPriceList) {
			$arPriceList['VAT_RATE'] = $arVAT['RATE'];
			$arPriceList['VAT_INCLUDED'] = $arVAT['VAT_INCLUDED'];
			$arPriceList['ORIG_VAT_INCLUDED'] = $arPriceList['VAT_INCLUDED'];

			if ($boolDiscountVat) {
				if ('N' == $arPriceList['VAT_INCLUDED']) {
					$arPriceList['PRICE'] *= (1 + $arPriceList['VAT_RATE']);
					$arPriceList['VAT_INCLUDED'] = 'Y';
				}
			} else {
				if ('Y' == $arPriceList['VAT_INCLUDED']) {
					$arPriceList['PRICE'] /= (1 + $arPriceList['VAT_RATE']);
					$arPriceList['VAT_INCLUDED'] = 'N';
				}
			}

			if ($arPriceList["CURRENCY"] == $resultCurrency)
				$dblCurrentPrice = $arPriceList["PRICE"];
			else
				$dblCurrentPrice = CCurrencyRates::ConvertCurrency($arPriceList["PRICE"], $arPriceList["CURRENCY"], $resultCurrency);

			$arDiscounts = array();
			if (CCatalogProduct::getUseDiscount()) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$arDiscounts = CCatalogDiscount::GetDiscount($intProductID, $intIBlockID, $arPriceList["CATALOG_GROUP_ID"], $arUserGroups, $renewal, $siteID, $arDiscountCoupons);
			}

			$result = CCatalogDiscount::applyDiscountList($dblCurrentPrice, $resultCurrency, $arDiscounts);
			if ($result === false)
				return false;

			if ($minPrice === false || $minPrice > $result['PRICE']) {
				$basePrice = $dblCurrentPrice;
				$minPrice = $result['PRICE'];
				$arMinPrice = $arPriceList;
				$arMinDiscounts = $result['DISCOUNT_LIST'];
			}
		}
		unset($arPriceList);

		if ($boolDiscountVat) {
			if ('N' == $arMinPrice['ORIG_VAT_INCLUDED']) {
				$arMinPrice['PRICE'] /= (1 + $arMinPrice['VAT_RATE']);
				$arMinPrice['VAT_INCLUDED'] = $arMinPrice['ORIG_VAT_INCLUDED'];
			}
			if (!CCatalogProduct::getPriceVatIncludeMode()) {
				$minPrice /= (1 + $arMinPrice['VAT_RATE']);
				$basePrice /= (1 + $arMinPrice['VAT_RATE']);
			}
		} else {
			if ('Y' == $arMinPrice['ORIG_VAT_INCLUDED']) {
				$arMinPrice['PRICE'] *= (1 + $arMinPrice['VAT_RATE']);
				$arMinPrice['VAT_INCLUDED'] = $arMinPrice['ORIG_VAT_INCLUDED'];
			}
			if (CCatalogProduct::getPriceVatIncludeMode()) {
				$minPrice *= (1 + $arMinPrice['VAT_RATE']);
				$basePrice *= (1 + $arMinPrice['VAT_RATE']);
			}
		}
		unset($arMinPrice['ORIG_VAT_INCLUDED']);

		$arResult = array(
			'PRICE' => $arMinPrice,
			'RESULT_PRICE' => array(
				'BASE_PRICE' => $basePrice,
				'DISCOUNT_PRICE' => $minPrice,
				'DISCOUNT' => $basePrice - $minPrice,
				'PERCENT' => ($basePrice > 0 ? (100 * ($basePrice - $minPrice)) / $basePrice : 0),
				'CURRENCY' => $resultCurrency,
				'VAT_INCLUDED' => (CCatalogProduct::getPriceVatIncludeMode() ? 'Y' : 'N')
			),
			'DISCOUNT_PRICE' => $minPrice,
			'DISCOUNT' => array(),
			'DISCOUNT_LIST' => array()
		);
		if (!empty($arMinDiscounts)) {
			reset($arMinDiscounts);
			$arResult['DISCOUNT'] = current($arMinDiscounts);
			$arResult['DISCOUNT_LIST'] = $arMinDiscounts;
		}
		return $arResult;
	}

	/**
	* OnGetOptimalPriceResult handler
	*
	* @param &array &$arResult
	* @return array
	*/
	public static function OnGetOptimalPrice($intProductID, $quantity, $arUserGroups, $renewal, $arPrices, $siteID, $arDiscountCoupons) {
		static $arCache;
		$cacheID = md5(serialize(func_get_args()));
		$arPrice = true;
		if (!\Bitrix\Main\Loader::IncludeModule('catalog')) return NULL;
		if (!isset($arCache[$cacheID])) {
			$activeItem = self::GetActiveItemId();
			if (isset($activeItem)) {
				$arPrices = array();
				$rsPrices = self::GetList('price', array(), array('ITEM_ID' => $activeItem), array('PRICE_ID'));
				while ($arPrice = $rsPrices->Fetch()) {
					$arPrices[] = $arPrice['PRICE_ID'];
				}
				$arPrice = self::GetOptimalPrice($intProductID, $quantity, $arUserGroups, $renewal, $arPrices, $siteID, $arDiscountCoupons);
				$arCache[$cacheID] = $arPrice;
			}
		} else {
			$arPrice = $arCache[$cacheID];
		}
		return $arPrice;
	}

	/**
	 * OnBeforeUserAdd event handler
	 *
	 * @param &array &$arFields
	 */
	public static function OnBeforeUserAdd(&$arFields)
	{
		global $DB;

		$activeItemID = self::GetActiveItemId();
		if (empty($activeItemID)) return true;

		$arUserGroup = self::GetList('item', array(), array('ID' => $activeItemID), array('GROUP_ID'))->Fetch();
		if (empty($arUserGroup)) return true;

		if (!is_array($arFields['GROUP_ID'])) {
			$arFields['GROUP_ID'] = array();
		}
		if (!in_array($arUserGroup['GROUP_ID'], $arFields['GROUP_ID'])) {
			$arFields['GROUP_ID'][] = $arUserGroup['GROUP_ID'];
		}
	}

	/**
	 * Init scripts for public part
	 */
	public static function InitPublicScript()
	{
		CJSCore::RegisterExt(
			'ysgeopubl',
			array(
				'js'   => '/bitrix/js/' . self::moduldeID . '/core.js',
				'rel'  => array('jquery'),
			)
		);
		CJSCore::Init('ysgeopubl');
	}

	/**
	* Get Item by ID
	*
	* @param int $id
	* @return array
	*/
	public static function GetByID($id)
	{
		global $DB;

		$strSql = 'SELECT * FROM ' . self::tblItems . ' WHERE `ID` = ' . intval($id);
		$arRes = $DB->Query($strSql, false, $err_mess.__LINE__)->GetNext();
		return $arRes;
	}

	/**
	* Get Item by LOCATION_ID_DELIVERY
	*
	* @param int $id
	* @return CDBResult
	*/
	public static function GetByLocationStatID($id)
	{
		global $DB;

		$strSql = "SELECT * FROM " . self::tblItems . ' WHERE `ID` IN (SELECT ITEM_ID FROM ' . self::tblPlaces .
			' WHERE `LOCATION_ID_STATISTIC` = ' . intval($id) . ') AND `SITE_ID`="'.SITE_ID.'"';
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);

		return $dbRes;
	}

	/**
	* Get Entities list
	*
	* @param string $entity as 'item', 'place', 'price', 'store'
	* @param array $arOrder
	* @param array $arFilter
	* @param array $arSelect
	* @return CDBResult
	*/
	public static function GetList($entity = 'item', $arOrder = array(), $arFilter = array(), $arSelect = array(), $options = array('CDBResult' => 'Y'))
	{
		global $DB;
			if (!empty($arFilter)) {
				$strFilter = ' WHERE ';
				$i = 0;
				foreach ($arFilter as $key => $value) {
					$strFilter .= '`'.$DB->ForSql($key).'`="'.$DB->ForSql($value).'"';
					if ($i != count($arFilter) - 1) {
						$strFilter .= 'AND ';
					}
				}
			}

			$strSelect = (!empty($arSelect) && is_array($arSelect))
			           ? implode(', ', $arSelect)
			           : '*';

			switch ($entity) {
				case 'item':
				case 'place':
				case 'price':
				case 'store':
					$constName = 'tbl' . ucfirst($entity) . 's';
					$tableName = constant(__CLASS__ .'::'. $constName);
					$strSql = 'SELECT ' . $strSelect . ' FROM ' . $tableName . $strFilter;
					$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);
					return $dbRes;

				default:
					break;
			}
		return false;
	}

	/**
	* Add Item
	*
	* @param array $arFields
	*/
	public static function AddItem($arFields)
	{
		global $DB;

		$strSql = 'INSERT INTO ' . self::tblItems . '('.
			'`NAME`,
			`LOCATION_ID_DELIVERY`,
			`DOMAIN_NAME`,
			`GROUP_ID`,
			`SITE_ID`,
			`META_TITLE`,
			`META_KEYWORDS`,
			`META_DESCRIPTION`) VALUES("' .
				$DB->ForSql($arFields['NAME']) .'", ' .
				$arFields['LOCATION_ID_DELIVERY'] . ', "' .
				$DB->ForSql($arFields['DOMAIN_NAME']) . '", '.
			'"' . $arFields['GROUP_ID'] . '",'.
			'"' . $DB->ForSql($arFields['SITE_ID']) . '",' .
			'"' . $DB->ForSql($arFields['META_TITLE']) . '", ' .
			'"' . $DB->ForSql($arFields['META_KEYWORDS']) . '", '.
			'"' . $DB->ForSql($arFields['META_DESCRIPTION']) . '" ' . ')';

		$DB->Query($strSql, false, $err_mess.__LINE__);

		$id = intval($DB->LastID());

		foreach ($arFields['LOCATION_ID_STATISTIC'] as $locId) {
			$strSql = 'INSERT INTO ' . self::tblPlaces . '(`ITEM_ID`, `LOCATION_ID_STATISTIC`) VALUES(' . $id . ', ' . intval($locId) . ')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}

		foreach ($arFields['PRICE_ID'] as $price) {
			$strSql = 'INSERT INTO ' . self::tblPrices . '(`PRICE_ID`, `PRICE_CODE`, `ITEM_ID`) VALUES('.
				$price['ID'].', "'.$DB->ForSql($price['NAME']).'", '.$id.')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}

		foreach ($arFields['STORE_ID'] as $store) {
			$strSql = 'INSERT INTO ' . self::tblStores . '(`STORE_ID`, `ITEM_ID`) VALUES(' . $store . ', ' . $id . ')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}

		// Set default item if not exist
		$strSql = 'SELECT ID FROM ' . self::tblItems . ' WHERE `DEFAULT` IS NOT NULL';
		$arRes = $DB->Query($strSql, false, $err_mess.__LINE__)->Fetch();
		if (empty($arRes)) {
			$strSql = 'UPDATE ' . self::tblItems . ' SET `DEFAULT` = "Y" WHERE `ID`=' . $id;
			$DB->Query($strSql, false, $err_mess.__LINE__);

			// Set default store
			// Must be 1 query
			//
			//$strSql = 'UPDATE ' . self::tblStores . ' SET `DEFAULT` = "Y" WHERE `ITEM_ID` = ' . $id;
			//$DB->Query($strSql, false, $err_mess.__LINE__);
		}
		\Bitrix\Main\Data\Cache::clearCache(self::getCachePath());
	}

	/**
	* Update Item
	*
	* @param int $id
	* @param array $arFields
	*/
	public static function UpdateItem($id, $arFields)
	{
		global $DB;

		$strSql = 'UPDATE ' . self::tblItems . ' SET' .
			' `NAME`="' . $DB->ForSql($arFields['NAME']) .
			'", `LOCATION_ID_DELIVERY`=' . $arFields['LOCATION_ID_DELIVERY'] .
			', `DOMAIN_NAME`="' . $DB->ForSql($arFields['DOMAIN_NAME']) .
			'", `GROUP_ID`=' . $arFields['GROUP_ID'] .
			', `SITE_ID`="' . $DB->ForSql($arFields['SITE_ID']) .
			'", `META_TITLE`="' . $DB->ForSql($arFields['META_TITLE']) .
			'", `META_KEYWORDS`="' . $DB->ForSql($arFields['META_KEYWORDS']) .
			'", `META_DESCRIPTION`="' . $DB->ForSql($arFields['META_DESCRIPTION']) .
		'" WHERE `ID`=' . (int)$id;

		$DB->Query($strSql, false, $err_mess.__LINE__);

		// --- places ---
		$strSql = 'DELETE FROM ' . self::tblPlaces . ' WHERE `ITEM_ID` = ' . (int)$id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		foreach ($arFields['LOCATION_ID_STATISTIC'] as $locId) {
			$strSql = 'INSERT INTO ' . self::tblPlaces . '(`ITEM_ID`, `LOCATION_ID_STATISTIC`) VALUES(' . intval($id) . ', ' . intval($locId) . ')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}
		// ---

		// --- prices ---
		$strSql = 'DELETE FROM ' . self::tblPrices . ' WHERE `ITEM_ID` = ' . (int)$id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		foreach ($arFields['PRICE_ID'] as $price) {
			$strSql = 'INSERT INTO ' . self::tblPrices . '(`PRICE_ID`, `PRICE_CODE`, `ITEM_ID`) VALUES('.
				$price['ID'].', "'.$DB->ForSql($price['NAME']).'", '.intval($id).')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}
		// ---

		// --- stores ---
		$strSql = 'DELETE FROM ' . self::tblStores . ' WHERE `ITEM_ID` = ' . (int)$id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		foreach ($arFields['STORE_ID'] as $store) {
			$strSql = 'INSERT INTO ' . self::tblStores . '(`STORE_ID`, `ITEM_ID`) VALUES(' . (int)$store . ', ' . intval($id) . ')';
			$DB->Query($strSql, false, $err_mess.__LINE__);
		}
		// ---
		\Bitrix\Main\Data\Cache::clearCache(self::getCachePath());
	}

	/**
	* Remove Item
	*
	* @param int $id
	*/
	public static function RemoveItem($id)
	{
		global $DB;

		$strSql = 'DELETE FROM ' . self::tblItems . ' WHERE `ID` = ' . $id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = 'DELETE FROM ' . self::tblPlaces . ' WHERE `ITEM_ID` = ' . $id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = 'DELETE FROM ' . self::tblPrices . ' WHERE `ITEM_ID` = ' . $id;
		$DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = 'DELETE FROM ' . self::tblStores . ' WHERE `ITEM_ID` = ' . $id;
		$DB->Query($strSql, false, $err_mess.__LINE__);
		\Bitrix\Main\Data\Cache::clearCache(self::getCachePath());
	}

	/**
	* Set active item
	*
	* @param int $id
	*/
	public static function SetActiveItem($id, $domain = false)
	{
		$arSite = CSite::GetByID(SITE_ID)->Fetch();
		$domain = ($domain) ? '.'.$domain : $arSite['SERVER_NAME'];
		global $APPLICATION;
		$APPLICATION->set_cookie('GEO_IP_STORE_ACTIVE', $id, false, "/", $domain, false, false, 'YS');
		//setcookie('GEO_IP_STORE_ACTIVE', $id, time() + 3600, "/", $domain);
		//setcookie('YS_GEO_IP_STORE_ACTIVE', $id, time() + 3600, "/");
		$_COOKIE['YS_GEO_IP_STORE_ACTIVE'] = $id;
		self::$activeID = $id;
	}

	/**
	* Get active Item
	*
	* @return array
	*/
	public static function GetActiveItem()
	{
		//$id = $GLOBALS['APPLICATION']->get_cookie('GEO_IP_STORE_ACTIVE');
		if ((int)self::$activeID > 0) {
			$id = self::$activeID;
		} else {
			$id = $_COOKIE['YS_GEO_IP_STORE_ACTIVE'];
		}
		return self::GetByID($id);
	}

	/**
	* Get active Item
	*
	* @return int $id
	*/
	public static function GetActiveItemId($notDef = false) {
		if ((int)self::$activeID > 0) {
			$id = self::$activeID;
		} else {
			if (IntVal($_COOKIE['YS_GEO_IP_STORE_ACTIVE'])) {
				$id = $_COOKIE['YS_GEO_IP_STORE_ACTIVE'];
			} else if(!$notDef) {
				$arDef = self::GetDefaultItem()->Fetch();
				$id = $arDef['ID'];
				self::SetActiveItem($id);
			}
		}
		return $id;
	}

	/**
	* Set default Item
	*
	* @param int $id
	*/
	public static function SetDefaultItem($id)
	{
		global $DB;

		$arRes = self::GetDefaultItem()->Fetch();

		$strSql = 'UPDATE ' . self::tblItems . ' SET `DEFAULT` = "" WHERE `ID` = ' . intval($arRes['ID']);
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = 'UPDATE ' . self::tblItems . ' SET `DEFAULT` = "Y" WHERE `ID` = ' . intval($id);
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);
	}

	/**
	* Get default Item
	*
	* @return CDBResult
	*/
	public static function GetDefaultItem()
	{
		global $DB;

		$strSql = 'SELECT * FROM ' . self::tblItems . ' WHERE `DEFAULT` = "Y"';
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);

		return $dbRes;
	}

	/**
	 * Check HTTP_HOST to find out if we need to set new item
	 *
	 * @param bool $bOnlyNew (optional)
	 * false (default) - just return store item extracted by subdomain in HTTP_HOST;
	 * true - compare store item against active item and return false if equals.
	 *
	 * @return array|false - Array with new item fields or false if no change required
	 */
	public static function getItemFromHttpHost($bOnlyNew = false)
	{
		static $newItem;

		if (!isset($newItem)) {
			$chips = explode('.', $_SERVER['HTTP_HOST']);
			$rsItems = self::GetList();
			while ($arItem = $rsItems->Fetch()) {
				if ($arItem['DOMAIN_NAME'] !== $chips[0]) continue;
				if ($arItem['SITE_ID']     !== SITE_ID)   continue;
				if ($bOnlyNew && $arItem['ID'] == self::GetActiveItemId()) break;

				return $newItem = $arItem;
			}
			$newItem = false;
		}

		return $newItem;
	}

	/**
	* Set default store
	*
	* @param int $id - Item ID
	* @param int $storeId
	*/
	/*public static function SetDefaultStore($id, $storeId)
	{
		global $DB;

		$arRes = self::GetDefaultItem()->Fetch();

		$strSql = 'UPDATE ' . self::tblStores . ' SET `DEFAULT` = "" WHERE `ITEM_ID` = ' . intval($arRes['ID']) .
			' AND `DEFAULT` = "Y"';
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = 'UPDATE ' . self::tblStores . ' SET `DEFAULT` = "Y" WHERE `ITEM_ID` = ' . intval($id) .
			' AND `STORE_ID` = ' . intval($storeId);
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);
	}*/

	/**
	* Get default store from Item
	*
	* @param int $itemId
	* @return CDBResult
	*/
	/*public static function GetDefaultStore($itemId)
	{
		global $DB;

		$strSql = 'SELECT STORE_ID FROM ' . self::tblStores . ' WHERE `ITEM_ID` = ' . intval($itemId) . ' AND `DEFAULT` = "Y"';
		$dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);

		return $dbRes;
	}*/

	/**
	* Convert encoding from cp1251 to utf-8
	*
	* @param string $text
	* @return string
	*/
	public static function ConvertText($text)
	{
		if (defined('BX_UTF')) {
			return $text;
		}

		return $GLOBALS['APPLICATION']->ConvertCharset($text, 'UTF-8', 'WINDOWS-1251');
	}

	public static function getCachePath() {
		return '/ys_geoip_store/meta';
	}

	public static function setMetaTags() {
		global $APPLICATION;
		$storeID = self::GetActiveItemId();
		$ob = new \CPHPCache();
		$arMeta = array(
			'META_TITLE' => NULL,
			'META_KEYWORDS' => NULL,
			'META_DESCRIPTION' => NULL,
		);
		if ($ob->InitCache(1209600, $storeID, self::getCachePath())) {
			$arMeta = $ob->GetVars();
		} else if ($ob->StartDataCache()) {
			$arItem = self::GetByID($storeID);
			foreach($arItem as $key => $val) {
				if ($key{0} != '~' && strpos($key, 'META_') !== false) {
					$arMeta[$key] = $val;
				}
			}
			$ob->EndDataCache($arMeta);
		}
		if (!empty($arMeta['META_TITLE'])) {
			$APPLICATION->SetPageProperty('title', $arMeta['META_TITLE']);
		}
		if (!empty($arMeta['META_KEYWORDS'])) {
			$APPLICATION->SetPageProperty('keywords', $arMeta['META_KEYWORDS']);
		}
		if (!empty($arMeta['META_DESCRIPTION'])) {
			$APPLICATION->SetPageProperty('description', $arMeta['META_DESCRIPTION']);
		}

	}
	
	public static function GetByCurrentLocation($activeItem = array())
	{
		global $APPLICATION;
		$cookie = $_COOKIE['YS_GEO_IP_CITY'];

		if (empty($cookie) && !empty($activeItem['ID'])) return $activeItem;

		if (!defined('BX_UTF')) {
			$cookie = $APPLICATION->ConvertCharset($cookie, 'UTF-8', 'WINDOWS-1251');
		}

		$arLocs = explode('/', $cookie);
		$ar = CCity::GetList(array(), array('CITY_NAME' => $arLocs[2]))->Fetch();
		
		if(!($arItem = CYSGeoIPStore::GetByLocationStatID($ar['CITY_ID'])->Fetch()))
		{
			$arItem = self::GetDefaultItem()->Fetch();
		}
	
		return $arItem;
	}
	
	public static function GetCurrencyByCurrentLocation()
	{
		$strCurrency = NULL;

		if (CModule::IncludeModule('statistic')) {
			$obCity = new CCity;
			$countryId = $obCity->GetCountryCode();

			$obCache = new CPHPCache;
			if ($obCache->InitCache(PHP_INT_MAX, $countryId, \Yenisite\Geoipstore\Currency2Country::CACHE_DIR)) {
				$strCurrency = $obCache->GetVars();
			} else {
				$strCurrency = \Yenisite\Geoipstore\Currency2Country::getCurrencyByCountry($countryId);
				if (CModule::IncludeModule('currency') && !CCurrency::GetByID($strCurrency)) {
					$strCurrency = NULL;
				} else {
					if ($obCache->StartDataCache()) {
						$obCache->EndDataCache($strCurrency);
					}
				}
			}
		}
		if (empty($strCurrency) && CModule::IncludeModule('currency')) {
			$strCurrency = CCurrency::GetBaseCurrency();
		}
		return $strCurrency;
	}
	
	// function redirect on right domain if it needed
	public static function checkDomain($id)
	{		
		$isRedir = COption::GetOptionString("yenisite.geoipstore", 'is_redirect') == 'Y';
		
		if(!$isRedir) return;
		
		$activeItem = CYSGeoIPStore::GetByID($id);
		$rsSites = CSite::GetByID(SITE_ID);
		$arSite = $rsSites->Fetch();
	
		$arDomains = explode(chr(10),$arSite['DOMAINS']);
		foreach($arDomains as &$domain)
		{
			$domain = str_replace(chr(13),'',$domain);
		}
		$arServerName = (is_array($arDomains) && count($arDomains) > 1) ? $arDomains : array($arSite['SERVER_NAME']);
		
		if($activeItem['DEFAULT'] == 'Y')
		{
			$activeItem["DOMAIN_NAME"] = '';
		}
		
		self::RedirectDomain($activeItem["DOMAIN_NAME"], $arServerName);		
	}
	
	public static function RedirectDomain($domain, $arServerName)
	{
		global $APPLICATION;
		$curHost = $_SERVER['SERVER_NAME'];
		if(in_array($curHost, $arServerName) || in_array($curHost . ':' . $_SERVER['SERVER_PORT'], $arServerName))
		{
			if(empty($domain)) return;
			$host = $domain . '.' . $curHost . ':' . $_SERVER['SERVER_PORT'];
		}			
		else
		{
			$arTmp = explode('.', $curHost);
			if($arTmp[0] == $domain) return;
			if(empty($domain))
				unset($arTmp[0]);
			else
				$arTmp[0] = $domain;
			
			$host = implode('.',$arTmp). ':' . $_SERVER['SERVER_PORT'];
		}
		
		$protocol = (CMain::IsHTTPS() ? "https" : "http");
		$url = $protocol."://".$host.$APPLICATION->GetCurUri();
		
		global $GEOIP_IN_BUFFER;
		while ($GEOIP_IN_BUFFER){@ob_end_clean(); $GEOIP_IN_BUFFER--;}  //for right redirect on composite
		
		LocalRedirect($url, true);
	}
}
?>