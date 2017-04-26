<?
####################################
#   Company: Yenisite              #
#   Developer: Andrey Shilov       #
#   Site: http://www.yenisite.ru   #
#   E-mail: andrey@yenisite.ru     #
####################################
?>

<?
class CMarket{
    
    static function add_event($func, $arFields = array())
    {
        $function = $func;
        if(function_exists($func))        
            $function($arFields);        
    }

}

class CMarketPrice extends CMarket{
    static function GetList($by = 'id', $order = 'desc')
    {
        global $DB;
        $strSql = "SELECT * FROM yen_market_catalog_price ORDER BY ".$by." ".$order;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        return $res;
    }

    static function GetItemPriceValues($productID, $arPrices = array())
    {
        $result = array();
        
        CModule::IncludeModule("iblock");
        $res = CIBlockElement::GetByID($productID);
        if($el = $res->GetNextElement())
        {
            $fields = $el->GetFields();
            if(!CMarketCatalog::IsCatalog($fields["IBLOCK_ID"])) return 0;
            $properties = $el->GetProperties();
            $db_price = CMarketPrice::GetList();

			global $USER;
            while($price = $db_price->GetNext())
            {
                $arPriceGroups = self::GetPriceGroup($price["code"]);
                if( intval($properties[$price["code"]]["VALUE"])>0 
                    && count(array_intersect($USER->GetUserGroupArray(),$arPriceGroups))>0
                    && (count($arPrices)==0 || array_key_exists($price["code"], $arPrices))
                    )
                    {
                    $result[$price["code"]] = $properties[$price["code"]]["VALUE"];
                    }
            }
        }
        return $result;
    }

    static function GetPriceGroup($id)
    {
        global $DB;
        $strSql = "SELECT id, groups FROM yen_market_catalog_price WHERE id='".$id."' OR code='".$id."'";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        if($groups = $res->GetNext())
        {            
            $result = explode("#",$groups["groups"]);
            return $result;
        }
        return 0;
    }

    static function IsCanAdd($id)
    {
        global $USER;
        $my_groups = $USER->GetUserGroupArray();
        $groups = CMarketPrice::GetPriceGroup($id);
        foreach($my_groups as $mygr)
        {
            if(in_array($mygr, $groups))
                    return 1;

        }
        return 0;
    }

    static function GetByCode($code)
    {

        global $DB;
        $strSql = "SELECT * FROM yen_market_catalog_price WHERE code='".$code."'";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        return $res;
    }

