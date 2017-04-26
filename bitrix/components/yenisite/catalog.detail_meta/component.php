<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 86400;

$title = array();
$title2 = array();
$description = array();
$keywords = array();
$arParams["ELEMENT_ID"] = htmlspecialchars($arParams["ELEMENT_ID"]);
$arParams['CONVERT_CURRENCY'] = $arParams['CONVERT_CURRENCY'] ? $arParams['CONVERT_CURRENCY'] : false ;
$arParams['CURRENCY'] = $arParams['CURRENCY'] ? $arParams['CURRENCY'] : false ;
//$arParams['VAT_INCLUDE'] = $arParams['VAT_INCLUDE'] ? $arParams['VAT_INCLUDE'] : 'Y' ;

if(!$arParams["ELEMENT_ID"])
	return;

if($arParams["META_TITLE"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_TITLE"] , $title);
	$title = $title[1];
}

if($arParams["META_TITLE2"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_TITLE2"] , $title2);
	$title2 = $title2[1];
}

if($arParams["META_KEYWORDS"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_KEYWORDS"] , $keywords);
	$keywords = $keywords[1];
}

if($arParams["META_DESCRIPTION"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_DESCRIPTION"] , $description);
	$description = $description[1];
}

$arSelect = array_unique(array_merge($title2, $title, $keywords, $description));

$arFilter = array(
			"LOGIC" => "OR",
			array("ID" => $arParams["ELEMENT_ID"]),
			array("CODE" => $arParams["ELEMENT_ID"]),
		  );

$arResult["META_TITLE"] = $arParams["META_TITLE"] ? $arParams["META_TITLE"] : false ;
$arResult["META_TITLE2"] = $arParams["META_TITLE2"] ? $arParams["META_TITLE2"] : false;
$arResult["META_KEYWORDS"] = $arParams["META_KEYWORDS"] ? $arParams["META_KEYWORDS"] : false;
$arResult["META_DESCRIPTION"] = $arParams["META_DESCRIPTION"] ? $arParams["META_DESCRIPTION"] : false;
		  
if($this->StartResultCache(false, "META"))
{
	$arCurrency = $arParams['CONVERT_CURRENCY'] == 'Y' && $arParams['CURRENCY_ID'] ? array('CURRENCY_ID' => $arParams['CURRENCY_ID']) : array() ;
	
	$arSel = array(
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
		"PROPERTY_*",
	);
	
	$arPriceCode = array () ;
	
	foreach($arSelect as $item)
	{
		if(substr_count($item, "PRICE_") > 0)
			$arPriceCode[] = str_replace ('PRICE_', '', $item) ;
	}
	if(count($arPriceCode))
	{
		$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arPriceCode);
		foreach($arResultPrices as $price_code => $arPrice)
			$arSel[] = $arPrice['SELECT'] ;
	}
	
	$obel = CIBlockElement::GetList(array(), $arFilter, false, false, $arSel)->GetNextElement();
	
	if($obel)
	{
		
		$prop = $obel->GetProperties();	
		$field = $obel->GetFields();
		
		$arResult["ID"] = $field["ID"];
		
	
		foreach($arSelect as $item)
		{
			$bPrice = substr($equation_code, -7)=="_PRICE}";
			if(substr_count($item, "PROPERTY_") > 0)
			{
				$value = "";
				$key = str_replace("PROPERTY_", "", $item);
				
				switch($prop[$key]["PROPERTY_TYPE"])
				{
					case "E":
						if(IntVal($prop[$key]["VALUE"]))
						{
							$v = CIBlockElement::GetList(array(), array("ID" => $prop[$key]["VALUE"]), false, false, array("NAME"));
	                        if($v->SelectedRowsCount() == 1)
	                        {
	                            if($prop[$key]["MULTIPLE"] == "Y")
	                            {					
	                                $value = array();
	                                while($e = $v->GetNext())
	                                {
	                                    $value[] = $e["NAME"];
	                                }
	                                $value = implode($arParams["META_SPLITTER"], $value);
	                            }
	                            else
	                            {
	                                $e = $v->GetNext();
	                                $value = $e["NAME"];
	                            }
	                        }
                    	}
						break;
					case "G":
                            $sec = CIBlockSection::GetByID($prop[$key]["VALUE"])->GetNext();
                            $value = $sec['NAME'];
                        break;
					case "L":				
					default:
						if($prop[$key]["MULTIPLE"] == "Y")
						{
							$value = implode($arParams["META_SPLITTER"], $prop[$key]["VALUE"]);	
						}
						else{
							$value = $prop[$key]["VALUE"];	
						}
						
						break;
				}
			}
			elseif(substr_count($item, "OFFERS_PRICE_") > 0)
			{
				//CIBlockPriceTools::GetOffersArray($IBLOCK_ID, $arElementID, $arOrder, $arSelectFields, $arSelectProperties, $limit, $arPrices, $vat_include, $arCurrencyParams = array())
				$price_code = array () ;
				$price_code[0] = str_replace('OFFERS_PRICE_','',$item);
				
				$arResultPrices = CIBlockPriceTools::GetCatalogPrices($field["IBLOCK_ID"], $price_code);
				
				$arOffersPrice = CIBlockPriceTools::GetOffersArray(
					$field["IBLOCK_ID"]
					,array($arResult["ID"])
					,array(
						"ID" => "DESC",
					)
					,false
					,false
					,false
					,$arResultPrices
					,$arParams['VAT_INCLUDE']
					,$arCurrency
				);
				
				$value = $arOffersPrice[$price_code[0]]['PRINT_DISCOUNT_VALUE'] ? $arOffersPrice[$price_code[0]]['PRINT_DISCOUNT_VALUE'] : $arOffersPrice[$price_code[0]]['PRINT_VALUE'];
			}
			elseif(substr_count($item, "PRICE_") > 0)
			{
				// CIBlockPriceTools::GetItemPrices($IBLOCK_ID, $arCatalogPrices, $arItem, $bVATInclude = true, $arCurrencyParams = array())
				$price_code = array () ;
				$price_code[0] = str_replace('PRICE_','',$item);
				
				$arResultPrices = CIBlockPriceTools::GetCatalogPrices($field["IBLOCK_ID"], $price_code);

				$arPrices = CIBlockPriceTools::GetItemPrices(
					$field["IBLOCK_ID"]
					,$arResultPrices
					,$field
					,$arParams['VAT_INCLUDE']
					,$arCurrency
				);
				
				$value = $arPrices[$price_code[0]]['PRINT_DISCOUNT_VALUE'] ? $arPrices[$price_code[0]]['PRINT_DISCOUNT_VALUE'] : $arPrices[$price_code[0]]['PRINT_VALUE'];
			}
			else
			{
				switch($item)
				{
					case "SECTION_NAME":
						$sec = CIBlockSection::GetByID($field["IBLOCK_SECTION_ID"])->GetNext();
						$value = $sec["NAME"];
						break;				
					
					case "SECTION_CODE":
						$sec = CIBlockSection::GetByID($field["IBLOCK_SECTION_ID"])->GetNext();
						$value = $sec["CODE"];
						break;			
					
					case "IBLOCK_NAME":
						$iblock = CIBlock::GetByID($field["IBLOCK_ID"])->GetNext();
						$value = $iblock["NAME"];
						break;
					
					default:
						$value = $field[$item];
						break;
				}
			}
			
			$arResult["META_TITLE"] = str_replace("#".$item."#", $value , $arResult["META_TITLE"]);
			if($prop[$arParams["META_TITLE_FORCE"]]["VALUE"])
				$arResult["META_TITLE"] = $prop[$arParams["META_TITLE_FORCE"]]["VALUE"];
				
			$arResult["META_TITLE2"] = str_replace("#".$item."#", $value , $arResult["META_TITLE2"]);
			if($prop[$arParams["META_TITLE2_FORCE"]]["VALUE"])
				$arResult["META_TITLE2"] = $prop[$arParams["META_TITLE2_FORCE"]]["VALUE"];
			
			$arResult["META_KEYWORDS"] = str_replace("#".$item."#", $value , $arResult["META_KEYWORDS"]);
			if($prop[$arParams["META_KEYWORDS_FORCE"]]["VALUE"])
				$arResult["META_KEYWORDS"] = $prop[$arParams["META_KEYWORDS_FORCE"]]["VALUE"];
				
			$arResult["META_DESCRIPTION"] = str_replace("#".$item."#", $value , $arResult["META_DESCRIPTION"]);
			if($prop[$arParams["META_DESCRIPTION_FORCE"]]["VALUE"])
				$arResult["META_DESCRIPTION"] = $prop[$arParams["META_DESCRIPTION_FORCE"]]["VALUE"];
			
			$this->SetResultCacheKeys(array(
					"ID",
					"META_TITLE",
					"META_TITLE2",
					"META_KEYWORDS",
					"META_DESCRIPTION",
				));
		}
	}
	$this->IncludeComponentTemplate();
}
if($arResult["ID"])
{
	global $APPLICATION;
	
	if($arResult["META_TITLE2"])			
		$APPLICATION->SetPageProperty("title", $arResult["META_TITLE2"]);			
	if($arResult["META_KEYWORDS"])
		$APPLICATION->SetPageProperty("keywords", $arResult["META_KEYWORDS"]);
	if($arResult["META_DESCRIPTION"])
		$APPLICATION->SetPageProperty("description", $arResult["META_DESCRIPTION"]);
	if($arResult["META_TITLE"])	
		$APPLICATION->SetTitle($arResult["META_TITLE"]);	
}

return 0;
?>

