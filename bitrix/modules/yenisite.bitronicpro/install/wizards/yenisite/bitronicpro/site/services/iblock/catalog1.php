<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

/*
if(!CModule::IncludeModule("catalog"))
	return;
	
if(!CModule::IncludeModule("sale"))
	return;
*/	

	
//if(COption::GetOptionString("store", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
//	return;
	
//$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml";


$wizard =& $this->GetWizard();

$architect = $wizard->GetVar("architect", true);
$redaction = $wizard->GetVar("redaction", true);
$demo_install = $wizard->GetVar("demo_install");


CModule::IncludeModule("catalog");

if($demo_install != 'Y' && $architect != 'multi')
{
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog2_empty-".LANGUAGE_ID.".xml";
	$iblockCode = "555"; 
	$iblockType = "catalog";
}
elseif($demo_install == "Y")
{	
	if($architect == 'multi'){

		if(SITE_CHARSET == "UTF-8")
		{
		 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog1-utf.xml";
		}
		else{
		 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog1.xml";
		}
		
		$iblockCode = "50"; 
		$iblockType = WIZARD_SITE_ID."_computers_and_laptops"; 

	}else{

		if(SITE_CHARSET == "UTF-8")
		{
		 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog2-".LANGUAGE_ID."-utf.xml";
		}
		else{
		 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog2-".LANGUAGE_ID.".xml";
		}
		
		$iblockCode = "555"; 
		$iblockType = "catalog";
	}
}
else
	return;

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 
if ($arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"]; 
	/*if (WIZARD_INSTALL_DEMO_DATA)
	{
		$arCatalog = CCatalog::GetByIDExt($arIBlock["ID"]); 
		if (is_array($arCatalog) && (in_array($arCatalog['CATALOG_TYPE'],array('P','X'))) == true) 
		{
			CCatalog::UnLinkSKUIBlock($arIBlock["ID"]);
			CIBlock::Delete($arCatalog['OFFERS_IBLOCK_ID']);
		}
		CIBlock::Delete($arIBlock["ID"]); 
		$iblockID = false; 
		COption::SetOptionString("sitestore", "demo_deleted", "N", "", WIZARD_SITE_ID);
	}*/
}


global $IBID, $IBID1;

if($architect == 'multi'){

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("CIam", "add"));
class CIam{
    function add($arFields)
    {        
        $res = CIBlock::GetByID($arFields[IBLOCK_ID])->Fetch();
        if($res[XML_ID] == "50"){
            global $IBID;
            $IBID = $arFields[IBLOCK_ID];
			if(CModule::IncludeModule("catalog"))
            if(!CCatalog::GetList(array(), array("IBLOCK_ID" => $arFields[IBLOCK_ID]))->Fetch()){        
                if(!CCatalog::Add(array("IBLOCK_ID" => $arFields[IBLOCK_ID]))) return;
            }
        }      
        else return;
    }
}

}else{

	AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("CIam", "add"));
	class CIam{
		function add($arFields)
		{        
			$res = CIBlock::GetByID($arFields[IBLOCK_ID])->Fetch();
			if($res[XML_ID] == "555"){
				global $IBID;
				$IBID = $arFields[IBLOCK_ID];
				if(CModule::IncludeModule("catalog"))
				if(!CCatalog::GetList(array(), array("IBLOCK_ID" => $arFields[IBLOCK_ID]))->Fetch()){        
					if(!CCatalog::Add(array("IBLOCK_ID" => $arFields[IBLOCK_ID]))) return;
				}
			}      
			else return;
		}
	}

}


global $IBID, $IBID1;

$iblockID = $IBID?$IBID:$iblockID;
$iblockID1 = $IBID1?$IBID1:$iblockID1;

