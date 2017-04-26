<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

$arUF_SECTION = array() ;
if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$dbUF = CUserTypeEntity::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ENTITY_ID" => "IBLOCK_". $arCurrentValues["IBLOCK_ID"]."_SECTION" ));
	while ($arUF=$dbUF->Fetch())
		$arUF_SECTION [$arUF["FIELD_NAME"]] = "[{$arUF['FIELD_NAME']}] {$arUF['FIELD_NAME']}";
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
		"SECTION_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SECTION_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "{$_REQUEST['SECTION_ID']}",
		),
		"SECTION_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SECTION_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => "{$_REQUEST['SECTION_CODE']}",
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
			"VALUES" => $arUF_SECTION,
		),
		
		"META_TITLE_PROP" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_TITLE_PROP"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		
		"META_TITLE_PROP_FORCE" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("META_TITLE_PROP_FORCE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "",
			"VALUES" =>$arUF_SECTION,
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
			"VALUES" => $arUF_SECTION,
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
			"VALUES" => $arUF_SECTION,
		),		
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>86400),
		
	),
);
?>