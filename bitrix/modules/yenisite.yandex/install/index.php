<?
global $MESS;
$strPath2Lang = str_replace('\\', '/', __FILE__);

$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
include($strPath2Lang."/install/version.php");

Class yenisite_yandex extends CModule 																								// <------------ HERE------------- CLASS NAME MUST BE CHANGED
{
        var $MODULE_ID = 'yenisite.yandex';																							
        var $MODULE_VERSION;
        var $MODULE_VERSION_DATE;
        var $MODULE_NAME;
        var $MODULE_DESCRIPTION;
		
	var $arComponents = array(
					'yandex.market_new'
				);
		
        function yenisite_yandex()																												// <------------ HERE------------- CONSTRUCTOR NAME MUST BE CHANGED
        {        		
                $arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->PARTNER_NAME = "yenisite";
		$this->PARTNER_URI = "http://www.yenisite.ru/";
		$this->MODULE_NAME = GetMessage("yenisite.yandex_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.yandex_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("yenisite.yandex_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.yandex_PARTNER_URI");

		return true;
        }

		
        function DoInstall(){		
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.yandex/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.yandex/install/public_html", $_SERVER["DOCUMENT_ROOT"]."/", true, true);    
		RegisterModule($this->MODULE_ID);
	}

        function DoUninstall(){           
			foreach($this->arComponents as $comp)
				if($comp)
					self::removeDirRec($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite/{$comp}");
			UnRegisterModule($this->MODULE_ID);
	}

	function removeDirRec($dir)
	{
		if ($objs = glob($dir."/*")) {
			foreach($objs as $obj) {
				is_dir($obj) ? self::removeDirRec($obj) : unlink($obj);
			}
		}

		rmdir($dir);
	}

}
?>
