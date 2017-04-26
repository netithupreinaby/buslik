<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule("yenisite.market");
CModule::IncludeModule("iblock");
$APPLICATION->SetTitle(GetMessage("PLATRON_TITLE_SUCCESS"));
/*
 * Конфигурации и параметры
 */
$strScriptName = CMarketPlatronSignature::getOurScriptName();

$arPaySystem = CMarketPayment::GetByName('Platron');

$db_props = CIBlockElement::GetProperty($arPaySystem['IBLOCK'], $arPaySystem['ID'], array("sort" => "asc"),Array("CODE"=>"SHOP_SECRET_KEY"));
while($ar_props = $db_props->GetNext())
{
	$arrShopParams['SHOP_SECRET_KEY']['VALUE'] = $ar_props['VALUE'];
}


if(empty($arrShopParams))
{
	CMarketPlatronIO::makeResponse($strScriptName, '', 'error',
		'Please re-configure the options of Platron in module yenisite.market. The payment system should have a name "platron", iblock code "payment" and iblock_type "dict"');
}

$strSecretKey = $arrShopParams['SHOP_SECRET_KEY']['VALUE'];

$arrRequest = CMarketPlatronIO::getRequest();

$nOrderId = intval(isset( $_REQUEST["pg_order_id"] ) ? $_REQUEST["pg_order_id"] : 0 );


$bPay = isset($_GET['pay'])?$_GET['pay']:'n';
COption::SetOptionString("yenisite.market","pay",$bPay);
unset($_GET['pay']);


if(!CMarketPlatronSignature::check($arrRequest['pg_sig'], $strScriptName, $arrRequest, $strSecretKey) )
    print("Invalid params.");
else
	if ($nOrderId != 0){
		print(GetMessage("PLATRON_SUCCESS"));
	}
	else
		die("Invalid params.");
  

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
