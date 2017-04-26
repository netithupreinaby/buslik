<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$PRODUCT_ID = 97665;
$COUNT_DATA = 2;
$user_id = CSaleBasket::GetBasketUserID();
$user_cart = CSaleUser::GetList(array('USER_ID' => $user_id));
$deleteResult = CSaleBasket::Delete($user_cart['ID']);

 
$result = Add2BasketByProductID(
                $PRODUCT_ID,
                $COUNT_DATA,array());


/*$arFields = array(
    "PRODUCT_ID" => $PRODUCT_ID,
    "PRODUCT_PRICE_ID" => $_SESSION['catalogGroupId'],
    "QUANTITY" => 1,
    "LID" => LANG,
    "DELAY" => "N",
    "CAN_BUY" => "Y",

);

  $arProps = array();

  $arProps[] = array();



  $arFields["PROPS"] = $arProps;

 $dasf =  CSaleBasket::Add($arFields);*/

?>