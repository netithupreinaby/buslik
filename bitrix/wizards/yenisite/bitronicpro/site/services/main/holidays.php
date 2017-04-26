<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock"))
{
    die("ERROR: can't include iblock module");
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
	$res_tib = $obBlocktype->Add($arFields);
}
if(!$res_tib)
    $res_tib = "dict";

//Check IBlock exist
$code = "holiday";
$test_ib = CIBlock::GetList(Array(), Array('TYPE' => $res_tib, 'CODE' => $code . "%"), false);
$test_ib_flag = true;
$res_ib = false;
while($ar_res = $test_ib->Fetch())
{
	$test_ib_flag = false;
}
if ($test_ib_flag)
{
	//Create IBlock
	$ib = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"NAME" => GetMessage("HOLIDAYS"),
		"CODE" => $code,
		"LIST_PAGE_URL" => "",
		"DETAIL_PAGE_URL" => "",
		"IBLOCK_TYPE_ID" => $res_tib,
		"INDEX_ELEMENT" => "N",
		"SITE_ID" => Array(WIZARD_SITE_ID),
		"SORT" => "500",
		"PICTURE" => "",
		"DESCRIPTION" => "",
		"DESCRIPTION_TYPE" => "text",
		"GROUP_ID" => Array("2"=>"R"),
	);

	$res_ib = $ib->Add($arFields);
}

if(!$res_ib)
{
	$hres = CIBlock::GetList(
		Array(), 
		Array(
			'TYPE'=>$res_tib, 
			"CODE"=>$code
		), true
	);
	if($har_res = $hres->Fetch())
		$res_ib = $har_res['ID'];
}

if($res_ib)
{

	//link holiday property
	$ibp = new CIBlockProperty();
	$properties = CIBlockProperty::GetList(array(), Array("CODE"=>"HOLIDAY", "PROPERTY_TYPE"=>"E"));
	while ($prop_fields = $properties->GetNext())
	{
		$ibp->Update($prop_fields["ID"], array('LINK_IBLOCK_ID' => $res_ib));
	}
	
	for ($i = 1; $i <= 9; $i++) 
	{//FIlling
		$count = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$res_ib,"CODE"=>"HOLIDAY_".$i), Array(), false);

		if($count == 0)
		{
			$ib_elem = new CIBlockElement;
			$arFields = Array(
				"NAME" => GetMessage("HOLIDAY_".$i),
				"CODE" => "HOLIDAY_".$i,
				"IBLOCK_ID" => $res_ib,
				"DETAIL_PICTURE" => CFile::MakeFileArray(WIZARD_ABSOLUTE_PATH."/site/services/main/holidays/images/bigIcon".$i.".png")
			);
			$res_elem = $ib_elem->Add($arFields);	
		}
	}
}

?>