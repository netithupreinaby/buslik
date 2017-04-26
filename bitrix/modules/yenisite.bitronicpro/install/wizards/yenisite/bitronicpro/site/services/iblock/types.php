<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
	
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

COption::SetOptionString(CYSBitronicSettings::getModuleId(), 'SEF_UID_1', 'Y', false, WIZARD_SITE_ID );
COption::SetOptionString(CYSBitronicSettings::getModuleId(), 'sef_mode', 'Y', false, WIZARD_SITE_ID );

if(!CModule::IncludeModule("iblock"))
	return;


$wizard =& $this->GetWizard();

$architect = $wizard->GetVar("architect", true);
$redaction = $wizard->GetVar("redaction", true);
	
if($architect == 'multi'){
	
	$arTypes = Array(

		Array(
			"ID" => WIZARD_SITE_ID."_household_appliances",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
			Array(
			"ID" => WIZARD_SITE_ID."_computers_and_laptops",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
			Array(
			"ID" => WIZARD_SITE_ID."_tv_audio_video",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
			Array(
			"ID" => WIZARD_SITE_ID."_automotive",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
			Array(
			"ID" => WIZARD_SITE_ID."_small_appliances",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => WIZARD_SITE_ID."_accessories",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => WIZARD_SITE_ID."_photos_and_videos",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => WIZARD_SITE_ID."_phones_and_communication",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),

		Array(
			"ID" => "news",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 100,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => "dict",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 100,
			"LANG" => Array(),
		),
		
		
	);
}else{


	$arTypes = Array(

		Array(
			"ID" => "catalog",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 300,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => "news",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 100,
			"LANG" => Array(),
		),
		
		Array(
			"ID" => "dict",
			"SECTIONS" => "Y",
			"IN_RSS" => "N",
			"SORT" => 100,
			"LANG" => Array(),
		),
		
		
	);


}


$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];

$iblockType = new CIBlockType;
foreach($arTypes as $arType)
{
	$dbType = CIBlockType::GetList(Array(),Array("=ID" => $arType["ID"]));
	if($dbType->Fetch())
		continue;

	foreach($arLanguages as $languageID)
	{
		WizardServices::IncludeServiceLang("type.php", $languageID);

		$code = strtoupper(str_replace(WIZARD_SITE_ID,"catalog",$arType["ID"]));
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code."_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code."_ELEMENT_NAME");

		if ($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code."_SECTION_NAME");
		
	
		
			
	}
	
	$iblockType->Add($arType);

}

COption::SetOptionString('iblock', 'combined_list_mode', 'Y');
?>
