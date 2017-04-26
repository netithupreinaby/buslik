<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));

while($arr=$rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

$arProperties_LNS	= array() ; // list, number, string
if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$dbProperties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"])));
	while ($arProperty = $dbProperties->GetNext())
	{
		if(in_array($arProperty["PROPERTY_TYPE"], array("L", "N", "S")))
			$arProperties_LNS[$arProperty["CODE"]] = "[{$arProperty['CODE']}] {$arProperty['NAME']}" ; 
	}
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "{$_REQUEST['ELEMENT_ID']}",
		),

		"META_SPLITTER" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_SPLITTER"),
			"TYPE" => "STRING",
			"DEFAULT" => ",",
		),
		
		"META_TITLE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_H1"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		
		"META_TITLE_FORCE" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_H1_FORCE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "",
			"VALUES" => $arProperties_LNS,
		),
		
		"META_TITLE2" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_TITLE_PROP"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		
		"META_TITLE2_FORCE" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_TITLE_PROP_FORCE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "",
			"VALUES" => $arProperties_LNS,
		),		

		"META_KEYWORDS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_KEYWORDS"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		
		"META_KEYWORDS_FORCE" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_KEYWORDS_FORCE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "",
			"VALUES" => $arProperties_LNS,
		),		
		
		"META_DESCRIPTION" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_DESCRIPTION"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		
		"META_DESCRIPTION_FORCE" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_DESCRIPTION_FORCE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "",
			"VALUES" => $arProperties_LNS,
		),		
		"CACHE_TIME"  =>  Array("DEFAULT"=>86400),
		
		'VAT_INCLUDE' => array(
			"PARENT" => "BASE",
			"NAME"	 => GetMessage('META_VAT_INCLUDE'),
			"TYPE"	 => "CHECKBOX",
			"DEFAULT" => 'Y'
		),
	),
);

if (CModule::IncludeModule('catalog') && CModule::IncludeModule('currency'))
{
	$arCurrencyList = array();
	$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
	while ($arCurrency = $rsCurrencies->Fetch())
	{
		$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
	}
	$arComponentParameters['PARAMETERS']['CONVERT_CURRENCY'] = array(
			'PARENT' => 'BASE',
			'NAME'   => GetMessage('META_CONVERT_CURRENCY'),
			'TYPE'	 => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arComponentParameters['PARAMETERS']['CURRENCY_ID'] = array(
			"PARENT" => "BASE",
			"NAME"	 => GetMessage('META_CURRENCY_ID'),
			"TYPE"   => "LIST",
			"VALUES" => $arCurrencyList,
			"DEFAULT" => CCurrency::GetBaseCurrency(),
		); 
	}
}
?>