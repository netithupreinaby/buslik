<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule("yenisite.market");
IncludeModuleLangFile(__FILE__);
$arrRequestMethods = array("POST", "GET");
$arrUserRedirectMethods = array("POST", "GET", "AUTOPOST", "AUTOGET");

$strCustomerEmail = "";
$strCustomerPhone = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && trim($_POST["SET_NEW_USER_DATA"])!="")
{
	if(!empty($_POST["NEW_EMAIL"]))
		$strCustomerEmail = $_POST["NEW_EMAIL"];
	if(!empty($_POST["NEW_PHONE"]))
		$strCustomerPhone = $_POST["NEW_PHONE"];

}
if(empty($strCustomerEmail))
	 $strCustomerEmail = $arResult['ORDER']['EMAIL'];
if(empty($strCustomerPhone))
	$strCustomerPhone = $arResult['ORDER']['PHONE'];

if(!CMarketPlatronIO::emailIsValid($strCustomerEmail) || !CMarketPlatronIO::checkAndConvertUserPhone($strCustomerPhone)){
	
	echo "
		<form method=\"POST\" action=\"".POST_FORM_ACTION_URI."\">";
	if(!CMarketPlatronIO::checkAndConvertUserPhone($strCustomerPhone))
		echo "
		<p><font color=\"Red\">".GetMessage('INVALID_PHONE')."<br>
		(формат 7**********)</font></p>
		<input type=\"text\" name=\"NEW_PHONE\" size=\"30\" value=\"$strCustomerPhone\" />";
	if(!CMarketPlatronIO::emailIsValid($strCustomerEmail))
		echo "
			<p><font color=\"Red\">".GetMessage('INVALID_EMAIL')."</font></p>
			<input type=\"text\" name=\"NEW_EMAIL\" size=\"30\" value=\"$strCustomerEmail\" />";
	echo "<br><br>
			<input type=\"submit\" name=\"SET_NEW_USER_DATA\" value=\"".GetMessage('USE_NEW')." ".
			(!CMarketPlatronIO::checkAndConvertUserPhone($strCustomerPhone)? GetMessage('PHONE') : "").
			(!CMarketPlatronIO::checkAndConvertUserPhone($strCustomerPhone) && !CMarketPlatronIO::emailIsValid($strCustomerEmail)? GetMessage('AND') : "").
			(!CMarketPlatronIO::emailIsValid($strCustomerEmail)? GetMessage('EMAIL') : "").
			"\" />
	</form>";
	exit();
}

$nAmount = $arResult['ORDER']['AMOUNT'];
$nMerchantId = $arResult['ORDER']['PAY_SYSTEM']['SHOP_MERCHANT_ID'];
$strSecretKey = $arResult['ORDER']['PAY_SYSTEM']['SHOP_SECRET_KEY'];
$bTestingMode = $arResult['ORDER']['PAY_SYSTEM']['SHOP_TESTING_MODE'];
$nOrderId = $order_id;
//$nOrderLivetime = IntVal(CSalePaySystemAction::GetParamValue("BILL_LIFETIME"));

$nOrderLivetime = $arResult['ORDER']['PAY_SYSTEM']['ORDER_LIVETIME'];

$strSiteUrl = $arResult['ORDER']['PAY_SYSTEM']['SITE_URL'];
$strCheckUrl = $arResult['ORDER']['PAY_SYSTEM']['CHECK_URL'];
$strResultUrl = $arResult['ORDER']['PAY_SYSTEM']['RESULT_URL'];
$strRequestMethod = $arResult['ORDER']['PAY_SYSTEM']['REQUEST_METHOD'];

$strSuccessUrl = $arResult['ORDER']['PAY_SYSTEM']['SUCCESS_URL'];
$strSuccessUrlMethod = $arResult['ORDER']['PAY_SYSTEM']['SUCCESS_URL_METHOD'];
$strFailureUrl = $arResult['ORDER']['PAY_SYSTEM']['FAILURE_URL'];
$strFailureUrlMethod = $arResult['ORDER']['PAY_SYSTEM']['FAILURE_URL_METHOD'];


$nAmount = number_format($nAmount, 2, '.', '');

$bPay = COption::GetOptionString("yenisite.market", "pay", "n");

$arrRequest['pg_salt'] = uniqid();
$arrRequest['pg_merchant_id'] = $nMerchantId;
$arrRequest['pg_order_id']    = $nOrderId;
if(!empty($nOrderLivetime))
	$arrRequest['pg_lifetime']   = $nOrderLivetime;
else	
	$arrRequest['pg_lifetime']    = 3600*24;

$arrRequest['pg_amount']      = $nAmount;
$arrRequest['pg_description'] = "order number #".$nOrderId;

$arrRequest['pg_user_phone'] = $strCustomerPhone;
$arrRequest['pg_user_contact_email'] = $strCustomerEmail;
$arrRequest['pg_user_mail'] = $strCustomerEmail;

if(!empty($strSiteUrl))
	$arrRequest['pg_site_url']   = $strSiteUrl;
else
	$arrRequest['pg_sire_url']		= "http://".$_SERVER['HTTP_HOST']; 
if(!empty($strCheckUrl))
	$arrRequest['pg_check_url']   = $strCheckUrl;
else
	$arrRequest['pg_check_url']		= "http://".$_SERVER['HTTP_HOST']."/yenisite.market/platron/check.php"; 
if(!empty($strResultUrl))
	$arrRequest['pg_result_url']	= $strResultUrl;
else
	$arrRequest['pg_result_url'] = "http://".$_SERVER['HTTP_HOST']."/yenisite.market/platron/result.php";
if(!empty($strRequestMethod) && array_search($strRequestMethod, $arrRequestMethods) !== false)
	$arrRequest['pg_request_method']	= $strRequestMethod;
else
	$arrRequest['pg_request_method']	= 'POST';

if(!empty($strSuccessUrl))
	$arrRequest['pg_success_url']   = $strSuccessUrl;
else
	$arrRequest['pg_success_url']   = "http://".$_SERVER['HTTP_HOST']."/yenisite.market/platron/success.php";
if(!empty($strSuccessUrlMethod) && array_search($strSuccessUrlMethod, $arrUserRedirectMethods) !== false)
	$arrRequest['pg_success_url_method']	= $strSuccessUrlMethod;
else
	$arrRequest['pg_success_url_method']	= 'AUTOPOST';
if(!empty($strFailureUrl))
	$arrRequest['pg_failure_url']   = $strFailureUrl;
else
	$arrRequest['pg_failure_url']   = "http://".$_SERVER['HTTP_HOST']."/yenisite.market/platron/failure.php";
if(!empty($strFailureUrlMethod) && array_search($strFailureUrlMethod, $arrUserRedirectMethods) !== false)
	$arrRequest['pg_failure_url_method']	= $strFailureUrlMethod;
else
	$arrRequest['pg_failure_url_method']	= 'AUTOPOST';

if($bTestingMode)
	$arrRequest['pg_testing_mode']	= '1';

// Добавить кодировку, телефон и email

/*
 * Запрос на Platron
 */
$arrRequest['pg_sig'] = CMarketPlatronSignature::make('payment.php', $arrRequest, $strSecretKey);
$strQuery = http_build_query($arrRequest);
//echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
if($bPay == 'y' || ($_SERVER['PHP_SELF'] != '/failure.php' && $_SERVER['PHP_SELF'] != '/yenisite.market/platron/failure.php'))
   header("Location: https://www.platron.ru/payment.php?".$strQuery);
?>