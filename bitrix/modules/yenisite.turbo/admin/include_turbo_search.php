<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php";
$MODULE_ID = "yenisite.turbo";
CModule::IncludeModule($MODULE_ID);

/* ----  for AJAX -----------*/
if(isset($_REQUEST['get_link']) && $_REQUEST['get_link'] == 'Y' && isset($_REQUEST['element_name']))
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.turbo/classes/general/yenisite_turbo.php");
	$arReturn = array();
	$arReturn = CTurbine::getYandexLink($_REQUEST['element_name'],$_REQUEST['element_id'],$_SERVER['DOCUMENT_ROOT']."/yandex_cook.txt");
	echo json_encode($arReturn) ;
	die();
}
/* ---------------------------*/?>
<?
global $USER;
if(!$USER->IsAdmin())
	return;
if(!check_bitrix_sessid())
	 return;
	
IncludeModuleLangFile(__FILE__);
$property = "TURBO_YANDEX_LINK";
CModule::IncludeModule("iblock");
$APPLICATION->SetTitle(GetMessage('PAGE_TITLE'));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<style>
.turbo-result-table, .turbo-search-params{
	border: 1px solid #cccccc;
	padding: 10px;
	margin-bottom: 20px;
	margin-top: 10px;
	font-size: 12px !important; 
	height: 100%;
}
.row	{ padding-top: 15px;  clear: both; border-top: dotted 1px #ccc;}

.colRight	{ width: 70%;  float: right; }
.colRight p	{ margin: 0px;	padding: 0px;}
  
.colLeft{ float: left; vertical-align:bottom;font-size: 12px !important; }

.top	{font-weight: bold;} 
.bottom {clear: both;}
.bottom input {margin-top: 15px;}
</style>
<?CJSCore::Init(array("jquery", "ys_core_parser")); ?>

<script type="text/javascript">
timeout_min = <?=1000*COption::GetOptionString($MODULE_ID, 'turbo_interval_min', '10');?>;
timeout_max = <?=1000*COption::GetOptionString($MODULE_ID, 'turbo_interval_max', '20');?>;
ys_readLog.module = 'yenisite.turbo';

function reload_page(iblock)
{
	location.replace("/bitrix/admin/yci_turbo_search.php?<?=bitrix_sessid_get()?>&iblock="+iblock+"&lang=ru");
}

function yenisite_start(ar_id,iblock_type,iblock)
{
	if(ar_id=== null || ar_id.length < 1)
		$(".turbo-notice-all").html($(".turbo-notice-all").html()+"<?=GetMessage("NO_ELEMENTS")?>");
	else
	{
		$(".turbo-result-table").show();
		el = -1;
		iblock_type_id = iblock_type;
		iblock_id = iblock;
		ar_element = [];
		ar_element = ar_id;
		//console.log(ar_element);
		yenisite_next_element();
	}
}

function yenisite_next_element()
{
	el++;
	if(el>=ar_element['ID'].length)
	{
		$("#save").removeAttr('disabled');
		$("#next_page").removeAttr('disabled');
		$("#yen-progress").hide();
	}
	else
	{
		set_progress(ar_element['ID'].length, el+1);
		yenisite_parse_links(ar_element['NAME'][el]);
	}
}

function yenisite_parse_links(name)
{
	ys_curId = ar_element['ID'][el];
	ys_cp.ajax({
		url: "/bitrix/admin/yci_turbo_search.php",
		data: {
			element_name: name,
			element_id: ys_curId,
			get_link: 'Y',
			lang: 'ru'
		},
		success: function(data){
				
			html_input = 
			"<div class='row'>"+
				"<div class='colLeft'><a href='/bitrix/admin/iblock_element_edit.php?ID="+ar_element['ID'][el]+"&type="+iblock_type_id+"&lang=ru&IBLOCK_ID="+iblock_id+"&find_section_section=-1' target='_blank'>"+ar_element['NAME'][el]+"</a></div>"+
				"<div class='colRight'>";
			if (data.length > 0) {
				for(var i = 0; i < data.length; i++) 
				{				
					html_input += "<p><input type='radio' name="+ar_element['ID'][el]+" value='"+data[i]['ID']+"'> "+
					"<a href='http://market.yandex.ru/product/"+data[i]['ID']+"' target='_blank'>"+data[i]['NAME']+"</a>	</p>";
				}
				html_input += "<p><input type='radio' name="+ar_element['ID'][el]+" value=''><?=GetMessage("NOT_LINK_ELEMENT")?> </p>";
			}
			else {
				html_input += "<p><?=GetMessage("NOT_FOUND")?></p>";
			}
			html_input += 
				"</div>"+
			"</div>";
			
			$(".turbo-result-table  form .body").html($(".turbo-result-table  form .body").html()+html_input);
		},
		complete: function(){
			var timeout = rand(timeout_min,timeout_max);
			setTimeout(yenisite_next_element,timeout);
		}
	});
}

// Generate a random integer
if (typeof window.rand !== "function") {
	function rand( min, max ){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}

function set_progress(cnt,i)
{
	$("#yen-progress").show();
	iPercent = Math.round((i/cnt)*100);
	if(iPercent>=100)
		iPercent = 99;
	document.getElementById('percent').innerHTML = iPercent + '%';
	document.getElementById('indicator').style.width = iPercent + '%';
}

$(document).ready(function(){
	ys_addLogHtml();
});

</script>
<?
//	FILTER ON PRODUCTS	----------------------------------
$arFilter = array();

$arFilter['IBLOCK_ID'] = $_REQUEST['iblock'];
if($_REQUEST['section'])
{
	$arFilter['SECTION_ID'] = $_REQUEST['section'];
	$arFilter['INCLUDE_SUBSECTIONS'] = "Y";
	$arFilter['SECTION_GLOBAL_ACTIVE'] = "Y";
}
$arFilter['ACTIVE'] = "Y";
$arFilter["PROPERTY_".$property] = false;
//	  ----------------------------------------------------
$arSelect = array('ID','NAME');
//	  ----------------------------------------------------
$arSort = array("ID" => "desc");
//	  ----------------------------------------------------
$arNavStartParams = array();
$arNavStartParams["nPageSize"] = 20;
$arNavStartParams["iNumPage"] = 1;
//	  ----------------------------------------------------

if($_REQUEST['save_yandex_link'] || $_REQUEST['next_page'])
{
	$dbResultList = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
	while($prof = $dbResultList->GetNext())
	{
		if(isset($_REQUEST[$prof['ID']]) && !empty($_REQUEST[$prof['ID']]) && is_numeric($_REQUEST[$prof['ID']]))
		{
			CIBlockElement::SetPropertyValueCode($prof['ID'], $property, $_REQUEST[$prof['ID']]);
		}
	}
}

if($_REQUEST['next_page'] && isset($_REQUEST['last_element']) && !empty($_REQUEST['last_element']) && is_numeric($_REQUEST['last_element']))
	$arFilter['<ID'] = $_REQUEST['last_element'];
	
if($_REQUEST['get_link']!= 'Y' && ($_REQUEST['start'] || $_REQUEST['next_page']) && $_REQUEST['iblock'] && !$_REQUEST['save_yandex_link'])
{
	$dbResultList = CIBlockElement::GetList($arSort, $arFilter, false, $arNavStartParams, $arSelect);
	while($prof = $dbResultList->GetNext())
	{
		$arElements['ID'][]=$prof['ID'];
		if(LANG_CHARSET == "windows-1251")
			$prof['NAME'] = iconv("windows-1251", "utf-8", $prof['NAME']);  
		$arElements['NAME'][]=$prof['NAME'];
	}
	$last_element = $arElements['ID'][count($arElements['ID'])-1];
	$arFilter['<ID'] = $last_element;
	$dbResultList = CIBlockElement::GetList($arSort, $arFilter, false, $arNavStartParams, $arSelect);
	if(!$prof = $dbResultList->GetNext())
		$not_next_page = 'style="display:none;"';
	$res = CIBlock::GetByID($arFilter['IBLOCK_ID']);
	if($ar_res = $res->GetNext())
	{
		$arElements = json_encode($arElements);
		?><script>setTimeout(yenisite_start,1000,<?=$arElements?>,'<?=$ar_res['IBLOCK_TYPE_ID']?>',<?=$ar_res['ID']?>);</script><?
	}
}?>
<!--start SEARCH PARAMS-->
<div class='turbo-search-params'>
	<form method="POST">
	<?=bitrix_sessid_post();?>
		<table>
			<tr class="iblock_id">
				<td class="colLeft"><?=GetMessage("IBLOCK_ID")?>:</td>
				<td class="colRight">		
					<select name="iblock" onchange="reload_page(this.options[this.selectedIndex].value)">
						<option value="">...</option>
						<?
						$ibs = array();
						$res = CIBlock::GetList( Array("NAME" => "asc"), Array('ACTIVE'=>'Y'));
						while($ar_res = $res->Fetch())
						{
							$db_iblock_type = CIBlockType::GetByIDLang($ar_res["IBLOCK_TYPE_ID"], "ru");
							$ibs[$ar_res["ID"]] = $db_iblock_type["NAME"]." \\ ".$ar_res["NAME"];
							asort($ibs);
						}
						
						foreach($ibs as $k=>$ib):
							if($k == $_REQUEST['iblock'])
								$sel = "selected='selected'";
							else 
								$sel="";  
							
							?><option <?=$sel?> type="text" value="<?=$k?>" /><?=$ib?></option>
						<?endforeach;?>                
					</select>
				</td>
			</tr>
			<?if($_REQUEST['iblock']):?>
			<tr class="section_id" >
				<td class="colLeft"><?=GetMessage("SECTION_ID")?>:</td>
				<td class="colRight">			
					<select name="section" >
						<option value="">...</option>
						<?
						$arFilter = array();
						$arFilter['ACTIVE'] = 'Y';
						$arFilter['IBLOCK_ID'] = $_REQUEST['iblock'];
						
						$res = CIBlockSection::GetList(Array("IBLOCK_ID" => "asc"), $arFilter);
						$secs = array();
						while($ar_res = $res->Fetch())
						{
							$secs[$ar_res["ID"]]["NAME"] = $ar_res["NAME"];
							$secs[$ar_res["ID"]]["IBID"] = $ar_res["IBLOCK_ID"];
						}
						
						foreach($secs as $k=>$sec):
							if($k == $_REQUEST['section'])
								$sel = "selected='selected'";
							else $sel="";
							?>
							<option <?=$sel?> type="text" value="<?=$k?>" /><?=$sec["NAME"]?></option>
						<?endforeach;?> 
					</select>
				</td>
			</tr>
			<?endif;?>
		</table>
		<div class='bottom'>
			<input id='start' type="submit" name="start" value="<?=GetMessage('START')?>">
		</div>
	</form>
</div>
<!--end SEARCH PARAMS-->
<!--start Progress_bar-->
<div id="yen-progress" style="display:none;" width="100%">
	<br />
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<td height="10">
				<div style="border:1px solid #B9CBDF">
					<div id="indicator" style="height:10px; width:0%; background-color:#B9CBDF"></div>
				</div>
			</td>
			<td width=30>&nbsp;<span id="percent">0%</span></td>
		</tr>
	</table>
</div>
<!--end Progress_bar-->	
<!--start TABLE WITH SEARCH RESULTS-->
<div class = 'turbo-notice-all'></div>
<div class = 'turbo-result-table'  style=' display: none'>
	<form method="POST">
	<?=bitrix_sessid_post();?>
	<input type="hidden" name='iblock' value='<?=$_REQUEST['iblock']?>'/>
	<input type="hidden" name='section' value='<?=$_REQUEST['section']?>'/>
	<input type='hidden' name='last_element' value='<?=$last_element?>'/>
		<div class='top'>
			<div class='colLeft' style=' border-top: 0px'><?=GetMessage('NAME')?></div>		
			<div class='colRight' ><?=GetMessage('YANDEX_LINK')?></div>
		</div>
		<div class='body'> </div>
		<div class='bottom'>
			<input id='save' type='submit' name='save_yandex_link' value='<?=GetMessage('SAVE')?>' disabled>
			<input id='next_page' type='submit' <?=$not_next_page?> name='next_page' value='<?=GetMessage('NEXT_PAGE')?>' disabled>
		</div>
	</form>	
</div>
<!--end TABLE-->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>