if(CModule::IncludeModule("catalog")){

	$dbResultList = CCatalogGroup::GetList(Array(), Array("CODE" => "BASE"));
	if(!($arRes = $dbResultList->Fetch()))
	{
		$arFields = Array();
		$rsLanguage = CLanguage::GetList($by, $order, array());
		while($arLanguage = $rsLanguage->Fetch())
		{
			WizardServices::IncludeServiceLang("catalog.php", $arLanguage["ID"]);
			$arFields["USER_LANG"][$arLanguage["ID"]] = GetMessage("WIZ_PRICE_NAME");
		}
		$arFields["BASE"] = "Y";
		$arFields["SORT"] = 100;
		$arFields["NAME"] = "BASE";
		$arFields["USER_GROUP"] = Array(1, 2);
		$arFields["USER_GROUP_BUY"] = Array(1, 2);
		CCatalogGroup::Add($arFields);
	}
	else
	{
		$arFields["BASE"] = "Y";
		CCatalogGroup::Update($arRes["ID"], $arFields);
	}
}

ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");	


if($architect == 'multi'){

	if(!$iblockID){
		$ib = CIBlock::GetList(array(), array("XML_ID" => "50"))->Fetch();
		$iblockID = $ib[ID];
	}
}else{

	if(!$iblockID){
		$ib = CIBlock::GetList(array(), array("XML_ID" => "555"))->Fetch();
		$iblockID = $ib[ID];
	}


}


$arProperty = Array();
$dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProperty->Fetch())
	$arProperty[$arProp["CODE"]] = $arProp["ID"];
	

$templateDir = $_SERVER[DOCUMENT_ROOT].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID;


if(CModule::IncludeModule('forum') && $demo_install == "Y"){

	$arFields = array("SORT" => "1000");
	$arSysLangs = array("ru", "en");
	for ($i = 0; $i<count($arSysLangs); $i++)
	{
	$arFields["LANG"][] = array(
	    "LID" => $arSysLangs[$i],
	    "NAME" => "Comments",	 
        "XML_ID"   => "12121"
	    );
	}
	$ID = CForumGroup::Add($arFields);

	$arFields = Array(
	   "XML_ID" => "21212",
	   "ACTIVE" => "Y",
	   "NAME" => "Item review",
	   "DESCRIPTION" => "Forum with comments",
	   "FORUM_GROUP_ID" => $ID,
	   "GROUP_ID" => array(1 => "Y", 2 => "Y"), 
	   /*"SITES" => array(
	       '"'.WIZARD_SITE_ID.'"' => "/url/"
		)*/
	);
		
	$db_res = CSite::GetList($lby="sort", $lorder="asc");
    while ($res = $db_res->Fetch()):
        $arFields["SITES"][$res["LID"]] = "/".$res["LID"]."/forum/#FORUM_ID#/#TOPIC_ID#/";
    endwhile;

	$ID = CForumNew::Add($arFields);
}		

if($architect == 'multi'){	
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/computers-and-laptops/tablet-pc/index.php", array("IBLOCK_ID" => $iblockID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/computers-and-laptops/tablet-pc/index.php", array("SITE_DIR" => WIZARD_SITE_DIR));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/computers-and-laptops/tablet-pc/index.php", array("FORUM_ID" => $ID));
	$SELF_FOLDER = WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/";
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/computers-and-laptops/tablet-pc/index.php", array("SEF_FOLDER" => $SELF_FOLDER));

	CModule::IncludeModule('iblock');

	$ib = new CIBlock;
	$arFields = Array(		 
		"LIST_PAGE_URL" => WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/",		  
		"DETAIL_PAGE_URL" => WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/#SECTION_CODE#/#ELEMENT_CODE#.html",
	);
	$res = $ib->Update($iblockID, $arFields);

}else{
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/index.php", array("CATALOG_IBLOCK_ID" => $iblockID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", array("IBLOCK_ID" => $iblockID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.catalog.menu_ext.php", array("IBLOCK_ID" => $iblockID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", array("SITE_DIR" => WIZARD_SITE_DIR));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", array("FORUM_ID" => $ID));
}

if(CModule::IncludeModule('catalog') && CModule::IncludeModule('iblock')){
	$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $iblockID), false, false, array("ID"));
	while($el = $res->GetNext()){
		CPrice::SetBasePrice($el["ID"], rand(10000, 100000), 'RUB');
		$arFields = array( "ID" => $el["ID"], "QUANTITY" => 0);
        CCatalogProduct::Add($arFields);
	}
}
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
            "DEFAULT_VALUE" => Array(
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
			"DEFAULT_VALUE" => Array(
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
