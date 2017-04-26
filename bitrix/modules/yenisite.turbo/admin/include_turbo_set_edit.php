<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?CJSCore::Init(array("jquery")); ?>
<style>
	.column-name {
		width: 30%;  
	}
	.column-value {
		width: 70%;
	}
	.errorstr {
		width: 100%;
		color: red; 
		text-align: left; 
		font-weight: bold; 
		font-size: 12px;
	}
</style>
  
<script type="text/javascript">
$(document).ready(function() {
		//getSections(<?=$set["IBLOCK_ID"]?>,'iblock_id_1');
	});

// function getSections(iblock_id, select_name){

	// template = /\d+/i;
	// found = select_name.match(template);
	// num_select = parseInt(found[0]);
	

	// $("[class*='section_id_"+num_select+"']").show();
	
	// $("[class*='section_id_"+num_select+"']").find("option[class*='yen_section']").hide();
	// $("[class*='section_id_"+num_select+"']").find(".yen_section_"+iblock_id).show();
// }



    /* FOR MUCH IBLOCK
	function newIblock(){
	
		var clone_iblock_select = $('tr.iblock_id_1').clone();
		var clone_section_select = $('tr.section_id_1').clone();
		template = /\d+/i;
		found = $("[class*='iblock_id']:last").attr("class").match(template);
		num_select = parseInt(found[0]) + 1;
				
		
		clone_iblock_select.find("select").attr('name', 'iblock_id_'+num_select);
		clone_iblock_select.removeClass().addClass('iblock_id_'+num_select);
		clone_iblock_select.insertAfter("[class*=section_id]:last");
		
		clone_section_select.find("select").attr('name', 'section_id_'+num_select);
		clone_section_select.removeClass().addClass('section_id_'+num_select);
		clone_section_select.insertAfter("[class*=iblock_id]:last");
		
		$("[class*=iblock_id]:last").before("</br>");
	}*/
	
function reload_page(iblock, active_tab){
	var id = '<?=$_REQUEST["id"]?>';
	var action = '<?=$_REQUEST["action"]?>';
	
	if(id.length>0)		id = '&id='+id;
	else				id = '';
	
	if(action.length>0)	action = '&action='+action;
	else				action = '';
	
	if(iblock.length>0)	iblock = '&select_iblock='+iblock;
	else				iblock = '';	
	//location.replace("/bitrix/admin/yci_turbo_set_edit.php?lang=ru"+id+action+iblock+active_tab);
	$('form[name=turbo_settings]').submit();
	// $.ajax({
	  	// url: "/bitrix/admin/yci_turbo_set_edit.php?lang=ru"+id+action+iblock+active_tab,
	  	// success: function(data) {
	  		// $('tr.section_id_1 td.column-value').html($(data).find('tr.section_id_1 td.column-value').html());

	  	// }	
	// });
}
</script>

<?
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.turbo");
CModule::IncludeModule("catalog");

global $USER;
if(!$USER->IsAdmin())
	return;

$id = htmlspecialcharsEx($_REQUEST["id"]);	
$action = htmlspecialcharsEx($_REQUEST["action"]);
$name = htmlspecialcharsEx($_REQUEST["name"]);
$iblock_id = htmlspecialcharsEx($_REQUEST["iblock_id_1"]);
$section_id = htmlspecialcharsEx(base64_encode(serialize($_REQUEST["section_id_1"])));
$rent = htmlspecialcharsEx($_REQUEST["rent"]);
$price_id = htmlspecialcharsEx($_REQUEST["price_id"]);
$discount = htmlspecialcharsEx($_REQUEST["discount"]);
$apply = htmlspecialcharsEx($_REQUEST["apply"]);
$save = htmlspecialcharsEx($_REQUEST["save"]);
$region = htmlspecialcharsEx($_REQUEST["region"]);
$delivery = htmlspecialcharsEx($_REQUEST["delivery"]);
$include_subsections = htmlspecialcharsEx($_REQUEST["include_subsections"]);
$turbo_prop_for_select = htmlspecialcharsEx($_REQUEST["turbo_prop_for_select"]);

