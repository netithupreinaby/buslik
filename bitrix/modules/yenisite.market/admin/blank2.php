<?
//header("Content-Type: application/msword; charset=windows-1251");
//header("Content-Disposition: attachment; filename=Praice.rtf");
//header("Pragma: no-cache");
//header("Expires: 0");
/*?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Untitled Document</title>
</head>

<body>

<table style="width:600px; font-size:14px; font-family:'Times New Roman', Times, serif; border-collapse:collapse;">
<tr>
<td colspan="3" style="text-align:right; text-transform:uppercase;">ТОВАРНЫЙ ЧЕК №</td>
<td style="width: 30px;"><div style="border-bottom:1px #000 solid; ">&nbsp;<?=$_REQUEST["ID"]?></div></td>
<td style="text-align:right;">от </td>
<td><div style="border-bottom:1px #000 solid;">&nbsp; <?=date('d/m');?></div></td>

<td><div style="border-bottom:1px #000 solid;">&nbsp;</div></td>
<td>2010 г.</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8">Продавец: ИП Кудренко М.А.</td>
</tr>

<tr>
<td colspan="8">ОГРН: 310032702100129 ИНН: 03260571209</td>
</tr>
<tr>
<td colspan="8">Адрес: www.igrivoe-nastroenie.ru Тел.: 277-110</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Наименование товара</td>

<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Кол-во</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Цена</td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Сумма</td>
</tr>

<? $i=0; foreach($_REQUEST["ITEMS"] as $val):?>
<?
list($id, $size, $price, $quantity, $name) = explode('#', $val);
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$name?> (<?=$size?>)</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity?></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$price?></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity*$price?></td>
</tr>
<?$i++; endforeach?>
<?
if($i < 7):
while($i<7)
{
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
</tr>
<?
$i++;
}
endif;
?>

<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="4"><div style="border-bottom:1px #000 solid;">Итого</div></td>
<td></td>
<td colspan="3"><div style="border-bottom:1px #000 solid;">Подпись</div></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
</table>

<table style="width:600px; font-size:14px; font-family:'Times New Roman', Times, serif; border-collapse:collapse;">
<tr>
<td colspan="3" style="text-align:right; text-transform:uppercase;">ТОВАРНЫЙ ЧЕК №</td>
<td style="width: 30px;"><div style="border-bottom:1px #000 solid; ">&nbsp;<?=$_REQUEST["ID"]?></div></td>
<td style="text-align:right;">от </td>
<td><div style="border-bottom:1px #000 solid;">&nbsp; <?=date('d/m');?></div></td>

<td><div style="border-bottom:1px #000 solid;">&nbsp;</div></td>
<td>2010 г.</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8">Продавец: ИП Кудренко М.А.</td>
</tr>

<tr>
<td colspan="8">ОГРН: 310032702100129 ИНН: 03260571209</td>
</tr>
<tr>
<td colspan="8">Адрес: www.igrivoe-nastroenie.ru Тел.: 277-110</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Наименование товара</td>

<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Кол-во</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Цена</td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Сумма</td>
</tr>

<? $i=0; foreach($_REQUEST["ITEMS"] as $val):?>
<?
list($id, $size, $price, $quantity, $name) = explode('#', $val);
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$name?> (<?=$size?>)</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity?></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$price?></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity*$price?></td>
</tr>
<?$i++; endforeach?>
<?
if($i < 7):
while($i<7)
{
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
</tr>
<?
$i++;
}
endif;
?>

<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="4"><div style="border-bottom:1px #000 solid;">Итого</div></td>
<td></td>
<td colspan="3"><div style="border-bottom:1px #000 solid;">Подпись</div></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
</table>

<table style="width:600px; font-size:14px; font-family:'Times New Roman', Times, serif; border-collapse:collapse;">
<tr>
<td colspan="3" style="text-align:right; text-transform:uppercase;">ТОВАРНЫЙ ЧЕК №</td>
<td style="width: 30px;"><div style="border-bottom:1px #000 solid; ">&nbsp;<?=$_REQUEST["ID"]?></div></td>
<td style="text-align:right;">от </td>
<td><div style="border-bottom:1px #000 solid;">&nbsp; <?=date('d/m');?></div></td>

<td><div style="border-bottom:1px #000 solid;">&nbsp;</div></td>
<td>2010 г.</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8">Продавец: ИП Кудренко М.А.</td>
</tr>

<tr>
<td colspan="8">ОГРН: 310032702100129 ИНН: 03260571209</td>
</tr>
<tr>
<td colspan="8">Адрес: www.igrivoe-nastroenie.ru Тел.: 277-110</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Наименование товара</td>

<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Кол-во</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Цена</td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Сумма</td>
</tr>

<? $i=0; foreach($_REQUEST["ITEMS"] as $val):?>
<?
list($id, $size, $price, $quantity, $name) = explode('#', $val);
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$name?> (<?=$size?>)</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity?></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$price?></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity*$price?></td>
</tr>
<?$i++; endforeach?>
<?
if($i < 7):
while($i<7)
{
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
</tr>
<?
$i++;
}
endif;
?>

<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="4"><div style="border-bottom:1px #000 solid;">Итого</div></td>
<td></td>
<td colspan="3"><div style="border-bottom:1px #000 solid;">Подпись</div></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
</table>

<table style="width:600px; font-size:14px; font-family:'Times New Roman', Times, serif; border-collapse:collapse;">
<tr>
<td colspan="3" style="text-align:right; text-transform:uppercase;">ТОВАРНЫЙ ЧЕК №</td>
<td style="width: 30px;"><div style="border-bottom:1px #000 solid; ">&nbsp;<?=$_REQUEST["ID"]?></div></td>
<td style="text-align:right;">от </td>
<td><div style="border-bottom:1px #000 solid;">&nbsp; <?=date('d/m');?></div></td>

<td><div style="border-bottom:1px #000 solid;">&nbsp;</div></td>
<td>2010 г.</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8">Продавец: ИП Кудренко М.А.</td>
</tr>

<tr>
<td colspan="8">ОГРН: 310032702100129 ИНН: 03260571209</td>
</tr>
<tr>
<td colspan="8">Адрес: www.igrivoe-nastroenie.ru Тел.: 277-110</td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Наименование товара</td>

<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Кол-во</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Цена</td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; font-style:italic; height:20px; font-size:12px;">Сумма</td>
</tr>

<? $i=0; foreach($_REQUEST["ITEMS"] as $val):?>
<?
list($id, $size, $price, $quantity, $name) = explode('#', $val);
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$name?> (<?=$size?>)</td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity?></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$price?></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"><?=$quantity*$price?></td>
</tr>
<?$i++; endforeach?>
<?
if($i < 7):
while($i<7)
{
?>
<tr>
<td colspan="5" style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-right:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
<td style="border-bottom:1px #000 solid; border-top:1px #000 solid; text-align:center; height:20px; font-size:12px;"></td>
</tr>
<?
$i++;
}
endif;
?>

<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr><tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="4"><div style="border-bottom:1px #000 solid;">Итого</div></td>
<td></td>
<td colspan="3"><div style="border-bottom:1px #000 solid;">Подпись</div></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>

</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
<tr>
<td colspan="8"style="height:10px;"></td>
</tr>
</table>


</body>
</html>
*/?>