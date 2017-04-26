<?
//created by C_arter /*hello from bitrix's support*/
//last modified: 12.09.2010
/////////////////////////preparing =)///////////////////////////////////////////


define('ver', '2.0');
define('MENULINES', 5);
$APicture=Array('jpg','jpeg','gif','png');
$UPLOAD_DIR='/upload';
$script_name=$_SERVER['SCRIPT_NAME'];


if ((isset($_SERVER["HTTP_USER_AGENT"]))&&(strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')))
die('Этот скрипт не работает в Internet Explorer по причине личной неприязни автора скрипта к данному браузеру. Спасибо за понимание:)');
if ((@$_GET['mode']!='query'))
define('NEED_AUTH',true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
COption::SetOptionString("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");
COption::SetOptionString("sale", "secure_1c_exchange", "N")    ; 
if (!isset($_REQUEST['action']))
{
$arUrlCreateIBlockType = array(
"URL"=> $script_name.'?action=createiblocktypeform',
"PARAMS"=> array(
"width" => '300',
"height" => '200',
"title"=>'Создать тип инфоблока',
"resizable" => false
)
);

$arUrlChangeTime = array(
"URL"=> $script_name.'?action=change_time',
"head"=>'123123',
"PARAMS"=> array(
"width" => '300',
"height" => '120',
"title"=>'12313',
"resizable" => false,

)
);


$CreateIBLOCK=$APPLICATION->GetPopupLink($arUrlCreateIBlockType);
$change_time=$APPLICATION->GetPopupLink($arUrlChangeTime);
}
//Готовим кнопки
$MenuArray=Array(
			'main_info'=>Array(
							"msg"=>'откроется окно информации по файлам',
							"title"=>'Поиск',
							"onclick"=>"BX('main_info').style.display='block'",
							"class"=>'small_but'
							),
			'main'=>Array(
							"msg"=>'откроется окно сброса даты последнего обмена с 1С',
							"title"=>'Метка времени',
							"onclick"=>"javascript:".$change_time,
							"class"=>'small_but'
						),
			'param'=>Array(
							"msg"=>'откроется окно параметров выгрузки заказов',
							"title"=>'Выгрузка заказов',
							"onclick"=>"BX('param').style.display='block'",
							"class"=>'small_but'
						),
			'imp_file_win'=>Array(
							"msg"=>'Откроется окно импорта файлов',
							"title"=>'Импорт файлов',
							"onclick"=>"AddWindowRequest('".$script_name."?action=addimpfilewin','custom_windows','ipfs');",
							"class"=>'small_but'
						),
			'stepdiag'=>Array(
							"msg"=>'появится окно пошагового импорта. Пошаговая диагностика только для файлов каталога из папки '.$UPLOAD_DIR.'/1c_catalog/',
							"title"=>'Пошаговый импорт',
							"onclick"=>"BX('stepdiag').style.display='block'",
							"class"=>'small_but'
							),
			'log3'=>Array(
							"msg"=>'появится окно лога импорта файла',
							"title"=>'Лог импорта(инф.)',
							"onclick"=>"wObj=BX('log3'); winOpen();",
							"class"=>'small_but'
							),
			'list'=>Array(
							"msg"=>'откроется список заказов, которые выгрузятся в 1С. Сначала его нужно сформировать в окне параметров выгрузки заказов.',
							"title"=>'Заказы (инф.)',
							"onclick"=>"BX('list').style.display='block'",
							"class"=>'small_but'
							),
			'crtiblock'=>Array(
							"msg"=>'откроется окно создания типа инфоблока',
							"title"=>'Создать тип инфоблока',
							"onclick"=>"javascript:".$CreateIBLOCK,
							"class"=>'small_but'
							),

				'xmltree'=>Array(
							"msg"=>'будет отображено содержимое временной таблицы',
							"title"=>'Временная таблица',
							"onclick"=>"AjaxRequest('".$script_name."?action=show_bxmltree','para1',false)",
							"class"=>'small_but'
							)
				);

//  $CustomButton - массив кастомных кнопок
$CustomButton['searchbutton']=Array(
							"msg"=>'произойдёт поиск',
							"title"=>'найти',
							"onclick"=>"searchbyxmlid();",
							"class"=>'button2'
							);
$CustomButton['change1']=Array(
							"msg"=>'сменится время последнего обмена с 1С, после этого посмотреть список заказов, которые выгрузятся в 1С при следующем обмене',
							"title"=>'Сменить',
							"onclick"=>"ChangeLastMoment();",
							"class"=>'button2'
							);
$CustomButton['delete']=Array(
							"msg"=>'удалится весь этот скрипт',
							"title"=>'Удалить скрипт',
							"onclick"=>"delete_file()",
							"class"=>'but'
							);
$CustomButton['refresh']=Array(
							"msg"=>'обнулится шаг импорта',
							"title"=>'Обнулить шаг',
							"onclick"=>"reset()",
							"class"=>'but'
							);
$CustomButton['cat_imp']=Array(
							"msg"=>"Импорт файла, это импорт каталога",
							"title"=>'Каталог',
							"onclick"=>"ConfirmImport('import.xml');",
							"class"=>'but'
							);
$CustomButton['cat_off']=Array(
							"msg"=>"Импорт файла, это импорт предложений",
							"title"=>'Предложения',
							"onclick"=>"ConfirmImport('offers.xml');",
							"class"=>'but'
							);
$CustomButton['order_import']=Array(
							"msg"=>"Импорт файла, это импорт заказов",
							"title"=>'Импорт заказов',
							"onclick"=>"OrderImport('ord_imp');",
							"class"=>'but'
							);
$CustomButton['cat_comp']=Array(
							"msg"=>"Импорт файла, это импорт сотрудников",
							"title"=>'Сотрудники',
							"onclick"=>"ConfirmImport('company.xml');",
							"class"=>'but'
							);
$CustomButton['iblockbut']=Array(
							"msg"=>'создастся тип инфоблока',
							"title"=>'создать',
							"onclick"=>"CreateIBlock();",
							"class"=>'button2'
							);
$CustomButton['test_123']=Array(
							"msg"=>'откроется FileMan',
							"title"=>'FileMan (ctrl+~)',
							"onclick"=>"BX('test_window').style.display='block';GetFileList2('','testfileman');",
							"class"=>'small_but'
							);
$CustomButton['crfile']=Array(
							"msg"=>'будем создавть файл',
							"title"=>'создать файл',
							"onclick"=>"CreateFileDialog('Создаём файл/папку');",
							"class"=>'small_but'
							);
$CustomButton['upfile']=Array(
							"msg"=>'будем загружать файл',
							"title"=>'загрузить файл',
							"onclick"=>"BX('upload_file').style.display='block'",
							"class"=>'small_but'
							);

//пункты контекстого меню
$ContextMenu=Array(
		Array(
				'msg'=>"файл откроется на просмотр",
				'id'=>"view",
				'class'=>"menu",
				'aid'=>"v",
				'point_name'=>"view"
			),
		Array(
				'msg'=>"файл откроется на редактирование",
				'id'=>"edit",
				'class'=>"menu2",
				'aid'=>"e",
				'point_name'=>"edit"
			),

		Array(
				'msg'=>"файл будет удалён",
				'id'=>"del",
				'class'=>"menu_del",
				'aid'=>"d",
				'point_name'=>"delete"
			),
		Array(
				'msg'=>"это архив и он будет распакован",
				'id'=>"unzip_",
				'class'=>"menu_unzip",
				'aid'=>"u",
				'point_name'=>"unpack"
			),
		Array(
				'msg'=>"скачается файл",
				'id'=>"down",
				'class'=>"menu_dw",
				'aid'=>"dw",
				'point_name'=>"download"
			)
);


//----------------------------------------------------------------------------------------------
///////////////////////end preparing///////////////////////////////////////////

//описание стилей окон
$DefaultWinStyle=Array(
					"width"=>'40%',
					"border"=>'1px solid black',
					"background"=>'#FFDEAD',
					"display"=>'none',
					"position"=>'fixed',
					"cursor"=>'hand',
					"left"=>390,
					"top"=>50,
					"padding"=>5,
					"z-index"=>1000,
					"is_moveable"=>'Y'
					);
$DefaultWinStyleSmall=Array(
					"width"=>320,
					"height"=>200,
					"border"=>'1px solid black',
					"background"=>'#FFF8DC',
					"display"=>'block',
					"position"=>'fixed',
					"cursor"=>'hand',
					"left"=>550,
					"top"=>250,
					"padding"=>5,
					"z-index"=>1001,
					"is_moveable"=>'Y',
					 "display"=>'none',
					);

$DefaultFieldStyle=Array(
			        "width"=>1000,
					"height"=>660,
					"border"=>'1px solid #B9D3EE',
					"background"=>'#FFF8DC',
					"display"=>'block',
					"position"=>'fixed',
					"cursor"=>'hand',
					"left"=>350,
					"top"=>20,
					"padding"=>5,
					"z-index"=>10,
					"workcolor"=>"#EEE8AA"
					);

$WinStyleIBlock=Array(
					"width"=>320,
					"height"=>220,
					"border"=>'1px solid black',
					"background"=>'#FFF8DC',
					"display"=>'block',
					"position"=>'fixed',
					"cursor"=>'hand',
					"left"=>550,
					"top"=>250,
					"padding"=>5,
					"z-index"=>1001,
					"is_moveable"=>'Y'
					);
$WinStyleIpfs=Array(
					"width"=>'320px',
					//"height"=>'400px',
					"border"=>'1px solid black',
					"background"=>'#B9D3EE',
					"display"=>'block',
					"position"=>'fixed',
					"cursor"=>'hand',
					"left"=>'7px',
					"top"=>'230px',
					"padding"=>5,
					"z-index"=>100,
					"is_moveable"=>'Y'
					);
$EditStyle=Array(
					"width"=>'70%',
					"height"=>'90%',
					"border"=>'1px solid black',
					"background"=>'#6699CC',
					"display"=>'block',
					"position"=>'fixed',
					"cursor"=>'defult',
					"left"=>350,
					"font-size"=>'14',
					"top"=>20,
					"color"=>"black",
					"padding"=>10,
					"z-index"=>10,
					"workcolor"=>"white",
					"is_moveable"=>'N',
					"fileman"=>'Y'
					);

//строим меню
function BuildContextMenu()
{
	global $ContextMenu;
	echo '<table class="menu">';
	foreach ($ContextMenu as $point):
		echo '<tr><td class=menu onmousedown="moveState = false;" onmousemove="moveState = false;" onmouseover=\'LightOn(this,"'.$point['msg'].'")\' onmouseout=LightOff() id="'.$point['id'].'"><a class="'.$point['class'].' point_menu" id="'.$point['aid'].'">'.$point['point_name'].'</a></td></tr>';
	endforeach;
	echo '</table>
	<iframe id="dwframe" style="display:none" src=""></iframe>';
}

//список файлов указаной директории
function ShowFileSelect($listid='test',$Title='undefined',$dir,$ext='xml',$listsize=1,$DblClickAction='')
{
	$ifile=Array();
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$dir) && is_dir($_SERVER['DOCUMENT_ROOT'].$dir))
	{
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].$dir))
		{
			while (false !== ($file_1 = readdir($handle)))
			{
				$file_ext=substr(strrchr($file_1, '.'), 1);
				if ($file_ext==$ext)
					$ifile[]=$file_1;
			}
		}
	}

	if ($ifile!=Array())
	{
		echo '<b style="font-size:10" align=\'left\'>'.$Title.'</b><br>';
		echo '<select style="width:100%;font-size:11;" size='.$listsize.' onmousedown="moveState = false;" onmousemove="moveState = false;" style="font-size:10" align=\'right\' id='.$listid.' onDblClick='.$DblClickAction.'>';
		$select=false;
		foreach ($ifile as $value):
			if ($select==false)
			{
				$select=true;
				echo '<option  selected onmousedown="moveState = false;" onmousemove="moveState = false; "value="'.$key.'">'.$value.'</option>';
				continue;
			}
		echo '<option  onmousedown="moveState = false;" onmousemove="moveState = false; "value="'.$key.'">'.$value.'</option>';
		endforeach;
							echo '</select></br>';
	}
}


