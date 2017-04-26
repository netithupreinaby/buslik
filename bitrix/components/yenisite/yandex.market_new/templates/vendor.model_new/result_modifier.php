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

foreach($arResult["OFFER"] as &$arOffer)
{		
	foreach($arParams["PARAMS"] as $k=>$v)
	{			
		if ($v == "EMPTY") continue;
				
		$arOffer["LIST_PROPERTIES"]["PARAMS"][$v][] = $v;
	}
	
	$i = 0; $f = 0;
	foreach($arParams as $k=>$v)
	{
		if (is_array($v)) $v = $v[0];
		if ($v == "NONE") $f = 1;
		if ( strpos($k, "_UNIT") )
		{
			$s = explode("_UNIT", $k);
			$arOffer['UNIT'][$s[0]] = $v;
			continue;
		}
		
		if(strpos($k, "~") !== false) continue;
		
		if($k == "DEVELOPER" || $k == "COUNTRY" || $k == "VENDOR_CODE" || $k == "MANUFACTURER_WARRANTY")
			$i = 1;
			
		if($k == "PARAMS" || $k == "COND_PARAMS")
			$i = 0;
			
		if($v == "EMPTY") continue;
				
		if($i == 0) continue;
		
//		var_export($k);
//		var_export($v); echo "\n";
		$code = $v;
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
		if(!empty($props) && !empty($props['VALUE']))
		{
			$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
			
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"] ? 
				$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"] :
				strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
				
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsBx($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
				
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];	
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $props["DESCRIPTION"];
			unset($props);
			if( !empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) )
			{
				$arOffer["LIST_PROPERTIES"][$k][] = $k;
			}
		}	
		
			
		/*if ( $f && $k == "GENDER" )
		{
			array_push ($arOffer["LIST_PROPERTIES"][$k], $code);
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = "Пол";
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $code;
		}*/
			
		$x = 0;
		if(is_array($arOffer['LIST_PROPERTIES']))
		foreach($arOffer["LIST_PROPERTIES"] as $k_prop=>$v_prop)
		{
			// если характеристика была добавлена выше из торгового предложения
			// (текущее значение k из arParams совпало хоть с одним значением из arOffer["LIST_PROPERTIES"])
			if($k == $k_prop)
				$x++;
		}
		
		//если текущая характеристика еще не добавлена
		if($x == 0 && !empty($arOffer["GROUP_ID"]))
		{	
			//заполняем массивы значениями из торгового каталога
			$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
			
			if(!empty($props) && !empty($props['VALUE']))
			{
				$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsBx($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $props["DESCRIPTION"];

				if($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] != "")
					$arOffer["LIST_PROPERTIES"][$k][] = $k;
				unset($props);
			}
		}
				
		if ( !$f && empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) && $k == "GENDER" )
		{
			$arOffer["LIST_PROPERTIES"][$k][] = $code;
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = GetMessage("YENISITE_YANDEX_POL");
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $code;
		}
	}
		
	foreach($arParams['MULTI_STRING_PROP'] as $k=>$code)
	{
		$dbProp = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
			Array("CODE" => $code));
			
		while($arProp = $dbProp->GetNext())
		{
			$cod = $code.'_'.$arProp['PROPERTY_VALUE_ID'];
			
			$arOffer["DISPLAY_CHARACTERISTICS"][$cod] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"],
			$arProp, "ym_out");
			
			$arOffer["DISPLAY_CHARACTERISTICS"][$cod]["DISPLAY_VALUE"] =
				$arOffer["DISPLAY_CHARACTERISTICS"][$cod]["VALUE_ENUM"] ?
				$arOffer["DISPLAY_CHARACTERISTICS"][$cod]["VALUE_ENUM"] :
				strip_tags($arOffer["DISPLAY_CHARACTERISTICS"][$cod]["DISPLAY_VALUE"]);
				
			$arOffer["DISPLAY_CHARACTERISTICS"][$cod]["DISPLAY_VALUE"] = htmlspecialcharsBx($arOffer["DISPLAY_CHARACTERISTICS"][$cod]["DISPLAY_VALUE"]);
			$arOffer["DISPLAY_CHARACTERISTICS"][$cod]["DISPLAY_NAME"] = $arProp['DESCRIPTION'];
			unset ($arProp);
		}
		unset($dbProp);
	}
}
?>