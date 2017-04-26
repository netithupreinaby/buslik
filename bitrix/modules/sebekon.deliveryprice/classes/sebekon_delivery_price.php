<?php

/**
 *	Класс по работе с зонами доставки
 *
 */

class CSebekonDeliveryPrice {

    public static $loaded = false;
    public static $js_loaded = false;

    /**
     * Проверка принадлежности точки многоугольнику, методом луча в бесконечность 
     * 
     * @param array $point точка, которую нужно проверить array(x=>.., y=>...)
     * @param array $zone многоугольник в виде массива точек array( array(x=>.., y=>...), ...)
     * 
     * @return bool true - Если точка находится в многоугольнике
     */	
    public static function contains ($point, $zone) {
        //сначала проверяем не находится ли точка на границе полигона
        foreach ($zone as $_point) {
            if ($point['x']==$_point['x'] && $point['y']==$_point['y']) return true;
        }

        //теперь проверяем методом луча в бесконечность
        $result = false;

        $intersections = 0;
        $vertices_count = count($zone);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $zone[$i-1];
            $vertex2 = $zone[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return true;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is even, then it's in the polygon.
        if ($intersections % 2 != 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Проверка принадлежности точки составному полигону, методом луча в бесконечность для каждого многоугольника
     * 
     * @param array $point точка, которую нужно проверить array(x=>.., y=>...)
     * @param array $zones массив многоугольник в виде массива точек array( array(x=>.., y=>...), ...)
     * 
     * @return <type>
     */	
    public static function arrayContains ($point, $zones) {
        $total_intersects = 0;
        foreach ($zones as $polygon) {
            if (self::contains($point,$polygon)) $total_intersects++;
        }
        if ($total_intersects%2==1) return true;
        else return false;
    }

    /**
     * Преобразует строку с полигоном от yandex во внутренний формат
     * 
     * @param string $s строка с полигоном в формате yandex
     * 
     * @return array многоугольник в виде массива точек array( array(x=>.., y=>...), ...)
     */
    public static function convertToPolygon ($s) {
        $result = array();
        $s = str_replace(' ','',$s);
        $s = str_replace('[]','',$s);
        $s = str_replace(array(',]','[,',',,'),array(']','[',','),$s);
        $arr = explode('],[',$s);
        foreach ($arr as $k=>$v) {
            $result[] = self::convertToPoint(explode(',',str_replace(array(']','['),'',$v)));
        }

        return $result;
    }

    /**
     * Преобразует строку с полигоном от yandex во внутренний формат
     * 
     * @param string $s строка с полигоном в формате yandex
     * 
     * @return array массив многоугольников в виде массива точек array( array( array(x=>.., y=>...), ...), ...)
     */
    public static function convertToPolygons ($s) {
        $result = array();
        $s = str_replace(' ','',$s);
        $s = str_replace('[]','',$s);
        $s = str_replace(array(',]','[,',',,'),array(']','[',','),$s);
        $polygons = explode(']],[[',$s);
        foreach ($polygons as $k=>$polygon) {
            $polygon = explode('],[',$polygon);
            foreach ($polygon as $_k=>$_v) {
                $result[$k][] = self::convertToPoint(explode(',',str_replace(array(']','['),'',$_v)));
            }
        }
        return $result;
    }

    /**
     * Преобразует точку от yandex во внутренний формат
     * 
     * @param array $s координаты точки
     * 
     * @return array точка во внутреннем формате array(x=>.., y=>...)
     */	
    public static function convertToPoint ($s) {
        return array('x'=>floatval(trim($s[0])), 'y'=>floatval(trim($s[1])));
    }

    /**
     * Получение всей информации по подходящим картам
     * 
     * @param array $mapIds массив идентификаторов карт, если нужно ограничить выборку
     * @param float $order_price  сумма заказа (если не передать, сумма будет рассчитана по текущей корзине)
     * @param float $order_weight   вес заказа (если не передать, вес будет рассчитан по текущей корзине)
     * 
     * @return array массив подходящих карт с зонами
     */	
    public static function getMap ($mapIds, $order_price = false, $order_weight = false) {

        CSebekonDeliveryPrice::$loaded = true;

        $result = array();

        CModule::IncludeModule('iblock');
        $mapFilter = array(
            "IBLOCK_TYPE" => "sebekon_DELIVERY_PRICE",
            "IBLOCK_CODE" => "sebekon_MAPS",
            "ACTIVE" => "Y",
            "ID" => $mapIds
        );
        $zoneFilter = array(
            "IBLOCK_TYPE" => "sebekon_DELIVERY_PRICE",
            "IBLOCK_CODE" => "sebekon_ZONES",
            "ACTIVE"=>"Y",
            'ID'=>0
        );


        if (CModule::IncludeModule('sale')) {
            //Если установлен интернет магазин добавляем фильтр по стоимости и весу заказа
            $ORDER_PRICE = 0;
            $ORDER_WEIGHT = 0;

            if ($order_price!==false || $order_weight!==false) {
                $ORDER_PRICE = $order_price;
                $ORDER_WEIGHT = $order_weight;
            } else {
                $dbBasketItems = CSaleBasket::GetList(array("ALL_PRICE" => "DESC",),array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL","CAN_BUY" => "Y","SUBSCRIBE" => "N",),false,false,array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY"));
                while ($arItems = $dbBasketItems->Fetch()) {
                    if (strlen($arItems["CALLBACK_FUNC"]) > 0) {
                        CSaleBasket::UpdatePrice($arItems["ID"], $arItems["CALLBACK_FUNC"], $arItems["MODULE"], $arItems["PRODUCT_ID"], $arItems["QUANTITY"]);
                    }
                }

                $dbBasketItems = CSaleBasket::GetList(
                    array(),array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"),false,false,
                    array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME", "DISCOUNT_PRICE", "VAT_RATE")
                );
                while ($arBasketItems = $dbBasketItems->GetNext()) {
                    if ($arBasketItems["DELAY"] == "N" && $arBasketItems["CAN_BUY"] == "Y") {
                        $arBasketItems["PRICE"] = roundEx($arBasketItems["PRICE"], SALE_VALUE_PRECISION);
                        $arBasketItems["QUANTITY"] = DoubleVal($arBasketItems["QUANTITY"]);
                        $arBasketItems["WEIGHT"] = DoubleVal($arBasketItems["WEIGHT"]);

                        $ORDER_PRICE += $arBasketItems["PRICE"] * $arBasketItems["QUANTITY"];
                        $ORDER_WEIGHT += $arBasketItems["WEIGHT"] * $arBasketItems["QUANTITY"];
                    }
                }
            }

            if ($ORDER_PRICE>=0) {
                $mapFilter[] = array(
                    'LOGIC'=>'OR',
                    array('PROPERTY_ORDER_PRICE_MIN'=>'', 'PROPERTY_ORDER_PRICE_MAX'=>''),
                    array('=PROPERTY_ORDER_PRICE_MIN'=>false, '=PROPERTY_ORDER_PRICE_MAX'=>false),
                    array('<=PROPERTY_ORDER_PRICE_MIN'=>$ORDER_PRICE, 'PROPERTY_ORDER_PRICE_MAX'=>0),
                    array('<=PROPERTY_ORDER_PRICE_MIN'=>$ORDER_PRICE, '=PROPERTY_ORDER_PRICE_MAX'=>false),
                    array('PROPERTY_ORDER_PRICE_MIN'=>0, '>PROPERTY_ORDER_PRICE_MAX'=>$ORDER_PRICE),
                    array('=PROPERTY_ORDER_PRICE_MIN'=>false, '>PROPERTY_ORDER_PRICE_MAX'=>$ORDER_PRICE),
                    array('<=PROPERTY_ORDER_PRICE_MIN'=>$ORDER_PRICE, '>PROPERTY_ORDER_PRICE_MAX'=>$ORDER_PRICE),
                );
            }

            if ($ORDER_WEIGHT>=0) {
                $mapFilter[] = array(
                    'LOGIC'=>'OR',
                    array('PROPERTY_ORDER_WEIGHT_MIN'=>0, 'PROPERTY_ORDER_WEIGHT_MAX'=>0),
                    array('=PROPERTY_ORDER_WEIGHT_MIN'=>false, '=PROPERTY_ORDER_WEIGHT_MAX'=>false),
                    array('<=PROPERTY_ORDER_WEIGHT_MIN'=>$ORDER_WEIGHT, 'PROPERTY_ORDER_WEIGHT_MAX'=>0),
                    array('<=PROPERTY_ORDER_WEIGHT_MIN'=>$ORDER_WEIGHT, '=PROPERTY_ORDER_WEIGHT_MAX'=>false),
                    array('PROPERTY_ORDER_WEIGHT_MIN'=>0, '>PROPERTY_ORDER_WEIGHT_MAX'=>$ORDER_WEIGHT),
                    array('=PROPERTY_ORDER_WEIGHT_MIN'=>false, '>PROPERTY_ORDER_WEIGHT_MAX'=>$ORDER_WEIGHT),
                    array('<=PROPERTY_ORDER_WEIGHT_MIN'=>$ORDER_WEIGHT, '>PROPERTY_ORDER_WEIGHT_MAX'=>$ORDER_WEIGHT),
                );
            }
        }

        $maps = CIBlockElement::GetList(
            array(),
            $mapFilter
        );
        while($map = $maps->GetNextElement()){
            $arItem = $map->GetFields();
            $arItem["PROPS"] = $map->GetProperties();

            if (CModule::IncludeModule('sale') && $_SESSION['sebekon_yaroute_location']>0) {
                //фильтр по местоположениям
                if (is_array($arItem["PROPS"]['PLACE']['VALUE']) && count($arItem["PROPS"]['PLACE']['VALUE'])>0) {
                    if (!in_array($_SESSION['sebekon_yaroute_location'], $arItem["PROPS"]['PLACE']['VALUE'])) {
                        $arLocationTo = CSaleLocation::GetByID($_SESSION['sebekon_yaroute_location']);
                        if (!$arLocationTo ||
                            (
                                !in_array($arLocationTo['ID'], $arItem["PROPS"]['PLACE']['VALUE']) &&
                                (!$arLocationTo['CITY_ID'] || !in_array($arLocationTo['CITY_ID'], $arItem["PROPS"]['PLACE']['VALUE']))
                            )
                        ){
                            continue;
                        }
                    }
                }
            }

            $result['MAPS'][] = $arItem;

            //если указаны зоны
            if(!empty($arItem["PROPS"]["ZONES"]["VALUE"])){
                if(!is_array($zoneFilter["ID"])){
                    $zoneFilter["ID"] = array($zoneFilter["ID"]);
                };
                if(!is_array($arItem["PROPS"]["ZONES"]["VALUE"])){
                    $arItem["PROPS"]["ZONES"]["VALUE"] = array($arItem["PROPS"]["ZONES"]["VALUE"]);
                };

                $zoneFilter["ID"] = array_merge($zoneFilter["ID"], $arItem["PROPS"]["ZONES"]["VALUE"]);
            }
        }

        $result["ZONES"] = array();
        $zones = CIBlockElement::GetList(
            array('SORT'=>'DESC','ID'=>'ASC'),
            $zoneFilter
        );
        while($zone = $zones->GetNextElement()){
            $arItem = $zone->GetFields();
            $arItem["PROPS"] = $zone->GetProperties();

            $result["ZONES"][] = $arItem;
        }

        $events = GetModuleEvents("sebekon.deliveryprice", "OnMapsFilter");
        while ($arEvent = $events->Fetch())
            ExecuteModuleEventEx($arEvent, array(&$result));

        return $result;
    }

	/**
	 * Вылидируем изменение карты, не даем добавить "отвязанные" зоны
	 * 
	 * @param <type> $arFields 
	 * 
	 * @return <type>
	 */
    function OnBeforeIBlockElementUpdate(&$arFields) {
        if (!CModule::IncludeModule('iblock')) return true;

        $maps = CIBlock::GetList(array(), array('CODE'=>'sebekon_MAPS', 'TYPE'=>'sebekon_DELIVERY_PRICE'))->Fetch();
        if (!$maps) return true;

        $zone = CIBlock::GetList(array(), array('CODE'=>'sebekon_ZONES', 'TYPE'=>'sebekon_DELIVERY_PRICE'))->Fetch();
        if (!$zone) return true;

        $prop = CIBlockProperty::GetList(array(), array('IBLOCK_ID'=>$maps['IBLOCK_ID'], 'CODE'=>'ZONES'))->Fetch();

        if(strlen($arFields["IBLOCK_ID"])==$maps['IBLOCK_ID'] && is_array($arFields['PROPERTY_VALUES'][$prop['ID']]['VALUE'])) {
            $vals = $arFields['PROPERTY_VALUES'][$prop['ID']]['VALUE'];

            $zones = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$zone['IBLOCK_ID'], 'ACTIVE'=>'Y', 'ID'=>$vals), false,false, array('ID','IBLOCK_ID','PROPERTY_ZONE_FROM', 'NAME'));
            while ($z = $zones->Fetch()) {
                if ($z['PROPERTY_ZONE_FROM_VALUE']>0 && !in_array($z['PROPERTY_ZONE_FROM_VALUE'], $vals)) {
                    $zone = CIBlockElement::GetById($z['PROPERTY_ZONE_FROM_VALUE'])->Fetch();
                    global $APPLICATION;
                    $APPLICATION->throwException(str_replace(array('#DEPENDS#','#DEPENDS_FROM#'),array($z['NAME'], $zone['NAME']),GetMessage('SEBEKON_DELIVERYPRICE_ZONDE_DEPENDENS')));
                    return false;
                }
            }
        }
    }
	