$place = htmlspecialcharsEx($_REQUEST["place"]);
$status = htmlspecialcharsEx($_REQUEST["ELEM_STATUS"]);

if($action=='edit' && $id>0 && ($apply || $save))
{
    if(!isset($iblock_id) || !isset($price_id) || !isset($name) || !isset($rent) || !isset($discount)){
        LocalRedirect("/bitrix/admin/yci_turbo_set_edit.php?id={$id}&lang=ru&errorstr=Y");
    }
    CTurbineSet::Update($id, $name, $iblock_id, $section_id, $rent, $price_id, $discount, $region, $place, $delivery, $include_subsections, $turbo_prop_for_select, $status);    
    if($_REQUEST['report'] == "Y") LocalRedirect("/bitrix/admin/yci_turbo_report.php?id=".$id."&lang=ru");
}

if($action=='add' && ($apply || $save))
{   
    if(!isset($iblock_id) || !isset($price_id) || !isset($name) || !isset($rent) || !isset($discount))
        LocalRedirect("/bitrix/admin/yci_turbo_set_edit.php?lang=ru&errorstr=".GetMessage("ERROR"));
    
    CTurbineSet::Add($name, $iblock_id, $section_id, $rent, $price_id, $discount, $region, $place, $delivery, $include_subsections, $turbo_prop_for_select, $status);
	$propYan = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE" => "TURBO_YANDEX_LINK"))->Fetch();
	if(!$propYan){
		$arFields = Array(
					  "NAME" => "YANDEX LINK",
                      "ACTIVE" => "Y",
                      "SORT" => "10001",
                      "PROPERTY_TYPE" => "S",
                      "IBLOCK_ID" => $iblock_id,
                      "CODE" => "TURBO_YANDEX_LINK"
         );
         $ibp = new CIBlockProperty;
         $PropID = $ibp->Add($arFields);
	}
}

if($save) Localredirect("/bitrix/admin/yci_turbo_sets.php?lang=".LANG."");


$aMenu = array();

