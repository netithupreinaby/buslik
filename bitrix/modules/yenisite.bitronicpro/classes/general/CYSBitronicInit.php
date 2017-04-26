<?

class CYSBitronicInit {

	function Redirect404() 
	{		
		define("PATH_TO_404", SITE_DIR."404.php");
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
	}

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
	
	function bitronic_OnAfterUserRegister($arFields)
	{
		if($arFields["USER_ID"]>0)
	   {
		  if ($_POST["SUBSCRIBE"] == "Y")
		  {
			if(!CModule::IncludeModule("subscribe"))
				return;
			$arFilter = array(
				"ACTIVE" => "Y",
				"LID" => SITE_ID,
				"VISIBLE"=>"Y",
			 );
		  
			$rsRubrics = CRubric::GetList(array(), $arFilter);
			$arRubrics = array();
			while($arRubric = $rsRubrics->GetNext()) $arRubrics[] = $arRubric["ID"];
				
			$arSubFields = Array(
				"USER_ID" => $arFields["USER_ID"],
				"ACTIVE" => "Y",
				"EMAIL" => $arFields["EMAIL"],
				"FORMAT" => "html",
				"CONFIRMED" => "Y",
				"SEND_CONFIRM" => "N",
				"RUB_ID" => $arRubrics,
			 );
			 $subscr = new CSubscription;
			 $ID = $subscr->Add($arSubFields, SITE_ID);
		  }
	   }
	}
}

?>