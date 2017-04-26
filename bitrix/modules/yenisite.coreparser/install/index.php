<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

Class yenisite_coreparser extends CModule
{
	const MODULE_ID = 'yenisite.coreparser';
	var $MODULE_ID = 'yenisite.coreparser'; 
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
		$this->MODULE_NAME = GetMessage("yenisite.coreparser_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.coreparser_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("yenisite.coreparser_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.coreparser_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		return true;
	}

	function UnInstallDB($arParams = array())
	{
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

	function InstallFiles($arParams = array()) {
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/admin')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $item,
						'<' . '? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/' . self::MODULE_ID . '/admin/' . $item . '");?' . '>');
				}
				closedir($dir);
			}
		}
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/modules/' . self::MODULE_ID . '/install/bitrix', $_SERVER["DOCUMENT_ROOT"] . BX_ROOT, true, true);
		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
						continue;

					$dir0 = opendir($p0);
					while (false !== $item0 = readdir($dir0))
					{
						if ($item0 == '..' || $item0 == '.')
							continue;
						DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
					}
					closedir($dir0);
				}
				closedir($dir);
			}
		}
		$p = BX_ROOT.'/js/'.self::MODULE_ID;
		if (is_dir($_SERVER['DOCUMENT_ROOT'].$p)) {
			DeleteDirFilesEx($p);
		}
		if (@file_exists($path = $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/themes/.default/'.self::MODULE_ID.'.css')) {
			unlink($path);
		}
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION, $errors;
		$errors = '';
		if (Loader::includeModule('yenisite.specifications')) {
			$errors .= Loc::getMessage('yenisite.coreparser_INST_specifications') . '<br>';
		}
		if (Loader::includeModule('yenisite.turbo')) {
			$errors .= Loc::getMessage('yenisite.coreparser_INST_turbo') . '<br>';
		}
		if (strlen($errors) > 0) {
			$APPLICATION->IncludeAdminFile(GetMessage("yenisite.coreparser_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.coreparser/install/unstep1.php");
		} else {
			UnRegisterModule(self::MODULE_ID);
			$this->UnInstallDB();
			$this->UnInstallFiles();
		}
	}
}
?>
