<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arParams["ACTION_VARIABLE"]=trim($arParams["ACTION_VARIABLE"]);
if(strlen($arParams["ACTION_VARIABLE"])<=0|| !preg_match("^[A-Za-z_][A-Za-z01-9_]*$", $arParams["ACTION_VARIABLE"]))
	$arParams["ACTION_VARIABLE"] = "action";
$arParams["PRODUCT_ID_VARIABLE"]=trim($arParams["PRODUCT_ID_VARIABLE"]);
if(strlen($arParams["PRODUCT_ID_VARIABLE"])<=0|| !preg_match("^[A-Za-z_][A-Za-z01-9_]*$", $arParams["PRODUCT_ID_VARIABLE"]))
	$arParams["PRODUCT_ID_VARIABLE"] = "id";


$quantity = htmlspecialchars($_REQUEST["quantity"]);


CModule::IncludeModule("iblock");

$strError = "";
if (array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST))
{
	$action = strtoupper(htmlspecialchars($_REQUEST["action"]));
	$actionBUY = strtoupper(htmlspecialchars($_REQUEST["actionBUY"]));
	$actionADD2BASKET = strtoupper(htmlspecialchars($_REQUEST["actionADD2BASKET"]));
	$productID = intval(htmlspecialchars($_REQUEST["id"]));
	if(($action == "ADD2BASKET" || $action == "BUY") && $productID > 0)
	{
		if(CModule::IncludeModule("yenisite.market"))
		{
			$addByAjax = isset($_REQUEST['ajax_basket']) && $_REQUEST['ajax_basket'] === 'Y';

			$res = CIBlockElement::GetByID($productID);
			if($el = $res->GetNextElement())
			{
				$fields = $el->GetFields();
				if(!CMarketCatalog::IsCatalog($fields["IBLOCK_ID"])){ShowError(GetMessage('MARKET_IBLOCK_NOT_CATALOG',array("#IBLOCK_ID#" => $fields["IBLOCK_ID"]))); return 0;}
			}

			$successfulAdd = CMarketBasket::Add($productID, $_REQUEST["prop"], $quantity);

			if ($addByAjax)
			{
				if ($successfulAdd)
				{
					$addResult = array('STATUS' => 'OK', 'MESSAGE' => 'OK');//GetMessage('CATALOG_SUCCESSFUL_ADD_TO_BASKET'));
				}
				else
				{
					$addResult = array('STATUS' => 'ERROR', 'MESSAGE' => $strError);
				}
				$APPLICATION->RestartBuffer();
				echo json_encode($addResult);
				die();
			}
			else
			{
				if($action == "ADD2BASKET"){
					if(!empty($_SERVER['HTTP_REFERER']))
						$page = $_SERVER['HTTP_REFERER'];
					else
						$page = $APPLICATION->GetCurPageParam("", array("action", "id"));
					LocalRedirect($page);   
				}

				if($action == "BUY" && !$actionADD2BASKET) {
					LocalRedirect($arParams["BASKET_URL"]);
				}
			}
		}
	}
}
if(strlen($strError)>0)
{
	ShowError($strError);
	return;
}

$arResult['ITEMS'] = array();

	if(CModule::IncludeModule("yenisite.market"))
	{
		if(is_array($_SESSION["YEN_MARKET_BASKET"][SITE_ID]))
		{
			$arResult['PROPERTIES'] = array();
			$arResult['FIELDS'] = array();

			foreach($_SESSION["YEN_MARKET_BASKET"][SITE_ID] as $key=>$value)
			{

				$res = CMarketBasket::DecodeBasketItems($key);

				if(!is_array($arResult["PROPERTIES"][$res["ID"]]))
				{
					$ob_el = CIBlockElement::GetByID($res["ID"]);
					if($el = $ob_el->GetNextElement())
					{
						$arResult["PROPERTIES"][$res["ID"]] = $el->GetProperties();
						$arResult["FIELDS"][$res["ID"]] = $el->GetFields();
					}
				}
				$res["FIELDS"] = $arResult["FIELDS"][$res["ID"]];
				$res["COUNT"] = $value["YEN_COUNT"];
				$res['KEY'] = $key;

				$res['PRODUCT_PROPERTIES'] = array();
				foreach($res["PROPERTIES"] as $key1=>$value1)
				{
					$arProp = $arResult["PROPERTIES"][$res["ID"]][$key1];
					$res["PRODUCT_PROPERTIES"][$key1] = array("VALUE" => $res["PROPERTIES"][$key1], "NAME" => $arProp["NAME"]);

					if($arProp["PROPERTY_TYPE"] == "L")
					{
						$db_enum = CIBlockProperty::GetPropertyEnum($arProp["ID"], array(), array());
						while($enum = $db_enum->Fetch()) {
							if($enum["ID"] == $value1) {
								$res["PRODUCT_PROPERTIES"][$key1]['VALUE'] = $enum["VALUE"];
								break;
							}
						}
					}
					else if($arProp["PROPERTY_TYPE"] == "S" && $arProp["USER_TYPE"] == "directory")
					{
						if (!array_key_exists('DISPLAY_VALUE', $arProp)) {
							$arProp = CIBlockFormatProperties::GetDisplayValue($res['FIELDS'], $arProp, '');
							$arResult["PROPERTIES"][$res["ID"]][$key1] = $arProp;
						}
						$arDisplayValue = $arProp['DISPLAY_VALUE'];
						if (is_array($arDisplayValue)) {
							foreach ($arProp['VALUE'] as $xmlKey => $xmlId) {
								if ($xmlId == $value1) {
									$res['PRODUCT_PROPERTIES'][$key1]["VALUE"] = $arDisplayValue[$xmlKey];
									break;
								}
							}
						} else {
							$res["PRODUCT_PROPERTIES"][$key1]['VALUE'] = $arDisplayValue;
						}
						unset($arDisplayValue);
					}
					unset($arProp);
				}
				
				$prices = CMarketPrice::GetItemPriceValues($res["ID"]);
				foreach($prices as $key=>$value)
					if(CMarketPrice::IsCanAdd($key))
					{
						$res["PRICE"][$key] = $value;
						$res["MIN_PRICE"] = $value;
					}

				foreach($res["PRICE"] as $price)
					if($price < $res["MIN_PRICE"])
						$res["MIN_PRICE"] = $price;

				$arResult["ITEMS"][] = $res;
			}
		}
			$arResult["COMMON_PRICE"] = 0;
			$arResult["COMMON_COUNT"] = 0;
		if(is_array($arResult["ITEMS"]))
		{
			foreach($arResult["ITEMS"] as $key => $arElement)
			{
				$arResult["COMMON_PRICE"] += $arElement["MIN_PRICE"]*$arElement["COUNT"];
				$arResult["COMMON_COUNT"]++;
			}
		}
	}

	$this->IncludeComponentTemplate();

?>
