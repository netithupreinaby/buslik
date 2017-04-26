<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");
function custom_RemoveDir($path)
{
	if(file_exists($path) && is_dir($path))
	{
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle))) 
		{
			if ($file!='.' && $file!='..')// исключаем папки с назварием '.' и '..' 
			{
				$tmpPath=$path.'/'.$file;
				chmod($tmpPath, 0777);
				
				if (is_dir($tmpPath))
	  			{  // если папка
					custom_RemoveDir($tmpPath);
			   	} 
	  			else 
	  			{ 
	  				if(file_exists($tmpPath))
					{
						// удаляем файл 
	  					unlink($tmpPath);
					}
	  			}
			}
		}
		closedir($dirHandle);
		
		// удаляем текущую папку
		if(file_exists($path))
		{
			rmdir($path);
		}
	}
	else
	{
		;//echo "Удаляемой папки не существует или это файл!";
	}
}

// add new IB
CModule::IncludeModule("iblock");
$res = CIBlock::GetList(
	Array("ID"=>"ASC"), 
	Array("CODE"=>"yenisite_set_groups"))->Fetch();
if(!$res['ID'])
{
	$ib = new CIBlock;
	$arFields = Array(
		"ACTIVE" => 'Y',
		"NAME" => GetMessage('IBLOCK_NAME_COMPLETE_SET'),
		"CODE" => 'yenisite_set_groups',
		"IBLOCK_TYPE_ID" => 'dict',
		"SITE_ID" => Array(WIZARD_SITE_ID),
		"SORT" => 500,
	);
	$ID = $ib->Add($arFields);
}
if (!defined("WIZARD_TEMPLATE_ID"))
	return;
	


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".CYSBitronicSettings::getModuleId()."/install/version.php");
$ver = $arModuleVersion["VERSION"];


// delete old templates
// WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID = /bitrix/wizards/yenisite/bitronic/site/templates/bitronic
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID.'/components/yenisite/catalog.basket');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID.'/components/yenisite/catalog.basket.small');
unlink($_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID.'/components/yenisite/catalog.section.all/block/component_epilog.php');
// delete old components
$module_dir = "/bitrix/modules/".CYSBitronicSettings::getModuleId()."/install/wizards/yenisite/bitronic/" ;
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.basket' );
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.basket.small' );
unlink(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.section.all/block/component_epilog.php' );
unlink(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.section.all/block/component_epilog.php' );
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public/ru/bitrix/components/yenisite/catalog.filter_complete' );
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.filter_complete/.default' );
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/yenisite/catalog.filter_complete/old.default' );
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/bitrix/catalog/catalog/bitrix/catalog.compare.list');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/bitrix/catalog.smart.filter' );

custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public/ru/news/rss');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public_one/ru/news/rss');

custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public/ru/bitrix/components/yenisite/catalog.section.all');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public_one/ru/bitrix/components/yenisite/catalog.section.all');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public/ru/bitrix/components/yenisite/bitronic.worktime');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public_one/ru/bitrix/components/yenisite/bitronic.worktime');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public/ru/bitrix/components/yenisite/stickers');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/public_one/ru/bitrix/components/yenisite/stickers');
custom_RemoveDir(WIZARD_ABSOLUTE_PATH.'/site/templates/bitronic/components/bitrix/catalog/catalog/bitrix/catalog.compare.result');

unlink($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/yenisite/catalog.section.all/block/component_epilog.php' );
unlink($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/yenisite/catalog.section.all/block/component_epilog.php' );
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/yenisite/catalog.filter_complete/.default' );
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/yenisite/catalog.filter_complete/old.default' );
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/bitrix/components/yenisite/catalog.filter_complete') ;

custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/bitrix/catalog/catalog/bitrix/catalog.compare.list');

custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/news/rss');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/news/rss');

custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/bitrix/components/yenisite/catalog.section.all');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/bitrix/components/yenisite/catalog.section.all');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/bitrix/components/yenisite/bitronic.worktime');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/bitrix/components/yenisite/bitronic.worktime');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/bitrix/components/yenisite/stickers');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/bitrix/components/yenisite/stickers');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/templates/bitronic/components/bitrix/catalog/catalog/bitrix/catalog.compare.result');

custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/new');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/new');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/hit');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/hit');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/sale');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/sale');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public/ru/bestseller');
custom_RemoveDir($_SERVER["DOCUMENT_ROOT"].$module_dir.'site/public_one/ru/bestseller');
// end delete
// #######################################//
// ###### START RENAME OLD SPEC DIR ######//
// #######################################//
if(is_dir(WIZARD_SITE_PATH.'/new/'))
	rename(WIZARD_SITE_PATH.'/new/', WIZARD_SITE_PATH.'/new_old/');
if(is_dir(WIZARD_SITE_PATH.'/hit/'))
	rename(WIZARD_SITE_PATH.'/hit/', WIZARD_SITE_PATH.'/hit_old/');
if(is_dir(WIZARD_SITE_PATH.'/sale/'))
	rename(WIZARD_SITE_PATH.'/sale/', WIZARD_SITE_PATH.'/sale_old/');
if(is_dir(WIZARD_SITE_PATH.'/bestseller/'))
	rename(WIZARD_SITE_PATH.'/bestseller/', WIZARD_SITE_PATH.'/bestseller_old/');
// ###### END RENAME OLD SPEC DIR ########//
// #######################################//

// #################################################### //
// ### rules for 4PU of component yenisite:mainspec	### //
// #################################################### //
$arUrlRewrite = array(); 
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}
$arNewUrlRewrite = array(
	array(
		"CONDITION"	=>	"#^/new/(.*)#",
		"RULE"	=>	"$1&ys_ms_sef=y&tab_block=NEW",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/hit/(.*)#",
		"RULE"	=>	"$1&ys_ms_sef=y&tab_block=HIT",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/sale/(.*)#",
		"RULE"	=>	"$1&ys_ms_sef=y&tab_block=SALE",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/bestseller/(.*)#",
		"RULE"	=>	"$1&ys_ms_sef=y&tab_block=BESTSELLER",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	
	//simple + page
	array(
		"CONDITION"	=>	"#^/new/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$2&PAGEN_1=$1&ys_ms_sef=y&tab_block=NEW",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/hit/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$2&PAGEN_1=$1&ys_ms_sef=y&tab_block=HIT",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/sale/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$2&PAGEN_1=$1&ys_ms_sef=y&tab_block=SALE",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/bestseller/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$2&PAGEN_1=$1&ys_ms_sef=y&tab_block=BESTSELLER",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	
	//simple + sort
	array(
		"CONDITION"	=>	"#^/new/sort-(.*[^-])-(.*)/(.*)#",
		"RULE"	=>	"$3&order=$1&by=$2&ys_ms_sef=y&tab_block=NEW",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/hit/sort-(.*[^-])-(.*)/(.*)#",
		"RULE"	=>	"$3&order=$1&by=$2&ys_ms_sef=y&tab_block=HIT",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/sale/sort-(.*[^-])-(.*)/(.*)#",
		"RULE"	=>	"$3&order=$1&by=$2&ys_ms_sef=y&tab_block=SALE",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/bestseller/sort-(.*[^-])-(.*)/(.*)#",
		"RULE"	=>	"$3&order=$1&by=$2&ys_ms_sef=y&tab_block=BESTSELLER",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	
	//simple + sort + page
	array(
		"CONDITION"	=>	"#^/new/sort-(.*[^-])-(.*)/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$4&order=$1&by=$2&PAGEN_1=$3&ys_ms_sef=y&tab_block=NEW",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/hit/sort-(.*[^-])-(.*)/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$4&order=$1&by=$2&PAGEN_1=$3&ys_ms_sef=y&tab_block=HIT",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/sale/sort-(.*[^-])-(.*)/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$4&order=$1&by=$2&PAGEN_1=$3&ys_ms_sef=y&tab_block=SALE",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
	array(
		"CONDITION"	=>	"#^/bestseller/sort-(.*[^-])-(.*)/page-([0-9]+)/(.*)#",
		"RULE"	=>	"$4&order=$1&by=$2&PAGEN_1=$3&ys_ms_sef=y&tab_block=BESTSELLER",
		"ID"	=>	"yenisite:main_spec",
		"PATH"	=>	"/yenisite.main_spec/index.php",
	),
);
foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}
// #################################################### //

// Copy temlate dir
$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver;
CopyDirFiles(
	$_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID,
	$bitrixTemplateDir,
	$rewrite = true,
	$recursive = true, 
	$delete_after_copy = false,
	$exclude = "themes"
);

//Attach template to default site
$obSite = CSite::GetList($by = "def", $order = "desc", Array("LID" => WIZARD_SITE_ID));
if ($arSite = $obSite->Fetch())
{
	$arTemplates = Array();
	$found = false;
	$foundEmpty = false;
	$obTemplate = CSite::GetTemplateList($arSite["LID"]);
	while($arTemplate = $obTemplate->Fetch())
	{
		// copy template_styles.css from previous template
		if(strpos($arTemplate["TEMPLATE"], WIZARD_TEMPLATE_ID."_") !== false)
		{
			CopyDirFiles(
				$_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".$arTemplate["TEMPLATE"]."/template_styles.css",
				$_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/template_styles.css",
				$rewrite = true,
				$recursive = true, 
				$delete_after_copy = false
			);
		}
		if(!$found && strlen(trim($arTemplate["CONDITION"]))<=0)
		{
			$arTemplate["TEMPLATE"] = WIZARD_TEMPLATE_ID."_".$ver;
			$found = true;
		}
		if($arTemplate["TEMPLATE"] == "empty")
		{
			$foundEmpty = true;
			continue;
		}
		$arTemplates[]= $arTemplate;
	}

	if (!$found)
		$arTemplates[]= Array("CONDITION" => "", "SORT" => 150, "TEMPLATE" => WIZARD_TEMPLATE_ID."_".$ver);

	$arFields = Array(
		"TEMPLATE" => $arTemplates,
		"NAME" => $arSite["NAME"],
	);

	$obSite = new CSite();
	$obSite->Update($arSite["LID"], $arFields);
}
COption::SetOptionString("main", "wizard_template_id", WIZARD_TEMPLATE_ID."_".$ver, false, WIZARD_SITE_ID);

// ################################ //
// ### CREATE NEED MENU TYPE	### //
// ################################ //
$arMenuType = GetMenuTypes(WIZARD_SITE_ID);
if(!array_key_exists('help', $arMenuType))
	$arMenuType['help'] = GetMessage('MENU_TYPE_HELP');
if(!array_key_exists('user', $arMenuType))
	$arMenuType['user'] = GetMessage('MENU_TYPE_USER');
if(!array_key_exists('userlight', $arMenuType))
	$arMenuType['userlight'] = GetMessage('MENU_TYPE_USERLIGHT');
SetMenuTypes($arMenuType, WIZARD_SITE_ID);

if(CModule::IncludeModule("sale"))
{
	if(!file_exists(WIZARD_SITE_PATH."/.user.menu.php"))
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/.user.menu.php",
			WIZARD_SITE_PATH."/.user.menu.php",
			$rewrite = false,
			$recursive = false,
			$delete_after_copy = false
		);
	}
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.user.menu.php", Array(
		"ORDER_HISTORY" => GetMessage("MENU_USER_ORDER_HISTORY"),
		"PROFILES" => GetMessage("MENU_USER_PROFILES"),
		"PROFILE" => GetMessage("MENU_USER_PROFILE"),
		"SUBSCRIBE" => GetMessage("MENU_USER_SUBSCRIBE"),
		"LOGOUT" => GetMessage("MENU_USER_LOGOUT"),
	));
}
else
{
	if(!file_exists(WIZARD_SITE_PATH."/.userlight.menu.php"))
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/.userlight.menu.php",
			WIZARD_SITE_PATH."/.userlight.menu.php",
			$rewrite = false,
			$recursive = false,
			$delete_after_copy = false
		);
	}
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.userlight.menu.php", Array(
		"ORDER_HISTORY" => GetMessage("MENU_USER_ORDER_HISTORY"),
		"PROFILE" => GetMessage("MENU_USER_PROFILE"),
		"LOGOUT" => GetMessage("MENU_USER_LOGOUT"),
	));
}
// ### END CREATE NEED MENU TYPE ###//
// #################################//
?>
