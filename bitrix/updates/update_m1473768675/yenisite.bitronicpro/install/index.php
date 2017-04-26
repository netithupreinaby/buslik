<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class yenisite_bitronicpro extends CModule
{
	var $MODULE_ID = "yenisite.bitronicpro";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("SCOM_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("SCOM_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
		/*global $DB, $DBType, $APPLICATION;


		RegisterModuleDependences("main", "OnBeforeProlog", "bitrix.sitestore", "CSiteStore", "ShowPanel");
        */
  		RegisterModule("yenisite.bitronicpro");
 		LocalRedirect("/bitrix/admin/wizard_install.php?lang=ru&wizardName=yenisite:bitronicpro&".bitrix_sessid_get());
		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		/*
		global $DB, $DBType, $APPLICATION;

		UnRegisterModuleDependences("main", "OnBeforeProlog", "bitrix.sitestore", "CSiteStore", "ShowPanel"); 		
        */
        UnRegisterModule("yenisite.bitronicpro");
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.bitronicpro/install/wizards/yenisite/bitronicpro", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/yenisite/bitronicpro", true, true);
	
		return true;
	}

	function InstallPublic()
	{
	}

	function UnInstallFiles()
	{
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		$APPLICATION->IncludeAdminFile(GetMessage("SCOM_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.bitronicpro/install/step.php");
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$APPLICATION->IncludeAdminFile(GetMessage("SCOM_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.bitronicpro/install/unstep.php");
	}
}
?>