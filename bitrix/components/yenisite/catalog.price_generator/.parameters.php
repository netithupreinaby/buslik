<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;
CModule::IncludeModule("catalog");
use Bitrix\Iblock;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$iblockExists = ((!empty($arCurrentValues['IBLOCK_ID'][0]) || !empty($arCurrentValues['IBLOCK_ID'][1])) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
$arFilterPropIblocks = $arCurrentValues["IBLOCK_ID"];

while($arr=$rsIBlock->Fetch()){
		$mxResult = CCatalogSKU::GetInfoByProductIBlock($arr["ID"]);

		if(is_array($mxResult)){
			if(in_array($arr["ID"],$arCurrentValues["IBLOCK_ID"])){
				$arFilterPropIblocks[] = $mxResult["IBLOCK_ID"];
			}
			$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
		}
			
		
}

unset($arr, $rsIBlock);

	if($iblockExists){
		$propertyIterator = Iblock\PropertyTable::getList(array(
			'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
			'filter' => array('=IBLOCK_ID' => $arFilterPropIblocks, '=ACTIVE' => 'Y'),
			'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
		));	
	
		while ($property = $propertyIterator->fetch())
		{
			$propertyCode = (string)$property['CODE'];
			if ($propertyCode == '')
				$propertyCode = $property['ID'];
			$propertyName = '['.$propertyCode.'] '.$property['NAME'];

			if ($property['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
			{
				$arProperty[$propertyCode] = $propertyName;

				if ($property['MULTIPLE'] == 'Y')
					$arProperty_X[$propertyCode] = $propertyName;
				elseif ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
					$arProperty_X[$propertyCode] = $propertyName;
				elseif ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT && (int)$property['LINK_IBLOCK_ID'] > 0)
					$arProperty_X[$propertyCode] = $propertyName;
			}

			if ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER)
				$arProperty_N[$propertyCode] = $propertyName;

		}
	}
	
$arProperty_UF = array();
$arSProperty_LNS = array();
$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arProperty_UF[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
	if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
		$arSProperty_LNS[$FIELD_NAME] = $arProperty_UF[$FIELD_NAME];
}

$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
$arProperty_Offers = array();
if($OFFERS_IBLOCK_ID)
{
	$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$OFFERS_IBLOCK_ID));
	while($arr=$rsProp->Fetch())
	{
		if($arr["PROPERTY_TYPE"] != "F")
			$arProperty_Offers[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
			}
}

$arPrice = array();
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}
else
{
	$arPrice = $arProperty_N;
}

if (CModule::IncludeModule('currency')) {
	$arCurrencyList = array();
	$db_cur = CCurrency::GetList(($by="sort"), ($order="asc"));
	while($arCur = $db_cur->Fetch())
	{
		$arCurrencyList[$arCur['CURRENCY']] = $arCur['CURRENCY'];
	}
}

$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);
$arFileType = array(
	"xls" => GetMessage("XLS"),
	"doc" => GetMessage("DOC"),
	"pdf" => GetMessage("PDF"),
	);
$arComponentParameters = array(
	"GROUPS" => array(
		"PRICES" => array(
			"NAME" => GetMessage("IBLOCK_PRICES"),
		),
	),
	"PARAMETERS" => array(		
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
			"MULTIPLE" => "Y"
		),
		
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"MULTIPLE" => "Y",
			"REFRESH" => "Y",
		),
 
		"FILE_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("FILE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "/price",
		),
		
		"FILE_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("FILE_TYPE"),
			"TYPE" => "LIST",
			"DEFAULT" => "xls",
			"VALUES" => $arFileType,
		),
		
 		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_FILTER_NAME_IN"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		), 
		
		"PROPERTY_CODE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty,
			"ADDITIONAL_VALUES" => "Y",
		),
				
		"PRICE_CODE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
		
		"CACHE_FILTER" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BCS_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),		
		
		"DISCOUNT_CHECK" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("DISCOUNT_CHECK"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),		
		
		"EXISTENCE_CHECK" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("EXISTENCE_CHECK"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	),
);

if (CModule::IncludeModule('currency')) {
	$arComponentParameters["PARAMETERS"]["CURRENCY_LIST"]= array(
		"PARENT"    => "PRICES",
		"NAME"      => GetMessage("CURRENCY_LIST"),
		"TYPE"      => "LIST",
		"VALUES"    => $arCurrencyList
	);
}

?>
