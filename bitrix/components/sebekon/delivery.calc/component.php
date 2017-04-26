<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/*
 * Module: sebekon.deliveryprice
 */

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sebekon.deliveryprice/include.php");

if (!CModule::IncludeModule("sebekon.deliveryprice")) {
    if (CModule::IncludeModuleEx("sebekon.deliveryprice") == MODULE_DEMO_EXPIRED) {
        ShowError(GetMessage("sebekon_DP_module_demo_expired"));
    } else {
        ShowError(GetMessage("sebekon_DP_please_install_module"));
    }
    return;
}

CSebekonDeliveryPrice::$loaded = true;

$mapIds = array();
if(!empty($arParams["MAP"])){
	$mapIds = $arParams["MAP"];
}

$order_price = false;
$order_weight = false;
if ($arParams['CUSTOM_CALC']=='Y') {
	$order_price = floatval($arParams['CUSTOM_PRICE']);
	$order_weight = floatval($arParams['CUSTOM_WEIGHT']);
}

$arParams["ADD2BASKET"] = $arParams["ADD2BASKET"]=="Y";

//подгружаем все карты и зоны
$arResult = CSebekonDeliveryPrice::getMap($mapIds, $order_price, $order_weight);
$arResult['ID'] = md5(time()).randString(3);

CSebekonDeliveryPrice::LoadJs();
$arResult['jsResult'] = array('MAPS'=>array(), 'ZONES'=>array(), 'ID'=>$arResult['ID']);
foreach ($arResult['MAPS'] as $k=>$map) {
	$_map = array(
		'ID'=>$map['ID'],
		'NAME'=>$map['NAME'],
		'PROPS'=>array(),
	);
	
	foreach ($map['PROPS'] as $code=>$prop) {
		$_map['PROPS'][$code]['VALUE'] = $prop['VALUE'];
		$_map['PROPS'][$code]['DESCRIPTION'] = $prop['DESCRIPTION'];
	}
	
	$arResult['jsResult']['MAPS'][$k] = $_map;
}

foreach ($arResult['ZONES'] as $k=>$zone) {
	$_zone = array(
		'ID'=>$zone['ID'],
		'NAME'=>$zone['NAME'],
		'SORT'=>$zone['SORT'],
		'PROPS'=>array(),
	);
	
	foreach ($zone['PROPS'] as $code=>$prop) {
		$_zone['PROPS'][$code]['VALUE'] = $prop['VALUE'];
		$_zone['PROPS'][$code]['DESCRIPTION'] = $prop['DESCRIPTION'];
	}
	
	$arResult['jsResult']['ZONES'][$k] = $_zone;
}

$arResult['jsParams'] = array(
	'MULTI_POINTS' => $arParams['MULTI_POINTS'],
	'CUSTOM_CALC' => $arParams['CUSTOM_CALC'],
	'CUSTOM_PRICE' => $arParams['CUSTOM_PRICE'],
	'CUSTOM_WEIGHT' => $arParams['CUSTOM_WEIGHT'],
);

$arResult['jsLabels'] = array();
foreach (array('sebekon_DP_PUBLIC_UNPOSSIBLE', 'sebekon_RUB', 'sebekon_DP_IBLOCK_ROUTE_LEN', 'sebekon_DP_IBLOCK_KM') as $lbl) {
	$arResult['jsLabels'][$lbl] = GetMessage($lbl);
}

if (\CModule::IncludeModule("sale")) {
	$arResult['HANDLER'] = \CSaleDeliveryHandler::GetBySID('sebekon_yaroute')->Fetch();
}

$this->IncludeComponentTemplate();

?>