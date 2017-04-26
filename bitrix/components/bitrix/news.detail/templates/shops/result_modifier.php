<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	//получение имени города
	$elements =array();
	$arFilter = array('IBLOCK_ID' => IntVal($arResult['PROPERTIES']['city']['LINK_IBLOCK_ID']),'ID'=>IntVal($arResult['PROPERTIES']['city']['VALUE']),'ACTIVE'=>'Y'); 
	$rsSect = CIBlockElement::GetList(array('NAME','ID','IBLOCK_ID'),$arFilter, false, Array(), array());
	while($elements = $rsSect->GetNext()){
	$listidcity[$elements['ID']] = array('ID'=>$elements['ID'], 'NAME'=>$elements['NAME']);
	};
	
	$elements =array();
	$arFilter = array('IBLOCK_ID' => IntVal($arResult['PROPERTIES']['td']['LINK_IBLOCK_ID']),'ID'=>$arResult['PROPERTIES']['td']['VALUE_ID'],'ACTIVE'=>'Y'); 
	$rsSect = CIBlockElement::GetList(array('NAME','ID','IBLOCK_ID'),$arFilter, false, Array(), array());
	while($elements = $rsSect->GetNext()){
	$td[$elements['ID']] = $elements['NAME'];
	};
	
	$elements =array();
	$arFilter = array('IBLOCK_ID' => IntVal($arResult['PROPERTIES']['inshops']['LINK_IBLOCK_ID']),'ID'=>$arResult['PROPERTIES']['inshops']['VALUE_ID'],'ACTIVE'=>'Y'); 
	$rsSect = CIBlockElement::GetList(array('NAME','ID','IBLOCK_ID'),$arFilter, false, Array(), array());
	while($elements = $rsSect->GetNext()){
	$inshops[$elements['ID']] = $elements['NAME'];
	};
	
	
	$arResult['listidcity'] = $listidcity;
	$arResult['listtd'] = $td;
	$arResult['listinshops'] = $inshops;
	
	
//printr($td);
//printr($arResult);
