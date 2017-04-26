<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

$demo_install = $wizard->GetVar("demo_install");
if($demo_install != "Y")
{
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/campaigns/index.php", array("IBLOCK_ID" => "", "CAMPAINGS_COMPONENT_ACTIVE" => "N"));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/campaigns/index.php", array("URL_PATH" => WIZARD_SITE_DIR."campaigns/"));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/index/campaigns.php", array("CAMPAIGNS_IBLOCK_ID" => "", "CAMPAINGS_COMPONENT_ACTIVE" => "N"));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/urlrewrite.php", array("SITE_DIR" => WIZARD_SITE_DIR));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", array("CAMPAIGNS_IBLOCK_ID" => "", "CAMPAINGS_COMPONENT_ACTIVE" => "N"));
	return;
}

if(SITE_CHARSET == "UTF-8")
{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/campaigns-utf.xml";
}
else{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/campaigns.xml";
}


$iblockCode = "7"; 
$iblockType = "news"; 
ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");




$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 
if ($arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"]; 	
}

ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");



CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/campaigns/index.php", array("IBLOCK_ID" => $iblockID, "CAMPAINGS_COMPONENT_ACTIVE" => "Y"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/campaigns/index.php", array("URL_PATH" => WIZARD_SITE_DIR."campaigns/"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/index/campaigns.php", array("CAMPAIGNS_IBLOCK_ID" => $iblockID, "CAMPAINGS_COMPONENT_ACTIVE" => "Y"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/urlrewrite.php", array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", array("CAMPAIGNS_IBLOCK_ID" => $iblockID, "CAMPAINGS_COMPONENT_ACTIVE" => "Y"));


?>

<?
$arFields = array(

			  "FIELDS" => array(
									"CODE" => array(
										"IS_REQUIRED" => "Y",
										"DEFAULT_VALUE" => array(
											"UNIQUE" => "Y",
											"TRANSLITERATION" => "Y",
											"TRANS_LEN" => 100,
											"TRANS_CASE" => "L",
											"TRANS_SPACE" => "-",
											"TRANS_OTHER" => "-",
											"TRANS_EAT" => "Y"
										)
									),
									"SECTION_CODE" => array(
										"IS_REQUIRED" => "Y",
										"DEFAULT_VALUE" => array(
											"UNIQUE" => "Y",
											"TRANSLITERATION" => "Y",
											"TRANS_LEN" => 100,
											"TRANS_CASE" => "L",
											"TRANS_SPACE" => "-",
											"TRANS_OTHER" => "-",
											"TRANS_EAT" => "Y"
										)
									),
									"PREVIEW_PICTURE" => array(
                                                "IS_REQUIRED" => "N",
                                                "DEFAULT_VALUE" => Array
                                                    (
                                                        "FROM_DETAIL" => "Y",
                                                        "SCALE" => "Y",
                                                        "WIDTH" => 160,
                                                        "HEIGHT" => 160,
                                                        "IGNORE_ERRORS" => "Y",
                                                        "METHOD" => "resample",
                                                        "COMPRESSION" => 95,
                                                    )

                                            ),
									"DETAIL_PICTURE" => array(
                                                "IS_REQUIRED" => "N",
                                                "DEFAULT_VALUE" => Array
                                                    (
                                                        "SCALE" => "Y",
                                                        "WIDTH" => 800,
                                                        "HEIGHT" => 600,
                                                        "IGNORE_ERRORS" => "Y",
                                                        "METHOD" => "resample",
                                                        "COMPRESSION" => 95,
                                                    )

                                            )                                            
									
								)

);

CModule::IncludeModule('iblock');
$ib = new CIBlock;
$ib->Update($iblockID, $arFields);
?>
