<?
define('MODULE_DIR', 'yenisite.profileadd');  // <------------ HERE------------- MODULE DIR MUST BE CHANGED

global $MESS;
$strPath2Lang = str_replace('\\', '/', __FILE__);

$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
include($strPath2Lang."/install/version.php");

Class yenisite_profileadd extends CModule // <------------ HERE------------- CLASS NAME MUST BE CHANGED
{
        var $MODULE_ID = 'yenisite.profileadd';	
        var $MODULE_VERSION;
        var $MODULE_VERSION_DATE;
        var $MODULE_NAME;
        var $MODULE_DESCRIPTION;
		
		var $arComponents = array(
							'catalog.profileadd',
									//<------------ HERE------------- LIST OF COMPONENTS. IT NEED TO DELETE COMPONENTS
						);
		
        function yenisite_profileadd()    // <------------ HERE------------- CONSTRUCTOR NAME MUST BE CHANGED
        {        		
                $arModuleVersion = array();
				$path = str_replace("\\", "/", __FILE__);
				$path = substr($path, 0, strlen($path) - strlen("/index.php"));
				include($path."/version.php");
				$this->MODULE_VERSION = $arModuleVersion["VERSION"];
				$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
				$this->PARTNER_NAME = GetMessage("PROFILEADD_SPER_PARTNER");
				$this->PARTNER_URI = GetMessage("PROFILEADD_PARTNER_URI");
				$this->MODULE_NAME = GetMessage("PROFILEADD_MODULE_NAME"); // <------------ HERE------------- LANG MUST BE CHANGED
				$this->MODULE_DESCRIPTION = GetMessage("PROFILEADD_MODULE_DESC");  // <------------ HERE------------- LANG MUST BE CHANGED
			return true;
        }
        function DoInstall(){		
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".MODULE_DIR."/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);  
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