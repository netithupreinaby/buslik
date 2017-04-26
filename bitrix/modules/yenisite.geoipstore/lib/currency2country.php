<?php
namespace Yenisite\Geoipstore;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Currency2CountryTable
 *
 * Fields:
 * <ul>
 * <li> COUNTRY_ID char mandatory
 * <li> CURRENCY_ID char mandatory
 * </ul>
 *
 * @package Bitrix\Currency2CountryTable
 **/
class Currency2CountryTable extends Entity\DataManager {
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return 'ys_geoip_store_currency2country';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap() {
		return array(
			'COUNTRY_ID' => array(
				'data_type' => 'string',
				'primary' => true,
				'title' => Loc::getMessage('CATCHBUY2CAT_ENTITY_COUNTRY_ID_FIELD'),
			),
			'CURRENCY_ID' => array(
				'data_type' => 'string',
				'primary' => true,
				'title' => Loc::getMessage('CATCHBUY2CAT_ENTITY_CURRENCY_ID_FIELD'),
			),
		);
	}
}

class Currency2Country extends Currency2CountryTable {

	const CACHE_DIR = '/ys_geoip_store/currencies';

	public static function getCurrencyByCountry($countryId) {
		if (empty($countryId) || strlen($countryId) != 2) {
			return false;
		}
		$rs = self::GetList(array(
			'filter' => array('COUNTRY_ID' => $countryId)
		));
		if ($arLink = $rs->Fetch()) {
			return $arLink['CURRENCY_ID'];
		}
		return false;
	}
}