function ShowMenuWindow($ID,$NAME,$ShowHideSectionID,$content='')
	{
					echo '<table id='.$ID.' onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);" class=menu_table cellspacing=0 cellpadding=0>';
									echo '<tr><td>';
						echo '<b class="rtopwin">
  <b class="r1win"></b> <b class="r2win"></b> <b class="r3win"></b> <b class="r4win"></b>
</b>';
			echo '</td></tr>';
			echo '<tr><td class=msection>';
			echo '<div style="background:#B9D3EE;position:relative;left:10;width:180;color:black">'.$NAME.'</div></td></tr>';
			echo '<tr><td class=menu_td>';
				echo '<div  id='.$ShowHideSectionID.'_ps style="background:white;padding:10" align=center>';
			echo $content;
			echo '</div>';
			echo '</td></tr>';
			echo '<tr><td>';
						echo '<b class="rbottomwin">
  <b class="r4win"></b> <b class="r3win"></b> <b class="r2win"></b> <b class="r1win"></b>
</b>';
			echo '</td></tr>';
		echo '</table>';

	}



function AddButton($value,$mainmenu=false,$returnbutton=false,$MyButtons=false)
		{
			global $MenuArray;
			global $CustomButton;
			if (is_array($MyButtons))
			$arButtons=$MyButtons;
			elseif($mainmenu==true)
			$arButtons=$MenuArray; else $arButtons=$CustomButton;
			$but=$value;
				if (!is_array($but))
				{
					$but=$arButtons[$value];
					if (!is_array($but)) return false;
				}

			$Button='<div class="'.$but['class'].'" align="center" OnClick="'.$but['onclick'].'" OnMouseOver="LightOn(this,\''.$but["msg"].'\');" OnMouseOut="LightOff();" onmousedown="moveState = false;" onmousemove="moveState = false;">'.$but['title'].'</div>';
			if ($returnbutton==false)
			echo $Button;
			else return $Button;
		}

function AddWindow($NewId="newwindow",$NewName="NoNameWindow",$WorkID=false,$inner=false,$WinStyle=false,$buttons="",$mainmenu=false,$beforeInner='',$afterInner="")
		{
			global $MenuArray;
			global $CustomButton;
			global $DefaultWinStyle;
			if (!is_array($buttons))
			$button=AddButton($buttons,$mainmenu,true);
			else
			foreach ($buttons as $val)
			$button.=AddButton($val,$mainmenu,true);
			if (!$WinStyle)
			$WinStyle=$DefaultWinStyle;

			if (!$inner)
			{
				$inner="<div style='background-color:".$WinStyle['workcolor']."' id=".$WorkID."></div>".$button;
				//$inner.="<script>GetFileList2('/upload/','testfileman');</script>";;
			}

			if ($WinStyle['is_moveable']=='Y')
						 echo  '<div id="'.$NewId.'" class="divwin_'.$NewId.'" onselectstart="return false"  onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);"><b>'.$NewName.'</b><hr>
								<div class="closeButton" OnMouseOver="LightOn(this,\'закроется окно\');" OnMouseOut="LightOff();" onclick="Close(\''.$NewId.'\')">X</div>';
			else echo '<div id="'.$NewId.'" class="divwin_'.$NewId.'"><b>'.$NewName.'</b><hr>';
			echo $beforeInner;
			if ($WinStyle['fileman']=='Y')
					AddButton('test_123');
				    	echo $inner;
					echo $afterInner;
					   echo '</div>';

					   echo '<style>';
					   echo '.divwin_'.$NewId.'{';
				       foreach ($WinStyle as $atr=>$value):
					   echo $atr.':'.$value.';';
					   endforeach;
					   echo '}</style>';
					   echo "<script>
					   if (!new_id)
					   var new_id=new Array();
					   new_id[new_id.length]='".$NewId."';
					   </script>";
		}

function AddField($NewId="newwindow",$NewName="NoNameWindow",$WorkID=false,$inner=false,$WinStyle=false,$buttons="",$mainmenu=false,$tableft=5)
		{
			global $MenuArray;
			global $CustomButton;
			global $DefaultFieldStyle;
			if (!is_array($buttons))
			$button=AddButton($buttons,$mainmenu,true);
			else
			foreach ($buttons as $val)
			$button.=AddButton($val,$mainmenu,true);
			if (!$WinStyle)
			$WinStyle=$DefaultFieldStyle;
			$field_id=$NewId.'_field';
			$tab_id=$NewId.'_tab';
					ob_start();
					   echo '<div onmousedown="moveState = false;" onmousemove="moveState = false;" id='.$NewId.'>';
					   echo '<div onmousedown="moveState = false;" onmousemove="moveState = false;" id='.$field_id.' style="width:980;padding:5;left:5;height:600;position:absolute;top:55;border:1px solid #00C5CD;z-index:99;">';
					   echo $inner;
					   echo '</div>';
					   echo '<b><div id='.$tab_id.' onmousedown="ShowField(this,\''.$field_id.'\');"
					   style="
	position:absolute;
	left:'.$tableft.';
  height:15;
  top:28;
  border-top:1px solid #00C5CD;
  border-right:1px solid #00C5CD;
  border-left:1px solid #00C5CD;
  border-bottom:2px solid #FFF8DC;
  background:#FFF8DC;
  padding:5;
  margin:0;
  width:100;
  z-index:100;">'.$NewName.'</div></b>';
                       echo '</div>';
					   $content = ob_get_contents();
					ob_end_clean();
					echo $content;
				echo '<script>
     		   var old_node = BX("'.$NewId.'");
			   var oldparentNode = BX("'.$NewId.'").parentNode;
			  // alert(oldparentNode);
			   var clone = old_node.cloneNode(true);
			   var newparentNode = BX("'.$WorkID.'").appendChild(clone);
			   oldparentNode.removeChild(old_node);

				</script>';
				//echo $test;
				//echo "BX('".$WorkID."').innerHTML=";
				 //echo "<script> </script>";

//$content=ob_get_contents();

		}
//удаление скрипта
if (@$_GET['delete']=="Y")
{
header("Content-type:text/html; charset=windows-1251");
unlink(__File__);
echo "<div style='background-color:#B9D3EE;
   border:1px solid red;
   text-align:center;
   color:red;
   height:30;
   z-index:10000;'> Файла теперь нет - он удалён!</div>";
die();
}

$UPLOAD_DIR="/".COption::GetOptionString("main", "upload_dir");
$interval=COption::GetOptionString("catalog", "1C_INTERVAL", "-");
if ((!$USER->IsAdmin())&&(@($_GET['mode']!='query')))
	{
		echo 'Доступ запрещён. Вы не администратор сайта. До свидания.';
                localredirect("/404.php");
        }


error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
header("Content-type:text/html; charset=windows-1251");
if (@$_GET['action']=="addfield")
{
	AddField('test_123','test','para1','test',false,false,false,5);
	AddField('testfield2','offers.xml','para1','test2',false,false,false,120);
	die();
}

if (@$_GET['action']=="createfile")
	{

					if (file_exists($_SERVER['DOCUMENT_ROOT'].$_GET['path']))
						{
							echo 'error001';
							die();
						}

					if ($_GET['isdir']=='Y')
						{
							if (mkdir($_SERVER['DOCUMENT_ROOT'].$_GET['path'], 0, true))
								echo 'success'; else echo 'fail';
						}
					else
						{
							if ($f = fopen ($_SERVER['DOCUMENT_ROOT'].$_GET['path'], 'a+'))
								echo 'success'; else echo 'fail';
							fclose($f);
						}
	die();
	}

if (@$_GET['action']=="change_time"):?>
<table align='center'>
<tr>
<th class="th_table">Путь</th>
<th class="th_table2"><input id='path1' type="text" size="30" value="<?if(isset($_POST['path1'])) echo $_POST['path1']; else echo "/bitrix/admin/1c_exchange.php"?>" name="path1"></th>
</tr>
<tr>
<th class="th_table">Дата </th>
<th class="th_table2"><input id='date' type="text" size="30" value="<?if(!$_POST['date']=='') echo $_POST['date']; else echo $date;?>" name="date"></th>
</tr>
<tr><td COLSPAN=2 align="center">
<?AddButton('change1');?>
</td></tr>
</table>
<?die();
endif;
if (@$_GET['action']=="createiblocktypeform")
			{
					$inner='<div id="successiblock"></div>
					Введите ID типа инфоблока:<br>
					<input onmousedown="moveState = false;" onmousemove="moveState = false;" id="iblocktype" size=45 value="support_test_iblock_type"><br>'.
					//'Введите название инфоблока:<br><input id="iblockname" size=45 value="support_test_iblock"><br>'
					'Выгружать в этот тип инфоблока <input onmousedown="moveState = false;" onmousemove="moveState = false;" type="checkbox" id="1ciblock" checked>'.AddButton('iblockbut',false,true);
					echo $inner;
					//AddWindow('iblock','Создание типа инфоблока',false,$inner,$WinStyleIBlock);
					die();
			}

if (@$_GET['action']=="addimpfilewin")
			{
				ob_start();
				echo '<input type=\'checkbox\' checked id=\'intofield\'><span style="font-size:10">выводить лог в главное окно</span><br>';
				ShowFileSelect('cat_file','Файл каталога в '.$UPLOAD_DIR.'/1c_catalog/:',$UPLOAD_DIR.'/1c_catalog/','xml',2,'ConfirmImport(\'import.xml\')');
				//ShowFileSelect('off_file','Файл предложений в /upload/1c_catalog/:','/upload/1c_catalog/','xml',2,'start(\'offers.xml\')');
				ShowFileSelect('order_file','Файл заказов в '.$UPLOAD_DIR.'/1c_exchange/:',$UPLOAD_DIR.'/1c_exchange/','xml',2,'OrderImport(\'hz\')');
				ShowFileSelect('worker','Файл сотрудников в '.$UPLOAD_DIR.'/1c_intranet/:',$UPLOAD_DIR.'/1c_intranet/','xml',2,'ConfirmImport(\'company.xml\')');
				$inner=ob_get_contents();
				ob_end_clean();
				AddWindow('ipfs','Импорт файлов',false,$inner,$WinStyleIpfs);
					die();
			}

if (@$_GET['action']=="createiblocktype")
			{
					CModule::IncludeModule('iblock');
					$arFields = Array(
						'ID'=>$_GET['iblocktype'],
						'SECTIONS'=>'Y',
						'IN_RSS'=>'N',
						'SORT'=>100,
						'LANG'=>Array(
							'en'=>Array(
								'NAME'=>'Catalog',
								'SECTION_NAME'=>'Sections',
								'ELEMENT_NAME'=>'Products'
								)
							)
						);

					$obBlocktype = new CIBlockType;
					$DB->StartTransaction();
					$res = $obBlocktype->Add($arFields);
					if(!$res)
					{
					   $DB->Rollback();
					   echo '<div style="color:red;border:1px dashed red;padding:5">'.$obBlocktype->LAST_ERROR;
					}
					else
					{
						echo '<div style="color:green;border:1px dashed green;padding:5">Тип инфоблока создан успешно!';
						$DB->Commit();
					}
                   if (@$_GET['USE_IBLOCK_TYPE']=='Y')
					   {
					   COption::SetOptionString("catalog",'1C_IBLOCK_TYPE', $_GET['iblocktype']);
					   COption::SetOptionString("catalog", "1C_USE_IBLOCK_TYPE_ID", "Y");
					   echo 'Каталог будет выгружаться в тип инфоблока '.$_GET['iblocktype'].'</div></br>';
					   } else echo '</div></br>';
					die();
			}

if (@$_GET['action']=='getstep')
			{
				echo $_SESSION["BX_CML2_IMPORT"]["NS"]["STEP"];
				die();
			}

