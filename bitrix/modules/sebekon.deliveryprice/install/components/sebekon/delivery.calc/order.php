<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!CModule::IncludeModule("sebekon.deliveryprice") || !CModule::IncludeModule("sale")) {
    die();
}

$mapIds = $_REQUEST["maps"];
if ($_REQUEST['action'] == 'prices' && count($_REQUEST['prices'])>0 && count($_REQUEST['coords'])==2) {

	if (!function_exists('iconv_utf_convert')) {
		function iconv_utf_convert ($val) {
			if (is_array($val)) {
				foreach ($val as $k=>$v) {
					$val[$k] = iconv_utf_convert($v);
				}
			} else {
				$val = iconv("UTF-8", SITE_CHARSET, $val);
			}
			return $val;
		}
	}

	if(ToUpper(SITE_CHARSET)!="UTF-8"){
		$_REQUEST = iconv_utf_convert($_REQUEST);
	}

	$_SESSION['sebekon_yaroute_point'] = array('x'=>floatval($_REQUEST['coords'][0]),'y'=>floatval($_REQUEST['coords'][1]));
	$_SESSION['sebekon_yaroute_name'] = htmlspecialcharsEx($_REQUEST['name']);
	$_SESSION['sebekon_yaroute_prices'] = array();
	$_SESSION['sebekon_yaroute_routes'] = array();
	foreach ($_REQUEST['prices'] as $mapId=>$price) {
		$_SESSION['sebekon_yaroute_prices'][intval($mapId)] = floatval($price['DELIVERY_PRICE']);
		$_SESSION['sebekon_yaroute_routes'][intval($mapId)] = floatval($price['LENGTH']);
	}
	
	//CSaleDeliveryHandler::ResetAll();
	
	$events = GetModuleEvents("sebekon.deliveryprice", "OnOrderShippingPriceCalculated");
	while ($arEvent = $events->Fetch())
		ExecuteModuleEventEx($arEvent, array(&$_SESSION['sebekon_yaroute_prices']));
	
	echo json_encode(array('1'=>'1'));
	die();
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");	
}
?>
<link href="/bitrix/components/sebekon/delivery.calc/templates/order/style.css?v=3" type="text/css" rel="stylesheet" />
<?$APPLICATION->IncludeComponent(
	"sebekon:delivery.calc",
	"order",
	Array(
		"MAP" => $mapIds,
		"SHOW_ROUTE" => "N",
		"ADD2BASKET" => "N"
	)
);?>