<?/*
header("Content-Type: application/msword; charset=utf-8");
header("Content-Disposition: attachment; filename=Счет_№{$_REQUEST['ID']}.doc");
header("Pragma: no-cache");
header("Expires: 0");

function num2str($inn, $stripkop=false) {
$nol = 'ноль';
$str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
 $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
  $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
  $sex = array(
  array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
 array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
  );
  $forms = array(
  array('копейка', 'копейки', 'копеек', 1), // 10^-2
 array('рубль', 'рубля', 'рублей',  0), // 10^ 0
array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
  array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
 array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
 array('триллион', 'триллиона', 'триллионов',  0), // 10^12
 );
 $out = $tmp = array();
 // Поехали!
  $tmp = explode('.', str_replace(',','.', $inn));
  $rub = number_format($tmp[ 0], 0,'','-');
 if ($rub== 0) $out[] = $nol;
  // нормализация копеек
  $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
 $segments = explode('-', $rub);
 $offset = sizeof($segments);
if ((int)$rub== 0) { // если 0 рублей
 $o[] = $nol;
  $o[] = morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
   }
 else {
foreach ($segments as $k=>$lev) {
 $sexi= (int) $forms[$offset][3]; // определяем род
 $ri = (int) $lev; // текущий сегмент
 if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
 $offset--;
 continue;
 }
 // нормализация
 $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
// получаем циферки для анализа
  $r1 = (int)substr($ri, 0,1); //первая цифра
 $r2 = (int)substr($ri,1,1); //вторая
 $r3 = (int)substr($ri,2,1); //третья
$r22= (int)$r2.$r3; //вторая и третья
 // разгребаем порядки
 if ($ri>99) $o[] = $str[100][$r1]; // Сотни
 if ($r22>20) {// >20
 $o[] = $str[10][$r2];
 $o[] = $sex[ $sexi ][$r3];
}
 else { // <=20
 if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
 elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
 }
 // Рубли
 $o[] = morph($ri, $forms[$offset][ 0],$forms[$offset][1],$forms[$offset][2]);
 $offset--;
   }
}
 // Копейки
  if (!$stripkop) {
 $o[] = $kop;
 $o[] = morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
 }
 return preg_replace("/\s{2,}/",' ',implode(' ',$o));
 }

 // Склоняем словоформу

 function morph($n, $f1, $f2, $f5) {
 $n = abs($n) % 100;
 $n1= $n % 10;
 if ($n>10 && $n<20) return $f5;
   if ($n1>1 && $n1<5) return $f2;
 if ($n1==1) return $f1;
return $f5;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<table style="border-collapse:collapse; width:600px; font-family:'Times New Roman', Times, serif; font-size:10px; color:#000;">
  <tr>
    <td colspan="2">Продавец: ИП Кудренко Марина Александровна</td>
  </tr>
  <tr>

    <td colspan="2">Адрес: 670045, г.Улан-Удэ, Норильская 16-21 </td>
  </tr>
  <tr>
    <td colspan="2">ОГРНИП 307032602400030</td>
  </tr>
  <tr>
    <td colspan="2">ИНН: 032604498374</td>

  </tr>
  <tr>
    <td colspan="2">Расчетный счет: 40802810300000002570</td>
  </tr>
  <tr>
    <td colspan="2">Кор. счет: 30101810200000000736</td>
  </tr>
  <tr>

    <td colspan="2">Банк: ОАО АК Байкалбанк</td>
  </tr>
  <tr>
    <td colspan="2">БИК: 048142736</td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>

  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>

  <tr>
    <td colspan="7" style="text-align:center; font-weight:bold;">Счет № <?=$_REQUEST["ID"]?> от 01.06.2010</td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>

  </tr>
  <tr>
    <td style="border-bottom:2px #000 solid; border-left:2px #000 solid; border-right: 1px #000 solid; border-top:2px #000 solid; text-align: center; height:20px; font-weight:bold;">№</td>
    <td style="border-bottom:2px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:2px #000 solid; text-align: center; height:20px;font-weight:bold;">Наименование</td>
    <td colspan="2" style="border-bottom:2px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:2px #000 solid; text-align: center; height:20px;font-weight:bold;">Ед. изм.</td>
    <td style="border-bottom:2px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:2px #000 solid; text-align: center; height:20px;font-weight:bold;">Кол-во</td>
    <td style="border-bottom:2px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:2px #000 solid; text-align: center; height:20px;font-weight:bold;">Цена</td>

    <td style="border-bottom:2px #000 solid; border-left:1px #000 solid; border-right: 2px #000 solid; border-top:2px #000 solid; text-align: center; height:20px;font-weight:bold;">Сумма</td>
  </tr>
  
  <? $summ=0; $i=1; foreach($_REQUEST["ITEMS"] as $val):?>
<?
list($id, $size, $price, $quantity, $name, $komp, $color) = explode('#', $val);
$summ += $quantity*$price;
?> 
  
  <tr>
    <td style="border-bottom:1px #000 solid; border-left:2px #000 solid; border-right: 1px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;"><?=$i?></td>
    <td style="border-bottom:1px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;"><?=iconv("windows-1251", "utf-8", $name)?></td>
    <td colspan="2" style="border-bottom:1px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;">шт.</td>
    <td style="border-bottom:1px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;"><?=iconv("windows-1251", "utf-8", $quantity)?></td>

    <td style="border-bottom:1px #000 solid; border-left:1px #000 solid; border-right: 1px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;"><?=iconv("windows-1251", "utf-8", $price)?></td>
    <td style="border-bottom:1px #000 solid; border-left:1px #000 solid; border-right: 2px #000 solid; border-top:1px #000 solid; text-align: center; height:20px;"><?=iconv("windows-1251", "utf-8", $quantity*$price)?></td>
  </tr>
  <?$i++; endforeach?>  
 
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7">Сумма прописью: <?=num2str($summ)?></td>
  </tr>

  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>

  <tr>
    <td colspan="7" style="height:10px;"></td>
  </tr>
  <tr>
    <td colspan="2"><div style="border-bottom:1px #000 solid;">Руководитель предприятия &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Кудренко М.А.</div></td>

    <td></td>
    <td colspan="4"><div style="border-bottom:1px #000 solid;">Бухгалтер</div></td>
  </tr>
</table>
</body>
</html>
*/?>