<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock"))
{
    die("ERROR: can't include iblock module");
}
if (!CModule::IncludeModule("catalog"))
{
   return;
}

//Create IBlock Type
$type_exist = CIBlockType::GetByID('dict')->GetNext();
if(!$type_exist)
{
	$arFields = Array(
		'ID'=>'dict',
		'SECTIONS'=>'Y',
		'IN_RSS'=>'N',
		'SORT'=>100,
		'LANG'=>Array(
			'en'=>Array(
				'NAME'=>'Dict',
				'SECTION_NAME'=>'Section',
				'ELEMENT_NAME'=>'Elements'
			),
			'ru'=>Array(
				'NAME' => GetMessage("DICT"),
				'SECTION_NAME' => GetMessage("SECTION_NAME"),
				'ELEMENT_NAME' => GetMessage("ELEMENT_NAME")
			)
		)
	);
	$obBlocktype = new CIBlockType;
	$iblockType = $obBlocktype->Add($arFields);
}
if(!$iblockType)
    $iblockType = "dict";
	
//Check IBlock exist
$code = "service";
$test_ib = CIBlock::GetList(Array(), Array('TYPE' => $iblockType, 'CODE' => $code . "%"), false);
$test_ib_flag = true;
$res_ib = false;
while($ar_res = $test_ib->Fetch())
{
	$test_ib_flag = false;
}
if ($test_ib_flag)
{
	//Import IBlock
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/services.xml";
	
	ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");	
}