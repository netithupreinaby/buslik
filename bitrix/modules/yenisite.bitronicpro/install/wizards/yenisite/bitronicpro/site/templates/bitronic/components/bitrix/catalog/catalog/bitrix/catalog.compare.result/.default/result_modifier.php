<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);
$arDeleteProp = $arResult["SHOW_PROPERTIES"];
foreach($arResult["ITEMS"] as &$item) {
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item) ;
	
	if(strpos($item['ADD_URL'], '?')!== false)
		$item['ADD_URL'] = substr($item['ADD_URL'], strpos($item['ADD_URL'], '?'));
	foreach($item['PROPERTIES'] as $k => $v)
	{
		if(array_key_exists($k, $arDeleteProp) && !empty($v['VALUE']))
			unset($arDeleteProp[$k]);
	}
	if ($sef == "Y") 	{
		$url = $item["DETAIL_PAGE_URL"];
		$tmpAr = explode('/', $url);

		if (!empty($item['IBLOCK_SECTION_ID'])) {
			$obCache = new CPHPCache();
			$life_time =  2592000;
			$cache_id = "ys-section-url";

			if ($obCache->InitCache($life_time, $cache_id, 'ys-cache')) {
				$vars = $obCache->GetVars();
				$arSecs = $vars['SECS'];
			}

			if (empty($arSecs[$item['IBLOCK_SECTION_ID']])) {
				$arSecPage = CIBlockSection::GetByID($item['IBLOCK_SECTION_ID'])->GetNext();

				$arSecs[$item['IBLOCK_SECTION_ID']] = $arSecPage['SECTION_PAGE_URL'];

				if ($obCache->StartDataCache($life_time, $cache_id, "/")) {
					$obCache->EndDataCache(array("SECS" => $arSecs));
				}
			}
			unset($obCache);

			$item["DETAIL_PAGE_URL"] = $arSecPage['SECTION_PAGE_URL'] . $item["CODE"] . '.html';

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

foreach($arDeleteProp as $k => $v)
	unset($arResult["SHOW_PROPERTIES"][$k]);
unset($arDeleteProp, $k, $v);
?>