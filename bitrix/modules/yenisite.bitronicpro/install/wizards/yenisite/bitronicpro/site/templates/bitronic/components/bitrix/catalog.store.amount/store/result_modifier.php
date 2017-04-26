<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (CModule::IncludeModule('yenisite.geoipstore')) {
	foreach ($arResult["STORES"] as $k => &$store) {
		$flag = 0;
		foreach ($arParams['STORE_CODE'] as $st) {
			if ($store['ID'] == $st) {
				$flag = 1;
				break;
			}
		}
		if(!$flag) unset($arResult['STORES'][$k]);
	}
}
?>