<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arPrice = array();
if(!CModule::IncludeModule("iblock"))
	return;

use Bitrix\Iblock;

if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["ID"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	
}
elseif(CModule::IncludeModule("yenisite.market"))
{
	$rsPrice = CMarketPrice::GetList();
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["code"]] = "[".$arr["code"]."] ".$arr["name"];
} 



$arCurrency = array();
$BaseCurrency = "USD";
if(CModule::IncludeModule("currency")){  
	$BaseCurrency = CCurrency::GetBaseCurrency();
	$lcur = CCurrency::GetList(($by="name"), ($order1="asc"), LANGUAGE_ID);
	while($lcur_res = $lcur->Fetch())
	{
		$arCurrency[$lcur_res["CURRENCY"]] = $lcur_res["FULL_NAME"];
	}
} 

global $arComponentParameters;

unset($arComponentParameters['PARAMETERS']["PAGE"]);

$arComponentParameters["GROUPS"]["VISUAL"] = array(
	"NAME" => GetMessage("GROUPS_VISUAL"),
	"SORT" => '400',
);

$arComponentParameters["PARAMETERS"]["CATEGORY_0_TITLE"]["DEFAULT"] = GetMessage("FIND_ALL");

$arTemplateParameters = array(

	/*BASE*/

	"SEARCH_IN_TREE" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("SEARCH_IN_TREE"), 
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y'
	),
	/*URL TEMPLATES*/
	"PAGE" => array(
		"PARENT" => "URL_TEMPLATES",
		"NAME" => GetMessage("PAGE"),
		"TYPE" => "STRING",
		"DEFAULT" => "#SITE_DIR#search/index.php",
	),
	"PAGE_2" => array(
		"PARENT" => "URL_TEMPLATES",
		"NAME" => GetMessage("PAGE_2"),
		"TYPE" => "STRING",
		"DEFAULT" => "#SITE_DIR#search/catalog.php",
	),
	
	/*VISUAL*/
	"COLOR_SCHEME" => Array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("COLOR_SCHEME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"red" => GetMessage("COLOR_SCHEME_RED"), 
			"green" => GetMessage("COLOR_SCHEME_GREEN"), 
			"ice" => GetMessage("COLOR_SCHEME_BLUE"), 
			"metal" => GetMessage("COLOR_SCHEME_METAL"), 
			"pink" => GetMessage("COLOR_SCHEME_PINK"), 
			"yellow" => GetMessage("COLOR_SCHEME_YELLOW")
		),
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => '={$ys_options["color_scheme"]}',
	),

	/*OTHER*/
	"CACHE_TIME"  =>  Array(
		"NAME" => GetMessage("CACHE_TIME"),
		"DEFAULT"=>86400,
	),
	"INCLUDE_JQUERY" => Array(
		"NAME" => GetMessage("INCLUDE_JQUERY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y'
	),
	"INPUT_ID_CSS" => array(
		"NAME" => GetMessage("INPUT_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "ys-title-search-input",
	),
	"CONTAINER_ID_CSS" => array(
		"NAME" => GetMessage("CONTAINER_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "ys-title-search",
	),
	"AJAX_BASKET" => array(	
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("AJAX_BASKET"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",	
	),
	);
	
	
	
	
  	if(CModule::IncludeModule("catalog")){
		$arTemplateParameters["CURRENCY"] = array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("CURRENCY"),
		"TYPE" => "LIST",
		"VALUES" => $arCurrency,
		"DEFAULT" => $BaseCurrency,
		);
	}	

	
	
	
	
	
  	if(CModule::IncludeModule("catalog") || CModule::IncludeModule("yenisite.market")){
		$arTemplateParameters["PRICE_CODE"] = array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
			"DEFAULT" => array(
				0 => "0",
				1 => "1",
				2 => "2",
				3 => "3",
			)
		);
	}else
	{  
		$arTemplateParameters["PRICE_CODE"] = array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PRICE_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => "PRICE_BASE",
		);
	}

/*RESIZER*/
if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"].""] = "[".$arr["id"]."] ".$arr["NAME"];
	}
	$arTemplateParameters["PHOTO_SIZE"] = array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("PHOTO_SIZE"),
			"TYPE" => "LIST",
			"DEFAULT" => "5",
			"VALUES" => $list,
		);
}
/*
$arTemplateParameters["PROPERTY_ENABLE"] = array(	
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("PROPERTY_ENABLE"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
	"REFRESH" => "Y",
);

if($arCurrentValues["PROPERTY_ENABLE"] == "Y")
	$arTemplateParameters["PROPERTY_CODE"] = array(	
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("PROPERTY_CODE"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",			
		"ADDITIONAL_VALUES" => "Y",
	);
*/
$arTemplateParameters["EXAMPLE_ENABLE"] = array(
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("EXAMPLE_ENABLE"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
	"REFRESH" => "Y",
);

if($arCurrentValues["EXAMPLE_ENABLE"] == "Y")
	$arTemplateParameters["EXAMPLES"] = array(		
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("EXAMPLES"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"ADDITIONAL_VALUES" => "Y",
	);


?>
