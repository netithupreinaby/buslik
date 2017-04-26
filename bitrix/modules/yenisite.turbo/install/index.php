<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

Class yenisite_turbo extends CModule
{
	var $MODULE_ID = "yenisite.turbo";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	function yenisite_turbo()
	{
		$arModuleVersion = array();
		include dirname(__FILE__) . "/version.php";

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("yenisite.turbo_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.turbo_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("yenisite.turbo_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.turbo_PARTNER_URI");

		return true;
	}

	function InstallDB()
	{
		global $DB;
		$strSql = 'CREATE TABLE IF NOT EXISTS yen_turbo_sets(
				ID INT(10) NOT NULL AUTO_INCREMENT,
				NAME VARCHAR(100) NOT NULL,
				IBLOCK_ID INT(10),
				SECTION_ID VARCHAR(800),
				RENT INT(10),
				PRICE_ID INT(10),
				DISCOUNT INT(10),
				REGION INT(10),
				DELIVERY VARCHAR(1),
				PLACE INT(10),
				SUBSECT VARCHAR(1),
				SELECT_PROP INT(10),
				XML_ID VARCHAR(255),
				ELEM_STATUS int(1),
			PRIMARY KEY(id))';
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	}

	function UnInstallDB()
	{
		global $DB; 
		$strSql = 'DROP TABLE IF EXISTS yen_turbo_sets';
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	}

	function DoInstall()
	{
		global $APPLICATION, $errors;
		if (!\Bitrix\Main\Loader::includeModule('yenisite.coreparser')) {
			$errors = Loc::getMessage('yenisite.turbo_UNINS_yenisite.coreparser');
			$APPLICATION->IncludeAdminFile(Loc::getMessage("yenisite.turbo_INSTALL_TITLE"), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.turbo/install/step1.php");
		} else {
			RegisterModule($this->MODULE_ID);
			$this->InstallDB();
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.turbo/install/bitrix", $_SERVER["DOCUMENT_ROOT"]."/bitrix", true, true);
		}
	}

	function DoUninstall()
	{
		$this->UnInstallDB();
		UnRegisterModule($this->MODULE_ID);
	}


}

?>