if (@$_GET['action']=='download')
			{
			$filename=$_SERVER["DOCUMENT_ROOT"].$_GET['path'].$_GET['file'];
			$mimetype='application/octet-stream';
				  if (file_exists($filename)) {
					header($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
					header('Content-Type: ' . $mimetype);
					header('Last-Modified: ' . gmdate('r', filemtime($filename)));
					header('ETag: ' . sprintf('%x-%x-%x', fileinode($filename), filesize($filename), filemtime($filename)));
					header('Content-Length: ' . (filesize($filename)));
					header('Connection: close');
					header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
					echo file_get_contents($filename);
				  } else {
					header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
					header('Status: 404 Not Found');
				  }
				  exit;
}

if(@$_POST['action']=="deletefile")
{
if (is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['bx_1c_import']['path'].$_POST['filename']))
    $res=rmdir($_SERVER['DOCUMENT_ROOT'].$_SESSION['bx_1c_import']['path'].$_POST['filename']);
else
    $res=unlink($_SERVER['DOCUMENT_ROOT'].$_SESSION['bx_1c_import']['path'].$_POST['filename']);

echo $res;
die();
}

if(@$_GET['action']=="getfiles")
{
	if (!isset($_GET['path']))
		$urlpath='/'; else $urlpath=$_GET['path'];
	$realpath=str_replace('//','/',$urlpath.'/');
	$_SESSION['bx_1c_import']['path']=$realpath;
	@$_SESSION['bx_1c_import']['filter']=$_GET['like_str'];
	if (isset($_GET['workarea']))
		$wa=$_GET['workarea']; else $wa="minifileman";
	if ($wa == 'minifileman')
	{
		$rows=12;
		$cols=2;
	}
	else
	{
		$rows=400;
		$cols=1;
	}
	$dirs=explode('/',$realpath);
	$i=1;
	$full="";
	$el['DIR']='корень';
	$el['PATH']='/';
	$cat[]=$el;
	while ($i<=count($dirs))
	{
		$el=Array();
		$el['DIR']=$dirs[$i];
		if ($dirs[$i]!='')
		{
			$el['PATH']=$full.$dirs[$i].'/';
			$full.=$dirs[$i].'/';
			$cat[]=$el;
		}
		$i++;
	}
	$link_path="/";
	$id=0;$l=1;
	echo '<div style="font-size:11;background:#DCDCDC;padding:3px;">';
	foreach ($cat as $el_d)
	{
		$id="p_".$wa.'_'.$l++;
		$func=str_replace('//','/','/'.$el_d["PATH"]);?><a id="<?=$id?>" style="font-size:10;" OnMouseOver="LinkLightOn('<?=$id?>','#1C1C1C');" OnMouseOut="LinkLightOff();" href="javascript:GetFileList2('<?=$func;?>','<?=$wa?>')"><?=$el_d["DIR"]?></a>/<?
	}
	echo '</div>';
	echo '<div style="overflow:auto;height:200px;width:100%;background:white;" onmousedown="moveState = false;" onmousemove="moveState = false">';
	echo '<table style="font-size:9;width:100%">';
	if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].$_GET['path']))
	{
		$i=-1;
		$col=0;
		$fun_str="";
		$q=$_GET['like_str'];
		$IfoundFiles=false;
		if ($q=='') {$q="all";$fun_str="all";}
		$id=0;$l=1;
		$mdir=Array();
		$mfile=Array();
		echo "<tr><td valign='top' style='font-size:12;width:50%;border-right:1px solid #DCDCDC;'>";
		while (false !== ($file_1 = readdir($handle)))
		{
			if (is_dir($_SERVER['DOCUMENT_ROOT'].$_GET['path'].'/'.$file_1)):
				$mdir[]=$file_1;
			else:
				$mfile[]=$file_1;
			endif;
		}
		sort($mdir);
		sort($mfile);
		$mdirectory=array_merge($mdir,$mfile);
		$color='#FFF8DC';
		foreach ($mdirectory as $file)
		{
			if ($color=='#FFF8DC') $color='#EEEEE0'; else $color='#FFF8DC';
			if(($file!==".")&&($file!=="..")&&(strpos($file.$fun_str,$q)!==false))
			{

				$id="f_".$wa.'_'.$l++;
				if ($i>$rows) {if(++$col==$cols)
					break;
				elseif($IfoundFiles==true)
				{
					echo '</td><td width=200 valign="top" style="font-size:9">';$IfoundFiles=false;}$i=1;
				}
				$IfoundFiles=true;?>

				<div width=100% style="background:<?=$color?>;">
				<?

				if (is_dir($_SERVER['DOCUMENT_ROOT'].$_GET['path'].'/'.$file)):?>

					<img src='/bitrix/images/fileman/folder.gif'> <a id="<?=$id?>" style="font-size:12;color:blue;" OnMouseOver="LinkLightOn('<?=$id?>','#363636');" OnMouseOut="LinkLightOff();" href="javascript:GetFileList2('<?=str_replace('//','/',$_GET['path'].'/').$file.'/'?>','<?=$wa?>')"><?=$file?></a>
				<?else:?>

				<img src='/bitrix/images/fileman/file.gif'>
				<a id="<?=$id?>" style="font-size:12;" href="javascript:ShowFile('<?=$file?>','<?=$realpath?>','N')" oncontextmenu="return ShowMenu(event);" OnMouseOver="LinkLightOn('<?=$id?>','#1C1C1C');" OnMouseOut="LinkLightOff();" href="#" onmousedown="moveState = false;" onmousemove="moveState = false;"><?if (strlen($file)>50) echo substr($file,0,-(strlen($file)-8))."...".substr($file,-4); else echo $file;?></a>
				<a  style="color:red;font-size:10;" href=javascript:Delete('<?=$file?>','<?=$wa?>') OnMouseOver="LightOn(this,'! удалится <b> <?=$file?></b> !');" OnMouseOut=LightOff()>[X]</a><a style="color:green;font-size:10;" href=javascript:ShowInfo('<?=$realpath.$file?>') OnMouseOver="LightOn(this,'отобразится информация по <b> <?=$file?></b>');" OnMouseOut=LightOff()>[!]</a>
				</div>
				<?endif;?>
				<?
			}
			$i++;
		}
		closedir($handle);
	}
echo '</td></tr></table>';
echo '</div>';
die();
}
//распаковка файла
if (@$_POST['action']=="unzip")
{
    $zip = $_POST['filename'];
	CModule::IncludeModule('iblock');
	$result = CIBlockXMLFile::UnZip($zip);
	echo 1;
	die();
		//echo "<div class='message2'>Архив $zip распакован</div>";

}

