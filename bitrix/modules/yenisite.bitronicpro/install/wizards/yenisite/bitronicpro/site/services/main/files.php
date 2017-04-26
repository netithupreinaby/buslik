<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if (!defined("WIZARD_SITE_DIR"))
	return;

CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

if(COption::GetOptionString("store", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
{
	$wizard =& $this->GetWizard();

	if($wizard->GetVar('siteName')){
	    $templateDir = $_SERVER[DOCUMENT_ROOT].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID;
	    die($templateDir);
        CWizardUtil::ReplaceMacros($templateDir."/modules/logo-header.php", array("SITE_NAME" => $wizard->GetVar("siteName")));
	}

	if($wizard->GetVar('rewriteIndex', true)){
		if($wizard->GetVar('siteLogoSet', true)){
			CopyDirFiles(
				WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/_index_.php",
				WIZARD_SITE_PATH."/_index.php",
				$rewrite = true,
				$recursive = true,
				$delete_after_copy = false
			);
		} else {
			CopyDirFiles(
				WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/_index.php",
				WIZARD_SITE_PATH."/_index.php",
				$rewrite = true,
				$recursive = true,
				$delete_after_copy = false
			);
		}
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
	}
	return;
}


$wizard =& $this->GetWizard();

$architect = $wizard->GetVar("architect", true);
$redaction = $wizard->GetVar("redaction", true);
$demo_install = $wizard->GetVar("demo_install");

$arExclude = array(".", "..", "bitrix");
if($demo_install != 'Y')
{
	//$arExclude[] = 'catalog';
	$arExclude[] = 'computers-and-laptops';
}


if($architect == "multi")
	$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"); 
else
	$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public_one/".LANGUAGE_ID."/"); 	

// #######################################//
// ###### START DELETE OLD SPEC DIR ######//
// #######################################//
DeleteDirFilesEx(WIZARD_RELATIVE_PATH .'/site/public/ru/new');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH .'/site/public_one/ru/new');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH .'/site/public/ru/hit');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH.'/site/public_one/ru/hit');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH.'/site/public/ru/sale');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH.'/site/public_one/ru/sale');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH.'/site/public/ru/bestseller');
DeleteDirFilesEx(WIZARD_RELATIVE_PATH.'/site/public_one/ru/bestseller');
// ###### END DELETE OLD SPEC DIR ########//
// #######################################//

$handle = @opendir($path);
if ($handle)
{
	while ($file = readdir($handle))
	{
		if (in_array($file, $arExclude))
			continue; 

		CopyDirFiles(
			$path.$file,
			WIZARD_SITE_PATH."/".$file,
			$rewrite = true, 
			$recursive = true,
			$delete_after_copy = false,
			$exclude = "bitrix"
		);
		if($wizard->GetVar('siteLogoSet', true)){
			CopyDirFiles(
				WIZARD_SITE_PATH."/_index_.php",
				WIZARD_SITE_PATH."/_index.php",
				$rewrite = true,
				$recursive = true,
				$delete_after_copy = true
			);
		}
	}
}

// ############################################//
// ###### START COPY BITRONIC COMPONENTS ######//
// ############################################//	
$path .= 'bitrix/components/yenisite/';
$handle = @opendir($path);
if ($handle)
{
	while ($file = readdir($handle))
	{
		CopyDirFiles(
			$path.$file,
			$_SERVER['DOCUMENT_ROOT']."/bitrix/components/yenisite/".$file,
			$rewrite = true, 
			$recursive = true,
			$delete_after_copy = false
		);
	}
}
// ###### END COPY BITRONIC COMPONENTS ########//
// ############################################//	

WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."about/", Array("SALE_EMAIL" => $wizard->GetVar("shopEmail")));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."about/delivery/", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));

copy(WIZARD_THEME_ABSOLUTE_PATH."/favicon.ico", WIZARD_SITE_PATH."favicon.ico");

$arUrlRewrite = array(); 
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}
CYSBitronicSettings::setSetting("SEF", "Y", true);