$aMenu[] = array(
		"TEXT" => GetMessage("LIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/yci_turbo_sets.php?lang=".LANG."",
            );


$aMenu[] = array(
		"TEXT" => GetMessage("ADD"),
		"ICON" => "btn_new",
		"LINK" => "/bitrix/admin/yci_turbo_set_edit.php?lang=".LANG."&action=add",
            );


$context = new CAdminContextMenu($aMenu);
$context->Show();


$aTabs = array(
		array("DIV" => "edit_common", "TAB" => GetMessage("SET_COMMON"), "ICON" => "catalog", "TITLE" => ""),
		array("DIV" => "edit_iblock", "TAB" => GetMessage("SET_IBLOCK"), "ICON" => "catalog", "TITLE" => ""),
	);
?>
<form name="turbo_settings" method="POST">
<?
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextTab();
?>
<?/********* START FIRST TAB *************/?>
<?if($id):?>
	<?$set = CTurbineSet::GetByID($id);?>
	<?if($_REQUEST["errorstr"]):?>
        <tr>
			<td class="errorstr" colspan=2><?=GetMessage("ERROR");?></td>
		</tr>
	<?endif?>
		<tr>
			<td class="column-name">ID:</td>
			<td class="column-value"><?echo $id;?></td>
			<input type="hidden" name="action" value="edit" />
		</tr>
<?else:?>
		<?
		$set = array(
			'NAME' => $name,
			'RENT' => $rent,
			'DISCOUNT' => $discount,
			'PRICE_ID' => $price_id,
			'REGION' => $region,
			'PLACE' => $place,
			'DELIVERY' => $delivery,
			'IBLOCK_ID' => $iblock_id,
			'SECTION_ID' => $section_id,
			'SUBSECT' => $include_subsections,
			'ELEM_STATUS' => $status,
			'SELECT_PROP' => $turbo_prop_for_select
		);
		?>
		<input type="hidden" name="action" value="add" />
<?endif?>
<tr>
	<td class="column-name"><?=GetMessage("NAME")?>:</td>
	<td class="column-value"><input type="text" value="<?=$set["NAME"]?>" name="name"/></td>
</tr>
<tr>
	<td class="column-name"><?=GetMessage("RENT")?>:</td>
	<td class="column-value"><input type="text" value="<?=$set["RENT"]?>" name="rent"/></td>
</tr>
<tr>
	<td class="column-name"><?=GetMessage("DISCOUNT")?>:</td>
	<td class="column-value"><input type="text" value="<?=$set["DISCOUNT"]?>" name="discount"/></td>
</tr>
<tr>
	<td class="column-name"><?=GetMessage("PRICE_ID")?>:</td>
	<td class="column-value">
	<select name="price_id">
		<option value="">...</option>
		<?
		$res = CCatalogGroup::GetList(array("ID" => "asc"), array());
		while($p = $res->GetNext()):
			if($p["ID"] == $set["PRICE_ID"])
				$sel = "selected='selected'";
			else $sel="";?>		
			<option <?=$sel?> type="text" value="<?=$p["ID"]?>" ><?=$p["NAME"]?></option>
		<?endwhile;?>			
	</select>
</tr>		
<tr>
	<td class="column-name"><?=GetMessage("REGION")?>:</td>
	<td class="column-value">
		<?
		global $MESS;
		$regions = GetMessage("REGIONS");
		asort($regions);
		?>			
		<select name="region">
			<?foreach($regions as$k=> $reg):?>
				<?if($set["REGION"] == $k) $selected = "selected='selected'"; else $selected = "";?>
					<option <?=$selected?> value="<?=$k?>"><?=$reg?></option>
			<?endforeach;?>
		</select>
		<!--<input type="text" value="<?=$set["REGION"]?>" name="region"/>-->
	</td>
</tr>		
<tr>
	<td class="column-name"><?=GetMessage("PLACE")?>:</td>
	<td class="column-value">
		<select name="place">									
			<option <?=$set["PLACE"]==1?"selected='selected'":"";?> value="1">1</option>
			<option <?=$set["PLACE"]==2?"selected='selected'":"";?> value="2">2</option>
			<option <?=$set["PLACE"]==3?"selected='selected'":"";?> value="3">3</option>				
		</select>			
</tr>
<tr>
	<td class="column-name"><?=GetMessage("DELIVERY")?>:</td>
	<td class="column-value"><input type="checkbox" value="Y" <?=($set["DELIVERY"]=='Y')?"checked='checked'":"";?> name="delivery"/></td>
</tr>
		
<?/********* END FIRST TAB *************/?>
<?/********* START SECOND TAB *************/?>
<?$tabControl->BeginNextTab();?>
<tr class="iblock_id_1">
	<td class="column-name"><?=GetMessage("IBLOCK_ID")?>:</td>
	<td class="column-value">		
		<select name="iblock_id_1" onchange="reload_page(this.options[this.selectedIndex].value,'&tabControl_active_tab=edit_iblock')">
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
			$selected_iblock = $_REQUEST["iblock_id_1"]?$_REQUEST["iblock_id_1"]:$set["IBLOCK_ID"];
			print_r($selected_iblock);
			foreach($ibs as $k=>$ib):
				if($k == $selected_iblock)
					$sel = "selected='selected'";
				else 
					$sel="";  
				
				?><option class="yen_iblock_<?=$k?>"  <?=$sel?> type="text" value="<?=$k?>" ><?=$ib?></option>
			<?endforeach;?>                
		</select>
	</td>
</tr>
<tr class="section_id_1" >
	<td class="column-name"><?=GetMessage("SECTION_ID")?>:</td>
	<td class="column-value">			
		<select name="section_id_1[]" multiple size="10">
			<option value="">...</option>
			<?
			$arFilter = array();
			$arFilter['ACTIVE'] = 'Y';
			$arFilter['IBLOCK_ID'] = $selected_iblock;
			
			$res = CIBlockSection::GetList(Array("IBLOCK_ID" => "asc"), $arFilter);
			$secs = array();
			while($ar_res = $res->Fetch())
			{
				$resib = CIBlock::GetByID($ar_res["IBLOCK_ID"])->Fetch();
				$db_iblock_type = CIBlockType::GetByIDLang($ar_res["IBLOCK_TYPE_ID"], "ru");
				$secs[$ar_res["ID"]]["NAME"] = $ar_res["NAME"];
				//$secs[$ar_res["ID"]]["NAME"] = $ar_res["NAME"];
				$secs[$ar_res["ID"]]["IBID"] = $ar_res["IBLOCK_ID"];
			}
			$arSections = unserialize(base64_decode(trim($set["SECTION_ID"])));
			foreach($secs as $k=>$sec):
				if(in_array ($k, $arSections))
					$sel = "selected='selected'";
				else $sel="";
				?>
				<option class='yen_section_<?=$sec["IBID"]?>' <?=$sel?> type="text" value="<?=$k?>" ><?=$sec["NAME"]?></option>
			<?endforeach;?> 
		</select>
	</td>
</tr>
<tr>
	<td class="column-name"><?=GetMessage("INCLUDE_SUBSECTIONS")?>:</td>
	<td class="column-value"><input type="checkbox" value="Y" <?=($set["SUBSECT"]=='Y')?"checked='checked'":"";?> name="include_subsections"/></td>
</tr>
<tr>
	<td class="column-name"><?=GetMessage("TURBO_ELEM_STATUS")?>:</td>
	<td class="column-value">
		<select name="ELEM_STATUS">									
			<option <?=$set["ELEM_STATUS"]==0?"selected='selected'":"";?> value="0"><?=GetMessage("TURBO_ELEM_ALL")?></option>
			<option <?=$set["ELEM_STATUS"]==1?"selected='selected'":"";?> value="1"><?=GetMessage("TURBO_ELEM_ACTIVE")?></option>
			<option <?=$set["ELEM_STATUS"]==2?"selected='selected'":"";?> value="2"><?=GetMessage("TURBO_ELEM_AVAILABLE")?></option>
			<option <?=$set["ELEM_STATUS"]==3?"selected='selected'":"";?> value="3"><?=GetMessage("TURBO_ELEM_ACTIVE_AVAILABLE")?></option>				
		</select>			
</tr>
<tr>
	<td class="column-name"><?=GetMessage('TURBO_PROP_FOR_SELECT')?><span class="required"><sup><?echo '1'?></sup></span>: </td>
	<td class="column-value" >
		<select name="turbo_prop_for_select">
			<option value="">...</option>
				<?
				$ibs = array();
				$arFilter = array();
				$arFilter['ACTIVE'] = 'Y';
				$arFilter['IBLOCK_ID'] = $selected_iblock;
				$arFilter['PROPERTY_TYPE'] = 'L';
				
				$res = CIBlockProperty::GetList( Array("NAME" => "asc"), $arFilter);
				while($ar_res = $res->Fetch())
				{
					$ibs[$ar_res["ID"]] = $ar_res["NAME"];
					asort($ibs);
				}
				
				foreach($ibs as $k=>$ib):
					if($k == $set['SELECT_PROP'])
						$sel = "selected='selected'";
					else 
						$sel="";  
					
					?><option <?=$sel?> type="text" value="<?=$k?>"><?=$ib?></option>
				<?endforeach;?>
		</select>
	</td>
</tr>
<!--FOR MUCH IBLOCK
<tr><td>
<a href="javascript:void(0);" class="adm-btn adm-btn-add" id="new_iblock" onclick="newIblock()"><?=GetMessage("ADD_IBLOCK")?></a>
</td></tr>
-->
<?/********* END SECOND TAB *************/?>
<?
$tabControl->Buttons(
	array(
		"back_url" => "/bitrix/admin/yci_turbo_sets.php?lang=".LANG.""
	)
);
$tabControl->End();
?>
</form>
<?echo BeginNote();?>
<span class="required"><sup><?echo '1'?></sup></span><?=GetMessage('TURBO_PROP_FOR_SELECT_TOOLTIP')?><br>
<?echo EndNote();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>

