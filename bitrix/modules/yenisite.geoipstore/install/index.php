<?
IncludeModuleLangFile(__FILE__);
Class yenisite_geoipstore extends CModule
{
	const MODULE_ID = 'yenisite.geoipstore';
	var $MODULE_ID = 'yenisite.geoipstore';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct() {
		$arModuleVersion = array();
		include(dirname(__FILE__) . '/version.php');
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = GetMessage('yenisite.geoipstore_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('yenisite.geoipstore_MODULE_DESC');

		$this->PARTNER_NAME = GetMessage('yenisite.geoipstore_PARTNER_NAME');
		$this->PARTNER_URI = GetMessage('yenisite.geoipstore_PARTNER_URI');
	}

	function InstallDB($arParams = array()) {
		global $DB;

		CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/bitrix/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/', true, true);
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql');
		
		RegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CYSGeoIPStore', 'OnEpilog');
		RegisterModuleDependences('main', 'OnBeforeUserAdd', self::MODULE_ID, 'CYSGeoIPStore', 'OnBeforeUserAdd');
		RegisterModuleDependences('catalog', 'OnGetOptimalPrice', self::MODULE_ID, 'CYSGeoIPStore', 'OnGetOptimalPrice');

		return true;
	}

	function UnInstallDB($arParams = array()) {
		global $DB;

		DeleteDirFilesEx('/bitrix/js/' . self::MODULE_ID);
		DeleteDirFilesEx('/bitrix/components/yenisite/geoip.store/');
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql');

		UnRegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CYSGeoIPStore', 'OnEpilog');
		UnRegisterModuleDependences('main', 'OnBeforeUserAdd', self::MODULE_ID, 'CYSGeoIPStore', 'OnBeforeUserAdd');
		UnRegisterModuleDependences('catalog', 'OnGetOptimalPriceResult', self::MODULE_ID, 'CYSGeoIPStore', 'OnGetOptimalPrice');
		
		return true;
	}

	function DoInstall() {
		global $APPLICATION;
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall() {
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
	}
}
?>