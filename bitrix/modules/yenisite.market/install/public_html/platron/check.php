<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule("yenisite.market");
CModule::IncludeModule("iblock");

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
/*
$objShop = CSalePaySystemAction::GetList('', array("PS_NAME"=>"Platron"));
$arrShop = $objShop->Fetch();
if(!empty($arrShop)){
	$objShop = CSalePaySystemAction::GetList('', array("PS_NAME"=>"platron"));
	$arrShop = $objShop->Fetch();
}
if(!empty($arrShop))
	$arrShopParams = unserialize($arrShop['PARAMS']);
else
{
	CMarketPlatronIO::makeResponse($strScriptName, '', 'error',
		'Please re-configure the module Platron.PAY in Bitrix CMS. The payment system should have a name "platron" or "Platron"');
}
*/
$strSecretKey = $arrShopParams['SHOP_SECRET_KEY']['VALUE'];

$arrRequest = CMarketPlatronIO::getRequest();

$strSalt = $arrRequest["pg_salt"];

$nOrderAmount = $arrRequest["pg_amount"];
$nOrderId = intval($arrRequest["pg_order_id"]);
/*
 * 
 */

/*
 * check sign
 */
if(!CMarketPlatronSignature::check($arrRequest['pg_sig'], $strScriptName, $arrRequest, $strSecretKey) )
	CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, 'error',
		'signature is not valid', $strSalt);

$arOrder = CMarketOrder::GetByID($nOrderId);
if(!$arOrder)
{
	CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, 'error',
		'order not found', $strSalt);
}
if($nOrderAmount != $arOrder['AMOUNT'])
{
	CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, 'error',
		'amount is not correct', $strSalt);
}

if(CMarketOrder::GetStatus($nOrderId)=='PAYED')
{
	CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, "error",
		"Order alredy payed", $strSalt);
}
/*
if($arrOrder['CANCELED']=="Y")
	CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, 'error',
		'Order canceled', $strSalt);
*/
CMarketPlatronIO::makeResponse($strScriptName, $strSecretKey, "ok",
	"",$strSalt);
