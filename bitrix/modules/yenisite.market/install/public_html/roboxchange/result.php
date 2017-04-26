<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация оплаты заказа");

CModule::IncludeModule("yenisite.market");
CModule::IncludeModule("iblock");

$arPaySystem = CMarketPayment::GetByName('robokassa');

// registration info (password #2)
$IsTest = $_REQUEST["IsTest"];
if($IsTest == 1){
	$propNamePass2 = "MERCHANT_PASS_D_2";
}elseif(empty($IsTest) || $IsTest == 0){
	$propNamePass2 = "MERCHANT_PASS_2";
}

$db_props = CIBlockElement::GetProperty($arPaySystem['IBLOCK'], $arPaySystem['ID'], array("sort" => "asc"),Array("CODE"=>$propNamePass2));
while($ar_props = $db_props->GetNext())
{	
	$arrShopParams['SHOP_SECRET_KEY']['VALUE'] = $ar_props['VALUE'];
}

if(empty($arrShopParams))
{
	echo "Please re-configure the options of Robokassa in module yenisite.market. Check passwords.\n";
}

$mrh_pass2 = $arrShopParams['SHOP_SECRET_KEY']['VALUE'];


// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

// check signature
if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}

if(CMarketOrder::GetStatus($inv_id)!='PAYED')
{
	CMarketOrder::SetStatus($inv_id, 'PAYED');
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>


