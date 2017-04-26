<?
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))die();

foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if ($id > 0)
{
	$arProp = array();
	
	if ( CModule::IncludeModule('catalog') )
	{
		$andreytroll = CCatalog::GetList(array(),array("PRODUCT_IBLOCK_ID"=> $id), false, false, array());
		$check = $andreytroll->Fetch();
			
		$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $check["IBLOCK_ID"],
			array("LOGIC" => "OR", array("PROPERTY_TYPE" => "L"),
			array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );
			
		while( $arr = $rsProp->Fetch() )
		{
			if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
				$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
			{
				$arProp[$arr["CODE"]] = "SKU_".$arr["NAME"];
			}
		}
	}
		
	$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $id,  array("LOGIC" => "OR",
		array("PROPERTY_TYPE" => "L"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );
		
	while ( $arr = $rsProp->Fetch() )
	{
		if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
			$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
		{
			$arProp[$arr["CODE"]] = $arr["NAME"];
		}
	}
}

	$arProp["EMPTY"] = "				"; 
	natsort($arProp);

$arTemplateParameters = array(
	"DEVELOPER" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("DEVELOPER"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"MARKET_CATEGORY_CHECK" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("MARKET_CATEGORY_CHECK"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"MARKET_CATEGORY_PROP" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("MARKET_CATEGORY_PROP"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"GOOGLE_GTIN" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("GOOGLE_GTIN"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"PARAMS" => Array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("PARAMS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",	
			"HIDDEN" => "Y"
		),
	"COND_PARAMS" => Array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("COND_PARAMS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"HIDDEN" => "Y"	
		),/*
	"COUNTRY" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("COUNTRY"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),*/
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['FORCE_CHARSET'] = array(
	"PARENT" => "COMMON",
	"NAME" => GetMessage("FORCE_CHARSET"),
	"TYPE" => "STRING",
	"DEFAULT" => "UTF-8",
);
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';
$arComponentParameters["PARAMETERS"]["SITE"]["NAME"] = GetMessage("FEED_NAME");
$arComponentParameters["PARAMETERS"]["COMPANY"]["NAME"] = GetMessage("FEED_DESCRIPTION");
