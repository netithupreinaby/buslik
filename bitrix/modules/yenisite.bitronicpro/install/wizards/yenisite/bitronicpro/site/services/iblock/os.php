<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;


if(SITE_CHARSET == "UTF-8")
{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/os-utf.xml";
}
else{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/os.xml";
}


$iblockCode = "54"; 
$iblockType = "dict"; 
ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");




$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 
if ($arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"]; 	
}

ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");

?>