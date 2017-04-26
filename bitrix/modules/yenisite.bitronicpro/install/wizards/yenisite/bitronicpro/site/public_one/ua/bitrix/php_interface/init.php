<? /*
AddEventHandler("main", "OnPageStart", "ShowResizer2Head", 50);
function ShowResizer2Head()
{
	global $APPLICATION;
	if(substr_count($APPLICATION->GetCurDir(), "/bitrix/") == 0)
	{
		if( CModule::IncludeModule("yenisite.resizer2") )
			CResizer2::ShowResizer2Head();
		else
			return;
	}
}

AddEventHandler("main", "OnEndBufferContent", "replaceResizer2Content");
function replaceResizer2Content($content){
	if( CModule::IncludeModule("yenisite.resizer2") && !CSite::InDir("/bitrix/")){
		$resize_class = COption::GetOptionString("yenisite.resizer2", "resize_class", "");
		$resize_class_classname = COption::GetOptionString("yenisite.resizer2", "resize_class_classname", "");
		$resize_class_set_small = COption::GetOptionString("yenisite.resizer2", "resize_class_set_small", "");
		$resize_class_set_big = COption::GetOptionString("yenisite.resizer2", "resize_class_set_big", "");
		$resize_wm = COption::GetOptionString("yenisite.resizer2", "resize_wm", "");
		$resize_wm_set = COption::GetOptionString("yenisite.resizer2", "resize_wm_set", "");
		
		if($resize_class == "Y" && $resize_class_classname && $resize_class_set_small){			
			$content = CResizer2Resize::imgTagClassResize($resize_class_classname, $content, $resize_class_set_small, $resize_class_set_big);
		}				
		
		if($resize_wm == "Y" && $resize_wm_set ){			
			$content =  CResizer2Resize::imgTagWH($content, $resize_wm_set);
		}
	}
}*/
?><?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/yenisite/catalog.sets/userprop.php') ;
?><?include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/bitronic_ini.php") ;?>