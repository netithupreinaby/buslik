<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arParams['ID'] = IntVal($arParams['ID']) ;
if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return 0;
$arParams['IBLOCK_ID'] = IntVal($arParams['IBLOCK_ID']) ;
if($arParams['INCLUDE_JQUERY'] == 'Y')
	CJSCore::Init(array("jquery"));
if(!$arParams['PROPERTY_CODE'])
	$arParams['PROPERTY_CODE'] = 'COMPLETE_SETS' ;
if(!is_array($arParams['PRICE_CODE']))
	$arParams['PRICE_CODE'] = Array('BASE') ;
if(!$arParams['PROPERTY_PHOTO'])
	$arParams['PROPERTY_PHOTO'] = 'DETAIL_PICTURE';
if(!$arParams['IMAGE_WIDTH'])
	$arParams['IMAGE_WIDTH'] = 50;
if(!$arParams['IMAGE_HEIGHT'])
	$arParams['IMAGE_HEIGHT'] = 50;	
	
$arResultModules = array(
	'iblock' => true,
	'catalog' => true
);
// params for add2basket
if($arParams['ADD2BASKET'] == 'Y')
{
	$arParams["ACTION_VARIABLE"]=trim($arParams["ACTION_VARIABLE"]);
	if(strlen($arParams["ACTION_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
		$arParams["ACTION_VARIABLE"] = "action";

	$arParams["PRODUCT_ID_VARIABLE"]=trim($arParams["PRODUCT_ID_VARIABLE"]);
	if(strlen($arParams["PRODUCT_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_ID_VARIABLE"]))
		$arParams["PRODUCT_ID_VARIABLE"] = "id";

	$arParams["PRODUCT_QUANTITY_VARIABLE"]=trim($arParams["PRODUCT_QUANTITY_VARIABLE"]);
	if(strlen($arParams["PRODUCT_QUANTITY_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_QUANTITY_VARIABLE"]))
		$arParams["PRODUCT_QUANTITY_VARIABLE"] = "quantity";

	$arParams["PRODUCT_PROPS_VARIABLE"]=trim($arParams["PRODUCT_PROPS_VARIABLE"]);
	if(strlen($arParams["PRODUCT_PROPS_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_PROPS_VARIABLE"]))
		$arParams["PRODUCT_PROPS_VARIABLE"] = "prop";
	// end init params
		
	// start add2basket_sets
	$strError = ""; 
	if (array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST))
	{
		
		$action = strtoupper($_REQUEST[$arParams["ACTION_VARIABLE"]]);
			
		foreach($_REQUEST[$arParams['PRODUCT_ID_VARIABLE']] as $product_id)
		{
			echo 'product_id = '.$product_id.'<br/>';
			$productID = intval($product_id);
			if(($action == "ADD2BASKET_SET") && $productID > 0)
			{
				if(CModule::IncludeModule("sale"))
				{
					if($arParams["USE_PRODUCT_QUANTITY"])
						$QUANTITY = intval($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]]);
					if($QUANTITY <= 1)
						$QUANTITY = 1;
				
					$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
					$arNotify = unserialize($notifyOption);

					if(!$strError && Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, array()))
						$arAddedId[] = $productID;
					else
					{
						if($ex = $GLOBALS["APPLICATION"]->GetException())
							$strError = $ex->GetString();
						else
							$strError = GetMessage("CATALOG_ERROR2BASKET").".";
					}
					if(strlen($strError)>0)
					{
						ShowError($strError);
						return;
					}
				}
			}
		} 
		
		if ( $action == "ADD2BASKET_SET" )
		{
			LocalRedirect($APPLICATION->GetCurPageParam("", array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
		}
	}

	// end add2basket_sets	
}
else
{
	if(CModule::IncludeModule('yenisite.resizer2') && (!$arParams['RESIZER2_SET_BIG'] || !$arParams['RESIZER2_SET_SMALL']))
	{
		$dbSets = CResizer2Set::GetList();
		while($arSet = $dbSets->Fetch())
		{
			if(!$arParams['RESIZER2_SET_BIG'] && ( $arSet['h'] == 550 && $arSet['w'] == 800 ) )
				$default_big_set_id = $arSet['id'];
			if(!$arParams['RESIZER2_SET_SMALL'] && ( $arSet['h'] == 50 && $arSet['w'] == 50 ) )
				$defualt_small_set_id = $arSet['id'] ;
		}
		$arParams['RESIZER2_SET_BIG'] = $default_big_set_id ? $default_big_set_id : 1;
		$arParams['RESIZER2_SET_SMALL'] = $defualt_small_set_id ? $defualt_small_set_id : 5;
	}

	$arParams['PRICE_VAT_INCLUDE'] = $arParams['PRICE_VAT_INCLUDE'] ? $arParams['PRICE_VAT_INCLUDE'] : 'Y';
	$arCurrency = $arParams['CONVERT_CURRENCY'] == 'Y' && $arParams['CURRENCY_ID'] ? array('CURRENCY_ID' => $arParams['CURRENCY_ID']) : array() ;
	
	if(!$arParams['IBLOCK_ID'])
	{
		if($arParams['ID'])
		{
			$arElement = CIBlockElement::GetByID($arParams['ID']) -> Fetch();
			$arParams['IBLOCK_ID'] = $arElement['IBLOCK_ID'] ;
		}
		else
		{
			$arElement = CIBlockElement::GetList(Array(), Array('CODE'=>$arParams['CODE']), false, Array('nTopCount' => 1), Array('IBLOCK_ID', 'ID')) -> Fetch();
			$arParams['IBLOCK_ID'] = $arElement['IBLOCK_ID'] ;
		}
	}

	if(!$arParams['IBLOCK_ID'])
		return 0 ;
	if(!$arParams['ID'] && !$arParams['CODE'])
		return 0;
	
	// CACHE:
	//if ($this->StartResultCache())
	$arResult = Array() ;
		
	$dbElement = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'=>$arParams['IBLOCK_ID'], 'ID'=>$arParams['ID']), false, false, Array('IBLOCK_ID', 'ID', "PROPERTY_{$arParams['PROPERTY_CODE']}"));
	$arSetElements = array () ;
	
	while($arElement = $dbElement->Fetch())
	{
		$arPropValue = $arElement["PROPERTY_{$arParams['PROPERTY_CODE']}_VALUE"];
		if (!is_array($arPropValue)) {
			$arPropValue = array($arPropValue);
		}
		foreach ($arPropValue as $propValue) {
			$arSetElement = json_decode($propValue, true) ;
			
			if($arSetElement['element_id']>0 && $arSetElement['iblock_id']>0)
			{
				$arSetElementsID[$arSetElement['iblock_id']][] = $arSetElement['element_id'] ;
				$arSetElements[$arSetElement['element_id']] = $arSetElement ;
			}
		}
	}

	// cache groups
	$arResult['ITEMS'] = Array() ;
	
	$dbGroups = CIBlockElement::GetList(Array('SORT'=>'ASC'), Array('IBLOCK_CODE'=>'yenisite_set_groups'), false, false, Array('IBLOCK_ID', 'ID', 'NAME')) ;
	while($arGroup = $dbGroups->Fetch())
	{
		$arGroups[$arGroup['ID']] = $arGroup ;
		$arResult['ITEMS'][$arGroup['ID']] = array () ;
	}
	$arGroups[0] = array('NAME'=>GetMessage('YS_COMPLETE_SET_NONE_GROUP')) ;
	// if no all groups, reset cache
	$arResult['GROUPS'] = $arGroups ;
	$arResult['allSum'] = 0 ;
	$arFilter = Array () ;
	$arGroupKey = array();
	$currency = false;
	foreach($arSetElementsID as $IBLOCK_ID => $arSetInOneIBlock)
	{
		$arFilter = Array('IBLOCK_ID' => $IBLOCK_ID, 'ID'=>$arSetInOneIBlock) ;
		
		$arSelect = Array(
			"ID",
			"NAME",
			"CODE",
			"ACTIVE_FROM",
			"DATE_CREATE",
			"CREATED_BY",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"DETAIL_PAGE_URL",
			"DETAIL_TEXT",
			"DETAIL_TEXT_TYPE",
			"DETAIL_PICTURE",
			"PREVIEW_TEXT",
			"PREVIEW_TEXT_TYPE",
			"PREVIEW_PICTURE",
			"PROPERTY_*"
		);
		
		$arResultPrices = CIBlockPriceTools::GetCatalogPrices($IBLOCK_ID, $arParams['PRICE_CODE']) ;
		foreach($arResultPrices as $arResultPrice)
			$arSelect[] = $arResultPrice['SELECT'] ;

		$dbElements = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)  ;
		$arItem = array() ;
		while($obElement = $dbElements -> GetNextElement())
		{
			$arProps = $obElement->GetProperties();	
			$arFields = $obElement->GetFields();	
			$arItem = $arFields ;
			$arPrices =  CIBlockPriceTools::GetItemPrices($IBLOCK_ID, $arResultPrices, $arFields, false, $arCurrency);
			
			$min_price = false;
			$arItem['PRICE'] = array () ;
			
			foreach($arPrices as $arPrice)
			{
				if(!$currency)
					$currency = $arCurrency['CURRENCY_ID'] ? $arCurrency['CURRENCY_ID'] : $arPrice['CURRENCY'] ;
				if($arParams['PRICE_VAT_INCLUDE'])
				{
					$arPrice['VALUE'] = $arPrice['VALUE_VAT'] ;
					$arPrice['PRINT_VALUE'] = $arPrice['PRINT_VALUE_VAT'] ;
					$arPrice['DISCOUNT_VALUE'] = $arPrice['DISCOUNT_VALUE_VAT'] ;
					$arPrice['PRINT_DISCOUNT_VALUE'] = $arPrice['PRINT_DISCOUNT_VALUE_VAT'] ;
				}
				
				if(!$min_price || $min_price < $arPrice['VALUE'] || $min_price < $arPrice['DISCOUNT_VALUE'])
				{
					$min_price = $arPrice['DISCOUNT_VALUE'] && ($arPrice['DISCOUNT_VALUE'] < $arPrice['VALUE']) ? $arPrice['DISCOUNT_VALUE'] : $arPrice['VALUE'] ;				
					$arItem['PRICE']['VALUE'] = $arPrice['VALUE'] ;
					$arItem['PRICE']['PRINT_VALUE'] = $arPrice['PRINT_VALUE'] ;
					if($arPrice['VALUE'] != $arPrice['DISCOUNT_VALUE'])
					{
						$arItem['PRICE']['DISCOUNT_VALUE'] = $arPrice['DISCOUNT_VALUE'] ;
						$arItem['PRICE']['PRINT_DISCOUNT_VALUE'] = $arPrice['PRINT_DISCOUNT_VALUE'] ;
					}
					else
					{
						$arItem['PRICE']['DISCOUNT_VALUE'] = false ;
						$arItem['PRICE']['PRINT_DISCOUNT_VALUE'] = false ;
					}
				}
			}
			$arItem['YENISITE_SET'] = $arSetElements[$arFields['ID']] ;
			if($arItem['YENISITE_SET']['buy_checked'] == 1)
				$arResult['allSum'] += $min_price ;
			$arItem['DISPLAY_PROPERTIES'][$arParams['PROPERTY_PHOTO']] = $arProps[$arParams['PROPERTY_PHOTO']] ;
			
			
			if($arParams['PROPERTY_PHOTO'] == 'DETAIL_PICTURE' || $arParams['PROPERTY_PHOTO'] == 'PREVIEW_PICTURE')
				$image = $arItem[$arParams['PROPERTY_PHOTO']];
			else
			{							
				$image = $arProps[$arParams['PROPERTY_PHOTO']]['MULTIPLE'] == 'Y' ? $arProps[$arParams['PROPERTY_PHOTO']]['VALUE'][0] : $arProps[$arParams['PROPERTY_PHOTO']]['VALUE'] ;
			}
			
			if(!$image /*&& $arParams['PROPERTY_PHOTO'] != 'MORE_PHOTO'*/)
			{
				$image = $arItem['PREVIEW_PICTURE'] ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE'] ;
								
				if(!$image && is_array($arProps['MORE_PHOTO']))
				{
					$image = $arProps['MORE_PHOTO']['MULTIPLE'] == 'Y' ? $arProps['MORE_PHOTO']['VALUE'][0] : $arProps['MORE_PHOTO']['VALUE'] ;
				}
			}
			$pathResizeImage = array () ;
			if(CModule::IncludeModule('yenisite.resizer2'))
			{
				$pathImage = CFile::GetPath($image);
				$pathResizeImage['BIG'] = CResizer2Resize::ResizeGD2($pathImage, $arParams['RESIZER2_SET_BIG']);
				$pathResizeImage['SMALL'] = CResizer2Resize::ResizeGD2($pathImage, $arParams['RESIZER2_SET_SMALL']);
			}
			elseif($image)
			{
				$ResizeParams = array('width' => $arParams['IMAGE_WIDTH_BIG'], 'height' => $arParams['IMAGE_HEIGHT_BIG']);
				$ResizeImage['BIG'] = CFile::ResizeImageGet($image, $ResizeParams,  BX_RESIZE_IMAGE_PROPORTIONAL, true);
				$ResizeParams = array('width' => $arParams['IMAGE_WIDTH_SMALL'], 'height' => $arParams['IMAGE_HEIGHT_SMALL']);
				$ResizeImage['SMALL'] = CFile::ResizeImageGet($image, $ResizeParams,  BX_RESIZE_IMAGE_PROPORTIONAL, true);
				
				$pathResizeImage = $ResizeImage['src'] ;
			}
			$arItem['PHOTO_SRC'] = $pathResizeImage ;

			foreach($arParams['PROPERTIES'] as $prop_code)
				$arItem['DISPLAY_PROPERTIES'][$prop_code] = $arProps[$prop_code] ;
				
			$curGroupID = $arSetElements[$arFields['ID']]['group_id'] ? $arSetElements[$arFields['ID']]['group_id'] : 0 ;
			if (!array_key_exists($curGroupID, $arGroupKey)) $arGroupKey[$curGroupID] = 0;
			$arResult['ITEMS'][$curGroupID][$arGroupKey[$curGroupID]] = $arItem ;
			$arElementLink[$arItem['ID']] = &$arResult['ITEMS'][$curGroupID][$arGroupKey[$curGroupID]];
			$arResult["ELEMENTS"][] = $arItem["ID"];

			$arGroupKey[$curGroupID]++;
		}
		
		if ('SB' == $arParams['PRODUCT_DISPLAY_MODE'] && !empty($arParams["OFFERS_PROPERTY_CODE"]))
		{
			$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($IBLOCK_ID, $arParams["PRICE_CODE"]);
			$arOffers = CIBlockPriceTools::GetOffersArray(
				array(
					'IBLOCK_ID' => $IBLOCK_ID,
					'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
				)
				,$arResult["ELEMENTS"]
				,array(
					$arParams["OFFERS_SORT_FIELD"] => $arParams["OFFERS_SORT_ORDER"],
					$arParams["OFFERS_SORT_FIELD2"] => $arParams["OFFERS_SORT_ORDER2"],
				)
				,$arParams["OFFERS_FIELD_CODE"]
				,$arParams["OFFERS_PROPERTY_CODE"]
				,$arParams["OFFERS_LIMIT"]
				,$arResult["PRICES"]
				,$arParams['PRICE_VAT_INCLUDE']
				// ,$arConvertParams
			);
			
			if(!empty($arOffers))
			{
				foreach ($arResult["ELEMENTS"] as $id)
				{
					$arElementLink[$id]['OFFERS'] = array();
				}

				foreach($arOffers as $arOffer)
				{
					if (isset($arElementLink[$arOffer["LINK_ELEMENT_ID"]]))
					{
						$arOffer["~BUY_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
						$arOffer["BUY_URL"] = htmlspecialcharsbx($arOffer["~BUY_URL"]);
						$arOffer["~ADD_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
						$arOffer["ADD_URL"] = htmlspecialcharsbx($arOffer["~ADD_URL"]);
						$arOffer["~COMPARE_URL"] = $APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
						$arOffer["COMPARE_URL"] = htmlspecialcharsbx($arOffer["~COMPARE_URL"]);
						$arOffer["~SUBSCRIBE_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=SUBSCRIBE_PRODUCT&id=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
						$arOffer["SUBSCRIBE_URL"] = htmlspecialcharsbx($arOffer["~SUBSCRIBE_URL"]);

						$arElementLink[$arOffer["LINK_ELEMENT_ID"]]['OFFERS'][] = $arOffer;
					}
				}
			}
		}
		
	}
	$arResult['MODULES'] = $arResultModules;
	if($arParams['INCLUDE_OWNER_PRICE_VALUE'] > 0)
	{
		$arResult['allSum'] += $arParams['INCLUDE_OWNER_PRICE_VALUE'] * 1 ;
	}
	$arResult['allSum_FORMAT'] = CurrencyFormat($arResult['allSum'], $currency);

	$this->IncludeComponentTemplate();
}
?>