    static function Update($id, $name, $code, $base, $group)
    {
        /* ---------------- */
        $args = array("id" => $id, "name" => $name, "code" => $code, "base" => $base, "group" => $group);
        CMarket::add_event("onBeforeMarketPriceUpdate", $args);
        /* ---------------- */

        global $DB;
        $base = $base=='Y'?'Y':'N';

        $db_this = CMarketPrice::GetByID($id);
        if($ar_this = $db_this->GetNext())
        {
            $db_catalog = CMarketCatalog::GetList();
            while($catalog = $db_catalog->GetNext())
            {
                $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$catalog["iblock_id"], "CODE" => $ar_this["code"]));
                if($prop_fields = $properties->GetNext())
                {
                    $arFields = Array(
                        "NAME" => $name,
                        "ACTIVE" => "Y",
                        "SORT" => "5555",
                        "CODE" => $code,
                        "PROPERTY_TYPE" => 'N',
                        "IBLOCK_ID" => $catalog["iblock_id"],
                        "SMART_FILTER" => "Y",
                        "DISPLAY_TYPE" => "A",
                        "DISPLAY_EXPANDED" => "Y"
                    );

                    $ibp = new CIBlockProperty;
                    $ibp->Update($prop_fields["ID"], $arFields);
                }
            }
        }

        $strSql = "SELECT id FROM yen_market_catalog_price WHERE base='Y'";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        $price = $res->GetNext();
        if($price["id"] == $id)
            $base = 'Y';

        if($base == 'Y')
        {
            $strSql = "UPDATE yen_market_catalog_price SET base='N' WHERE base='Y'";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        }

        $prices = CMarketPrice::GetList();

		$cnt = 0;
        while($prices->GetNext()) $cnt ++;
        if($cnt == 1)
            $base = 'Y';

        $gr = implode("#", $group);        
        $strSql = "UPDATE yen_market_catalog_price SET name='".$name."', code='".$code."', groups='".$gr."', base='".$base."' WHERE id=".$id."";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);

        /* ---------------- */
        $args = array("id" => $id, "name" => $name, "code" => $code, "base" => $base, "group" => $group);
        CMarket::add_event("onAfterMarketPriceUpdate", $args);
        /* ---------------- */
    }

    static function GetByID($id)
    {
        global $DB;
        $strSql = "SELECT * FROM yen_market_catalog_price WHERE id='".$id."'";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        return $res;
    }

    static function GetBasePrice()
    {
        global $DB;
        $strSql = "SELECT * FROM yen_market_catalog_price WHERE base='Y'";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        return $res;
    }

    static function Delete($id)
    {
        global $DB;
        $strSql = "DELETE FROM yen_market_catalog_price WHERE id=".$id;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);        
    }

    static function Add($name, $code, $base, $group)
    {
        global $DB;
        $base = $base=='Y'?'Y':'N';
        if($base == 'Y')
        {
            $strSql = "UPDATE yen_market_catalog_price SET base='N' WHERE base='Y'";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        }
        else
        {
            $prices = CMarketPrice::GetList();
			
            if(!$prices->GetNext())
                    $base = 'Y';
        }
        $gr = implode("#",$group);

        $cat = CMarketPrice::GetByCode($code);
        if(!$cat->GetNext())
        {
            $strSql = "INSERT INTO yen_market_catalog_price(name, code, base, groups) values('".$name."','".$code."','".$base."','".$gr."')";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        }

        $db = CMarketCatalog::GetList();
        while($catalog = $db->GetNext())
        {
            $arPropLinks = CIBlockSectionPropertyLink::GetArray($catalog["ID"]);
            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$catalog["iblock_id"], "CODE" => $code));
            if($arProp = $properties->GetNext()) {
                $PropID = $arProp['ID'];
                if (array_key_exists($PropID, $arPropLinks))
                if ($arPropLinks[$PropID]['SMART_FILTER'] === 'Y') continue;
                $arFields = Array(
                  "SMART_FILTER" => "Y",
                  "DISPLAY_TYPE" => "A",
                  "DISPLAY_EXPANDED" => "Y"
                  );
                $ibp = new CIBlockProperty;
                $ibp->Update($PropID, $arFields);
            } else {
                $arFields = Array(
                  "NAME" => $name,
                  "ACTIVE" => "Y",
                  "SORT" => "5555",
                  "CODE" => $code,
                  "PROPERTY_TYPE" => "N",
                  "IBLOCK_ID" => $catalog["iblock_id"],
                  "SMART_FILTER" => "Y",
                  "DISPLAY_TYPE" => "A",
                  "DISPLAY_EXPANDED" => "Y"
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
        }
    
    }
}


class CMarketCatalog extends CMarket{

    static function GetList($by = "id", $order = "asc")
    {
        CModule::IncludeModule("iblock");
        global $DB;
        $strSql = "SELECT * FROM yen_market_catalog";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        /*
        $iblock = array();
        while($catalog = $res->GetNext())
            $iblock[] = $catalog["iblock_id"];
        $iblock[] = -1;
        $db_iblock = CIBlock::GetList(array($by=>$order), array("ID" => $iblock));
        return $db_iblock;
        */
        return $res;
    }