	/**
     * Обработчик события добавления заказа
	 * Сохраняем разные данные по доставке в свойствах заказа
     * 
     * @param int $ID 
     * @param mixed $arFields 
     * 
     * @return <type>
     */	
    public static function OnOrderAdd ($ID, $arFields) {
        if (\CModule::IncludeModule('sale')) {
            $order_info =  \CSaleOrder::GetById($ID);
            $handler = \CSaleDeliveryHandler::GetBySID('sebekon_yaroute')->Fetch();

            $mapId = explode(':',$order_info['DELIVERY_ID']);
            $mapId = $mapId[count($mapId)-1];

            if (stripos($order_info['DELIVERY_ID'],'sebekon_yaroute')!==FALSE) {

                //Получить свойство по CODE
                $codeProp       = "SEBEKON_DELIVERY_INFO";
                $nameProp       = \GetMessage('SEBEKON_DELIVERYPRICE_PARAMS_DELIVERY_DATA');
                $arOrderProp = \CSaleOrderProps::GetList(array(),array('CODE' => $codeProp, 'PERSON_TYPE_ID'=>$order_info['PERSON_TYPE_ID']))->Fetch();
                $propId = $arOrderProp['ID'];
                if (!$propId) {
                    /**
                     * Добавить свойство
                     */
                    $arPropFields = array(
                        "PERSON_TYPE_ID" => $order_info['PERSON_TYPE_ID'],
                        "NAME" => $nameProp,
                        "TYPE" => "TEXTAREA",
                        "REQUIED" => "N",
                        "DEFAULT_VALUE" => "",
                        "SORT" => 100,
                        "CODE" => $codeProp,
                        "USER_PROPS" => "N",
                        "IS_LOCATION" => "N",
                        "IS_LOCATION4TAX" => "N",
                        "PROPS_GROUP_ID" => 1,
                        "SIZE1" => 1000,
                        "SIZE2" => 1000,
                        "DESCRIPTION" => "",
                        "IS_EMAIL" => "N",
                        "IS_PROFILE_NAME" => "N",
                        "IS_PAYER" => "N",
                        "UTIL" => 'Y'
                    );

                    $propId = \CSaleOrderProps::Add($arPropFields);
                }

                if ($propId) {
                    $description = '';
                    if ($_SESSION['sebekon_yaroute_point']) {
                        $description  .= \GetMessage('SEBEKON_DELIVERYPRICE_MESS_COORDINATE')."X: {$_SESSION['sebekon_yaroute_point']['x']}, Y: {$_SESSION['sebekon_yaroute_point']['y']}, \n";
                    }
                    if ($_SESSION['sebekon_yaroute_name']) {
                        $description .= \GetMessage('SEBEKON_DELIVERYPRICE_MESS_ADDRESS')." {$_SESSION['sebekon_yaroute_name']}, \n";
                    }
                    if ($_SESSION['sebekon_yaroute_routes']) {
                        $description .= \GetMessage('SEBEKON_DELIVERYPRICE_MESS_ROUTES').number_format($_SESSION['sebekon_yaroute_routes'][$mapId],2,',',' ').GetMessage('sebekon_DP_IBLOCK_KM').", \n";
                    }
                    if ($_SESSION['sebekon_yaroute_prices']) {
                        $description .= \GetMessage('sebekon_DP_MODULE_NAME').": ".number_format($_SESSION['sebekon_yaroute_prices'][$mapId],2,',',' ').GetMessage('sebekon_RUB')."\n";
                    }

                    $arProp = array(
                        "ORDER_ID" => $order_info['ID'],
                        "ORDER_PROPS_ID" => $propId,
                        "NAME" => \GetMessage('SEBEKON_DELIVERYPRICE_PARAMS_DELIVERY_DATA'),
                        "CODE" => $codeProp,
                        "VALUE" => $description
                    );

                    $arOrderProp = \CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $order_info['ID'], 'ORDER_PROPS_ID'=>$propId))->Fetch();



                    if ($arOrderProp) {
                        \CSaleOrderPropsValue::Update($arOrderProp['ID'], $arProp);
                    } else {
                        \CSaleOrderPropsValue::Add($arProp);
                    }
                }
            }
        }
    }

    /**
     * Обработчик события добавления заказа
     * 
     * @param int $ID 
     * @param string $eventName 
     * @param mixed $arFields 
     * 
     * @return <type>
     */	
    public static function OnOrderNewSendEmail ($ID, &$eventName, &$arFields) {
        self::OnOrderAdd($ID, array());
    }

    /**
     * Получаем все картыдоставки
     * 
     * 
     * @return <type>
     */	
    public static function getMaps () {

        CSebekonDeliveryPrice::$loaded = true;

        $result = array();

        CModule::IncludeModule('iblock');
        $mapFilter = array(
            "IBLOCK_TYPE" => "sebekon_DELIVERY_PRICE",
            "IBLOCK_CODE" => "sebekon_MAPS"
        );
        $maps = array();
        $maps_db = CIBlockElement::GetList(
            array(),
            $mapFilter
        );
        while($map = $maps_db->GetNext()){
            $maps[] = $map;
        }

        return $maps;
    }

    /**
     * Строим конфиги для настройки службы доставки
     * 
     * 
     * @return <type>
     */
    public static function getConfig () {
        $arConfig = array();
        if (CModule::IncludeModule('sale')) {
            $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array());
            $bFirst = True;
            while ($ptype = $db_ptype->Fetch()) {
                $arConfig['CONFIG_GROUPS']["PARAMS_".$ptype['ID']] = $ptype['NAME'];
                $list = array(0=>GetMessage("SEBEKON_DELIVERYPRICE_NO_VALUE"));
                $db_props = CSaleOrderProps::GetList(array("SORT" => "ASC"),array("PERSON_TYPE_ID" => $ptype['ID']));
                while ($prop = $db_props->Fetch()) {
                    $list[$prop['ID']] = $prop['NAME'];
                }

                /*$arConfig['CONFIG']['COUNTRY_'.$ptype['ID']]	= array(
                    "TITLE" => GetMessage('SEBEKON_DELIVERYPRICE_PARAMS_COUNTRY'),
                    "TYPE" => "DROPDOWN",
                    "DEFAULT" => "",
                    "GROUP" => "PARAMS_".$ptype['ID'],
                    "VALUES" => $list
                );

                $arConfig['CONFIG']['CITY_'.$ptype['ID']]	= array(
                    "TITLE" => GetMessage('SEBEKON_DELIVERYPRICE_PARAMS_CITY'),
                    "TYPE" => "DROPDOWN",
                    "DEFAULT" => "",
                    "GROUP" => "PARAMS_".$ptype['ID'],
                    "VALUES" => $list
                );*/

                $arConfig['CONFIG']['ADDRESS_'.$ptype['ID']]	= array(
                    "TITLE" => GetMessage('SEBEKON_DELIVERYPRICE_PARAMS_ADDRESS'),
                    "TYPE" => "DROPDOWN",
                    "DEFAULT" => "",
                    "GROUP" => "PARAMS_".$ptype['ID'],
                    "VALUES" => $list
                );
            }
        }
        return $arConfig;
    }

    /**
     * Сбрасываем сессионные данные
     * 
     * @param <type> $arParams 
     * 
     * @return <type>
     */	
    public static function onLogout ($arParams) {
        unset($_SESSION['sebekon_yaroute_point']);
        unset($_SESSION['sebekon_yaroute_name']);
        unset($_SESSION['sebekon_yaroute_prices']);
    }

    /**
     * Подгружаем необходимые скрипты
     * 
     * 
     * @return <type>
     */	
    public static function LoadJs() {
        if (CSebekonDeliveryPrice::$js_loaded) return;
        global $APPLICATION;

        if ($_REQUEST['externalcontext']=='crm') return;
        if (stripos($APPLICATION->GetCurPage(),'/bitrix/admin/')!==FALSE) {
			
			$APPLICATION->AddHeadString('
				<script type="text/javascript">
					var sebekon_deliveryprice_order_click = function(){
						alert("'.GetMessage("SEBEKON_DELIVERYPRICE_ONLY_PUBLIC_ZONE").'");
						return false;
					}
				</script>		
			');
			
			return;
		}

        if (COption::GetOptionString("sebekon.deliveryprice", "DP_DISABLE_JQUERY", "N")=='N') {
            $APPLICATION->AddHeadString('
				<script type="text/javascript" src="/bitrix/js/sebekon.deliveryprice/jquery-1.8.3.min.js"></script>
				<script type="text/javascript">
					var $sebekon_jq_delivery = jQuery.noConflict();  
				</script>		
			', false, 'BEFORE_CSS');

            if ($APPLICATION->GetCurPage()=='/bitrix/admin/sale_delivery_handler_edit.php') {
                $APPLICATION->AddHeadScript('/bitrix/js/sebekon.deliveryprice/jquery-1.8.3.min.js');
            }
        }
        $APPLICATION->AddHeadString('<script type="text/javascript">
		
		if(!window[\'$sebekon_jq_delivery\'] && window[\'jQuery\']) { var $sebekon_jq_delivery = jQuery;}
		if(!window.ymaps){
			document.write(unescape(\'<script type="text/javascript" src="//api-maps.yandex.ru/2.0/?load=package.full,util.json&lang=ru-RU&wizard=sebekon.deliverycalc">%3C/script%3E\'));
		}
		$sebekon_jq_delivery(document).ready(function(){
			$sebekon_jq_delivery(\'body\').append(\''.GetMessage("SEBEKON_DELIVERYPRICE_JS").'\');
		});
		</script>', false, 'DEFAULT');

        $APPLICATION->AddHeadScript('/bitrix/js/sebekon.deliveryprice/deliveryprice.js?v=2.0');

        if (COption::GetOptionString("sebekon.deliveryprice", "DP_DISABLE_BOOTSRAP", "N")=='N') {
            $APPLICATION->AddHeadScript('/bitrix/js/sebekon.deliveryprice/bootstrap.min.js');
        }
        if (COption::GetOptionString("sebekon.deliveryprice", "DP_DISABLE_BOOTSRAP_MODAL", "N")=='N') {
            $APPLICATION->AddHeadScript('/bitrix/js/sebekon.deliveryprice/bootstrap-modal.js');
        }
        if (COption::GetOptionString("sebekon.deliveryprice", "DP_DISABLE_BOOTSRAP_TOOLTIP", "N")=='N') {
            $APPLICATION->AddHeadScript('/bitrix/js/sebekon.deliveryprice/bootstrap-tooltip.js');
        }
        $APPLICATION->AddHeadScript('/bitrix/components/sebekon/delivery.calc/templates/order/script.js');
        $APPLICATION->SetAdditionalCSS("/bitrix/js/sebekon.deliveryprice/css/sebekon_bootstrap.css");

        CSebekonDeliveryPrice::$js_loaded = true;
    }
}

?>