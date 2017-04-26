<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

if(!CModule::IncludeModule("catalog"))
	return;
	
if(!CModule::IncludeModule("sale"))
	return;
	
	
//if(COption::GetOptionString("store", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
//	return;
	
//$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml";




CModule::IncludeModule("catalog");

if(SITE_CHARSET == "UTF-8")
{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog-utf.xml";
}
else{
 $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/catalog.xml";
}
  



$iblockCode = "14"; 
$iblockType = "catalog_tv_audio_video"; 

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

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("CIam", "add"));
class CIam{
    function add($arFields)
    {        
        $res = CIBlock::GetByID($arFields[IBLOCK_ID])->Fetch();
        if($res[XML_ID] == "14"){
            global $IBID;
            $IBID = $arFields[IBLOCK_ID];
            if(!CCatalog::GetList(array(), array("IBLOCK_ID" => $arFields[IBLOCK_ID]))->Fetch()){        
                if(!CCatalog::Add(array("IBLOCK_ID" => $arFields[IBLOCK_ID]))) return;
            }
        }      
        else return;
    }
}


global $IBID, $IBID1;

$iblockID = $IBID?$IBID:$iblockID;
$iblockID1 = $IBID1?$IBID1:$iblockID1;



$dbResultList = CCatalogGroup::GetList(Array(), Array("CODE" => "BASE"));
if(!($dbResultList->Fetch()))
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



ImportXMLFile($iblockXMLFile, $iblockType, array(WIZARD_SITE_ID), "N", "N");	

if(!$iblockID){
    $ib = CIBlock::GetList(array(), array("XML_ID" => "14"))->Fetch();
    $iblockID = $ib[ID];
}



$arProperty = Array();
$dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProperty->Fetch())
	$arProperty[$arProp["CODE"]] = $arProp["ID"];
	





$templateDir = $_SERVER[DOCUMENT_ROOT].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID;


//CWizardUtil::ReplaceMacros($templateDir."/footer.php", array("CATALOG_IBLOCK_ID" => $iblockID));
//CWizardUtil::ReplaceMacros($templateDir."/header.php", array("CATALOG_IBLOCK_ID" => $iblockID));




if(!CModule::IncludeModule('forum')) return;

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
		
		/*
		$ele = CIBlockElement::GetList(array("rand" => "desc"), array("IBLOCK_ID" => $iblockID), false, array("nTopCount" => 11), array("ID", "NAME"));
		
		$top = new CForumTopic;
		$i = 0;
		while($otz = $ele->Fetch())
		{
		    $i++;
		    $TID = $top->Add(
		        array(
		            "TITLE" => $otz["NAME"],
		            "STATE" => "Y",
    	            "USER_START_NAME" => GetMessage("MA_".$i),
                    "FORUM_ID" => $ID,
                    "SORT" => 100,
                    "APPROVED" => "Y",
                    "LAST_POSTER_NAME" => GetMessage("MA_".$i),
		        )
		    );
		    

            $arFields = Array(
              "POST_MESSAGE" => GetMessage("M_".$i),
              "USE_SMILES" => "N",
              "APPROVED" => "Y",
              "AUTHOR_NAME" => GetMessage("MA_".$i),
              "AUTHOR_ID" => GetMessage("MA_".$i),
              "FORUM_ID" => $ID,
              "TOPIC_ID" => $TID,
              "AUTHOR_IP" => "<no address>",
              "NEW_TOPIC" => "Y"
            );
            CForumMessage::Add($arFields);
            
            CIBlockElement::SetPropertyValueCode($otz[ID], "FORUM_TOPIC_ID", $TID);
		    
		}
		
		
		$e = $GLOBALS['APPLICATION']->GetException();
           if ($e && $str = $e->GetString()):
               echo "Error: ".$str; die();
           else:
               echo "Unknown Error";
           endif;
		
		
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php", array("CATALOG_FORUM_ID" => $ID));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."index.php", array("CATALOG_FORUM_ID" => $ID));
		
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".top.menu.php", array("SITE_ID" => WIZARD_SITE_ID));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."about/.left.menu.php", array("SITE_ID" => WIZARD_SITE_ID));		
		
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", array("CATALOG_FORUM_ID" => $ID));
		
		CWizardUtil::ReplaceMacros($templateDir."/modules/logo-header.php", array("SITENAMESUR" => $wizard->GetVar("siteName")));
        CWizardUtil::ReplaceMacros($templateDir."/modules/phone-footer.php", array("SITEPHONESUR" => $wizard->GetVar("siteTelephone")));
        CWizardUtil::ReplaceMacros($templateDir."/modules/contacts-header.php", array("SITEPHONESUR" => $wizard->GetVar("siteTelephone")));
        CWizardUtil::ReplaceMacros($templateDir."/modules/contacts-header.php", array("SITETIMESUR" => $wizard->GetVar("siteTime")));
        CWizardUtil::ReplaceMacros($templateDir."/modules/phone-footer.php", array("SITETIMESUR" => $wizard->GetVar("siteTime")));
		*/
        
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/tv-audio-video/mediacentr/index.php", array("IBLOCK_ID" => $iblockID));
		
		
?>