    static function Add($iblock_id, $use_quantity = 0)
    {
        $iblock_id = intval($iblock_id);
        if ($iblock_id < 1) return 0;

        global $DB;
        $strSql = "SELECT id FROM yen_market_catalog WHERE iblock_id=".$iblock_id;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        if($res->GetNext())
            return 0;

        $use_quantity = intval($use_quantity);
        if ($use_quantity != 0) $use_quantity = 1;

        $strSql = "INSERT INTO yen_market_catalog(iblock_id,use_quantity) values(".$iblock_id.", ".$use_quantity.")";
        $DB->Query($strSql, false, $err_mess.__LINE__);

        $strSql = "SELECT MAX(id) as ID FROM yen_market_catalog";
        $max = $DB->Query($strSql, false, $err_mess.__LINE__);

        if($iblock = $max->GetNext())
            return $iblock["ID"];

        return 0;
    }

    static function Delete($id)
    {
        global $DB;
        $id = $id?$id:0;
        if(!$id)
            return 0;
        if($id == '*')
            $strSql = "DELETE FROM yen_market_catalog";
        else
            $strSql = "DELETE FROM yen_market_catalog WHERE ID=".$id;
        $DB->Query($strSql, false, $err_mess.__LINE__);
        return 1;
    }

    static function IsCatalog($iblock_id)
    {
        global $DB;
        $strSql = "SELECT id FROM yen_market_catalog WHERE iblock_id=". intval($iblock_id);
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        if(!$res->GetNext())
            return 0;
        return 1;
    }

    static function UsesQuantity($iblock_id)
    {
        global $DB;
        $strSql = "SELECT use_quantity FROM yen_market_catalog WHERE iblock_id = ". intval($iblock_id);
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        if ($catalog = $res->GetNext()) {
            return $catalog['use_quantity'];
        }
        return 0;
    }
}

class CMarketCatalogProduct extends CMarket
{
    static private function _GetByID($element_id, $iblock_id)
    {
        $arProduct = array(
            'QUANTITY' => self::_GetQuantity($element_id, $iblock_id),
            'QUANTITY_TRACE' => (CMarketCatalog::UsesQuantity($iblock_id) ? 'Y' : 'N'),
            'CAN_BUY_ZERO' => 'N'
            );
        return $arProduct;
    }

    static private function _GetQuantity($element_id, $iblock_id)
    {
        CModule::IncludeModule('iblock');
        $arProp = CIBlockElement::GetProperty($iblock_id, $element_id, array('sort'=>'asc'), array('CODE'=>'MARKET_QUANTITY'))->Fetch();
        if (!is_array($arProp)) return false;

        return intval($arProp['VALUE']);
    }

    static private function _TraceQuantity($delta, $element_id, $iblock_id)
    {
        $arProduct = self::_GetByID($element_id, $iblock_id);
        if ($arProduct === false) return false;
        if ($arProduct['QUANTITY_TRACE'] == 'N') return false;

        $newQuantity = $arProduct['QUANTITY'] - $delta;
        CModule::IncludeModule('iblock');
        CIBlockElement::SetPropertyValues($element_id, $iblock_id, $newQuantity, 'MARKET_QUANTITY');

        if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER'])) {
        	$GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_".$iblock_id);
        }
        return true;
    }

    static private function __checkID(&$element_id, &$iblock_id)
    {
        if (($element_id = intval($element_id)) <= 0) return true;
        CModule::IncludeModule('iblock');

        $iblock_id = intval($iblock_id);
        if ($iblock_id <= 0) {
            $arRes = CIBlockElement::GetByID($element_id)->Fetch();
            if (!is_array($arRes)) return true;
            $iblock_id = $arRes['IBLOCK_ID'];
        }
        return false;
    }

    static public function GetByID($element_id, $iblock_id = 0)
    {
        if (self::__checkID($element_id, $iblock_id)) return false;

        return self::_GetByID($element_id, $iblock_id);
    }

    static public function GetQuantity($element_id, $iblock_id = 0)
    {
        if (self::__checkID($element_id, $iblock_id)) return false;

        return self::_GetQuantity($element_id, $iblock_id);
    }

    static public function TraceQuantity($delta, $element_id, $iblock_id = 0)
    {
        if (($delta = intval($delta)) <= 0) return false;
        if (self::__checkID($element_id, $iblock_id)) return false;

        return self::_TraceQuantity($delta, $element_id, $iblock_id);
    }
}

