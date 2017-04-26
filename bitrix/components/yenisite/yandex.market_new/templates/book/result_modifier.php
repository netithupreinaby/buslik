<?

function getBaseCurrency()
{
	if ( CModule::IncludeModule('currency') )
	{
		$res = CCurrency::GetList( ($by="name"), ($order="asc"), "RU" );
		while( $arRes = $res->Fetch() )
		{
			if ( $arRes["AMOUNT"] == 1 )
				return $arRes["CURRENCY"];
		}
	}
}

$baseCur = getBaseCurrency();
if ( !CModule::IncludeModule('currency') ) $baseCur = $arParams["CURRENCY"];
$arCur = array();
$arCur[0] = $baseCur;
foreach( $arResult["CURRENCIES"] as $cur )
{
	if ($cur == 'RUR')
	{
		$cur = 'RUB';
	}
	
	if ( !in_array( $cur, $arCur ) )
		$arCur[] = $cur;
}

$arResult["CURRENCIES"] = $arCur;

foreach($arResult["OFFER"] as &$arOffer){		
		$code = "AUTHOR";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
		
		$code = "PUBLISHER";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
		
				
		$code = "SERIES";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

				
		$code = "YEAR";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

				
		$code = "ISBN";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

				
		$code = "VOLUME";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

				
		$code = "PART";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

				
		$code = "BINDING";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

		$code = "PAGE_EXTENT";
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$arParams[$code]))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
		
}
?>
