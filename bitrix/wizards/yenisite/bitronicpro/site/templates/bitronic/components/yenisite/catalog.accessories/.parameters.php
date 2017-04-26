<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

define("YS_PATH_TO_COMPONENT", '/bitrix/components/yenisite/catalog.accessories/settings/') ;

if (!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$rsIBlock = CIBlock::GetList(array(
	"sort" => "asc",
), array(
	"TYPE" => $arCurrentValues["IBLOCK_TYPE"],
	"ACTIVE" => "Y",
));
while ($arr = $rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
$arProperties = array() ;
if(IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$dbProperties = CIBlockProperty::GetList(Array('sort'=>'asc', 'name'=>'asc'), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"]), 'PROPERTY_TYPE'=>'E'));
	while ($arProperty = $dbProperties->GetNext())
	{
		$arProperties[$arProperty['ID']] = "[{$arProperty['CODE']}] {$arProperty['NAME']}" ;
	}
/*	
	if($arCurrentValues['ACCESSORIES_PROPS'])
		$arAccessProps = unserialize($arCurrentValues['ACCESSORIES_PROPS']) ;

	if(!is_array($arAccessProps) || IntVal($arAccessProps['PARENT_IBLOCK_ID']) != IntVal($arCurrentValues["IBLOCK_ID"]))
	{
		$arAccessProps = array() ;
		$arAccessProps['PARENT_IBLOCK_ID'] = IntVal($arCurrentValues["IBLOCK_ID"]) ;
	}
	$arAccessProps = serialize($arAccessProps) ;
*/
}
/*
else
	$arAccessProps = '' ;
*/
$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_IBLOCK_ID"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"ELEMENT_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["ELEMENT_ID"]}',
		),
		"ACCESSORIES_LINK"	=> array(
			"PARENT" => "DATA_SOURCE",
			"NAME"	=> GetMessage("ACCESSORIES_LINK"),
			"TYPE"	=> "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arProperties
		),
		"ACCESSORIES_PROPS" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_PROPS"),
			"TYPE" => "CUSTOM",
			"JS_FILE" => YS_PATH_TO_COMPONENT.'settings.js',
			"JS_EVENT" => 'OnYenisiteAccessoriesSettingEdit',
			"JS_DATA" => LANGUAGE_ID.'||'.GetMessage('ACCESSORIES_DATA_SET').'||'.$arCurrentValues["IBLOCK_ID"],
		),

		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_FILTER_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		"PAGE_ELEMENT_COUNT" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("ACCESSORIES_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>86400),
	),
);
?>