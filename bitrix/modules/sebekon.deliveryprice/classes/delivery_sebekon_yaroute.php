<?php

class CDeliverySebekonYaRouteDefault {

    public static $event_added = false;
    public static $loaded = false;

    function Init() {

        self::$loaded = true;
        CSebekonDeliveryPrice::LoadJs();

        if ($arCurrency = CCurrency::GetByID('RUR')) {
            $base_currency = 'RUR';
        } else {
            $base_currency = 'RUB';
        }

        $profiles = array();
        $maps = CSebekonDeliveryPrice::getMaps();

        $descr = GetMessage('sebekon_DELIVERY_PROFILE_DESCRIPTION_INNER');
        if ($_SESSION['sebekon_yaroute_name']!='') {
            $descr = str_replace('%PLACE%', $_SESSION['sebekon_yaroute_name'],GetMessage('sebekon_DELIVERY_PROFILE_DESCRIPTION_INNER_2'));
        }

        foreach ($maps as $m) {
            $profiles[$m['ID']] = array(
                "TITLE" => $m['NAME'],
                "DESCRIPTION" => $descr,
                "RESTRICTIONS_WEIGHT" => array(),
                "RESTRICTIONS_SUM" => array(),
            );
        }

        return array(
            /* Basic description */
            "SID" => "sebekon_yaroute_default",
            "NAME" => GetMessage('sebekon_DELIVERY_NAME'),
            "DESCRIPTION" => GetMessage('sebekon_DELIVERY_DESCRIPTION'),
            "DESCRIPTION_INNER" => GetMessage('sebekon_DELIVERY_DESCRIPTION_INNER'),
            "BASE_CURRENCY" => $base_currency,

            "HANDLER" => __FILE__,

            /* Handler methods */
            "DBGETSETTINGS" => array("CDeliverySebekonYaRouteDefault", "GetSettings"),
            "DBSETSETTINGS" => array("CDeliverySebekonYaRouteDefault", "SetSettings"),
            "GETCONFIG" => array("CDeliverySebekonYaRouteDefault", "GetConfig"),

            "COMPABILITY" => array("CDeliverySebekonYaRouteDefault", "Compability"),
            "CALCULATOR" => array("CDeliverySebekonYaRouteDefault", "Calculate"),

            /* List of delivery profiles */
            "PROFILES" => $profiles,
            "PROFILE_USE_DEFAULT" => 'Y'
        );
    }


    public static function addScript (&$content) {
        global $APPLICATION;

        if (stripos($APPLICATION->GetCurDir(),'/bitrix/admin/')!==FALSE) return $content;

        $order_page = COption::GetOptionString('sebekon.deliveryprice', 'DP_ORDER_PAGE');
        if (!$order_page) $order_page = '/personal/order/make/';

        if (CModule::IncludeModule('sale')) {
            if (	$APPLICATION->GetCurDir() == '/personal/order/make/' ||
                $APPLICATION->GetCurDir() == $order_page ||
                $APPLICATION->GetCurPage() == $order_page ||
                stripos($content, 'ORDER_PROP')!==FALSE
            )
            {
                $delivery = CSaleDeliveryHandler::GetBySID('sebekon_yaroute')->Fetch();
                if ($delivery && $delivery['ACTIVE']=='Y') {
                    CSebekonDeliveryPrice::LoadJs();
                }
            }
        }
        return $content;
    }

    public static function OnProlog () {
        global $APPLICATION;

        $order_page = COption::GetOptionString('sebekon.deliveryprice', 'DP_ORDER_PAGE');
        if (!$order_page) $order_page = '/personal/order/make/';

        if (CModule::IncludeModule('sale')) {
            if (	$APPLICATION->GetCurDir() == '/personal/order/make/' ||
                $APPLICATION->GetCurDir() == $order_page ||
                $APPLICATION->GetCurPage() == $order_page
            )
            {
                $delivery = CSaleDeliveryHandler::GetBySID('sebekon_yaroute')->Fetch();
                if ($delivery && $delivery['ACTIVE']=='Y') {
                    CSebekonDeliveryPrice::LoadJs();
                }
            }
        }
    }

    public static function isOrderEdit () {
        global $APPLICATION;
        if (stripos($APPLICATION->GetCurDir(),'/bitrix/admin/')!==FALSE) return true;
        if ($_REQUEST['externalcontext']=='crm') return;
        return false;
    }

    public static function getOrderDeliveryPrice () {
        global $APPLICATION;
        if (self::isOrderEdit()) {
            $ORDER_ID = intval($_REQUEST['ID']);
            if (!$ORDER_ID) $ORDER_ID = intval($_REQUEST['ORDER_ID']);
            if (!$ORDER_ID) $ORDER_ID = intval($_REQUEST['id']);

            if ($ORDER_ID) {
                if (CModule::IncludeMOdule('sale')) {
                    $arOrder = CSaleOrder::GetById($ORDER_ID);
                    return $arOrder['PRICE_DELIVERY'];
                }
            }
        }
        return 0;
    }

    function GetConfig() {
        $arConfig = CSebekonDeliveryPrice::getConfig();
        return $arConfig;
    }

