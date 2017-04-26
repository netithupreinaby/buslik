<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Спасибо!");

$orderId = intval($_REQUEST['id']);
?>
<div style="text-align: center; "><span class="Apple-style-span" style="color: rgb(74, 73, 68); font-family: Arial, Helvetica, sans-serif; "><b><font class="Apple-style-span" size="5">Спасибо за сделанный заказ! </font></b></span></div>

<div style="text-align: center; "><span class="Apple-style-span" style="color: rgb(74, 73, 68); font-family: Arial, Helvetica, sans-serif; ">В ближайшее время наш менеджер свяжется с Вами!<br/>
Номер вашего заказа: <a href="<?=SITE_DIR?>account/orders/detail.php?ID=<?=$orderId?>"><?=$orderId?></a>.</span></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>