<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
$MODULE_ID = "yenisite.turbo";
$property = "TURBO_YANDEX_LINK";
CModule::IncludeModule($MODULE_ID);
?>
<?if($_REQUEST['set_price'] == "Y" && $_REQUEST['element_id'] > 0 && $_REQUEST['id'] > 0):?>
<?
CModule::IncludeModule("yenisite.turbo");
$arResult = CTurbine::setElementPrices($_REQUEST['element_id'], $property, $_REQUEST['id'], $_SERVER['DOCUMENT_ROOT']."/yandex_cook.txt");
echo json_encode($arResult);
die();
?>
<?endif?>
<?
require_once $DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php";
CJSCore::Init(array("jquery", "ys_core_parser"));
?>
<script type="text/javascript">
ys_readLog.module = 'yenisite.turbo';

// Generate a random integer
if (typeof window.rand !== "function") {
	function rand( min, max ){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}

function start_parsing(elements, cnt, page, i, region, set_id)
{
	if (elements != null) {
		start_parsing.elements = elements;
	} else {
		elements = start_parsing.elements;
	}
	if (i < elements.length) {
		var t = rand(timeout_min, timeout_max);
		setTimeout(function(){
			parse_element(elements[i], cnt, page, i+1, t, region, set_id);
		}, t);
		return;
	}
	if (cnt > page * nPageSize) {
		reload(page+1, set_id);
	} else {
		reload_report(set_id);
	}
}

function parse_element(id, cnt, page, i, t, region, set_id)
{
	ys_curId = id;
	ys_cp.ajax({
		url: "/bitrix/admin/yci_turbo_set_start.php",
		data: {
			element_id: id,
			set_price: 'Y',
			lang: 'ru',
			region: region,
			id: set_id
		},
		success: function(){
				 //$(".yen-notice").html($(".yen-notice").html()+"<?=GetMessage('NOTICE')?> "+id+"<br>");
		},
		complete: function(){
			start_parsing(null, cnt, page, i, region, set_id);
		}
	});

	t = t/1000;
	set_progress(cnt,page-1,i);

	$(".yen-notice").html($(".yen-notice").html()+"<?=GetMessage('NOTICE')?> "+id+" ("+t+" <?=GetMessage('SEC')?>)<br>");
	$(".yen-notice-all").html("<?=GetMessage('ALL')?> "+ (page*10 - 10 + i) +" <?=GetMessage('IZ')?> "+cnt);
}

function reload(page, id)
{
	location.replace("/bitrix/admin/yci_turbo_set_start.php?id="+id+"&PAGE="+page+"&lang=ru");
}

function reload_report(id)
{
	location.replace("/bitrix/admin/yci_turbo_report.php?id="+id+"&lang=ru");
}

function set_progress(cnt,page,i)
{
	iPercent = Math.round(((page*10 + i)/cnt)*100);
	document.getElementById('percent').innerHTML = iPercent + '%';
	document.getElementById('indicator').style.width = iPercent + '%';
}

//setInterval(start_parsing, 5000);  

$(document).ready(function(){
	ys_addLogHtml();
});

</script>

<?

$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("PARSING"), "ICON" => "catalog", "TITLE" => "")
	);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->BeginNextTab();

$nPageSize = 10;

?>
<div class = "yen-notice-loading"><?=GetMessage('NO_CLOSE')?></div>
<div class = "yen-notice-all"></div>

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

<div class = "yen-notice"></div>

<?

if($_REQUEST[id] > 0 && !$_REQUEST[element_id] > 0):

?>

<div>



	<?
		CModule::IncludeModule('iblock');
		$set = CTurbineSet::GetByID(htmlspecialcharsEx($_REQUEST["id"]));
		/*	FILTER ON PRODUCTS	*/
		$arrFilter = CTurbine::getElementFilterForSet($set);

		$res = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $set['IBLOCK_ID'], "CODE" => $property))->GetNext();
		if (!$res) {
			$arFields = Array(
				"NAME" => "YANDEX LINK",
				"ACTIVE" => "Y",
				"SORT" => "111111",
				"PROPERTY_TYPE" => "S",
				"IBLOCK_ID" => $set['IBLOCK_ID'],
				"CODE" => $property
			);
			$ibp = new CIBlockProperty;
			$PropID = $ibp->Add($arFields);
		}


		$page = $_REQUEST['PAGE']?htmlspecialcharsEx($_REQUEST['PAGE']):1;        
		
		
		$cnt = CIBlockElement::GetList(array("ID" => "asc"), $arrFilter, array());
	 
		//print_r($arrFilter);
		//die("---");
		
		$res = CIBlockElement::GetList(array("ID" => "asc"), $arrFilter, false, array("iNumPage" => $page, "nPageSize" => $nPageSize), array("ID", "PROPERTY_".$property));

	?><script>set_progress(<?=$cnt?>, <?=$page?>-1, 0)</script><?
		$arElements = array();
		while ($el = $res->Fetch()) {
			$arElements[] = $el['ID'];
		}
	?>
	<script>
	nPageSize = <?=$nPageSize?>;
	timeout_min = <?=1000*COption::GetOptionString($MODULE_ID, 'turbo_interval_min', '10')?>;
	timeout_max = <?=1000*COption::GetOptionString($MODULE_ID, 'turbo_interval_max', '20')?>;
	start_parsing([<?=implode(', ', $arElements)?>], <?=$cnt?>, <?=$page?>, 0, <?=$set['REGION']?>, <?=$_REQUEST['id']?>);
	</script>
</div>


<?endif?>

<?
$tabControl->End();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
