<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!CModule::IncludeModule('yenisite.market') || $_SERVER['REQUEST_METHOD'] != 'POST' || !$_POST['action'])
	return false;
$id_basket_element = intval($_POST['id_basket_element']);
$key = $_POST['key'];
$return_basket_small = false;
switch($_POST['action'])
{
	case 'setQuantity':
		$new_quantity = intval($_POST['new_quantity']);

		if($new_quantity > 0)
		{
			//check available quantity
			$arProduct = CMarketCatalogProduct::GetByID($id_basket_element);
			if($arProduct['QUANTITY_TRACE'] == 'Y' && $arProduct['CAN_BUY_ZERO'] != 'Y' && $arProduct['QUANTITY'] < $new_quantity)
			{
				if (isset($_POST['old_quantity']) && $_POST['old_quantity'] == $arProduct['QUANTITY']) {
					die('err3');
				} else {
					$new_quantity = $arProduct['QUANTITY'];
				}
				//throw new Exception('not available quantity', 1);
			}

			CMarketBasket::setQuantity($key, $new_quantity);
			echo $new_quantity;
		}
		else{
			CMarketBasket::Delete($key);
			echo 'del';
		}
		
		//$addResult = array('STATUS' => 'OK', 'MESSAGE' => $codeAction);
	break;
}?>