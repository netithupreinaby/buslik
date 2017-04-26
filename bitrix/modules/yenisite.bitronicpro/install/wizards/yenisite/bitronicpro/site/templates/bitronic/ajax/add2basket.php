<?
if (!empty($_REQUEST['SITE_ID'])) define('SITE_ID', htmlspecialchars($_REQUEST['SITE_ID']));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");
if(!CModule::IncludeModule(CYSBitronicSettings::getModuleId()))
{
		//YOU MUST DIE!!!
}
if (!empty($_REQUEST['SITE_ID'])) IncludeTemplateLangFile($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');

if ( CModule::IncludeModule('yenisite.geoipstore') )
{
	$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/geoip_store.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));
	$APPLICATION->RestartBuffer();
	global $prices, $stores;
}

if(!function_exists(getBitronicSettings)){
	function getBitronicSettings($key){
		$k = $key;
		if($GLOBALS["USER"]->GetID()){        
			$key .= "_UID_".$GLOBALS["USER"]->GetID();
			$value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
			if(!$value)
				$value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $k, "");
		}
		else{
			$value = $GLOBALS["APPLICATION"]->get_cookie($key);
			if(!$value)
				$value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $k, "");
		}
		return $value;
	}
}
global $ys_options ;
$ys_options["action_add2b"] = getBitronicSettings("ACTION_ADD2B");
$ys_options["action_add2b"] = $ys_options["action_add2b"] ? $ys_options["action_add2b"]:"popup_window";
if(array_key_exists("action_add2b",$_REQUEST))
	$ys_options["action_add2b"] = ($_REQUEST["action_add2b"] == 'popup_window') ? "popup_window" : "popup_basket";

// защита от внешних обращение bxsessid
$_REQUEST['iblock_id'] = intval($_REQUEST['iblock_id']);
if(!check_bitrix_sessid() || $_REQUEST['iblock_id'] <= 0)
	die();

if($_REQUEST['main_page'] != 'Y')
	$curAjaxParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_{$_REQUEST['iblock_id']}", '');
else
	$curAjaxParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_main_page", '');

if($curAjaxParams)
	$arParams = unserialize($curAjaxParams) ;

if(!count($arParams) || !(CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && CModule::IncludeModule('iblock')))
	die('Error in params or modules');

