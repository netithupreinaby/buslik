<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);
$arDeleteProp = $arResult["SHOW_PROPERTIES"];
foreach($arResult["ITEMS"] as &$item) {
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item) ;
	if(intval($arResult['OFFERS_IBLOCK_ID'])>0)
	{
		$item["OFFERS_EXIST"] = CCatalogSKU::IsExistOffers( $item["ID"], $arResult['IBLOCK_ID']);
	}
	if(strpos($item['ADD_URL'], '?')!== false)
		$item['ADD_URL'] = substr($item['ADD_URL'], strpos($item['ADD_URL'], '?'));
	if(!CModule::IncludeModule('sale'))
		$item['ADD_URL'] = str_replace('COMPARE_ADD2BASKET', 'ADD2BASKET', $item['ADD_URL']);
	foreach($item['PROPERTIES'] as $k => $v)
	{
		if(array_key_exists($k, $arDeleteProp) && !empty($v['VALUE']))
			unset($arDeleteProp[$k]);
	}
	if ($sef == "Y") 	{
		$url = $item["DETAIL_PAGE_URL"];
		$tmpAr = explode('/', $url);

		if (!empty($item['IBLOCK_SECTION_ID'])) {
			$item["DETAIL_PAGE_URL"] = yenisite_sectionUrl($item['IBLOCK_SECTION_ID'], $item['ID']) . $item["CODE"] . '.html';
			
			continue;
		} else if ($arch == 'multi') {
			$tmpAr = array_slice($tmpAr, 0, 3);
		} else {
			$tmpAr = array_slice($tmpAr, 0, 2);
		}
		$url = implode('/', $tmpAr);
		$item["DETAIL_PAGE_URL"] = $url.'/'.$item["CODE"].'.html';
	}
}
unset($item);
foreach($arDeleteProp as $k => $v)
	unset($arResult["SHOW_PROPERTIES"][$k]);
unset($arDeleteProp, $k, $v);
?>