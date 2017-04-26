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
?><?AddEventHandler("main", "OnEpilog", "Redirect404");
function Redirect404() {
    
	define("PATH_TO_404", "/404.php");
	if( 
     !defined('ADMIN_SECTION') &&  
     defined("ERROR_404") &&  
     file_exists($_SERVER["DOCUMENT_ROOT"].PATH_TO_404) 
   ) {
        
        global $APPLICATION;
		$APPLICATION->RestartBuffer();
        
		
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
        include($_SERVER["DOCUMENT_ROOT"].PATH_TO_404);
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/footer.php");
    }
}?><?
	AddEventHandler("sale", "OnSaleStatusOrder", "yenisite_SaveNumberSales") ;
	function yenisite_SaveNumberSales($ID, $val)
	{
		if($val == "F" && CModule::IncludeModule("iblock") && CModule::IncludeModule("sale"))
		{
			$dbBasketItems = CSaleBasket::GetList(
					array(),
					array("ORDER_ID" => $ID),
					false,
					false,
					array()
				);
			while ($arBasketItem = $dbBasketItems->Fetch())
			{
				$arElement = CIBlockElement::GetByID($arBasketItem["PRODUCT_ID"])->Fetch() ;
				$arElement = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$arElement["IBLOCK_ID"], "ID"=>$arElement["ID"]), false, Array("nTopCount"=>1), Array("ID", "IBLOCK_ID", "PROPERTY_SALE_INT"))->Fetch();
				$arElement["PROPERTY_SALE_INT_VALUE"] = !$arElement["PROPERTY_SALE_INT_VALUE"] ? IntVal($arBasketItem["QUANTITY"]) : $arElement["PROPERTY_SALE_INT_VALUE"] + IntVal($arBasketItem["QUANTITY"]) ;
				CIBlockElement::SetPropertyValues($arElement["ID"], $arElement["IBLOCK_ID"], $arElement["PROPERTY_SALE_INT_VALUE"], "SALE_INT");
			}
		}
	}
?><?
function ys_log_write2($str)
{
	$str = date('[H:i:s]').$str."\r\n" ;
	file_put_contents ($_SERVER["DOCUMENT_ROOT"].'/ys_log_ini223.log', $str, FILE_APPEND) ;
	return ;
} 
AddEventHandler("catalog", "OnProductUpdate", "yenisite_amdphoto_Availability");
function yenisite_amdphoto_Availability ($ID, $arFields)
{
	ys_log_write2('ID = '.$ID.' ; arFields = '.serialize($arFields));
}
?><?
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/yenisite/catalog.sets/userprop.php') ;
?>