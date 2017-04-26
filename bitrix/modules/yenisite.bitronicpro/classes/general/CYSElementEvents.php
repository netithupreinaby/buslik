<?php

/**
 * Class CYSElementEvents
 * @author Eduard <ytko90@gmail.com>
 * @version 0.9.0
 */
class CYSElementEvents {
    
    static public $_PRICE_PROPERTY_ID       = FALSE;
    static public $_PRICE_LOWER_IBLOCK_NAME = "price_lower";
    static public $_ELEMENT_EXIST_IBLOCK_NAME = "element_exist";
    
    
    static private function getPricePropID($code = "PRICE_BASE")
    {
        $properties = CIBlockProperty::GetList(Array(), Array("CODE" => $code));
        $pricePropID = $properties->GetNext();
        
        return $pricePropID['ID'];
    }
    
    static private function getPriceFromArFields($arFields)
    {
        $priceArr = $arFields['PROPERTY_VALUES'][self::$_PRICE_PROPERTY_ID];
        $price = reset($priceArr);
        
        return $price['VALUE'];
    }

    static private function disableElement($ID)
    {
        global $USER;
        
        $el = new CIBlockElement;
        $arLoadProductArray = Array(
                                  "MODIFIED_BY"    => $USER->GetID(),
                                  "ACTIVE"         => "N",
                            );
        $res = $el->Update($ID, $arLoadProductArray);
    }

    static function sendEmailsByPrice($element_id, $price, $currency = false)
    {
			if(intval($element_id) <= 0 )
			{
				return false;
			}
        $arFilter = Array("IBLOCK_CODE" => self::$_PRICE_LOWER_IBLOCK_NAME, "ACTIVE" => "Y", "=PROPERTY_ELEMENT_ID" => $element_id, ">=PROPERTY_PRICE" => $price);
        $arSelect = Array("ID", "NAME", "PROPERTY_EMAIL", "PROPERTY_PRICE", "PROPERTY_ELEMENT_ID", "PROPERTY_ELEMENT_ID.NAME");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        
        $arrSites = array();
        $objSites = CSite::GetList();
        while ($arrSite = $objSites->Fetch())
            $arrSites[] = $arrSite["ID"];

        while($ar_fields = $res->GetNext())
        {
//            AddMessage2Log("Send mail " . $ar_fields['PROPERTY_EMAIL_VALUE'] . " about price lower for \"" . $ar_fields['PROPERTY_ELEMENT_ID_NAME'] . "\" to " . $price);

            $arEventFields = array(
                "NAME"      => $ar_fields['PROPERTY_ELEMENT_ID_NAME'],
                "EMAIL"     => $ar_fields['PROPERTY_EMAIL_VALUE'],
                "NEW_PRICE" => $price,
               );
			if($currency)
			{
				$baseCurrency = CCurrency::GetBaseCurrency();
				if($currency != $baseCurrency)
				{
					$arEventFields["NEW_PRICE"] = CCurrencyRates::ConvertCurrency($arEventFields["NEW_PRICE"], $currency, $baseCurrency);
				}
				
				$arEventFields["NEW_PRICE"] = CCurrencyLang::CurrencyFormat($arEventFields["NEW_PRICE"], $baseCurrency, false);
				$arEventFields["NEW_PRICE"] .= " {$baseCurrency}";
			}
            CEvent::Send("PRICE_LOWER", $arrSites, $arEventFields);
            
            self::disableElement($ar_fields['ID']);
        }
    }
    
    static function OnBeforePriceUpdateHandler($ID, &$arFields)
    {
        //AddMessage2Log(print_r($arFields, true));
				if(intval($arFields['PRODUCT_ID']) > 0)
					self::sendEmailsByPrice($arFields['PRODUCT_ID'], $arFields['PRICE'], $arFields['CURRENCY']);
        
        return TRUE;
    }
    
    static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if($arFields["RESULT"])
        {
//            AddMessage2Log(print_r($arFields, true));
            $pricePropID = self::getPricePropID();
            self::$_PRICE_PROPERTY_ID = $pricePropID;
            $price = self::getPriceFromArFields($arFields);
            $element_id = $arFields['ID'];
            
            self::sendEmailsByPrice($element_id, $price);
        }
        //else
        //    AddMessage2Log("Error edit ".$arFields["ID"]." (".$arFields["RESULT_MESSAGE"].").");
    }
    
    static function OnProductUpdateHandler($ID, $arFields)
    {
//        AddMessage2Log(print_r($arFields, true));
        if (!CModule::IncludeModule('iblock')) return;
    	$dbRes = CIBlockElement::GetList(array(), array('ID' => $ID, 'CATALOG_AVAILABLE' => 'Y'), false, array(), array('ID'));
        if ($arRes = $dbRes->GetNext())
        {
            $arFilter = Array("IBLOCK_CODE" => self::$_ELEMENT_EXIST_IBLOCK_NAME, "ACTIVE" => "Y", "=PROPERTY_ELEMENT_ID" => $ID);
            $arSelect = Array("ID", "NAME", "PROPERTY_EMAIL", "PROPERTY_ELEMENT_ID", "PROPERTY_ELEMENT_ID.NAME");
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);

            $arrSites = array();
            $objSites = CSite::GetList($by = 'sort', $order = 'asc');
            while ($arrSite = $objSites->Fetch())
                $arrSites[] = $arrSite["ID"];
        
            while($ar_fields = $res->GetNext())
            {
//                AddMessage2Log("Send mail to " . $ar_fields['PROPERTY_EMAIL_VALUE'] . " about \"" . $ar_fields['PROPERTY_ELEMENT_ID_NAME'] . "\" is exist");

                $arEventFields = array(
                    "NAME"      => $ar_fields['PROPERTY_ELEMENT_ID_NAME'],
                    "EMAIL"     => $ar_fields['PROPERTY_EMAIL_VALUE'],
                    );

                CEvent::Send("ELEMENT_EXIST", $arrSites, $arEventFields);
                
                self::disableElement($ar_fields['ID']);
            }
        }
        //AddMessage2Log($ID);
//        AddMessage2Log(print_r($arFields, true));
    }
}

?>