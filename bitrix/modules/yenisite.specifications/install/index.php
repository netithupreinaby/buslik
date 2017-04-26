<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

Class yenisite_specifications extends CModule
{
	const MODULE_ID = 'yenisite.specifications';
	var $MODULE_ID = 'yenisite.specifications'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';
	
	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("yenisite.specifications_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.specifications_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("yenisite.specifications_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.specifications_PARTNER_URI");
	}

	function InstallEvents()
	{
		
		RegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CSpecifications', 'OnEpilog');
		RegisterModuleDependences('main', 'OnAdminListDisplay', self::MODULE_ID, 'CSpecifications', 'OnAdminListDisplay');
		return true;
	}

	function UnInstallEvents()
	{
		UnRegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CSpecifications', 'OnEpilog');
		UnRegisterModuleDependences('main', 'OnAdminListDisplay', self::MODULE_ID, 'CSpecifications', 'OnAdminListDisplay');
		return true;
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.self::MODULE_ID.'/', $ReWrite = True, $Recursive = True);
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/images', $_SERVER['DOCUMENT_ROOT'].'/bitrix/images/'.self::MODULE_ID.'/', $ReWrite = True, $Recursive = True);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx('/bitrix/js/'.self::MODULE_ID);
		DeleteDirFilesEx('/bitrix/images/'.self::MODULE_ID);
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $errors;
		if (!\Bitrix\Main\Loader::includeModule('yenisite.coreparser')) {
			$errors = Loc::getMessage('yenisite.specifications_UNINS_yenisite.coreparser');
			$APPLICATION->IncludeAdminFile(Loc::getMessage("yenisite.specifications_INSTALL_TITLE"), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.specifications/install/step1.php");
		} else {
			$this->InstallFiles();
			RegisterModule(self::MODULE_ID);
			$this->InstallEvents();
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;
		$this->UnInstallEvents();
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallFiles();
	}
}
?>