    function GetSettings($strSettings)
    {
        $arConfig = unserialize($strSettings);
        $_SESSION['sebekon_yaroute_arconfig'] = $arConfig;
        return $arConfig;
    }

    function SetSettings($arSettings)
    {
        return serialize($arSettings);
    }

    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {

        if (is_array($_SESSION['sebekon_yaroute_prices']) && !isset($_SESSION['sebekon_yaroute_prices'][$profile])) {
            unset($_SESSION['sebekon_yaroute_prices']);
            unset($_SESSION['sebekon_yaroute_routes']);
            unset($_SESSION['sebekon_yaroute_name']);
            unset($_SESSION['sebekon_yaroute_point']);
        }

        if (!is_array($_SESSION['sebekon_yaroute_point']) && !self::isOrderEdit()) {
            return array(
                "RESULT" => "ERROR",
                "TEXT" => GetMessage('sebekon_DELIVERY_NO_POINT')
            );
        }

        unset($_SESSION['sebekon_yaroute_bad_point'][$profile]);

        if (!$_SESSION['sebekon_yaroute_prices'][$profile] && !self::isOrderEdit()) {
            $result = array();
            $maps = CSebekonDeliveryPrice::getMap('');
            //определяем принадлежность точек тем или иным зонам
            $point = $_SESSION['sebekon_yaroute_point'];
            foreach ($maps['MAPS'] as $map) {
                foreach ($maps['ZONES'] as $zone) {
                    if (!in_array($zone['ID'],$map['PROPS']['ZONES']['VALUE'])) continue;
                    $polygon = CSebekonDeliveryPrice::convertToPolygon($zone['PROPS']['ZONE_COORDS']['VALUE']);
                    if (CSebekonDeliveryPrice::contains($point,$polygon) || !is_array($point)) {
                        $result[$map['ID']] = $map['ID'];
                        break;
                    }
                }
            }
            if (!$result[$profile]) {
                $_SESSION['sebekon_yaroute_bad_point'][$profile] = true;
                return array(
                    "RESULT" => "ERROR",
                    "TEXT" => GetMessage('SEBEKON_DELIVERY_BAD_POINT')
                );
            }
        }
        $sum = $_SESSION['sebekon_yaroute_prices'][$profile];
        if ($sum==0  && self::isOrderEdit()) {
            $sum = self::getOrderDeliveryPrice();
        }

        \CModule::IncludeModule('catalog');

        return array(
            "RESULT" => "OK",
            "VALUE" => roundEx($sum, CATALOG_VALUE_PRECISION)
        );
    }


    function Compability($arOrder, $arConfig)
    {
        $_SESSION['sebekon_yaroute_location'] = 0;

        $result = array();
        if (CModule::IncludeModule('sale')) {
            $arLocationFrom = CSaleLocation::GetByID($arOrder["LOCATION_FROM"]);
            $arLocationTo = CSaleLocation::GetByID($arOrder["LOCATION_TO"]);

            if (LANGUAGE_ID !== 'en')
            {
                $arCountry = CSaleLocation::GetCountryLangByID($arLocationTo['COUNTRY_ID'], 'ru');
                if (false !== $arCountry)
                    $arLocationTo['COUNTRY_NAME_LANG'] = $arCountry['NAME'];
            }
        }

        $_SESSION['sebekon_yaroute_location'] = $arOrder["LOCATION_TO"];

        $maps = CSebekonDeliveryPrice::getMap('');
        if (self::isOrderEdit())  {
            $maps = CSebekonDeliveryPrice::getMap('', -1, -1);
        }
        //определяем принадлежность точек тем или иным зонам
        $point = $_SESSION['sebekon_yaroute_point'];

        foreach ($maps['MAPS'] as $map) {

            if (CModule::IncludeModule('sale')) {
                //фильтр по местоположениям
                if (is_array($map["PROPS"]['PLACE']['VALUE']) && count($map["PROPS"]['PLACE']['VALUE'])>0 && intval($arOrder["LOCATION_TO"])>0) {

                    if (!in_array($arOrder["LOCATION_TO"], $map["PROPS"]['PLACE']['VALUE'])) {
                        $arLocationTo = CSaleLocation::GetByID($arOrder["LOCATION_TO"]);
                        if (!$arLocationTo ||
                            (
                                !in_array($arLocationTo['ID'], $map["PROPS"]['PLACE']['VALUE']) &&
                                (!$arLocationTo['CITY_ID'] || !in_array($arLocationTo['CITY_ID'], $map["PROPS"]['PLACE']['VALUE']))
                            )
                        ) {
                            continue;
                        }
                    }
                }
            }

            $result[$map['ID']] = $map['ID'];
            foreach ($maps['ZONES'] as $zone) {
                if (!in_array($zone['ID'],$map['PROPS']['ZONES']['VALUE'])) continue;
                $polygon = CSebekonDeliveryPrice::convertToPolygon($zone['PROPS']['ZONE_COORDS']['VALUE']);
                if (CSebekonDeliveryPrice::contains($point,$polygon) || !is_array($point) || self::isOrderEdit()) {
                    $result[$map['ID']] = $map['ID'];
                    break;
                }
            }
        }
        return $result;
    }
}

?>