class CMarketCatalogProperties extends CMarket{

    static function GetList()
    {
        $strSql = "SELECT * FROM yen_market_catalog_properties";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        return $res;
    }

    static function Add($name, $code, $iblocks, $type = "L", $multiple = "N")
    {
        CModule::IncludeModule("iblock");
        /*   $code       ,   $code   yen_market_catalog_properties */
        $strSql = "SELECT id FROM yen_market_catalog_properties WHERE code=".$code;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        if(!$res->GetNext())
        {
            $ibs = implode("#", $iblocks);
            $strSql = "INSERT INTO yen_market_catalog_properties(code, name, iblocks) values('".$code."','".$name."','".$ibs."')";
            $DB->Query($strSql, false, $err_mess.__LINE__);
        }

        //$db_iblock = CMarketCatalog::GetList();
        //while($iblock = $db_iblock->GetNext())
        foreach($iblocks as $iblock)
        {
            $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock, "CODE" => $code));
            if(!$properties->GetNext())
            {
                $arFields = Array(
                    "NAME" => $name,
                    "ACTIVE" => "Y",
                    "SORT" => "100",
                    "CODE" => $code,
                    "MULTIPLE" => $multiple,
                    "PROPERTY_TYPE" => $type,
                    "IBLOCK_ID" => $iblock["ID"],
                );

                $ibp = new CIBlockProperty;
                $ibp->Add($arFields);

            }
        }


    }
}


class CMarketBasket extends CMarket{

    function Add($id, $props = array(), $quantity = 1)
    {
        CModule::IncludeModule("iblock");
        session_start();

        $quantity = intval($quantity);
        if($quantity == 0) $quantity = 1;

        $basketelementid = "";
        $basketelementid = CMarketBasket::EncodeBasketItems($id, $props);
        $db_el = CIBlockElement::GetByID($id);
        if($ar_el = $db_el->GetNext())
        {
            if(!CMarketCatalog::IsCatalog($ar_el["IBLOCK_ID"]))
                return 0;



            if(!$_SESSION['YEN_MARKET_BASKET'][SITE_ID][$basketelementid])
                $_SESSION['YEN_MARKET_BASKET'][SITE_ID][$basketelementid]["YEN_COUNT"]   =   $quantity;
            else
                $_SESSION['YEN_MARKET_BASKET'][SITE_ID][$basketelementid]["YEN_COUNT"]   += $quantity;

            //$_SESSION['YEN_MARKET_BASKET'][$basketelementid]["FIELDS"]    =   $ar_el;
            return 1;
        }
        return 0;
    }

    function DecodeBasketItems($key)
    {
        $res = explode("---", $key);
        $result["ID"] = $res[0];
        $result['PROPERTIES'] = array();
        for($i = 1; $i < count($res); $i++)
        {
            $prop = explode("###", $res[$i]);
            if (count($prop) < 2) continue;
            $result["PROPERTIES"][base64_decode($prop[0])] = base64_decode($prop[1]);
        }        
        return $result;
    }

    function EncodeBasketItems($id, $props)
    {
        $beids = array();
        foreach($props as $key=>$value)
        {
            if (empty($key) || empty($value)) continue;
            $beids[] = base64_encode($key)."###".base64_encode($value);
        }
        $basketelementid = $id."---".implode("---", $beids);
        return $basketelementid;

    }

    function Delete($key)
    {
        @session_start();
        unset($_SESSION["YEN_MARKET_BASKET"][SITE_ID][$key]);
    }
    
    function setQuantity($key, $quantity)
    {
        $quantity = intval($quantity);
        if ($quantity <= 0) {
            return self::Delete($key);
        }
        
        @session_start();
        if (is_array($_SESSION['YEN_MARKET_BASKET'][SITE_ID]) && isset($_SESSION['YEN_MARKET_BASKET'][SITE_ID][$key])) {
            $_SESSION['YEN_MARKET_BASKET'][SITE_ID][$key]['YEN_COUNT'] = $quantity;
        }
    }
    