//грузим  любой файл в указанную папку
if (@$_GET['upload']=="Y")
{
	if(is_array($_FILES['test_file']))
	{
		$tmp_name=$_FILES['test_file']['tmp_name'];
		if( $_SESSION['bx_1c_import']['path']=="")
		$test_file=$UPLOAD_DIR."/".$_FILES['test_file']['name'];
		else $test_file=$_SESSION['bx_1c_import']['path'].$_FILES['test_file']['name'];
		if(is_uploaded_file($tmp_name))
				{
					move_uploaded_file($tmp_name,$_SERVER['DOCUMENT_ROOT'].$test_file);
					echo("<a href='".$test_file."' target='_blank'>".$_FILES['test_file']['name']."</a>");
				}
		else
			echo "error";
			echo '<br>';
	}
	//форма для загрузки файла на сервер
	if (isset($_POST['test_file']))
				echo "Файл ".$_POST['test_file']." загружен";
	echo "<div style='background-color:#FFE4B5'>
	<form action='".$script_name."?upload=Y' method=post enctype='multipart/form-data'>
	<input onmousedown='moveState = false;' onmousemove='moveState = false;' type='file' name='test_file'>
	<input type='submit' value='загрузить' name='upload_file'>
	</form></div>
	";
	die();
}
//поиск элемента в файле и на сайте по XML_ID
if (isset($_GET['search']))
{
	$import=file_get_contents($_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/1c_catalog/import.xml");
	$import=$APPLICATION->ConvertCharset($import,"UTF-8","WINDOWS-1251");
	$q=$_GET['search'];
	$import=str_replace('/',"",$import);
	preg_match('/<Товар>.*.<Ид>'.$q.'<Ид>.*.<ПолноеНаименование>/is', $import , $product);
	if(count($product)) $ISaLive["file"]['here']="Y"; else $ISaLive["file"]['here']="N";
	CModule::IncludeModule("iblock");
		$check=CIBlockElement::GetList(Array(),Array("EXTERNAL_ID"=>$q));
		if (!$check) echo 'на сайте таких нет';
		while($res=$check->Fetch())
		echo 'IBLOCK_ID='.$res["IBLOCK_ID"].' <a href="/bitrix/admin/iblock_element_edit.php?ID='.$res["ID"].'&IBLOCK_ID='.$res["IBLOCK_ID"].'&type='.$res["IBLOCK_TYPE_ID"].'" target="_blank">Перейти</a><br>';
		die();
	}

//получение  текста xml-файла, который будет переправлен с сайта в 1С при следующем обмене.
if($_GET["mode"] == "query")
	{
		CModule::IncludeModule("sale");
		$arParams=Array(
			"SITE_LIST" => COption::GetOptionString("sale", "1C_SALE_SITE_LIST", ""),
			"EXPORT_PAYED_ORDERS" => COption::GetOptionString("sale", "1C_1C_EXPORT_PAYED_ORDERS", ""),
			"EXPORT_ALLOW_DELIVERY_ORDERS" => COption::GetOptionString("sale", "1C_EXPORT_ALLOW_DELIVERY_ORDERS", ""),
			"EXPORT_FINAL_ORDERS" => COption::GetOptionString("sale", "1C_EXPORT_FINAL_ORDERS", ""),
			"FINAL_STATUS_ON_DELIVERY" => COption::GetOptionString("sale", "1C_FINAL_STATUS_ON_DELIVERY", "F"),
			"REPLACE_CURRENCY" => COption::GetOptionString("sale", "1C_REPLACE_CURRENCY", ""),
			"GROUP_PERMISSIONS" => explode(",", COption::GetOptionString("sale", "1C_SALE_GROUP_PERMISSIONS", "")),
			"USE_ZIP" => COption::GetOptionString("sale", "1C_SALE_USE_ZIP", "Y"));
			$arFilter = Array();
			if($arParams["EXPORT_PAYED_ORDERS"])
				$arFilter["PAYED"] = "Y";
			if($arParams["EXPORT_ALLOW_DELIVERY_ORDERS"]<>"N")
				$arFilter["ALLOW_DELIVERY"] = "Y";
			if(strlen($arParams["EXPORT_FINAL_ORDERS"])>0)
			{
				$bNextExport = false;
				$arStatusToExport = Array();
				$dbStatus = CSaleStatus::GetList(Array("SORT" => "ASC"), Array("LID" => LANGUAGE_ID));
				while ($arStatus = $dbStatus->Fetch())
				{
					if($arStatus["ID"] == $arParams["EXPORT_FINAL_ORDERS"])
						$bNextExport = true;
					if($bNextExport)
						$arStatusToExport[] = $arStatus["ID"];
				}

				$arFilter["STATUS_ID"] = $arStatusToExport;
			}
			if(strlen($arParams["SITE_LIST"])>0)
				$arFilter["LID"] = $arParams["SITE_LIST"];
			if(strlen(COption::GetOptionString("sale", "last_export_time_committed_/bitrix/admin/1c_excha", ""))>0)
			$arFilter[">=DATE_UPDATE"] = ConvertTimeStamp(COption::GetOptionString("sale", "last_export_time_committed_/bitrix/admin/1c_excha", ""), "FULL");
			ob_start();
			CSaleExport::ExportOrders2Xml($arFilter, false, $arParams["REPLACE_CURRENCY"]);
			$xml=ob_get_contents();
			ob_end_clean();
			$dres=CSite::GetList();
			$site=$dres->Fetch();
			if (strtoupper($site['CHARSET'])<>'WINDOWS-1251')
			$xml=$APPLICATION->ConvertCharset($xml,$site['CHARSET'],"WINDOWS-1251");
			//$xml='<pre style="background:white; text-align:right">текст xml-файла, который будет передан в 1С при следующем обмене</pre>'.$xml;
			if (@$_GET['save']=='Y')
				{
					unlink($_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/bx_orders.xml");
					$f = fopen ($_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/bx_orders.xml", 'a+');
					fwrite ($f,$xml);
					fclose($f);
					$xml=trim($xml);
					echo '<pre style="background:white; text-align:right">текст xml-файла, который будет передан в 1С при следующем обмене</pre>';
					echo '<div onmousedown="moveState = false;" onmousemove="moveState = false;" style="overflow-y:scroll;height:80%;width:100%">';
					highlight_string($xml);
					echo '</div>';
				}
			else echo $xml;
			die();
	}

	if ($_GET["action"]=="show_bxmltree")
	{
		CModule::IncludeModule('iblock');
		$xmlfile=new CIBlockXMLFile;
		$dbres=$xmlfile->GetList();
		if (!$dbres)
				die();

		echo '<div  style="overflow:auto;height:90%;width:100%;">';
		echo '<table cellspacing=2 cellpadding=5 style="border:0px solid #E6E6FA;font-size:11">';

		echo '<tr style="background:grey;color:white;">';
		echo '<td>'.'ID'.'</td>';
		echo '<td>'.'PARENT_ID'.'</td>';
		echo '<td>'.'LEFT_MARGIN'.'</td>';
		echo '<td>'.'RIGHT_MARGIN'.'</td>';
		echo '<td>'.'DEPTH_LEVEL'.'</td>';
		echo '<td>'.'NAME'.'</td>';
		echo '<td>'.'VALUE'.'</td>';
		echo '<td>'.'ATTRIBUTES'.'</td>';
		echo '</tr>';

		while($res=$dbres->Fetch())
		{
			echo '<tr>';
			foreach ($res as $value):
			echo '<td valign=top  style="width:50px;border:1px solid #E6E6FA">'.$APPLICATION->ConvertCharset($value,SITE_CHARSET,"windows-1251").'</td>';
			endforeach;
			echo '</tr>';
		}
		echo '</table>';
		echo '<div>';
		die();
	}

//вывод содержимого файлов
if ($_GET["mode"]=="show_xml")
	{
	      echo '<pre><b>'.$_SERVER['DOCUMENT_ROOT'].$_GET["path"].$_GET["file"].$WriteError.'</b>'.'</pre>';

            if (isset($_GET["path"]))
				$filename=$_SERVER['DOCUMENT_ROOT'].$_GET["path"].$_GET["file"]; else
            $filename=$_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/1c_catalog/".$_GET["file"];
          	$file_ext=substr(strrchr($filename, '.'), 1);
			if (in_array($file_ext,$APicture))
			{
			echo "<img src='".$_GET["path"].$_GET["file"]."'>";  die();
			}
			$xml = file_get_contents($filename);
			if (!$xml) echo "Нет такого файла";

			if (ToUpper(SITE_CHARSET)!='WINDOWS-1251')
				$xml=$APPLICATION->ConvertCharset($xml,SITE_CHARSET,"windows-1251");
			//if(@$_GET['isutf']=='Y')
				//$xml=$APPLICATION->ConvertCharset($xml,"UTF-8","windows-1251");
			function callback($buffer)
						{
						if (round(filesize($_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/1c_catalog/".$_GET['offers'])/1024,2)<2000)
							 {
								$pattern=Array('/Товар/','/ЗначенияСвойства/');
								$replacements=Array("<b style='color:red'>Товар</b>","<b style='color:green'>ЗначенияСвойства</b>");
								$buffer=preg_replace($pattern, $replacements, $buffer);
							}
							 if (!$f=fopen($_SERVER['DOCUMENT_ROOT'].$_GET["path"].$_GET["file"],'a'))
							 $WriteError="<p style='color:red;'>Открыть на запись файл не удастся!</p>";
							 fclose($f);
return '<div onmousedown="moveState = false;" onmousemove="moveState = false;" style="overflow:auto;height:80%;width:100%;">'.$buffer.'</div>';


}
			ob_start("callback");
			highlight_string($xml);
			ob_end_flush();
			die();
	}

if ($_GET["mode"]=="edit")
	{

             if (isset($_GET["path"]))
			$filename=$_SERVER['DOCUMENT_ROOT'].$_GET["path"].$_GET["file"]; else
            $filename=$_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/1c_catalog/".$_GET["file"];
            echo '<pre style="background:white; text-align:right">'.$filename.'</pre>';

			$file_ext=substr(strrchr($filename, '.'), 1);
			if (in_array($file_ext,$APicture))
			{
			echo "<img src='".$_GET["path"].$_GET["file"]."'>";  die();
			}
			$xml = file_get_contents($filename);

			if (!$xml) echo "Нет такого файла";
			//if(@$_GET['isutf']=='Y')
			//$xml=$APPLICATION->ConvertCharset($xml,"UTF-8","windows-1251");
			if (ToUpper(SITE_CHARSET)!='WINDOWS-1251')
				$xml=$APPLICATION->ConvertCharset($xml,SITE_CHARSET,"windows-1251");
			?>


			<div id="sfstatus" onmousedown="moveState = false;" onmousemove="moveState = false;" style="display:none;color:green;border:1px dashed green;padding:5; text-align:center;width:250;margin:5"></div>
			<table>
			<tr>
				<td>
					<div onmousedown="moveState = false;" onmousemove="moveState = false;" id="savefile" align="center"  onclick="SaveFile('<?=$_GET["path"].$_GET["file"]?>')" OnMouseOver="LightOn(this,'сделанные изменения будут сохранены');" OnMouseOut="LightOff()"; class="button2">Сохранить</div>
				</td>
				<td>
					<div onmousedown="moveState = false;" onmousemove="moveState = false;" id="viewfile" align="center"  onclick="ShowFile('<?=$_GET["file"]?>','<?=$_GET["path"]?>','N')" OnMouseOver="LightOn(this,'переход в режим просмотра текущего файла');" OnMouseOut="LightOff()"; class="button2">Посмотреть</div>
				</td>
			</tr>
			</table>
			<?
			echo '<textarea onmousedown="moveState = false;" onmousemove="moveState = false;" id="textfile" rows="30" cols="119" style="overflow:auto;height:70%;width:100%;">'.htmlspecialchars($xml).'</textarea>';
			die();
	}

//save
	if ($_REQUEST["action"]=="save")
	{
	    $filename=$_SERVER['DOCUMENT_ROOT'].$_REQUEST["filename"];
		$f = fopen($filename, 'w+');
		if (ToUpper(SITE_CHARSET)!='UTF-8')
			$text=$APPLICATION->ConvertCharset($_REQUEST["text"],'UTF-8',SITE_CHARSET);
		if (($f)&&(fwrite($f, $text)!=false))
		echo 'OK'; else echo 'error';
		fclose($f);
		die();
	}
//проверка файла,  не существует или нет прав на чтение?
debugbreak();

/*if (@$_GET['check_file']=="Y")
{
	unset($_SESSION["BX_CML2_IMPORT"]);
	$c=0;
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$UPLOAD_DIR."/1c_catalog/".$_GET['file'])) $c=$c+2;
	else $c=$c+3;
	if($c==2)
	echo "<div style='width:270;font-size:11;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;background-color:FA8072;padding:5'>Нет прав на чтение файла!</div>";
	if ($c==3)
	echo "<div style='width:270;font-size:11;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;background-color:FA8072;padding:5'>Файла ".$_GET['file']." не сущестует!</div>";
	die();
} */

$items[0]=Array();
$group[0]=Array();
$c_offers[0]=Array();

//получение  информации по количеству групп, товаров и предложений, путём анализа файлов каталога и предложений
if (@$_GET['info']=="Y")
{
	$content=file_get_contents($_SERVER['DOCUMENT_ROOT'].$_GET['file']);
	$offer=iconv("WINDOWS-1251", "UTF-8", '<Предложение>');
	$product=iconv("WINDOWS-1251", "UTF-8", '<Товар>');
	$section=iconv("WINDOWS-1251", "UTF-8", '<Группа>');
	preg_match_all('/'.$product.'/', $content , $items);
	preg_match_all('/'.$section.'/', $content , $group);
	preg_match_all('/'.$offer.'/', $content, $c_offers);
    $file_size=round(filesize($_SERVER['DOCUMENT_ROOT'].$_GET['file'])/1024,2);
?>

	<table style="font-size:11;" cellpadding="0"><tr><td align="right">Размер файла: </td><td><b><?=$file_size.' kb';?></b> | </td>
	<td align="right" >Предложений: </td><td><b><?=count($c_offers[0]);?></b> | </td>
	<td align="right">Товаров: </td><td><b><?=count($items[0]);?></b> | </td>
	<td align="right">Групп: </td><td><b><?=count($group[0]);?></b></td></tr>
	</table>
<?	die();
}

//смена метки времени последнего обмена
if (!$_REQUEST['path1']==''):
	$path_companent = substr($_REQUEST['path1'], 0, 22);
	$full_path=$_REQUEST['path1'];
else:
	$path_companent = substr("/bitrix/admin/1c_exchange.php", 0, 22);
	$full_path="/bitrix/admin/1c_exchange.php";
endif;

if((!$_REQUEST['date']=='')&&(isset($_REQUEST['change'])))
{
	if (!file_exists("bx_exchange_date.log"))
	{
		$f = fopen ("bx_exchange_date.log", 'a+');
		fwrite ($f, ConvertTimeStamp(COption::GetOptionString("sale", "last_export_time_committed_".$path_companent, ""), "FULL"));
		fclose($f);
	}
	COption::SetOptionString("sale", "last_export_time_committed_".$path_companent, MakeTimeStamp($date, "DD.MM.YYYY HH:MI:SS"));
}

$date=ConvertTimeStamp((integer)COption::GetOptionString("sale", "last_export_time_committed_".$path_companent, ""), "FULL");
if (isset($_REQUEST['AJAX']))
{
	echo $date;
	die();
}

//получнеие списка заказов, которые будут выгружены в 1с при следующем обмене
if (isset($_REQUEST['check'])):
	CModule::IncludeModule("sale");
	$path_companent = substr($_REQUEST['path'], 0, 22);
	if(isset($_REQUEST['PAYED']))
		$arFilter['PAYED']="Y";
	if(isset($_REQUEST['ALLOW_DELIVERY']))
		$arFilter['ALLOW_DELIVERY']="Y";
	$arFilter[">=DATE_UPDATE"] = ConvertTimeStamp(COption::GetOptionString("sale", "last_export_time_committed_".$path_companent, ""), "FULL");
	$change=false;
	$dbOrderList = CSaleOrder::GetList(
						array("ID" => "DESC"),
						$arFilter,
						false,
						$count,
						array("ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED", "EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID", "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE", "SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION", "ADDITIONAL_INFO", "PS_STATUS", "PS_STATUS_CODE", "PS_STATUS_DESCRIPTION", "PS_STATUS_MESSAGE", "PS_SUM", "PS_CURRENCY", "PS_RESPONSE_DATE", "COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID")
					);
?>
	<div style="font-size:12;padding:3;background: white;"> Дата последнего обмена - <?=$arFilter[">=DATE_UPDATE"]?></div>
	<br>
<?	$n=0;
	echo '<div style="font-size:11;padding:3;background: white;">';
	while($arOrder = $dbOrderList->Fetch())
	{
		$n++;
		echo '<a href="/bitrix/admin/sale_order_detail.php?ID='.$arOrder["ID"].'" target="_blank" >Заказ №'.$arOrder["ID"].'</a>';
		echo ' - дата именения ',$arOrder["DATE_UPDATE"];
		echo '<br>';
		$change=true;
	}
	if (!$change) echo "На сайте нет заказов, изменённых после даты последнего обмена с 1С!!!";
	echo '<br><b>ВСЕГО ЗАКАЗОВ: '.$n.'</b><br>';
	echo "</div>";
	die();
endif;
if (isset($_REQUEST['setstep']))
{
	$_SESSION["BX_CML2_IMPORT"]["NS"]["STEP"]=IntVal($_REQUEST['setstep']);
	echo $_SESSION["BX_CML2_IMPORT"]["NS"]["STEP"];
	die();
}
unset($_SESSION["BX_CML2_IMPORT"]);//сброс  шага импорта
$host='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];//хост
?>
<html>
<body class="body" onmousedown="down_action(event)" onmouseup="up_action(event)" onkeydown="ShowFileMan()" >
<head>
<?CUtil::InitJSCore(Array('ajax','window'));
$APPLICATION->ShowHead();?>
<style>
body
{
	background:white;
	font-family:tahoma,verdana,arial,sans-serif,Lucida Sans;
	font-size:12;
}

.button
{
   background-color:#B9D3EE;
   border:1px solid #ADC3D5;
   width:150;
   height:20px;
   font-size:13;
   color:#2B587A;
   padding-top:5;
   padding-bottom:2;
}

.button2
{
   background-color:#B9D3EE;
   border:1px solid #ADC3D5;
   cursor:hand;
   text-align:center;
   width:150;
   height:20px;
   font-size:12;
   color:#2B587A;
   margin:5;
   padding-top:5;
}


.rtopwin, .rbottomwin{display:block;width:200;}
.rtopwin *,.rbottomwin *{display: block; height: 1px; overflow: hidden;background:#B9D3EE;}
.r1win{margin: 0 5px;}
.r2win{margin: 0 3px;}
.r3win{margin: 0 2px;}
.r4win{margin: 0 1px; height: 2px;}

.msection
{
	width:180;
	font-size:14;
	color:white;
	border-top:none;
	background:#B9D3EE;
	border-bottom:6px solid #B9D3EE;
}


.FrontTab
{
  position:absolute;
  height:15;
  top:30;
  border-top:1px solid black;
  border-right:1px solid black;
  border-left:1px solid black;
  border-bottom:2px solid #FFF8DC;
  background:#FFF8DC;
  padding:5;
  margin:0;
  width:100;
  z-index:100;
}

.message
{
   background-color:#B9D3EE;
   border:2px solid red;
   text-align:center;
   position:absolute;
   height:15px;
   padding:10;
   left:40%;
   top:50%;
   opacity:0.5;
   font-size:12;
   color:red;
   z-index:10000;
}

.message2
{
   background-color:#B9D3EE;
   border:2px solid green;
   text-align:center;
   position:absolute;
   height:15px;
   padding:10;
   left:0%;
   top:90%;
   opacity:0.5;
   font-size:12;
   color:green;
   position:fixed;
   z-index:10000;
}

.divwin
{
	width: 300;
	border: 1px solid black;
	background: #708090;
	z-index:100;
	display: none;
	position: fixed;
   	cursor:hand;
	left:320;
	top:160;
	padding:5;

}

.divwin_times
{
	width: 300px;
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: none;
	position: fixed;
	cursor:default;
	left:70%;
	top:170;
	padding:5;

}

.divwin_param
{
	width: 300;
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: none;
	position: fixed;;
	cursor:default;
	left:70%;
	top:8;
	padding:5;

}

.divwin_stepdiag
{
	width: 170;
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: none;
	position: fixed;;
	cursor:default;
	left:50%;
	top:8;
	padding:5;

}

.divwin_orderlist
{
	width: 300px;
	border: 1px solid black;
	background: #708090;
	z-index:100;
	display: none;
	position: fixed;
  	cursor:default;
	left:70%;
	top:400px;
	padding:5px;

}

.divwin_info
{
	width: 320px;
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: none;
	position: fixed;
	cursor:default;
	left:7px;
	top:60%;
	padding:5px;

}

.divwin_main
{
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: block;
	position: fixed;
	cursor:default;
	left:10px;
	top:20px;
	padding:5;

}

.divwin_custom
{
	width: 500px;
	border: 1px solid black;
	background: #B9D3EE;
	z-index:100;
	display: block;
	position: fixed;
	cursor:default;
	left:225px;
	top:8px;
	padding:5px;

}

.but
{
	border: 1px solid Gray;
	background-color: #DCDCDC;
	padding: 1px 1px 1px 1px;
	margin:1px;
	font-size:12;
	width:150;
	color:black;
	align:center;
}

.small_but
{
	border: 1px solid #E6E6FA;
	background-color: #DCDCDC;
	padding: 1px 1px 1px 1px;
	font-size:11;
	width:150;
	color:black;
	margin:1px;
	align:center;
}

.closeButton {
	position: absolute;
	top: 0px;
	right: 0px;
	border-bottom: 1px solid gray;
	border-left: 1px solid gray;
	font-weight: bold;
	cursor: pointer;
	z-index:250;
	background: white;
	padding: 2px 4px 2px 4px;
}

.sysbutton
{
	position: absolute;
	top: 2px;
	right: 2px;
	font-size:12;
	border: 1px solid gray;
	cursor: pointer;
	z-index:250;
	background: white;
	padding: 2px 4px 2px 4px;
}

.main_div
{
	width:25%;
   background-color:#FFE4B5;
   border:1px solid #ADC3D5;
   text-align:center;
   position:fixed;
   left:74%;
   top:45px;
   font-size:11
}

.main_table
{
   width:50%;
   text-align:center;
}

.th_table
{
	border:1px solid #ADC3D5;
	text-align:right;
	font-size:11
}

.th_table2
{
	border:1px solid #ADC3D5;
	text-align:right;
}


table.menu
{
   border:1px solid black;
   background-color:white;
   width:110;
   height:40;
   padding:5px;
   z-index:7000;
}

td.menu
{
   background-color:white;
   width:110;
   height:25;
   padding:2px;
   z-index:7000;
}

.point_menu
{
   background-position:left;
   font-size:11;
   background-repeat: no-repeat;
   padding-left:20;
   padding-top:10;
   padding-bottom:10;
   text-decoration: none;
   color:black;
   position:relative;
}

a.menu
{
   background-image: url(/bitrix/images/fileman/view.gif);
}

a.menu2
{
   background-image: url(/bitrix/images/fileman/edit_text.gif);
}

a.menu_del
{
   background-image: url(/bitrix/images/fileman/htmledit2/c2del.gif);
}

a.menu_unzip
{
	background-image: url(/bitrix/images/fileman/htmledit2/redo.gif);
}

a.menu_dw
{
   background-image: url(/bitrix/images/fileman/types/file.gif);
}


   A {
   text-decoration: none;
   color:#36648B;
   }

</style>
<script>
<?=$win;?>
</script>
</head>
<div id="custom_windows">

<?
ob_start();
?>
<table style="font-size:12" width=100%>
<tr><td width=5% align=right>
Фильтр:<br>
Путь:<br>
</td>
<td>
<input onmousedown='moveState = false;' onmousemove='moveState = false;' id=search_str style='font-size:10;width:90%'  name='search_str' OnChange=GetFileList('path_fileman','testfileman') value='<?if(isset($_SESSION['bx_1c_import']['filter'])) echo $_SESSION['bx_1c_import']['filter'].'\'>'; else echo '\'>'?><br>
<input onmousedown="moveState = false;" onmousemove="moveState = false;" OnChange="GetFileList('path_fileman','testfileman');" id="path_fileman" style="font-size:10;width:90%" name="path_fileman" value='<?if(isset($_SESSION['bx_1c_import']['path'])) echo $_SESSION['bx_1c_import']['path']; else echo $UPLOAD_DIR.'/1c_catalog/';?>'>
</td>
</tr>
<tr><td colspan=2 align=left>
<input onmousedown="moveState = false;" onmousemove="moveState = false;" onclick="GetFileList('path_fileman','testfileman');" type='button' name='go' value="перейти"></td></tr>
</td></tr>
</table>

<?
$beforeInner=ob_get_contents();
ob_end_clean();
$afterinner='<hr><div id="info">----</div>';

$inner='<table style="width:100%;font-size:10;"><tr><td><div id="testfileman"></div><td valign=top width=100><input type=checkbox id=isdir>папку<br><input id=cfilename onmousedown="moveState = false;" onmousemove="moveState = false;" value=\'bx_test.php\'><input type=button value=\'создать\' onclick=CreateFile(\'cfilename\',\'path_fileman\',\'testfileman\')><hr>'.AddButton('upfile',false,true).'</td></tr></table>';

AddWindow("test_window","Файловая структура",'testsfileman',$inner,false,"",false,$beforeInner,$afterinner);
AddWindow("upload_file","Загрузка файла файл",'upload_file_id','<iframe id="file_panel"  height=150 src="'.$script_name.'?upload=Y"></iframe>',$DefaultWinStyleSmall);
?>

</div>



<div  onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);" id='ind_'style="width:300;display:none;left:360;padding:1;z-index:10000;position:absolute;background-color:#EEE8CD;border:1px solid grey;height:30;cursor:move;">
<div>Прогресс выполнения шага импорта...</div>
<div id='indicate' style="width:0;background-color:green;border:none;z-index:1;height:10;text-align:center;"></div>
</div><br>
<div id="main_menu" class="divwin_main" onselectstart="return false" onmousedown = "initMove(this, event)"; onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<b>1С Diag </b>v<?=ver?> (for Google Chrome only)<hr>
<div class="closeButton">-</div>

<table onmousedown="moveState = false;" onmousemove="moveState = false;" style="width:300;background-color:#EEE8CD;border:1px solid grey;z-index:1" cellpadding=0 cellspacing=1>
				<tr style="border:1px solid #B9D3EE">
				<td width=100 align=right>
				<?AddButton('delete');?>
				</td>
				<td width=120>
				<?AddButton('refresh');?>
				</td></tr><tr>
<?$i=1;

foreach($MenuArray as $key=>$value):
if ($i===1) echo "<td valign='top' align='center' width=50>";
AddButton($key,true);
if (($i>=MENULINES)||(count($MenuArray)<=$i)) {echo '</td>';$i=1;} else {$i++;}
endforeach;

?>
				<td  valign='top' width=120>
				</td>
				</tr>
				<tr><td COLSPAN=2 id='system_inf' style='display:none;font-size:10;color:red'>Конвертирование файлов функцией iconv(): <?if(@BX_ICONV_DISABLE===true) echo 'нет'; else echo 'да';?> </td></tr>
				<tr id="FileImportSection" style='display:none;'>
				<td align="right" COLSPAN=2>
				<?AddButton('cat_imp');?>
				<?AddButton('cat_off');?>
				<?AddButton('cat_comp');?>
				<?AddButton('order_import');?>

				</td>
				</tr>

				<td COLSPAN=2 style="background-color:#FFE4B5;text-align:center;font-size:12">Просмотр файла заказов</td>
				<tr>
		<td width=120>
<div class="but" align="center" OnClick="SaveMe('<?=$host;?>')" OnMouseOver="LightOn(this,'будет получен текст xml-файла заказов, которые отдаст сайт 1с-ке при следующем обмене заказаим с 1С')" OnMouseOut="LightOff()"> XML-файл заказов</div>
   </td>
        <td>
   <div class="but" align="center"  Onclick="javascript:_BlankXML('<?='view-source:'.$host.'?mode=query'?>')" OnMouseOver="LightOn(this,'будет открыто <b>отдельное</b> окно с текстом xml-файла заказов, которые отдаст сайт 1с-ке при следующем обмене заказаим с 1С')" OnMouseOut="LightOff()">XML в отдельном окне</div></td>
				</tr>
				</table></div>
<div id="main_info" class="divwin_info" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<b>Поиск</b><hr>
<div class="closeButton" onclick="Close('main_info')">X</div>
<table>
<tr>
	<td valign="top">
		<table style='width:310;font-size:11;border:1px solid #ADC3D5;background-color:white;'>
					<tr>
						<td style='font-size:11;border-bottom:1px solid #ADC3D5;'>
							<a href="javascript:stayhere();">Поиск элемента по внешнему коду</a>
						</td>
					</tr>
					<tr>
						<td>

						</td>
					</tr>
					<tr>
						<td align="center">
<p style='font-size:10;'>Для поиска выделенного в тексте XML_ID нажмите <em>alt+s</em><br></p>
										<input onmousedown="moveState = false;" onmousemove="moveState = false;" style="font-size:10;" size=40 id="q" type="text" name="search" value="XML_ID"><br>
										<?AddButton('searchbutton');?>
						</td>
					</tr>
					<tr>
						<td align="center">
							<div id="result"></div>
						</td>
					</tr>
				</table>
	</td>
						</tr>
</table>
	</td>
</tr>
</table>
</div>

<div class="divwin" id="log3" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<b>Лог импорта файла</b>
<hr>
<div class="closeButton" onclick="winClose()">Х</div>
<div id="log" style='font-size:10;padding:3;background: white;overflow-y:scroll;height:300'></div>
<div id="timer" style='font-size:12;padding:5;background: white;'></div>
</div>

<?if (!isset($_REQUEST['check'])):?>
<title>Интеграция с 1С</title>
<?if (@$_POST['AJAX']!='Y'){?>
<div id="main" class="divwin_times" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<b>Смена метки времени</b><hr>
<div class="closeButton" onclick="Close('main')">X</div>
<div style='font-size:11;padding:3;background: white;'>
<table align='center'>
<tr>
<th class="th_table">Путь</th>
<th class="th_table2"><input onmousedown="moveState = false;" onmousemove="moveState = false;" id='path1' type="text" size="30" value="<?if(isset($_POST['path1'])) echo $_POST['path1']; else echo "/bitrix/admin/1c_exchange.php"?>" name="path1"></th>
</tr>
<tr>
<th class="th_table">Дата </th>
<th class="th_table2"><input onmousedown="moveState = false;" onmousemove="moveState = false;" id='date' type="text" size="30" value="<?if(!$_POST['date']=='') echo $_POST['date']; else echo $date;?>" name="date"></th>
</tr>
<tr><td COLSPAN=2 align="center">
<?AddButton('change1');?>
</td></tr>
</table>
<?}?>
<?
if (file_exists("bx_exchange_date.log"))
{
$f = fopen("bx_exchange_date.log", 'r');
$real_date=fread($f, filesize("bx_exchange_date.log"));
fclose($f);
if (@$_POST['AJAX']=='Y') {echo $real_date;die();}
echo "<hr>Реальная дата последнего обмена: ". $real_date;
}
?>
</div>
</div>
<div id="list" class="divwin_orderlist" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<b>Изменения в заказах</b><hr><div class="closeButton" onclick="CloseOrderList()">Х</div></div>
<div id="param" class="divwin_param" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<div class="closeButton" onclick="Close('param')">X</div>
<b>Параметры выгрузки заказов</b><hr>
<div style="background-color:#E8E8E8;border-top:1px solid #ADC3D5;text-align:center;font-size:11">
<table align="center">
<tr>
<th class="th_table">Путь</th>
<th class="th_table2"><input onmousedown="moveState = false;" onmousemove="moveState = false;" id="path" type="text" size="25" value="<?if(isset($_POST['path'])) echo $_POST['path']; else echo "/bitrix/admin/1c_exchange.php"?>" name="path"></th>
</tr>
<tr><th class="th_table">Оплаченные</th>
<th style="text-align:left;border:1px solid #ADC3D5;">
<input onmousedown="moveState = false;" onmousemove="moveState = false;" id="PAYED" type="checkbox" <?if(isset($_POST['PAYED'])) echo "checked";?> value='Y' name="PAYED"></th></tr>
<tr><th class="th_table">Доставленные</th>
<th style="text-align:left;border:1px solid #ADC3D5;">
<input onmousedown="moveState = false;" onmousemove="moveState = false;" id="DELIVERY" type="checkbox" <?if (isset($_POST['ALLOW_DELIVERY'])) echo "checked";?> value='Y' name="ALLOW_DELIVERY"></th></tr>
<tr><td COLSPAN=2 align="center"><div class="button2" OnMouseOver="LightOn(this,'сформируется список заказов в соответствии с указанными параметрами')" OnMouseOut="LightOff()" OnClick="GetOrders()">Проверить</div></td></tr>
</table>
</div></div>

<div id="stepdiag" class="divwin_stepdiag" onselectstart="return false" onmousedown = "initMove(this, event)";   onmouseup = "moveState = false;"  onmousemove = "moveHandler(this, event);">
<div class="closeButton" onclick="Close('stepdiag')" >X</div>
<b>Пошаговая диагностика</b><hr>
<div style="background-color:#E8E8E8;border-top:1px solid #ADC3D5;text-align:center;font-size:11">
<table align="center">
<tr><td><input onmousedown="moveState = false;" onmousemove="moveState = false;" id=stepfile value='import.xml'></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Удаление врем. таблиц')" OnMouseOut="LightOff()" OnClick="StartStep(0)">Удаление врем. таблиц</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Удаление врем. таблиц')" OnMouseOut="LightOff()" OnClick="StartStep(1)">Создание врем. таблиц</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Импорт во врем. талицу')" OnMouseOut="LightOff()" OnClick="StartStep(2)">Импорт во врем. таблицу</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Создание индекса')" OnMouseOut="LightOff()" OnClick="StartStep(3)">Создание индекса</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Импорт метаданных')" OnMouseOut="LightOff()" OnClick="StartStep(4)">Импорт метаданных</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Импорт секций')" OnMouseOut="LightOff()" OnClick="StartStep(5)">Импорт секций</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Удаление секций')" OnMouseOut="LightOff()" OnClick="StartStep(6)">Удаление секций</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Обработка элементов')" OnMouseOut="LightOff()" OnClick="StartStep(7)">Обработка элементов</div></td></tr>
<tr><td align="center"><div class="but" OnMouseOver="LightOn(this,'Удаление элементов')" OnMouseOut="LightOff()" OnClick="StartStep(8)">Удаление элементов</div></td></tr>

</table>
</div></div>

<?endif;?>
<br>
	<?
		$inner="empty";
		AddWindow('EditWindow','Здесь отображается содержимое файлов','para1',false,$EditStyle);

		?>
		</div>
<table id="tbl" cellpadding=4 cellspacing=0 style="position:relative;width:70%;z-index:1;left:350;text-align:left;font-size:10pt;">
		<tr><td style='padding-top:45px;text-align:right;'>
		</td>
		<td style='width:100%;'>

		</td></tr>
</table>
<div id="load" align="right" style='border:1px solid black;width:200;z-index:10000;font-size:15;position:fixed;top:85%;background-color:white;display:none;'>
Загрузка...
</div>


<div id="menu_1" style="z-index:7000;display:none;">
<?BuildContextMenu();?>
</div>

</body>
<div id="mess_decorate" style='width:100%;padding:5;z-index:10000;opacity:0.7;align:right;font-size:14;position:fixed;top:92%;background-color:#FFE4B5;display:none;'>
Что сейчас произойдёт:
</div>
<div id="text_mess" style='width:100%;padding:5;left:200;z-index:10000;align:right;font-size:14;position:fixed;top:92%;display:none;'>
пока ничего
</div>
</html>

<script>

var i,status,des,a
var log=BX("log");
var fileinfo=BX("info");
var result=BX("result");
var timer=BX("timer");
var load=BX("load");
var zup_import=false;
var text_mess=BX('text_mess');
var mess_decorate=BX('mess_decorate');
var load=BX("load");
globalpath='<?=$_SESSION['bx_1c_import']['path']?>';
var ImportStep=0;
var mywindows=new Array("log3","main","list","main_info","main_menu","stepdiag","param");
if (!new_id)
var new_id=new Array();
var moveState = false;
var x0, y0;
var divX0, divY0;
var lastwin="main_info";
var i=1;
var status="continue";
var menu=BX("menu_1");
var NewFieldID=1;


function CreateFileDialog(Name,where)
			{
				var where='testfileman';
				var newP=document.createElement('input');
				//var newP=document.createElement('div');
				var newField=document.createElement('div');
				var FieldID=NewFieldID+'_field';
				var TabID=NewFieldID+'_tab';
					NewFieldID++;
					//alert(TabID);
					//создаём поле для таба
					newField.style.width='350px';
					newField.style.height='80px';
					newField.style.padding='5px';
					newField.style.background = '#FFF8DC';
					newField.style.position='absolute';
					newField.style.top='250px';
					newField.style.left='130px';
					newField.style.display='block';
					newField.style.border='1px solid #00C5CD';
					newField.style.zIndex='99';
					newField.innerHTML='<input type=checkbox id=isdir>Создаём папку, а не файл<br><br>';
					newField.innerHTML+='Имя файла/папки:<br>'+'<input id=cfilename value=\'bx_test.php\'size=40><input type=button value=\'создать\' onclick=CreateFile(\'cfilename\',\'path_fileman\',\'testfileman\')>';
					BX(where).appendChild(newField);

					return newField.id;
			}

function MoveToNode(NodeID,ToNodeID)
		{
			   var old_node = BX(NodeID);
			   var oldparentNode = BX(NodeID).parentNode;
			   var clone = old_node.cloneNode(true);
			   var newparentNode = BX(ToNodeID).appendChild(clone);
			   oldparentNode.removeChild(old_node);
		}

function CreateIBlock()
				{
				var	iblock1c=BX('1ciblock');
			    var	iblocktype=BX('iblocktype');
				q="<?=$script_name?>?action=createiblocktype&iblocktype="+iblocktype.value;
				if (iblock1c.value=='on')
				q=q+'&USE_IBLOCK_TYPE=Y';
				AjaxRequest(q,'successiblock',false);
				}

function AddWindowRequest(url,id,windowid)
				 {
				 if ((("#" + mywindows.join("#,#") + "#").search("#"+windowid+"#") != -1)||(("#" + new_id.join("#,#") + "#").search("#"+windowid+"#") != -1))
						{
							BX(windowid).style.display="block";
						}
						else
						{
							AjaxRequest(url,id,true);
							new_id[new_id.length]=windowid;
						}
				 }

function AjaxRequest(url,id,AddResult)
				{
					var ajaxreq=createHttpRequest();
					load.style.display="block";
					load.innerHTML=' <img align="center" src="http://vkontakte.ru/images/upload.gif" width="50"/> загрузка...';
					   ajaxreq.open("GET", url, true);
						ajaxreq.onreadystatechange=function()
									{
										if (ajaxreq.readyState == 4)
										{
											if (AddResult==false)
											{
												BX(id).innerHTML=ajaxreq.responseText;
											}
											else
											{
												BX(id).innerHTML+=ajaxreq.responseText;
											}
												load.style.display="none";
										}
									}

						ajaxreq.send(null);

				}

function Download(file,path)
{
	JustHide();
	BX("dwframe").src="<?=$script_name?>?action=download&file="+file+"&path="+path;
}

// создание объекта XMLHttpRequest
function createHttpRequest()

   {
	var httpRequest;
		if (window.XMLHttpRequest)
		httpRequest = new XMLHttpRequest();
		else if (window.ActiveXObject) {
		try {
		httpRequest = new ActiveXObject('Msxml2.XMLHTTP');
		} catch (e){}
		try {
		httpRequest = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (e){}
		}
	return httpRequest;

}

// показываем недоменю
function ShowMenu(event)
{
	var	edit=BX('e');
	//var	editutf=BX('eutf');
	var	view=BX('v');
	//var	viewutf=BX('vutf');
	var	del=BX('d');
	var	unzip=BX('u');
	var	down=BX('dw');
	var evt=event;
	var ext;
	ext=event.target.textContent.substr(event.target.textContent.length-4,event.target.textContent.length);
	menu.style.display="block";
	menu.style.top=evt.clientY;
	menu.style.left=evt.clientX;
	menu.style.position="absolute";
	view.href=event.target.href;
	//vutf.href="javascript:ShowFile('"+event.target.textContent+"','"+globalpath+"','Y')";
	//editutf.href="javascript:Showforedit('"+event.target.textContent+"','"+globalpath+"','Y')";
	edit.href="javascript:Showforedit('"+event.target.textContent+"','"+globalpath+"','N')";
	del.href="javascript:Delete('"+event.target.textContent +"','"+event.target.parentNode.parentNode.parentNode.parentNode.parentNode.id+"')";
	down.href="javascript:Download('"+event.target.textContent+"','"+globalpath+"')";

	if(ext=='.zip')
	{
		BX("unzip_").style.display='block';
		unzip.href="javascript:UnZip('"+event.target.textContent+"','"+event.target.parentNode.parentNode.parentNode.parentNode.parentNode.id+"')";
	} else BX("unzip_").style.display='none';

	   return false;
}

//функция запускает процесс импорта
function start(file)
{
	i=1;
	a="";
	m_second=0;
        seconds=0;
	proccess="Y";

	display();

	timer.innerHTML="";
	if (file=="import.xml"||file=="offers.xml")
	{
		file=BX('cat_file').options[BX('cat_file').selectedIndex].innerHTML;
	}

	if (file=="company.xml")
	{
		file=BX('worker').options[BX('worker').selectedIndex].innerHTML;
		zup_import=true;
	}

	load.innerHTML='идёт загрузка...<img align="center" src="http://gifanimation.ru/images/ludi/17_3.gif" width="30"/>';
	wObj=BX('log3');
	if (BX('intofield').checked==false)
	{
		log=BX("log");
		log.innerHTML="<b>Импорт "+file+"</b><hr>";
		winOpen();
	}
	else
	{
		BX("para1").innerHTML='<div id="log2" style="font-size:15;padding:3;background: white;overflow-y:scroll;height:88%"></div>'
		log=BX("log2");
		log.innerHTML="<b>Импорт "+file+"</b><hr>";
	}

	query_1c(file, 'checkauth', 0);
}



//функция осущетсвляет импорт из файла
function query_1c(file, mode, timestamp)
{
	sInd=0;
	BX('indicate').style.width=0;
	var import_1c=createHttpRequest();
	gs="<?=$script_name?>?action=getstep";

	var getstep=createHttpRequest();
	getstep.open('GET',gs,true);
	getstep.onreadystatechange = function()
	{
		if (getstep.readyState == 4)
		{
			ImportStep = getstep.responseText;

			if (zup_import==true)
			{
				r = "/bitrix/admin/1c_intranet.php?type=catalog&mode="+mode+"&filename=" + file;
			}
			else
			{
				r = "/bitrix/admin/1c_exchange.php?type=catalog&mode="+mode+"&filename="+file+"&step="+ImportStep;
			}

			if (timestamp)
				r += '&timestamp=' + timestamp;

			//alert(r);
			load.style.display="block";

			import_1c.open("GET", r, true);
			import_1c.onreadystatechange = function()
			{
				a = log.innerHTML;
				if (import_1c.readyState == 4 && import_1c.status == 0)
				{
					error_text="<em>Ошибка в процессе выгрузки</em><div style='width:270;font-size:11;border:1px solid 				black;background-color:#ADC3D5;padding:5'>Сервер упал и не вернул заголовков.</div>"
					log.innerHTML=a+"Шаг "+i+": "+error_text;
					load.style.display="none";
					status="continue"
					alert("Import is crashed!");
				}

				if (import_1c.readyState == 4 && import_1c.status == 200)
				{
					if ((import_1c.responseText.substr(0,8)!="progress")&&(import_1c.responseText.substr(0,7)!="success"))
					{
						if (import_1c.responseText.substr(0,7)=="failure")
							check_file(file);

						error_text="<em>Ошибка в процессе выгрузки</em><div style='font-size:11;border:1px solid black;background-color:#ADC3D5;padding:5'>"+import_1c.responseText+"</div>"
						log.innerHTML=a+"Шаг "+i+": "+error_text;
						status="error"
					}
					else
					{
						n=import_1c.responseText.lastIndexOf('s')+1;
						l=import_1c.responseText.length;
						mess=import_1c.responseText.substr(n,l);
						log.innerHTML=a+"Шаг "+i+": "+mess+" ("+seconds+" сек.)"+"<br>";
						BX('ind_').style.display='none';
						seconds=0;
						i++;
					}

					if ((import_1c.responseText.substr(0,7)=="success") && (mode=='checkauth'))
					{
						console.info(import_1c.responseText);
						n=import_1c.responseText.lastIndexOf('timestamp=')+10;
						console.info(n);
						l=import_1c.responseText.length;
						console.info(l);
						timestamp=parseInt(import_1c.responseText.substr(n,l));
						console.info(import_1c.responseText.substr(n,l));
						query_1c(file, 'import', timestamp);
					}
					else if ((import_1c.responseText.substr(0,7)=="success") && (mode=='import'))
					{
						query_1c(file, 'deactivate', timestamp);
					}
					else if ((import_1c.responseText.substr(0,7)=="success")||(status=="error"))
					{
						load.style.display="none";
						load.innerHTML=' <img align="center" src="http://vkontakte.ru/images/upload.gif" width="50"/> загрузка...';
						BX('ind_').style.display='none';
						status="continue"
						proccess="N";
						timer.innerHTML="<hr>Время выгрузки: <b>"+minute+" мин. "+m_second+" сек.</b>";
					}
					else
					{
						query_1c(file, 'import', timestamp);
					}
				}
			};
			import_1c.send(null);
		}
	}
	getstep.send(null);
}

function OrderImport(elem)
		{
			var elem='para1';
			var import_1c=createHttpRequest();
			var	log=BX(elem);
			var	file=BX('order_file').options[BX('order_file').selectedIndex].innerHTML;

			q="/bitrix/admin/1c_exchange.php?type=sale&mode=file&filename="+file;
			load.style.display="block";
			import_1c.open("GET", q, true);
			StartTime();
			import_1c.onreadystatechange = function()
					{
					if (import_1c.readyState == 4)
									{
						                log.innerHTML=import_1c.responseText;
										proccess='N';
										alert('Длительность импорта заказов: '+seconds+' сек.');
						                load.style.display="none";
									}

					}
			import_1c.send(null);
		}

//проверка, существует ли файл, права на него
function check_file(file)
				{
				var error=createHttpRequest();
					q="<?=$script_name?>?check_file=Y&file="+file;
					error.open("GET", q, true);
					error.onreadystatechange = function()
							{

								if (error.readyState == 4 && error.status == 200)
									{
									des=error.responseText;
			log.innerHTML=log.innerHTML+des;
									}
							};
							error.send(null);

				}


	function StartStep(numstep)
		{
		var change_step=createHttpRequest();
		var stepfile=BX("stepfile").value;
		q="<?=$script_name?>?setstep="+numstep;
		change_step.open('GET',q);
		change_step.onreadystatechange = function()
				{
				if (change_step.readyState == 4)
						{
							//alert(change_step.responseText);
							start(stepfile);
						}
				}

		change_step.send(null);
		}

	//отображаем  информацию по товарам, группам и предложениям
	function ShowInfo(file)
				{
				var info=createHttpRequest();
				var fileinfo=BX("info");
					q="<?=$script_name?>?info=Y&file="+file;
					//alert(impfile);
					fileinfo.style.opacity=0.4;
					info.open("GET", q, true);
					info.onreadystatechange = function()
							{

								if (info.readyState == 4 && info.status == 200)
									{

										fileinfo.innerHTML=info.responseText;
										fileinfo.style.opacity="";
									}
							};
							info.send(null);

				}



	//сбрасываем шаг импорта
			function reset()
				{
				var rest=createHttpRequest();
					q="<?=$script_name?>";
					rest.open("GET", q, true);
					rest.onreadystatechange=function()
								{
								if (rest.readyState == 4 && rest.status == 200)
									alert("Шаг импорта обнулён!");
								}

					rest.send(null);

				}




	//удаляем скрипт
function delete_file()
	{
		if (confirm('Удалить файл?'))
			//edirect("bx_1c_import.php?delete=Y");
			document.location = "<?=$script_name?>?delete=Y";
	}

function ConfirmImport(file)
	{
		if (confirm('Импортировать файл?'))
			start(file);
	}


	//ищем товар по xml_id
		function searchbyxmlid()
				{
				var search=createHttpRequest();
				var qs=BX("q");
				var result=BX("result");
				q="<?=$script_name?>?search="+qs.value;
result.innerHTML=' <img align="center" src="http://vkontakte.ru/images/upload.gif" width="50"/> ';
			//	result.innerHTML='<div style="padding:5;" align="right"><em>Ищем...</em><img id="girl" width="20" src="http://gifanimation.ru/images/ludi/17_3.gif"></div>';
				result.style.opacity="";
					search.open("GET", q, true);
					search.onreadystatechange = function()
							{

								if (search.readyState == 4 && search.status == 200)
									{
										result.innerHTML=search.responseText;
										result.style.opacity="";
									}
							};

					search.send(null);
				}


var oldelem,oldop,borderold//переменные цвета



//подцветка кнопки или ещё чего нибудь
function LightOn(el,message)
{
oldelem=el;
el.style.cursor = 'hand';
borderold=el.style.background;
el.style.background="#9AC0CD";
if (message!='')
{
mess_decorate.style.display='block';
text_mess.style.display='block';
text_mess.innerHTML=message;
}
//lert(elem);
}



//возвращаение цвета кнопки или ещё чего-нибудь
function LightOff()
{
var el= oldelem;
el.style.background=borderold;
mess_decorate.style.display='none';
text_mess.style.display='none';
text_mess.innerHTML='ничего пока';
}

var Linkoldelem,Linkoldop,Linkborderold//переменные цвета



//подцветка ссылки
function LinkLightOn(elem,lcolor)
{
var el=BX(elem);
Linkoldelem=elem;
el.style.cursor = 'hand';
Linkborderold=el.style.color;
el.style.color=lcolor;
//lert(elem);
}


//возвращаение цвета ссылки
function LinkLightOff()
{
var el= BX(Linkoldelem);
el.style.color=Linkborderold;
}

// задаём переменные таймера процесса импорта
var m_second=0
var seconds=0
var minute=0
var proccess="Y"
var sInd=0;
var sIntStep=<?=IntVal(300/$interval);?>

//собственно таймер
		function display()
		{
		var indicate=BX('indicate');
			if (m_second==60)
			{
			m_second=0;
			minute+=1;
			}
			if (proccess=="Y")
			{
			seconds+=1;
			m_second+=1;
			//alert(ImportStep);
			if ((ImportStep=='2')||(ImportStep=='7'))
			{
			BX('ind_').style.display='block';
					if (sInd<300)
					{
						sInd=sInd+sIntStep;
						indicate.style.width=sInd +'px';
					} else {sInd=0;}
			}
			else
			{
					sInd=0;
					indicate.style.width=0;
					BX('ind_').style.display='none';
			}
			setTimeout("display()",1000);
		}
		}


		function gotime()
		{
			if (proccess=="Y")
			{
			seconds+=1;
			setTimeout("gotime()",1000);
			}
		}

	function StartTime()
		{
			proccess="Y";
			seconds=0;
			gotime();
		}


//окна дивные
var sStep = 16;
var sTimeout = 15;
var sLeft = 160;
var sRight = 160;
var wObj;

//закрываем окно
function Close(param)
{
BX(param).style.display='none';
}
function winOpen()
{
	wObj.style.display = 'block';
	if (sLeft > 0) {
		sRight += sStep;
		sLeft -= sStep;
		var rect = 'rect(auto, '+ sRight +'px, auto, '+ sLeft +'px)';
		wObj.style.clip = rect;
		setTimeout(winOpen, sTimeout);
	}
}


//закрывем окно красиво
function winClose()
{
	if (sLeft < sRight)
	{
		sRight-=sStep;
		sLeft+= sStep;
		var rect ='rect(auto, '+ sRight +'px, auto, '+ sLeft +'px)';
		wObj.style.clip = rect;
		setTimeout(winClose, sTimeout);
	}
	else wObj.style.display = 'none';
}

var cur="";
var d=document
var wincolor
var winopacity

//двигаем  div'ные окна
		function add_new_window_id(newid)
				{
					mywindows.unshift(newid);
				}
		function down_action(event)
				{
						var evt=event;
						Hide(event);
						if ((("#" + mywindows.join("#,#") + "#").search("#"+evt.target.id+"#") != -1)||(("#" + new_id.join("#,#") + "#").search("#"+evt.target.id+"#") != -1))
						{
							BX(lastwin).style.zindex=100;
							cur=evt.target.id;
							lastwin=cur;
							d.getElementById(cur).style.zindex=10000;
							d.getElementById(cur).style.cursor="move";
							winopacity=d.getElementById(cur).style.opacity;
							d.getElementById(cur).style.opacity="0.8"
						}
						else {
						cur="";}
				}
		function up_action(event) {
						//d.all[cur].style.background=wincolor;
						if (cur!="")
						BX(cur).style.opacity="";
						BX(cur).style.cursor='default';
						cur="";}
			function move_action(event) {
						var evt=event;
						//alert(evt);
						if (cur!="")
						{
							var obj=event.target;
   var result = "";
     for (var i in obj) // обращение к свойствам объекта по индексу
       result +="." + i + " = " + obj[i] + "<br />\n";
						}
				//window.event.cancelBubble=true;
				//window.event.returnValue=false;
				}

	function defPosition(event) {
    var x = y = 0;
    if (document.attachEvent != null) { // Internet Explorer & Opera
        x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
        y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
    }
    if (!document.attachEvent && document.addEventListener) { // Gecko
	//alert(window.scrollX);
        x = event.clientX + window.scrollX;
        y = event.clientY + window.scrollY;
    }
    return {x:x, y:y};
}

// Функция инициализации движения
// Записываем всё параметры начального состояния
function initMove(div, event)
{
    var event = event || window.event;
    x0 = defPosition(event).x;
    y0 = defPosition(event).y;
	divX0 = parseInt(div.offsetLeft);
    divY0 = parseInt(div.offsetTop);
   moveState = true;
}

// Если клавишу мыши отпустили вне элемента движение должно прекратиться
document.onmouseup = function() {
    moveState = false;
}

// И последнее
// Функция обработки движения:
function moveHandler(div, event) {
    var event = event || window.event;
    if (moveState) {
//BX('para1').innerHTML=divX0+''+divY0;
        div.style.left = divX0 + defPosition(event).x - x0;
        div.style.top  = divY0 + defPosition(event).y - y0;
    }
}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////Заказы и XML///////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

function Showforedit(file,path,is_utf)
			{
			JustHide();
					var editf=createHttpRequest();

					var elem = BX("para1");
						var tb=BX("tbl");
						load.style.display="block";
						editf.open("GET", "<?=$script_name?>?mode=edit&file="+file+"&path="+path+"&isutf="+is_utf, true);
						editf.onreadystatechange = function()
							{
										if (editf.readyState == 4)
										{
										if (editf.responseText=='')
											{
												elem.innerHTML='Файл отсутстует. Произведите выгрузку из 1С.';
												tb.style.display="block";
											}
											else
											{
												elem.innerHTML=editf.responseText;
												tb.style.display="block"
											}
											load.style.display="none";
										}
							};
						editf.send(null);

			}

function SaveFile(file)
{
	var text = encodeURIComponent(BX("textfile").value);
	var sfstatus=BX("sfstatus");
	var save=createHttpRequest();
	load.style.display="block";
	sfstatus.style.display='none';
	save.open("POST", "<?=$script_name?>", true);
	save.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	save.setRequestHeader("Content-length", text.length);


	save.onreadystatechange = function()
	{
				if (save.readyState == 4)
				{
				//alert(save.responseText);
				if (save.responseText=='OK')
					sfstatus.innerHTML="<b>Изменения в файле сохранены<b>"
					//sfstatus.innerHTML=save.responseText;
					else
					sfstatus.innerHTML="<b style='color:red'>Ошибка при сохранении файла</b>";
					//sfstatus.innerHTML=save.responseText;
					sfstatus.style.display='block';
					load.style.display="none";
				}
			};
	save.send("action=save&filename="+file+"&text="+text);
	}

	function ChangeLastMoment()
	{
	var path1=BX("path1").value;
	var date=BX("date").value;
	var clastmoment=createHttpRequest();
	load.style.display="block";
	clastmoment.open("POST", "<?=$script_name?>", true);
	clastmoment.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	clastmoment.onreadystatechange = function()
	{
				if (clastmoment.readyState == 4)
				{
					alert('Теперь дата последнего обмена: '+clastmoment.responseText);
					load.style.display="none";
				}
			};
	clastmoment.send("path1="+path1+"&date="+date+"&change=Y&AJAX=Y");
}

function Delete(file,workarea)
{
var del=createHttpRequest();
menu.style.display="none";
if (confirm('Удалить '+file+'?'))
{
load.style.display="block";
del.open("POST", "<?=$script_name?>", true);
del.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
del.onreadystatechange = function()
{
			if (del.readyState == 4)
			{
			if (del.responseText!='1') alert("Ошибка удаления файла");
			GetFileList2(globalpath,workarea);
			}
		};

del.send("action=deletefile&filename="+file);
}
}

function UnZip(file,workarea)
{
JustHide();
var unzipfile=createHttpRequest();
if (confirm('Распаковать '+file+'?'))
{
menu.style.display="none";
load.style.display="block";
unzipfile.open("POST", "<?=$script_name?>", true);
unzipfile.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
unzipfile.onreadystatechange = function()
{
			if (unzipfile.readyState == 4)
			{
			if (unzipfile.responseText!='1') alert("Ошибка распаковки файла");
			GetFileList2(globalpath,workarea);
			}
		};

unzipfile.send("action=unzip&filename=<?=$_SERVER['DOCUMENT_ROOT']?>"+globalpath+file);
}
}

function ShowHideSection(elem)
		{
			var t='block';
			if(BX(elem).style.display=='block')
			{t='none';}
			BX(elem).style.display=t;
		}

//показываем файлы импорта
function ShowFile(file,path,is_utf,workid)
{
JustHide();
if (!workid)
{
var elem = BX("para1");
} else {var elem = BX(workid);}
var tb=BX("tbl");
var req=createHttpRequest();
load.style.display="block";
if (file=="import")	{file=BX("cat_file").value;}
if (file=="offers") {file=BX("off_file").value;}
req.open("GET", "<?=$script_name?>?mode=show_xml&file="+file+"&path="+path+"&isutf="+is_utf+"&target=blank", true);
req.onreadystatechange = function()
{
			if (req.readyState == 4)
			{
			if (req.responseText=='')
				{
				elem.innerHTML='Файл отсутстует или он пустой.';
				tb.style.display="block";
				}
				else
				{
				elem.innerHTML=req.responseText;
				tb.style.display="block"
				}
				load.style.display="none";
			}
		};
req.send(null);
}

//сохраняем xml заказов
function SaveMe(path)
{
var load= BX("load");
var elem = BX("para1");
var tb=BX("tbl");
var req=createHttpRequest();
var showxml=createHttpRequest();
load.style.display="block";
	showxml.open("GET", "<?=$script_name?>?mode=query&path="+path+'&save=Y', true);
	showxml.onreadystatechange = function()
		{
		if (showxml.readyState == 4 && showxml.status == 200)
			{
			if (showxml.responseText=='')
				{
				elem.innerHTML='Ошибка формирования XML'}
				else
				{elem.innerHTML=showxml.responseText;
				load.style.display="none";
				number.innerHTML=" ";
				tb.style.display="block"
				}
				load.style.display="none";
			}
		};
showxml.send(null);
}

//получаем список заказов
function GetOrders()
{

var elema = BX("PAYED");
    elemb = BX("DELIVERY");
	elemc = BX("path");
	elem = BX("list");
var r;
r='<?=$script_name?>?path='+elemc.value+'&check=Y';

if (elema.checked==true) r=r+'&PAYED=Y';
if (elemb.checked==true) r=r+'&ALLOW_DELIVERY=Y';
elem.style.display="block";
elem.innerHTML='Загрузка...<img align="center" src="http://gifanimation.ru/images/ludi/17_3.gif" width="30"/>';
elem.innerHTML='<b>Изменения в заказах</b><hr><div class="closeButton" onclick="CloseOrderList()">Х</div><div><iframe style="font-size:11;padding:3;background: white;" width="280" src="'+r+'"></iframe>';
}

//xml в отдельном окне
function _BlankXML(path)
		{
		//alert(path);
		window.open(path,'new','width=700,height=500,toolbar=1 scrollbars=yes');
		}

// закрыть список заказов
function CloseOrderList()
{
BX("list").style.display="none";
}

// неважно
function Hide(event)
{
var element;
if (!event)
{
	event = window.event;
	element=event.srcElement;
} else {element=event.target}

//document.write(result);
//alert(event);
idlink=element.id.substr(0,2);
if((idlink!="f_")&&(element.id!='e')&&((element.id!='v'))&&((element.id!='d'))&&((element.id!='u'))&&((element.id!='dw'))&&((element.id!='eutf'))&&((element.id!='vutf')))
{
menu.style.display="none";
}
}

function JustHide()
{
menu.style.display="none";
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
////////////////////////Mini fileman////////////////////
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

function CreateFile(name,pathe,workarea)
{
	var pathf=BX(pathe);
	var name=BX(name).value;
	var filelist=createHttpRequest();
	var isdir=BX('isdir').checked;
	globalpath=pathf.value;
	q="<?=$script_name?>?action=createfile&path="+pathf.value+name;
	if (workarea)
	{
		q=q+"&workarea="+workarea;
		fileman=BX(workarea);
	}
	if (isdir==true)
	q=q+"&isdir=Y";
	filelist.open("GET", q, true);
	filelist.onreadystatechange = function()
	{
		if (filelist.readyState == 4 && filelist.status == 200)
				{
					if (filelist.responseText=='error001')
						alert('Файл/папка уже существует, задайте другое имя!');
					if (filelist.responseText=='fail')
						alert('Файл/папку создать не удалось:(');
					if (filelist.responseText=='success')
						GetFileList(pathe,workarea);
					fileman.style.display='block';
					load.style.display="none";
				}

	};
	filelist.send(null);
}


function GetFileList(pathe,workarea)
{
	var fileman=BX("minifileman")
	var pathf=BX(pathe);
	var search_str=BX('search_str');
	var filelist=createHttpRequest();
	globalpath=pathf.value;
	load.style.display="block";
	if (workarea)
		fileman=BX(workarea);
	q="<?=$script_name?>?action=getfiles&path="+pathf.value+"&like_str="+search_str.value;

	if (workarea)
	q=q+"&workarea="+workarea;
	filelist.open("GET", q, true);
	filelist.onreadystatechange = function()
	{
		if (filelist.readyState == 4 && filelist.status == 200)
		{
		    fileman.innerHTML=filelist.responseText;
			fileman.style.display='block';
			load.style.display="none";
		}

	};
	filelist.send(null);
}



function GetFileList2(pathe,workarea)
{
	var fileman=BX("minifileman");
	var search_str=BX('search_str');

	var pathf=BX("path_fileman").value;
	var filelist=createHttpRequest();
	if (pathe=="")
	pathe=BX("path_fileman").value;
	globalpath=pathe;
	load.style.display="block";
	if (workarea)
	fileman=BX(workarea);

	q="<?=$script_name?>?action=getfiles&path="+pathe+"&like_str="+search_str.value;
	if (workarea)
	q=q+"&workarea="+workarea;

	filelist.open("GET",q, true);
	filelist.onreadystatechange = function()
		{
			if (filelist.readyState == 4 && filelist.status == 200)
			{
				BX("path_fileman").value=pathe;
				fileman.innerHTML=filelist.responseText;
				load.style.display="none";
			}

		};
	filelist.send(null);
}

function ShowFileMan()
{
	if(event.altKey && event.keyCode == 83)
	{
		if (document.getSelection) {
		text = document.getSelection();
} else if (document.selection && document.selection.createRange) {
    text = document.selection.createRange().text;
}
	if (text!="")
	{
		  BX('q').value=text;
		  searchbyxmlid();
	}
}
	var dis=BX('test_window').style.display;
    if(event.ctrlKey && event.keyCode == 192)
	{
      if (dis=='none'||dis=='')
	  {
			BX('test_window').style.display='block';GetFileList2('','testfileman');
	  }
	  else
	  {
			BX('test_window').style.display='none';
      }
    }
}
AddWindowRequest('<?=$script_name?>?action=addimpfilewin','custom_windows','ipfs');
</script>