if (array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST))
{

	if(array_key_exists($arParams["ACTION_VARIABLE"]."BUY", $_REQUEST))
		$action = "BUY";
	elseif(array_key_exists($arParams["ACTION_VARIABLE"]."ADD2BASKET", $_REQUEST))
		$action = "ADD2BASKET";
	else
		$action = strtoupper($_REQUEST[$arParams["ACTION_VARIABLE"]]);

//  ---- modify by Ivan, 09.10.2013, more change ..., fixed buy complete set. ---- 
	
	/* if(is_array($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]))
		echo 'yes';
	else
		echo 'no' ; */
	// $productID = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]);
	$arProductID = array() ;


	if ($action == "ADD2BASKET" || $action == "COMPARE_ADD2BASKET" || $action == "ADD2BASKET_SET" || $action == "BUY" || $action == "SUBSCRIBE_PRODUCT" /* && $productID > 0 */)
	{

		if($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]] && !is_array($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]))
		{
			$bCompleteSet = false ;
			$arProductID[] = $_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]] ;
		}
		elseif(is_array($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]))
		{
			// start modify by Ivan, 09.10.2013 [2] ---->
			if(count($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]) > 1)
				$bCompleteSet = true ;
			// <---- end modify by Ivan, 09.10.2013 [2]
			$arProductID = $_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]] ;
		}
		else
		{
			die('Error product id');
		}
		
		//Get item list
		$arItems = array();
		$rsItems = CIBlockElement::GetList(array(), array('ID' => $arProductID), false, false);
		while($arItem = $rsItems->GetNext()) {
			$arItem['IBLOCK_ID'] = intval($arItem['IBLOCK_ID']);
			$arItem['ID'] = intval($arItem['ID']);
			$arItem['PROPERTIES'] = array();
			$arItems[$arItem['ID']] = $arItem;
		}
		if (!empty($arParams["PRODUCT_PROPERTIES"])) {
			//Get items properties for buy with properties
			$arPropFilter = array(
				'ID' => $arProductId,
				'IBLOCK_ID' => $_REQUEST['iblock_id']
			);
			CIBlockElement::GetPropertyValuesArray($arItems, $_REQUEST['iblock_id'], $arPropFilter);
		}
		
		$strError = '';
		
		//Item iteration
		foreach($arProductID as $productID)
		{
			$bSuccess = true;
			$productID = intval($productID) ;
			if ($productID > 0)
			{
				//Check quantity
				$QUANTITY = 0;
				if($arParams["USE_PRODUCT_QUANTITY"])
				{
					if(is_array($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]])) {
						$QUANTITY = $_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]][$productID];
					} else {
						$QUANTITY = $_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]];
					}
					
					if ('Y' === $arParams['QUANTITY_FLOAT'] || class_exists('CCatalogMeasureRatio')) {
						$QUANTITY = doubleval($QUANTITY);
					} else {
						$QUANTITY = intval($QUANTITY);
					}
				}
				if (0 >= $QUANTITY && class_exists('CCatalogMeasureRatio'))
				{
					$rsRatios = CCatalogMeasureRatio::getList(
						array(),
						array('PRODUCT_ID' => $productID),
						false,
						false,
						array('PRODUCT_ID', 'RATIO')
					);
					if ($arRatio = $rsRatios->Fetch())
					{
						$intRatio = (int)$arRatio['RATIO'];
						$dblRatio = doubleval($arRatio['RATIO']);
						$QUANTITY = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
					}
				}
				if (0 >= $QUANTITY) {
					$QUANTITY = 1;
				}

				$product_properties = array();

				//$rsItems = CIBlockElement::GetList(array(), array('ID' => $productID), false, false);	
				//if ($arItem = $rsItems->Fetch())
				if (array_key_exists($productID, $arItems))
				{
					$arItem = &$arItems[$productID];
							
					if ($arItem['IBLOCK_ID'] == $arParams["IBLOCK_ID"] || !$ibResult[$productID] = CCatalogSKU::GetInfoByOfferIBlock($arItem['IBLOCK_ID']))
					{
						if (!empty($arParams["PRODUCT_PROPERTIES"]))
						{
							//Check item properties for buy with properties
							$arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
								$arParams["IBLOCK_ID"],
								$arItem["ID"],
								$arParams["PRODUCT_PROPERTIES"],
								$arItem["PROPERTIES"]
							);
							
							if (count($arItem["PRODUCT_PROPERTIES"]) > 0) {
								$arItem['CHECK_PRODUCT_PROPERTIES'] = array_keys($arItem["PRODUCT_PROPERTIES"]);
								
								if ((
									array_key_exists($arParams["PRODUCT_PROPS_VARIABLE"], $_REQUEST)
									&& is_array($_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]]))
								|| (array_key_exists($arParams["PRODUCT_PROPS_VARIABLE"].$productID, $_REQUEST)
									&& is_array($_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"].$productID]))
								)
								{
									$arProp = array_key_exists($arParams["PRODUCT_PROPS_VARIABLE"].$productID, $_REQUEST)
									        ? $_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"].$productID]
											: $_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]];
									$product_properties = CIBlockPriceTools::CheckProductProperties(
										$arItem["IBLOCK_ID"],
										$productID,
										$arItem['CHECK_PRODUCT_PROPERTIES'],
										$arProp
									);
									if (!is_array($product_properties)) {
										$strError .= '<p>'.GetMessage("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR").': '.$arItem['NAME'].'.</p>';
										$bSuccess = false;
									}
								}
								else
								{
									$strError .= '<p>'.GetMessage("CATALOG_EMPTY_BASKET_PROPERTIES_ERROR").': '.$arItem['NAME'].'.</p>';
									$bSuccess = false;
								}
							}
						}
					}
					else
					{
						//Check SKU properties
						if(is_array($ibResult[$productID]))
							$arParams["IBLOCK_ID"] = $ibResult[$productID]['PRODUCT_IBLOCK_ID'];
						if (!empty($arParams["OFFERS_CART_PROPERTIES"]))
						{
							$product_properties = CIBlockPriceTools::GetOfferProperties(
								$productID,
								$arParams["IBLOCK_ID"],
								$arParams["OFFERS_CART_PROPERTIES"]
							);
						}
						else if (!empty($arParams["OFFER_TREE_PROPS"]))
						{
							$product_properties = CIBlockPriceTools::GetOfferProperties(
								$productID,
								$arParams["IBLOCK_ID"],
								$arParams["OFFER_TREE_PROPS"]
							);
						}
					}
				}
				else
				{
					$strError .= '<p>' . GetMessage('CATALOG_PRODUCT_NOT_FOUND') . ' (' . $productID . ').</p>';
					$bSuccess = false;
				}

				$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
				$arNotify = unserialize($notifyOption);
				$arRewriteFields = array();
				if ($action == "SUBSCRIBE_PRODUCT" && $arNotify[SITE_ID]['use'] == 'Y')
				{
					$arRewriteFields["SUBSCRIBE"] = "Y";
					$arRewriteFields["CAN_BUY"] = "N";
				}
				
				//if(!$baskID = Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, $product_properties))
					//$strError = 'API error Add2BasketByProductID';
				if ($bSuccess)
				{
					if(!$baskID = Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, $product_properties))
					{
						if ($ex = $APPLICATION->GetException())
							$strError .= $ex->GetString() . ': ' . $arItem['NAME'] . '.';
						else
							$strError .= GetMessage("CATALOG_ERROR2BASKET") . ': ' . $arItem['NAME'] . '.';
						$bSuccess = false;
					}
				}
					
				// SERVICES
				if(count($_REQUEST['SERVICE']) > 0 && 
				   $bSuccess &&
					((is_array($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]) 
						&& $productID==intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]][0])) 
					|| ($productID==intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]])) ))
				{
					$bask = CSaleBasket::GetByID($baskID);
					$price = $bask['PRICE'];
					$name = $bask['NAME'];
					$name_str = array();
					foreach($_REQUEST['SERVICE'] as $serviceId => $servicePrice)
					{
						$el = CIBlockElement::GetList(array(), array("ID" => $serviceId), false, false, array("NAME"))->Fetch();
						$name_str[] = $el['NAME'];
						if(intval($servicePrice) <= 0 )
							$servicePrice = CPrice::GetBasePrice($serviceId);
						$price += $servicePrice;
					}
					$name = $name." (".implode(", ", $name_str).")";
					$arFields = array(
						"NAME" => $name,
						"PRICE" => $price,
						"CAN_BUY" => "Y",
						"CALLBACK_FUNC" => "",
						"PRODUCT_PROVIDER_CLASS" => "",
					);
					CSaleBasket::Update($baskID, $arFields);
				}
			}
		}

		//show basketfly
		$arBasketSmallParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "BasketSmallParams", '');
		if($arBasketSmallParams)
		{
			$arBasketSmallParams = unserialize($arBasketSmallParams) ;
			if(count($arBasketSmallParams))
			{
				$arBasketSmallParams['IT_IS_AJAX_CALL'] = 'Y' ;
				$arBasketSmallParams['YS_BS_OPENED'] = $ys_options["action_add2b"] !=  'popup_window' ? 'Y' : 'N';
				$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "bitronic", $arBasketSmallParams, false);
			}
		}
		/* if ($action == "BUY")
			LocalRedirect($arParams["BASKET_URL"]);
		else
			LocalRedirect($APPLICATION->GetCurPageParam("", array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]))); */
		
		//show popup window
		if($ys_options['action_add2b'] == 'popup_window') // remove condition $bCompleteSet , modify by Ivan, 09.10.2013 [2]
		{
			echo '<!-- add2basket -->';
			$arADD2BParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ADD2BParams_{$_REQUEST['iblock_id']}", '');
			$arADD2BParams = unserialize($arADD2BParams);
			if(count($arADD2BParams) && empty($strError))
			{
				$arADD2BParams['SET_TITLE'] = "N";
				$arADD2BParams['SET_STATUS_404'] = "N";
				$arADD2BParams['ADD_SECTIONS_CHAIN'] = "N";
				$arADD2BParams['CACHE_TYPE'] = "N";
				$arADD2BParams['PROPERTY_CODE'] = array('MORE_PHOTO');
				
				// start modify by Ivan, 09.10.2013 [2]
				if(is_array($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]))
				{
					$element_id = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]][0]);
				}
				else
				{
					$element_id = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]) ;
				}
				
				if(intval($_REQUEST['iblock_id_sku']) > 0)
					$arADD2BParams['IBLOCK_ID'] = intval($_REQUEST['iblock_id_sku']);
				elseif(is_array($ibResult[$element_id]))
					$arADD2BParams['IBLOCK_ID'] = $ibResult[$element_id]['IBLOCK_ID'];
				else
					$arADD2BParams['IBLOCK_ID'] = $_REQUEST['iblock_id'] ;

				$arADD2BParams['ELEMENT_ID'] = $element_id;
				// end modify by Ivan, 09.10.2013 [2]
				
				if(isset($name))	$arADD2BParams['NAME'] = $name;
				if(isset($price))	$arADD2BParams['PRICE'] = $price;
				$_REQUEST = array();
				$_POST = array();
				$_GET = array();

				//for buy with props
				if (is_array($product_properties)) {
					$arADD2BParams['PRODUCT_PROPS'] = $product_properties;
				}
				
				$APPLICATION->IncludeComponent("bitrix:catalog.element", "add2b_popup",	$arADD2BParams,
					$component,
					array("HIDE_ICONS" => "Y")
				);
			}
		}
		
		if (!empty($strError))
		{
			echo '<!-- errors -->', $strError;
		}
		
	}
$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/counter_ya_metrika.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
}?>