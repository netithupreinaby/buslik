<?AddEventHandler("main", "OnEpilog", "Redirect404");
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
?>