    function getCartTotal()
    {
        @session_start();
        $priceTotal = 0;
        foreach ($_SESSION['YEN_MARKET_BASKET'][SITE_ID] as $key => $value) {
            $res = self::DecodeBasketItems($key);
            $prices = CMarketPrice::GetItemPriceValues($res["ID"]);
            $minPrice = 0;
            foreach($prices as $code => $pr) {
                if(CMarketPrice::IsCanAdd($code) && $pr > 0 && ($pr < $minPrice || empty($minPrice)) )
                {
                    $minPrice = $pr;
                }
            }
            $priceTotal += $minPrice * $value['YEN_COUNT'];
        }
        $arResult = array();
        $arResult['SUM_FORMATTED'] = $arResult['SUM'] = $priceTotal;
        $arResult['QUANTITY'] = count($_SESSION['YEN_MARKET_BASKET'][SITE_ID]);
        
        return array('BUY' => $arResult);
    }

    function GetList($id, $iblock_id = false)
    {
        CModule::IncludeModule("iblock");
        $arReturn = false;
        
        $iblock_id = intval($iblock_id);
        if (empty($iblock_id)) {
            $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("CODE" => "YENISITE_MARKET_ORDER"));
            $arr = $rsIBlock->Fetch();
            $iblock_id = $arr["ID"];
        }
        
        return CIBlockElement::GetProperty($iblock_id, $id, array("sort" => "asc"), array("CODE" => "ITEMS"));
    }
}


class CMarketOrderProperties extends CMarket{

    static function GetList($arSort)
    {
        CModule::IncludeModule("iblock");
        $db_iblock = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
            if(!$ar_iblock = $db_iblock->Fetch())
                return 0;
        $properties = CIBlockProperty::GetList($arSort, Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$ar_iblock["ID"]));
        return $properties;

    }

    static function Add($name, $code, $type = "S", $multiple = "N")
    {

        CModule::IncludeModule("iblock");
        $db_iblock = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
            if(!$ar_iblock = $db_iblock->Fetch())
                return 0;
            
        $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$ar_iblock["ID"], "CODE" => $code));
        if($prop_fields = $properties->GetNext())
            return 0;
        
         $arFields = Array(
            "NAME" => $name,
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => $code,
            "MULTIPLE" => $multiple,
            "PROPERTY_TYPE" => $type,
            "IBLOCK_ID" => $ar_iblock["ID"],
        );

        $ibp = new CIBlockProperty;
        if($PropID = $ibp->Add($arFields))
            return $PropID;
        
        return 0;
    }

}
class CMarketOrder extends CMarket{
    static function GetByID($id)
    {
        CModule::IncludeModule("iblock");
        $arReturn = false;
        
        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("CODE" => "YENISITE_MARKET_ORDER"));
        $arr = $rsIBlock->Fetch();
        
        //### GET INFO ABOUT ORDER ###//
        $res = CIBlockElement::GetProperty($arr["ID"], $id, array("sort" => "asc"));
        while($arRes = $res->GetNext())
        {
			if(!empty($arReturn[$arRes['CODE']]) && !is_array($arReturn[$arRes['CODE']])) $arReturn[$arRes['CODE']] = array($arReturn[$arRes['CODE']]);
			if(!is_array($arReturn[$arRes['CODE']]))
				$arReturn[$arRes['CODE']]= $arRes['VALUE'];
			else
				$arReturn[$arRes['CODE']][]= $arRes['VALUE'];
			
			if($arRes['CODE']=='PAYMENT_E')
                $link_iblock = $arRes['LINK_IBLOCK_ID'];
        }
        
        //### GET INFO ABOUT PAY SYSTEM ###//
        $res = CIBlockElement::GetProperty($link_iblock, $arReturn['PAYMENT_E'], array("sort" => "asc"));
        while($arRes = $res->GetNext())
        {
			if ($arRes['CODE'] == 'MERCHANT_LOGIN'){
				$arReturn["PAY_SYSTEM"][$arRes['CODE']][$arRes['DESCRIPTION']] = $arRes['VALUE'];
			}else{
				$arReturn["PAY_SYSTEM"][$arRes['CODE']]= $arRes['VALUE'];
			}
		}
        
