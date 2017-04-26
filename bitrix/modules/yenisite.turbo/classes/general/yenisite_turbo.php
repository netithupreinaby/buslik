<?
use Bitrix\Main\Loader;
/* -------------------------  */
//Developer: Denis Zimin
//SKYPE: dioonisss
//E-MAIL:   denis@yenisite.ru
//MODULE:   TURBINA
/* -------------------------  */
Class CTurbine extends Yenisite\CoreParser\YandexParser
{
	const moduldeID = 'yenisite.turbo';
	protected static $cache = array();

	static function setRegion($region_id, $c_file)
	{
		$fn = fopen($c_file, "w");
		fwrite($fn, ".yandex.ru TRUE  /  FALSE 0  yandex_gid  {$region_id}");
		fclose($fn);
		return $html;
	}

	static function setElementPrices($id, $prop_code, $set_id, $c_file, $enc_from = "utf-8", $enc_to = "windows-1251")
	{
		global $APPLICATION;
		self::setLogFile($id);

		Loader::includeModule('iblock');
		$iblock_id = CIBlockElement::GetIBlockByID($id);
		$arElement = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $iblock_id, "ID" => $id), false, false, array("PROPERTY_{$prop_code}","NAME"))->GetNext();

		$yandexId = $arElement["PROPERTY_{$prop_code}_VALUE"];
		$YANDEX_LINK = !empty($yandexId); //if property YANDEX_LINK is full

		$name = $arElement["NAME"];
		$country = COption::GetOptionString(self::moduldeID, 'turbo_country', 'ru');

		if($set_id == 0)
		{
			$arSet['DELIVERY'] = 'Y';
			$arSet['REGION'] = 62;  //Krasnoyarsk
			if($country == 'ua' )
				$arSet['REGION'] = 187; //Ukraine
		}
		else
			$arSet = CTurbineSet::GetByID($set_id);
		
		self::setRegion($arSet['REGION'], $c_file);

		$arShops = array();

		while (empty($yandexId))
		{
			$arExeptionWords = COption::GetOptionString(self::moduldeID, 'exception_words', '');
			$arExeptionWords = explode(chr(10),$arExeptionWords);
			$name_for_search = $name;
			foreach($arExeptionWords as $word)
			{
				$word = trim($word);
				$name_for_search = str_replace($word, '', $name_for_search);
			}
			if (!defined('BX_UTF')) {
				$name = $APPLICATION->ConvertCharset($name, LANG_CHARSET, 'utf-8');
				$name_for_search = $APPLICATION->ConvertCharset($name_for_search, LANG_CHARSET, 'utf-8');
			}
			$link = self::getSearchLink($name_for_search, $country);
			if($arSet['DELIVERY'] == 'Y') {
				$link .= "&deliveryincluded=1";
			}

			$arModels = self::getModelListByURL($link);
			if (self::$errCode != self::ERROR_NONE) {
				return $arModels;
			}
			if ($arModels['length'] == 1) {
				//we've found exactly one card for given name
				$yandexId = $arModels[0]['ID'];
				CIBlockElement::SetPropertyValueCode($id, $prop_code, $yandexId);
				break;
			}
			if ($arModels['length'] > 1) {
				//we've found more than one card for given name
				for ($i = 0; $i < $arModels['length']; $i++) {
					if (stripos($arModels[$i]['NAME'], $name) === false) {
						//remove elements which does not contain original name
						array_splice($arModels, $i--, 1);
						$arModels['length']--;
						$arModels['length2']--;
					}
				}
				if ($arModels['length'] > 0) {
					$yandexId = $arModels[0]['ID'];
					break;
				}
			}
			if ($arModels['length2'] > $arModels['length']) {
				self::writeLog(19, $arModels['length2'] - $arModels['length']);
				// search through offers without detail cards
				for ($i = $arModels['length']; $i < $arModels['length2']; $i++) {
					$arOffer = $arModels[$i];
					if (stripos($arOffer['NAME'], $name) === false) continue;

					$price = $arOffer['PRICE'];
					$shop = $arOffer['SHOP'];
					if (isset($arShops[$shop]) && ($arShops[$shop] < $price)) continue;

					$arShops[$shop] = $price;
				}
			}
			break;
		}

		if ($yandexId) {
			self::writeLog(11, $yandexId);
			$brands = (COption::GetOptionString(static::moduldeID, 'brands', 'N') == 'Y');
			if ($brands) {
				$link = "https://market.yandex.{$country}/product/{$yandexId}";
				$offerSelector = '.product-top-offer';
				$priceSelector = '.product-top-offer__item_type_price .price_discount_yes';
				$shopSelector = '.product-top-offer__item_type_shop .link_theme_outer';
			} else {
				$link = "https://market.yandex.{$country}/product/{$yandexId}/offers?grhow=shop&how=aprice&np=1&hideduplicate=0";
				$offerSelector = '.snippet-card';
				$priceSelector = '.snippet-card__price';
				$shopSelector = '.link_type_shop-name';
			}
			if($arSet['DELIVERY'] == 'Y') {
				$link .= ($brands ? '?' : '&') . "deliveryincluded=1";
			}
			$content = self::browser($link, false, false, 0, $c_file);
			if (self::$errCode != self::ERROR_NONE) {
				return array('error' => self::$errCode);
			}
			$crawler = new \Symfony\Component\DomCrawler\Crawler();
			$crawler->addContent($content);
			$obOffers = $crawler->filter($offerSelector);

			self::writeLog(19, count($obOffers));

			foreach ($obOffers as $domElement) {
				$offerCrawler = new \Symfony\Component\DomCrawler\Crawler($domElement);
				$shop = trim($offerCrawler->filter($shopSelector)->text());
				$price = $offerCrawler->filter($priceSelector);
				if (count($price)) {
					$price = $price->text();
				} else {
					$price = $offerCrawler->filter('.product-top-offer__item_type_price')->text();
				}
				$price = self::parsePrice($price);
				if (!isset($arShops[$shop]) || $arShops[$shop] > $price) {
					$arShops[$shop] = $price;
				}
			}
		}
		$sn = COption::GetOptionString(self::moduldeID, 'turbo_shop_name', '');
		$count = 0;

		foreach ($arShops as $shop => $price) {
		
			if(!$shop) continue;
			if(strpos($shop, '.seogadpxy.ru') !== false) continue;
			if (!defined('BX_UTF')) {
				$shop = $APPLICATION->ConvertCharset($shop, 'utf-8', LANG_CHARSET);
			}
			if($sn && $sn == $shop) continue;

			$shopCode = "TURBO_SHOP_YANDEX_" . md5($shop);
			$res = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $shopCode))->GetNext();
			if(!$res){
				$arFields = Array(
				  "NAME" => $shop,
				  "ACTIVE" => "Y",
				  "SORT" => "111111",
				  "PROPERTY_TYPE" => "S",
				  "IBLOCK_ID" => $iblock_id,
				  "CODE" => $shopCode
				);
				$ibp = new CIBlockProperty;
				$PropID = $ibp->Add($arFields);
			}
			if($price) {
				CIBlockElement::SetPropertyValueCode($id, $shopCode, $price);
				$count++;
			}
		}
		self::writeLog(20, $count);
		return array('status' => 'ok');
	}

	static function getYandexLink($name,$id,$c_file)
	{
		self::setLogFile($id);

		$country = COption::GetOptionString(self::moduldeID, 'turbo_country', 'ru');
		$arReturn = array();
		$arExeptionWords = COption::GetOptionString(self::moduldeID, 'exception_words', '');
		$arExeptionWords = explode(chr(10),$arExeptionWords);
		$name_for_search = $name;
		foreach($arExeptionWords as $word)
		{
			$word = trim($word);
			$name_for_search = str_replace($word, '', $name_for_search);
		}
		$link = self::getSearchLink($name_for_search);
		return self::getModelListByURL($link);
	}

	/**
	 * Make arFilter for CIBlockElement::GetList
	 *
	 * Method creates and returns $arFilter which can be used
	 * to fetch all iblock elements linked to given set.
	 *
	 * @param array|int $set - arSet or setId
	 * @return array $arFilter
	 */
	static function getElementFilterForSet($set)
	{
		$arFilter = array();
		if (!Loader::includeModule('iblock')) return $arFilter;

		// process incoming params
		if (is_numeric($set)) {
			$set = intval($set);
			if (!isset(self::$cache['set_'.$set])) {
				self::$cache['set_'.$set] = CTurbineSet::GetByID($set);
			}
			$set = self::$cache['set_'.$set];
		}
		if (is_array($set)) {
			$arSet = $set;
		} else {
			return $arFilter;
		}

		// filter custom property
		if ($arSet['SELECT_PROP'] > 0) {
			$arProp = CIBlockProperty::GetByID($arSet['SELECT_PROP'])->GetNext();
			if ($arProp) {
				$arFilter['!PROPERTY_'.$arProp['CODE']] = false;
			}
		}
		// filter iblock
		if ($arSet['IBLOCK_ID'] > 0) {
			$arFilter['IBLOCK_ID'] = $arSet['IBLOCK_ID'];
		}
		// filter section(s)
		if (strlen($arSet['SECTION_ID']) > 0) {
			$arFilter['SECTION_GLOBAL_ACTIVE'] = 'Y';
			$arFilter['SECTION_ID'] = unserialize(base64_decode(trim($arSet['SECTION_ID'])));
			if($arSet['SUBSECT'] == 'Y') {
				$arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
			}
		}
		// filter active & available states
		switch ($arSet['ELEM_STATUS']) {
			case 3: $arFilter['ACTIVE'] = 'Y';
			case 2: $arFilter['CATALOG_AVAILABLE'] = 'Y';
			case 0: break;
			case 1:
			default: $arFilter['ACTIVE'] = 'Y';
		}
		return $arFilter;
	}

	/**
	 * Agent updates prices for all set elements
	 *
	 * @param int $setId
	 * @param bool $bFirstRun - pass true if you want to create new agent
	 * @return string
	 */
	static function updateSetAgent($setId, $bFirstRun = false)
	{
		$setId     = intval($setId);
		$agentName = "CTurbine::updateSetAgent({$setId});";
		if (!Loader::includeModule('iblock')) return $agentName;

		$arFilter = self::getElementFilterForSet($setId);
		$total    = CIBlockElement::GetList(array("ID" => "asc"), $arFilter, array());
		if ($total < 1) return $agentName;

		$interval = 604800 / $total;

		if ($bFirstRun) {
			CAgent::AddAgent($agentName, self::moduldeID, $period = 'N', $interval);
			return;
		}
		$arElem = CIBlockElement::GetList(array("timestamp_x" => "asc"), $arFilter, false, array('nTopCount' => 1), array("ID"))->Fetch();
		if (!$arElem) return $agentName;

		$arResult = self::setElementPrices($arElem['ID'], 'TURBO_YANDEX_LINK', $setId, $_SERVER['DOCUMENT_ROOT']."/yandex_cook.txt");
		if ($arResult['status'] === 'ok') {
			// update timestamp_x
			$obElem = new CIBlockElement();
			$obElem->Update($arElem['ID'], array());
			// save parsed price
			self::saveRentPrice($arElem['ID'], self::$cache['set_'.$setId]);
		} elseif ($interval > 600) {
			//repeat on failure after 10 minutes
			$interval = 600;
		}
		// set next launch time
		$GLOBALS['pPERIOD'] = $interval;

		return $agentName;
	}

	/**
	 * This method has been copy|pasted from include_turbo_report.php
	 *
	 * Shit as it seen itself. No time to rewrite. Sorry.
	 *
	 * @param int $productId
	 * @param array $arSet
	 */
	static function saveRentPrice($productId, $arSet)
	{
		if (!Loader::includeModule('iblock')) return;
		if (!Loader::includeModule('catalog')) return;

		$codes = array();
		$arBadStatus = array('red','empty');
		$arGoodStatus = array('green','cyan','yellow');
		$sn = COption::GetOptionString(self::moduldeID, 'turbo_shop_name', '');
		$pr = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arSet['IBLOCK_ID'], "CODE" => "TURBO_SHOP_YANDEX_%"));

		while ($p = $pr->GetNext())
		{
			if ($sn && $sn == $p['NAME']) continue;
			$codes[] = $p['CODE'];
		}

		$vals = array();
		$values = array();
		foreach ($codes as $code) {
			$val = CIBlockElement::GetProperty($arSet['IBLOCK_ID'], $productId, array("sort" => "asc"), Array("CODE" => $code))->Fetch();
			$values[$code] = $val['VALUE'] ? $val['VALUE'] : "";
			if($values[$code]) $vals[] = $values[$code];
		}
		
		sort($vals); 
		
		if($arSet["PLACE"] > 0){
			$min = $vals[$arSet["PLACE"] - 1];
			if(!$min)
			{			
				for($i = $arSet["PLACE"]-1; $i>=0; $i--){
					$min = $vals[$i];
					if($min > 0)
						break;
				}
			}
		}
		else
			$min = $vals[0];

		$max = end($vals);

		$arPrice = CPrice::GetList(
			array(),
			array(
					"PRODUCT_ID" => $productId,
					"CATALOG_GROUP_ID" => $arSet['PRICE_ID']
				)
		)->Fetch();
		
		$values['ZAKUP_PRICE'] = $arPrice['PRICE'];
		$values['RENT_PRICE'] = floatval($values['ZAKUP_PRICE'] + $values['ZAKUP_PRICE'] * $arSet["RENT"]/100);
		$values['RENT_PRICE'] = round($values['RENT_PRICE']);

		// SET STATUS
		if(count($vals)<=0 || $max<=0)
			$idkey = "empty";  
		elseif($max <= $values['RENT_PRICE'])
			$idkey = "red";        
		elseif($values['RENT_PRICE'] >= $min)
			$idkey = "yellow";
		elseif($values['RENT_PRICE'] < $min && $values['RENT_PRICE'] > $min-$min*$arSet['DISCOUNT']/100)
			$idkey = "cyan";
		else
		{
			// consider DISCOUNT
			$values['RENT_PRICE'] = round(($min-$min*$arSet['DISCOUNT']/100));
			$idkey = "green";
		}

		if (in_array($idkey, $arBadStatus)) return;
		 
		if ($values['RENT_PRICE'] > 0) {
			$PriceType = CCatalogGroup::GetList( array(), array("NAME" => "TurboYandex"))->Fetch();
			if(!$PriceType['ID']){
				$arFields = array(
					"NAME" => "TurboYandex",
					"SORT" => 1111,
					"USER_GROUP" => array(1),
					"USER_GROUP_BUY" => array(1),
					"ACTIVE" => "N",
					"USER_LANG" => array(
						"ru" => "TurboYandex",
						"en" => "TurboYandex"
					)
				);
				$PriceType['ID'] = CCatalogGroup::Add($arFields);
			}

			$arFields = Array(
				"PRODUCT_ID" => $productId,
				"CATALOG_GROUP_ID" => $PriceType['ID'],
				"PRICE" => $values['RENT_PRICE'],
				"CURRENCY" => "RUB",
			);
			$r = CPrice::GetList(
				array(),
				array(
					"PRODUCT_ID" => $productId,
					"CATALOG_GROUP_ID" => $PriceType['ID']
				)
			);
			if ($arr = $r->Fetch())
				CPrice::Update($arr["ID"], $arFields);
			else
				CPrice::Add($arFields);
		}
	}
}


