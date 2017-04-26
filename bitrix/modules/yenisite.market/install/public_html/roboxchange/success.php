<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Успешное оформление заказа");

CModule::IncludeModule("yenisite.market");
CModule::IncludeModule("iblock");

$arPaySystem = CMarketPayment::GetByName('robokassa');


$IsTest = $_REQUEST["IsTest"];
if($IsTest == 1){
	$propNamePass1 = "MERCHANT_PASS_D_1";
}elseif(empty($IsTest) || $IsTest == 0){
	$propNamePass1 = "MERCHANT_PASS_1";
}


$db_props = CIBlockElement::GetProperty($arPaySystem['IBLOCK'], $arPaySystem['ID'], array("sort" => "asc"),Array("CODE"=>$propNamePass1));
while($ar_props = $db_props->GetNext())
{	
	$arrShopParams['SHOP_SECRET_KEY']['VALUE'] = $ar_props['VALUE'];
}


if(empty($arrShopParams))
{
	echo "Please re-configure the options of Platron in module yenisite.market. Check passwords.\n";
}

$mrh_pass1 = $arrShopParams['SHOP_SECRET_KEY']['VALUE'];

// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

// check signature
if ($my_crc != $crc)
{
  echo "bad sign\n";
  exit();
}

$filename = $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . 'personal/order/success.php';

if (file_exists($filename)) {
	include_once $filename;
}else{
?>
<div class="account row">
	<div class="col-xs-12" id="order_form_div">
		<h1>Оформление заказа</h1>
		<div class="account row">
			<div class="order-created col-xs-12">
				<h3>Заказ сформирован</h3>
				<p>
					 Ваш заказ <b>№<a href="<?=SITE_DIR?>personal/orders/?ID=<?=$inv_id?>
					 "><?=$inv_id?></a></b>.<br>
 <br>
					 Вы можете следить за выполнением своего заказа в <a href="<?=SITE_DIR?>personal/orders/">Персональном разделе сайта</a>. Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и пароль пользователя сайта.
				</p>

				<?
					if(CMarketOrder::GetStatus($inv_id)=='PAYED'){
						?>
						<p><b>
						Операция прошла успешно. Заказ оплачен.<br>
						Operation of payment is successfully completed.
						</b></p>
						<?
					}else{
						?>
						<p><b>
						Операция оплаты прошла успешно, но произошла ошибка базы данных. Обратитесь к администарации сайта для проверки логов оплаты. (сервер робокассы может отвечать с некоторой задержкой)
						</b></p>
						<?
					}
				?>
			</div>
			 <!-- /order-created -->
		</div>
	</div>
</div>
<?
}
	
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>


