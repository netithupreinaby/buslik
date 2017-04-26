<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!CModule::IncludeModule('catalog') || !CModule::IncludeModule('sale') || $_SERVER['REQUEST_METHOD'] != 'POST' || !$_POST['action'])
	return false;

$id_basket_element = IntVal($_POST['id_basket_element']);
$return_basket_small = false; 
switch($_POST['action'])
{
	case 'setQuantity':
		if($_POST['allow_float_q'] == 1){
			$new_quantity = floatval($_POST['new_quantity']);
		}
		else{
			$new_quantity = IntVal($_POST['new_quantity']);
		}
		$basketItem = CSaleBasket::GetByID($id_basket_element);
		if(!$basketItem['ORDER_ID'] && $basketItem['FUSER_ID'] == CSaleBasket::GetBasketUserID())
		{
			if($new_quantity > 0){
				if($_POST['control_quantity'] == 'Y' && $_POST['product_id'])
				{
					$arProduct = CCatalogProduct::GetByID($_POST['product_id']);
					if($arProduct['QUANTITY'] < $new_quantity)
					{
						echo 'err3';
						return ;
					}
				}
				if(CSaleBasket::Update($basketItem['ID'], array('QUANTITY' => $new_quantity)))
					echo $new_quantity;
				else
					echo 'err1';
			}
			else{
				if(CSaleBasket::Delete($basketItem['ID']))
				{
					echo 'del';
					$return_basket_small = true;
				}
				else
					echo 'err2';
			}
		}
	break;
}?>