Class CTurbineSet{

	static function Update($id, $name, $iblock_id, $section_id, $rent, $price_id, $discount, $region, $place, $delivery, $subsect, $select_prop, $status)
	{
		global $DB;
		$strSql = "UPDATE yen_turbo_sets SET 
				NAME='{$name}', 
				IBLOCK_ID='{$iblock_id}', 
				SECTION_ID='{$section_id}', 
				RENT='{$rent}', 
				PRICE_ID='{$price_id}', 
				DISCOUNT='{$discount}', 
				REGION='{$region}', 
				PLACE='{$place}', 
				DELIVERY='{$delivery}',  
				SUBSECT='{$subsect}', 
				ELEM_STATUS='{$status}', 
				SELECT_PROP='{$select_prop}'
			WHERE ID={$id}";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	}
	
	static function GetByID($id) 
	{
		global $DB;
		$strSql = "SELECT * FROM yen_turbo_sets WHERE ID={$id}";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$res = $res->GetNext();
		return $res;
	}
	
	static function GetList() 
	{
		global $DB;
		$strSql = "SELECT * FROM yen_turbo_sets";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		return $res;
	}
	
	static function Add($name, $iblock_id, $section_id, $rent, $price_id, $discount, $region, $place, $delivery, $subsect, $select_prop, $status)
	{
		global $DB;
		$strSql = "INSERT INTO yen_turbo_sets(NAME, IBLOCK_ID, SECTION_ID, RENT, PRICE_ID, DISCOUNT, REGION, PLACE, DELIVERY, SUBSECT, SELECT_PROP, ELEM_STATUS)  VALUES('{$name}', '{$iblock_id}', '{$section_id}', '{$rent}', '{$price_id}', '{$discount}', '{$region}', {$place}, '{$delivery}', '{$subsect}', '{$select_prop}', '{$status}')";        
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	}

}

?>
