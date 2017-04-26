<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return false ;
$arIBlockType = CIBlockParameters::GetIBlockTypes();
if($arCurrentValues["IBLOCK_TYPE"])
{
	$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));

	while($arr=$rsIBlock->Fetch())
		$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}
if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$arProperties_LNS	= array() ; // list, number, string

	$dbProperties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"])));
	while ($arProperty = $dbProperties->GetNext())
	{
		if(in_array($arProperty["PROPERTY_TYPE"], array("L", "N", "S")))
			$arProperties_LNS[$arProperty["CODE"]] = $arProperties_ALL[$arProperty["CODE"]] ;
	}
}

$list = array () ;
if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	$def_small = 5; $def_big = 1;
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] [".$arr['w']."x".$arr['h']."]".$arr["NAME"];
		if($arr['w'] == 50 && $arr['h'] == 50)
			$def_small = $arr['id'] ;
		elseif($arr['w'] == 880 && $arr['h'] == 500)
			$def_big = $arr['id'] ;
	}
	$arComponentParameters_resizer = array(
		"RESIZER_SET_SMALL" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_RESIZER_SETS'),
			"TYPE"	 => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $list,
			"DEFAULT" => $def_small
		),
		"RESIZER_SET_BIG" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_RESIZER_SETS'),
			"TYPE"	 => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $list,
			"DEFAULT" => $def_big
		),
	);
}
else{
	$arComponentParameters_resizer = array(
		"IMAGE_WIDTH_SMALL" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_IMAGE_WIDTH_SMALL'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '50'
		),
		"IMAGE_HEIGHT_BIG" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_IMAGE_HEIGHT_BIG'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '50'
		),
		"IMAGE_WIDTH_BIG" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_IMAGE_WIDTH_BIG'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '880'
		),
		"IMAGE_HEIGHT_BIG" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_IMAGE_HEIGHT_BIG'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '550'
		),
	);
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
		"PROPERTIES" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_PROPERTIES'),
			"TYPE" 	 => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arProperties_LNS,
			"DEFAULT" => "",
		),
		"DESCRIPTION" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_DESCRIPTION'),
			"TYPE"	 => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => Array(
				"PREVIEW_TEXT" => GetMessage('COMPLETE_SET_DESCRIPTION_PT'),
				"DETAIL_TEXT" => GetMessage('COMPLETE_SET_DESCRIPTION_DT'),
			),
			"DEFAULT" => "PREVIEW_TEXT"
		),
		"NO_INCLUDE_PRICE" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('COMPLETE_SET_NO_INCLUDE_PRICE'),
			"TYPE"	 => "CHECKBOX",
			"DEFAULT" => ""
		),
	)
);
?>