        return $arReturn;
    }
    
    static function GetList($arOrder = array("SORT"=>"ASC"), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
    {
        $baseFilter = array(
            "IBLOCK_CODE" => "YENISITE_MARKET_ORDER",
            "ACTIVE" => "Y",
            "PROPERTY_SITE_ID" => SITE_ID
        );
        $arFilter = array_merge($baseFilter, $arFilter);
        
        return CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
    }
    
    static function SetStatus($id,$status)
    {
        CModule::IncludeModule("iblock");
        IncludeModuleLangFile(__FILE__);
        
        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("CODE" => "YENISITE_MARKET_ORDER"));
        $arr = $rsIBlock->Fetch();
        
        $arStatus = CIBlockProperty::GetPropertyEnum("STATUS", Array("SORT"=>"asc"), Array("IBLOCK_ID"=>$arr["ID"], "VALUE"=>GetMessage('YEN_STATUS_'.$status)))->GetNext();
        
        CIBlockElement::SetPropertyValues($id, $arr["ID"], $arStatus['ID'], "STATUS");
        
        return true;
    }
    
    static function GetStatus($id)
    {
        CModule::IncludeModule("iblock");
        IncludeModuleLangFile(__FILE__);
        
        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("CODE" => "YENISITE_MARKET_ORDER"));
        $arr = $rsIBlock->Fetch();
        
        //### GET INFO ABOUT ORDER ###//
        $res = CIBlockElement::GetProperty($arr["ID"], $id, array("sort" => "asc"), Array("CODE" => "STATUS"));
        if($arRes = $res->GetNext())
        {
            $order_status = $arRes;
        }
        
        $res = CIBlockProperty::GetPropertyEnum("STATUS", Array("SORT"=>"asc"), Array("IBLOCK_ID"=>$arr["ID"], "VALUE"=>GetMessage('YEN_STATUS_PAYED')));
        if($arRes = $res->GetNext())
        {
            $status_payed = $arRes;
        }
        // if order status is 'PAYED' or higher
        if($order_status['VALUE_SORT']>=$status_payed['SORT'])
            return 'PAYED';
        else
            return 'NOT_PAYED';
        
    }
	
	static function CheckPaymentOnRobo($iblock_id, $id, $code)
    {
        CModule::IncludeModule("iblock");
		$returnRobo = true;
		if($code == 'robokassa'){

			$resRobo = CIBlockElement::GetProperty($iblock_id, $id, array("sort" => "asc"), array("CODE" => "MERCHANT_LOGIN"));
			while($arResRobo = $resRobo->GetNext())
			{
				if($arResRobo['DESCRIPTION'] == SITE_ID){
					$returnRobo = false;
					break;
				}
			}
		}else{
			$returnRobo = false;
		}
		
		return $returnRobo;
	}	
}
class CMarketPayment extends CMarket{
    static function GetByName($name)
    {
        CModule::IncludeModule("iblock");
        $res = CIBlock::GetList(
            Array(), 
            Array(
                'TYPE'=>'dict', 
                'ACTIVE'=>'Y', 
                'CODE'=>'payment',
                'CHECK_PERMISSIONS' => 'N'
            ), false
        );

        if($ar_res = $res->GetNext())
        {
            $arReturn['IBLOCK'] = $ar_res['ID'];
        }
        $arFilter = Array(
            "IBLOCK_ID"=>$arReturn['IBLOCK'],
            "ACTIVE"=>"Y", 
            array(
                'LOGIC' => 'OR',
                "NAME"=>$name,
                "CODE"=>strtolower($name),
            )
        );
        $res = CIBlockElement::GetList(Array(), $arFilter);
        if($arFields = $res->GetNext())
        {
            $arReturn['ID'] = $arFields['ID'];
        }
        
        return $arReturn;
    }
}

?>