if ($architect != "multi") {
	$arNewUrlRewrite = array(
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
				"RULE" => "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$6&page_count=$5",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page-([0-9]+)/#",
				"RULE" => "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$5",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/#",
				"RULE" => "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/#",
				"RULE" => "SECTION_CODE=$1&order=$3&by=$4&view=$2",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/page-([0-9]+)/#",
				"RULE" => "SECTION_CODE=$1&PAGEN_1=$2",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/page_count-([0-9]+)/#",
				"RULE" => "SECTION_CODE=$1&page_count=$2",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/sort-(.*[^-])-(.*)/#",
				"RULE" => "SECTION_CODE=$1&order=$2&by=$3",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.*)/view-(\\w*)/#",
				"RULE" => "SECTION_CODE=$1&view=$2",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.+)/(.+).html(.*)",
				"RULE" => "SECTION_CODE=$1&ELEMENT_CODE=$2",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/compare/(.*)/(.*)#",
				"RULE" => "action=COMPARE&compareQuery=$1",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.+)/(.+)#",
				"RULE" => "SECTION_CODE=$1",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			array(
				"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/(.+)/#",
				"RULE" => "SECTION_CODE=$1",
				"ID" => "",
				"PATH" => WIZARD_SITE_DIR."catalog/index.php",
			),
			
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."campaigns/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:news",
				"PATH"	=>	 WIZARD_SITE_DIR."campaigns/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."news/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:news",
				"PATH"	=>	 WIZARD_SITE_DIR."news/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:catalog",
				"PATH"	=>	 WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."catalog/#",
				"RULE"	=>	"",
				"ID"	=>	"",
				"PATH"	=>	 WIZARD_SITE_DIR."catalog/index.php",
			), 
	); 	
} else {
	$arNewUrlRewrite = array(
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."campaigns/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:news",
				"PATH"	=>	 WIZARD_SITE_DIR."campaigns/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."news/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:news",
				"PATH"	=>	 WIZARD_SITE_DIR."news/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:catalog",
				"PATH"	=>	 WIZARD_SITE_DIR."computers-and-laptops/tablet-pc/index.php",
			), 
			array(
				"CONDITION"	=>	"#^".WIZARD_SITE_DIR."catalog/#",
				"RULE"	=>	"",
				"ID"	=>	"bitrix:catalog",
				"PATH"	=>	 WIZARD_SITE_DIR."catalog/index.php",
			), 
	); 	
}

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}

function ___writeToAreasFile($fn, $text)
{
	if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
		@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($fn, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($fn, BX_FILE_PERMISSIONS);
}

$wizard =& $this->GetWizard();

if($wizard->GetVar('siteNameSet', true)){
	___writeToAreasFile(WIZARD_SITE_PATH."include_areas/company_name.php", $wizard->GetVar("siteName"));
}
___writeToAreasFile(WIZARD_SITE_PATH."include_areas/copyright.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include_areas/schedule.php", $wizard->GetVar("siteSchedule"));
___writeToAreasFile(WIZARD_SITE_PATH."include_areas/telephone.php", $wizard->GetVar("siteTelephone"));

if($wizard->GetVar('siteLogoSet', true)){
	$siteLogo = $wizard->GetVar("siteLogo");
	if($siteLogo>0)
	{
		$ff = CFile::GetByID($siteLogo);
		if($zr = $ff->Fetch())
		{
			$strOldFile = str_replace("//", "/", WIZARD_SITE_ROOT_PATH."/".(COption::GetOptionString("main", "upload_dir", "upload"))."/".$zr["SUBDIR"]."/".$zr["FILE_NAME"]);
			@copy($strOldFile, WIZARD_SITE_PATH."include_areas/logo.jpg");
			___writeToAreasFile(WIZARD_SITE_PATH."include_areas/company_logo.php", '<a href="'.WIZARD_SITE_DIR.'"><img src="'.WIZARD_SITE_DIR.'include_areas/logo.jpg"  /></a>');
			CFile::Delete($siteLogo);
		}
	}
	else if(!file_exists(WIZARD_SITE_PATH."include_areas/bx_default_logo.jpg") || WIZARD_INSTALL_DEMO_DATA)
	{
		copy(WIZARD_THEME_ABSOLUTE_PATH."/lang/".LANGUAGE_ID."/logo.jpg", WIZARD_SITE_PATH."include_areas/bx_default_logo.jpg");
		___writeToAreasFile(WIZARD_SITE_PATH."include_areas/company_logo.php",  '<a href="'.WIZARD_SITE_DIR.'"><img src="'.WIZARD_SITE_DIR.'include_areas/bx_default_logo.jpg"  /></a>');
	}
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => $wizard->GetVar("siteMetaDescription")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => $wizard->GetVar("siteMetaKeywords")));

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/developers.php", Array("THEME" => WIZARD_THEME_ID."."));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/logo.php", Array("THEME" =>WIZARD_THEME_ID."."));

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/address.php", Array("ADDRESS" =>$wizard->GetVar("shopAdr").", ".$wizard->GetVar("shopLocation")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/copy.php", Array("COPY" =>$wizard->GetVar("siteCopy")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/worktime.php", Array("WORKTIME" =>$wizard->GetVar("siteTime")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/phones.php", Array("PHONES" =>$wizard->GetVar("siteTelephone")));

$templateDir = $_SERVER[DOCUMENT_ROOT].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID;
?>
