<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

if (!defined("WIZARD_TEMPLATE_ID"))
	return;

$templateDir = BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID; //."_".WIZARD_THEME_ID;

CopyDirFiles(
	WIZARD_THEME_ABSOLUTE_PATH,
	$_SERVER["DOCUMENT_ROOT"].$templateDir,
	$rewrite = true, 
	$recursive = true,
	$delete_after_copy = false,
	$exclude = "description.php"
);


COption::SetOptionString("yenisite.market", "color_scheme", WIZARD_THEME_ID);
COption::SetOptionString(CYSBitronicSettings::getModuleId(), "COLOR_SCHEME", WIZARD_THEME_ID, WIZARD_SITE_ID);

//if (CModule::IncludeModule(CYSBitronicSettings::getModuleId()))
//{
//    CYSBitronicSettings::setSetting("color_scheme", WIZARD_THEME_ID, TRUE);
//}
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].$templateDir."/header.php", Array("THEME" => WIZARD_THEME_ID."."));

//Color scheme for main.interface.grid/form
//require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".strToLower($GLOBALS["DB"]->type)."/favorites.php");
//CUserOptions::SetOption("main.interface", "global", array("theme" => WIZARD_THEME_ID), true);
?>
