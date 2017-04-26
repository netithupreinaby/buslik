<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ? intval($arParams['CACHE_TIME']) : 3600000;
if($this->StartResultCache(false, $USER->GetGroups()))
{
	$arResult = array() ;
	$new_time = date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), time() - 1209600); // 1209600 - 2 week.
	$arFilters = array(
			'NEW' => Array("LOGIC" => "OR", array('!PROPERTY_NEW' => false),array('>DATE_CREATE' => $new_time)),
			'HIT' => Array("LOGIC" => "OR", array('!PROPERTY_HIT' => false), array('>PROPERTY_WEEK_COUNTER' => 100)),
			'SALE' => Array('!PROPERTY_SALE' => false),
			'BESTSELLER' => Array("LOGIC" => "OR", array('!PROPERTY_BESTSELLER' => false),array('>PROPERTY_SALE_INT' => 3)),
		);
	
	foreach ($arFilters as $key => $arFilter)
	{
		$arResult['TABS'][$key]['LINK'] = '/'.strtolower($key).'/' ;
		$arResult['TABS'][$key]['COUNT'] = CIBlockElement::GetList(Array(), Array('ACTIVE'=>'Y', 'SITE_ID'=>SITE_ID, Array('LOGIC'=>'OR', Array("IBLOCK_TYPE"=>"catalog_%"), Array("IBLOCK_TYPE"=>SITE_ID."_%")), $arFilter), Array(), false);
		if(!$arResult['TABS'][$key]['COUNT'])
					$arResult['TABS'][$key]['COUNT'] = CIBlockElement::GetList(Array(), Array('ACTIVE'=>'Y', 'SITE_ID'=>SITE_ID, 'IBLOCK_TYPE'=>'catalog', $arFilter), Array(), false);
	}

	$this->IncludeComponentTemplate();
}
?>