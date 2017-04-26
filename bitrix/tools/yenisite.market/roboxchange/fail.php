<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отказ от оплаты заказа");

$inv_id = $_REQUEST["InvId"];

$filename = $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . 'personal/order/fail.php';

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
				<p><b>
					Вы отказались от оплаты. Заказ №<a href="<?=SITE_DIR?>personal/orders/?ID=<?=$inv_id?>"><?=$inv_id?></a>. <br>
					You have refused payment. Order №<a href="<?=SITE_DIR?>personal/orders/?ID=<?=$inv_id?>"><?=$inv_id?></a>.
				</b></p>
			</div>
			 <!-- /order-created -->
		</div>
	</div>
</div>
<?
}
	
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>


