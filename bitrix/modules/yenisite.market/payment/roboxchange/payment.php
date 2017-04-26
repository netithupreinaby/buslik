<?
use Bitrix\Main\Page\Asset;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

IncludeModuleLangFile(__FILE__);

Asset::getInstance()->addCss(BX_ROOT . "/css/yenisite.market/roboxchange/robox_payment.css");

$APPLICATION->SetTitle(GetMessage("ORDER_TITLE"));

$errorParams = array();

// Payment of the set sum with a choice of currency on site ROBOKASSA


// registration info (login, password #1)
$mrh_login = $arResult['ORDER']['PAY_SYSTEM']['MERCHANT_LOGIN'][SITE_ID];

$IsTest = $arResult['ORDER']['PAY_SYSTEM']['SHOP_TESTING_MODE'];
if($IsTest == 1){
	$mrh_pass1 = $arResult['ORDER']['PAY_SYSTEM']['MERCHANT_PASS_D_1'];
}elseif(empty($IsTest) || $IsTest == 0){
	$mrh_pass1 = $arResult['ORDER']['PAY_SYSTEM']['MERCHANT_PASS_1'];
}else{
	$errorParams[] = "Error TESTING_MODE. Please re-configure the options of Robokassa in module yenisite.market. Check passwords.";
}

// number of order
$inv_id = $order_id;;

// order description
$inv_desc = "order number ".$order_id;

// sum of order
$out_summ = $arResult['ORDER']['AMOUNT'];
$out_summ = number_format($out_summ, 2, '.', '');

// code of goods
$shp_item = 1;

// default payment e-currency
if(!empty($arResult['ORDER']['PAY_SYSTEM']['PS_MODE'])){
	$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("ID"=>$arResult['ORDER']['PAY_SYSTEM']['PS_MODE']));
	while($enum_fields = $property_enums->GetNext())
	{
		$in_curr = $enum_fields['XML_ID'];
	}
}else{
	$in_curr = "";
}

// OutSum Currency
$OutSumCurrency = "";

// language
$culture = "";

// encoding
$encoding = ""; 

// E-mail
$Email = $arResult['ORDER']['EMAIL'];

// Expiration Date
$date1 = date("d.m.y H:i", time());
$ExpirationDate = time() - date('Z') + 10800;
$ExpirationDate += $arResult['ORDER']['PAY_SYSTEM']['ORDER_LIVETIME']?$arResult['ORDER']['PAY_SYSTEM']['ORDER_LIVETIME']:3600*24;
$ExpirationDate = date("Y-m-d\TH:i:s", $ExpirationDate);

// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// if (file_exists($_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "personal/history.php")) {
	// $hrefHistory = SITE_DIR . "personal/history.php";
	// $hrefHistoryId = SITE_DIR . "personal/history.php?ID=" . $inv_id;
// }
// if (file_exists($_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "personal/orders/index.php")) {
	// $hrefHistory = SITE_DIR . "personal/orders/";
	// $hrefHistoryId = SITE_DIR . "personal/orders/?ID=" . $inv_id;
// }
// if (file_exists($_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "personal/history/index.php")) {
	// $hrefHistory = SITE_DIR . "personal/history/";
	// $hrefHistoryId = SITE_DIR . "personal/history/" . $inv_id;
// }

$filename = $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . 'personal/order/payment.php';

if (file_exists($filename)) {
	include_once $filename;
}else{
?>
<main class="container order-successful-page">
	<div id="order_form_div" class="row wb order">
		<h1><?=GetMessage("ORDER_TITLE")?></h1>
		<div class="account row">
			<div class="order-created col-xs-12">
				<h3><?=GetMessage("ORDER_FORMED")?></h3>
				<p>
					 <?=GetMessage("YOUR_ORDER")?><a href="<?=SITE_DIR?>personal/orders/?ID=<?=$inv_id?>
					 "><?=$inv_id?></a><?=GetMessage("SUCCESS_FORMED")?><br>
 <br>
					 <?=GetMessage("MESSEGE_BODY_ORDER_1")?><a href="<?=SITE_DIR?>personal/orders/"><?=GetMessage("MESSEGE_BODY_ORDER_2")?></a><?=GetMessage("MESSEGE_BODY_ORDER_3")?>
				</p>

<?
}

// payment form
if(empty($errorParams)){
	print "<div class='formM shadows lifted'><div><form action='https://merchant.roboxchange.com/Index.aspx' target='_blank' method=POST>".
		"<input type='hidden' name='MrchLogin' value='$mrh_login' >".
		"<input type='hidden' name='OutSum' value='$out_summ' >".
		"<input type='hidden' name='InvId' value='$inv_id' >".
		"<input type='hidden' name='Desc' value='$inv_desc'>".
		"<input type='hidden' name='SignatureValue' value='$crc' >".
		"<input type='hidden' name='Shp_item' value='$shp_item'>".
		"<input type='hidden' name='IncCurrLabel' value='$in_curr' >".
		"<input type='hidden' name='Culture' value='$culture' >".
		"<input type='hidden' name='Email' value='$Email' >".
		"<input type='hidden' name='ExpirationDate' value='$ExpirationDate' >".
		"<input type='hidden' name='OutSumCurrency' value='$OutSumCurrency' >".
		"<input type='hidden' name='IsTest' value='$IsTest' >".
		"<input id='pay_button_robokassa' type='submit' value='" . GetMessage('PAY_BUTTON') . "' >".
		"</form><img src='" . BX_ROOT . "/images/yenisite.market/roboxchange/logo-l.png' alt='Robokassa'></div></div>";
}else{
	foreach($errorParams as $errorPrint){
		?>
			<p><b>
				<?=$errorPrint?><br>
			</b></p>
		<?
	}
}

if (!file_exists($filename)) {
?>
			</div>
			 <!-- /order-created -->
		</div>
	</div>
</main>

<?
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");	
?>
