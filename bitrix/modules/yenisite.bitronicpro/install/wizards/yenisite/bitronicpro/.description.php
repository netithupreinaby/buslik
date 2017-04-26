<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!defined("WIZARD_DEFAULT_SITE_ID") && !empty($_REQUEST["wizardSiteID"])) 
	define("WIZARD_DEFAULT_SITE_ID", $_REQUEST["wizardSiteID"]); 

$arWizardDescription = Array(
	"NAME" => GetMessage("PORTAL_WIZARD_NAME"), 
	"DESCRIPTION" => GetMessage("PORTAL_WIZARD_DESC"), 
	"VERSION" => "1.17.5",
	"START_TYPE" => "WINDOW",
	"WIZARD_TYPE" => "INSTALL",
	"IMAGE" => "/images/".LANGUAGE_ID."/solution.png",
	"PARENT" => "wizard_sol",
	"TEMPLATES" => Array(
		Array("SCRIPT" => "wizard_sol")
	),
	"STEPS" => array(),
);
//if(COption::GetOptionString("store", "wizard_installed", "N", false, WIZARD_SITE_ID) == "Y")
//{


if(COption::GetOptionString("yenisite.market", "color_scheme", "N") == "N"){


	if(defined("WIZARD_DEFAULT_SITE_ID"))
	{
		if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
			if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))
					$arWizardDescription["STEPS"] = Array("SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep" ,"FinishStep");
			else
					$arWizardDescription["STEPS"] = Array("SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "DataInstallStep", "FinishStep");
	}
	else
	{

		if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
			if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))
				$arWizardDescription["STEPS"] = Array("SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep" ,"FinishStep");
			else
					$arWizardDescription["STEPS"] = Array("SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "DataInstallStep" , "FinishStep");
		
	}
}
else{

	if(defined("WIZARD_DEFAULT_SITE_ID"))
	{
		if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
			if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))
					$arWizardDescription["STEPS"] = Array("SelectInstallType", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep" ,"FinishStep");
			else
					$arWizardDescription["STEPS"] = Array("SelectInstallType", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "DataInstallStep", "FinishStep");
	}
	else
	{
		if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
			if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))
				$arWizardDescription["STEPS"] = Array("SelectInstallType", "SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "ShopSettings", "PersonType", "PaySystem", "DataInstallStep" ,"FinishStep");
			else
					$arWizardDescription["STEPS"] = Array("SelectInstallType", "SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "SiteRedaction", "SiteArchitect", "DataInstallStep" , "FinishStep");
		
	}

}	
/*
}
else
{
	if(defined("WIZARD_DEFAULT_SITE_ID"))
		$arWizardDescription["STEPS"] = Array("SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "DataInstallStep" ,"FinishStep");
	else
		$arWizardDescription["STEPS"] = Array("SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "DataInstallStep" ,"FinishStep");
}
*/
?>
