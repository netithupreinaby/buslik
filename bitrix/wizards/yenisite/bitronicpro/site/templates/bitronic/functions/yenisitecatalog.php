<?
if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date); 
		list($day, $month, $year) = explode(".", $date); 
		list($hour, $minute, $second) = explode(":", $time); 
		return mktime($hour, $minute, $second, $month, $day, $year); 
	}
}
// function use in catalog.section templates

if(!function_exists('yenisite_CATALOG_AVAILABLE'))
{
	function yenisite_CATALOG_AVAILABLE ($arProduct)
	{
		if(CModule::IncludeModule("catalog") &&
			CModule::IncludeModule('yenisite.geoipstore') &&
			COption::GetOptionString('catalog', 'default_use_store_control', 'N') == 'Y'
		)
		{		
			if ($arProduct['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
			global $stores;
			$rsStore = CCatalogStoreProduct::GetList(array(), array("PRODUCT_ID" => $arProduct['ID'], "@STORE_ID" => $stores, ">AMOUNT" => 0), false, false, array('PRODUCT_ID', 'STORE_ID', 'AMOUNT'));
			while($arStore = $rsStore->Fetch())
			{
				if($arStore['AMOUNT'] > 0)
				{
					return true;
				}
			}
		}
		else
		{
			if(!count($arProduct['OFFERS']))
			{
				if ($arProduct['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
				if ($arProduct['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
				if ($arProduct['CATALOG_QUANTITY'] > 0)          return true;
					return false;
			}
			else
			{
				if($arProduct['CATALOG_QUANTITY'] > 0) return true;

				foreach ($arProduct['OFFERS'] as $arOffer)
				{
					if ($arOffer['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
					if ($arOffer['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
					if ($arOffer['CATALOG_QUANTITY'] > 0)          return true;
				}
			}
		}
		return false;
	}
}
if((!function_exists('yenisite_WEEK_COUNTER')))
{
	function yenisite_WEEK_COUNTER ($ELEMENT_ID, $IBLOCK_ID)
	{
		$ELEMENT_ID = IntVal($ELEMENT_ID) ;
		if(!is_array($_SESSION["YENISITE_WEEK_COUNTER"]))
			$_SESSION["YENISITE_WEEK_COUNTER"] = Array();
			
		if(!$ELEMENT_ID || in_array($ELEMENT_ID, $_SESSION['YENISITE_WEEK_COUNTER']) || !CModule::IncludeModule("iblock"))
			return false;
		
		$arSelect = Array('ID', 'IBLOCK_ID', 'PROPERTY_WEEK', 'PROPERTY_WEEK_COUNTER') ;
		$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'ID'=>$ELEMENT_ID) ;
		$dbElement = CIBlockElement::GetList(Array(), $arFilter, false, array('nCountTop'=>1), $arSelect);
		if($arElement = $dbElement->Fetch())
		{
			$days = array() ;
			$today = strtotime(date("d.m.Y"));
			
			if(!$arElement['PROPERTY_WEEK_COUNTER_VALUE'])
				$days = Array($today => 1) ;
			else
			{
				$days = unserialize($arElement["PROPERTY_WEEK_VALUE"]);
				$days[$today] = IntVal($days[$today]) ? $days[$today] + 1 : 1 ;
			}
			$first_week_day = $today - 604800; // 604800 = 60 * 60 * 24 * 7
			$week_counter = 0 ;
			foreach ($days as $day => $count)
			{
				if($day > $first_week_day)
					$week_counter += $count ;
				else
					unset ( $days[$day] ) ;
			}
			
			CIBlockElement::SetPropertyValues($ELEMENT_ID, $IBLOCK_ID, serialize($days), "WEEK");
			CIBlockElement::SetPropertyValues($ELEMENT_ID, $IBLOCK_ID, $week_counter, "WEEK_COUNTER");
			$_SESSION["YENISITE_WEEK_COUNTER"][] = $ELEMENT_ID;
		}
	}
}
function yenisite_GetCompositeLoader()
{
	return "<span class='notloader ws composite_loader' style='display: block; width: 100%; text-align: center; font-size: 18px;'>0</span><script>startPopupLoader($('.composite_loader'))</script>";
}

/**
 * @param $sectionID - id of iblock section
 * @param $elementID - id of iblock element (optional)
 * @return string
 */
function yenisite_sectionUrl($sectionID, $elementID = 0)
{
	$sectionID = intval($sectionID);
	$elementID = intval($elementID);
	if ($sectionID <= 0) return '';

	$obCache = new CPHPCache();
	$life_time =  2592000;
	$cache_id = "ys-section-url";
	$cache_path = '/ys-cache';

	if ($obCache->InitCache($life_time, $cache_id, $cache_path)) {
		$vars = $obCache->GetVars();
		$arSecs = $vars['SECS'];
	}

	if (empty($arSecs[$sectionID])) {
		$arSecPage = CIBlockSection::GetByID($sectionID)->GetNext();
		if ($elementID > 0) {
			$arItemPage = CIBlockElement::GetByID($elementID)->GetNext();

			if (strpos($arItemPage['DETAIL_PAGE_URL'], $arSecPage['SECTION_PAGE_URL']) === 0) {
				$arSecs[$sectionID] = $arSecPage['SECTION_PAGE_URL'];
			} else {
				$arSecs[$sectionID] = $arItemPage['LIST_PAGE_URL'];
			}
		} else {
			$arSecs[$sectionID] = $arSecPage['SECTION_PAGE_URL'];
		}
		
		CPHPCache::Clean($cache_id, $cache_path);

		if ($obCache->StartDataCache($life_time, $cache_id, $cache_path)) {
			$obCache->EndDataCache(array("SECS" => $arSecs));
		}
	}
	unset($obCache);
	return $arSecs[$sectionID];
}

function yenisite_rubleSign($price)
{
	$priceR = str_replace(
		GetMessage('RUB_REPLACE'),
		'<span class="rubl">' . GetMessage('RUB') . '</span>',
		$price
	);
	if (strlen($priceR) != strlen($price)) {
		$price = rtrim($priceR, ' .');
	}
	return $price;
}
?>