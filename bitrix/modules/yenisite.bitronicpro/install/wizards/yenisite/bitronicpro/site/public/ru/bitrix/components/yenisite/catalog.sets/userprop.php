<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$lang_path = str_replace ('\\', '/', __FILE__);
$lang_path = str_replace ('userprop.php', 'lang/ru/userprop.php', __FILE__);
include_once ($lang_path);
global $jq_include ;
global $select_group ;	
class UserCompleteSet extends CUserTypeString
{
	function GetUserTypeDescription()
	{
		global $MESS ;
		return array(
			'USER_TYPE_ID' => 'completeset',
			'CLASS_NAME' => 'UserCompleteSet',
			'DESCRIPTION' => $MESS['COMPLETE_SET_DESC'],
			'BASE_TYPE'	=> 'string',
		);
	}
	// инициализация пользовательского свойства для инфоблока
	function GetIBlockPropertyDescription()
	{
		global $MESS ;
		return array(
			"PROPERTY_TYPE" => "S",
			"USER_TYPE" => "UserCompleteSet",
			"DESCRIPTION" => $MESS['COMPLETE_SET_DESC'],
			'GetPropertyFieldHtml' => array('UserCompleteSet', 'GetPropertyFieldHtml'),
			'GetAdminListViewHTML' => array('UserCompleteSet', 'GetAdminListViewHTML'),
		);
	}
	// представление свойства
	function getViewHTML($name, $value)
	{
		if(strlen($value['VALUE']) > 0)
		{
			$arValue = json_decode($value['VALUE'], true) ;
			return ($arValue['element_id']);
		}
		else
			return '';
	}
	
	 // редактирование свойства
	function getEditHTML($name, $value, $is_ajax = false)
	{
		global $MESS ;
		global $arGroups ;
		global $select_group ;
		$dom_id  = $name ;
		$transform = Array ( '['=>'\\\\[', ']'=>'\\\\]') ;
		$jq_dom_id = strtr ($dom_id, $transform) ;
		if(!$select_group)
		{
			$select_group = '<select id="#ID_SELECT#"  onchange="save_value(\'#JQ_DOM_ID#\')"><option value="">'.$MESS['COMPLETE_SET_CHANGE'].'</option>' ;
			$dbGroups = CIBlockElement::GetList(Array('SORT'=>'ASC'), Array('IBLOCK_CODE'=>'yenisite_set_groups'), false, false, Array('ID', 'IBLOCK_ID', 'NAME')) ;
			while($arGroup = $dbGroups->Fetch())
			{
				$select_group .= '<option value="'.$arGroup['ID'].'">'.$arGroup['NAME'].'</option>';
			}
			$select_group .= '</select>';
		}
		//echo 'name='.$name.'<br/>value='.$value.'<br/>is_ajax='.$is_ajax.'<br/>' ;
		
		$arValue = json_decode($value, true) ;
		$cur_select_group = str_replace('#ID_SELECT#', "{$dom_id}[GROUP_ID]", $select_group);
		$cur_select_group = str_replace('#JQ_DOM_ID#', "{$jq_dom_id}", $cur_select_group);
		$cur_select_group = str_replace('value="'.$arValue['group_id'].'"', 'value="'.$arValue['group_id'].'" selected', $cur_select_group);
		//echo 'arValue = <pre>'; print_r($arValue); echo '</pre>';
		$init = $is_ajax ? 'init();' : '$(init);';
		$buy_checked = $arValue['buy_checked'] ? ' checked' : '';
		$hide_link = $arValue['hide_link'] ? ' checked' : '';
		//echo 'buy_checked = '.$buy_checked.' -- '.$arValue['buy_checked'].'<br/>';
		//$result_html = '<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>';
		global $jq_include ;
		$result_html = '';
		if(!$jq_include)
		{
			global $APPLICATION ;
			/* $result_html = */// $APPLICATION->AddHeadString('<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>');
			CJSCore::Init('jquery');
			/* $result_html = */ $APPLICATION->AddHeadString('<style type="text/css">
								.head_complete, .row_complete { overflow:hidden; border-bottom:1px solid #e0e8ea; clear:both; }
								.head_complete, .row_complete { margin:3px 0px; }
								.head_complete div, .row_complete div { height:100%; text-align:center; margin:2px 2px; }
								.element_col {float:left;}
								.buy_col {float:left;}
								.hide_col {float:left;}
								.group_col {float:left; }
								.group_col select { width: 150px ; }
							</style>');
			$jq_include = true ;
			$APPLICATION->AddHeadString('<script>function save_value(el_id){
				el_id = el_id.replace(/(\d)]/,"$1\\\\]") ;
				console.log(el_id);
				var obResult = {
					"element_id":$("#"+el_id+"\\\\[ELEMENT_ID\\\\]").val(),
					"iblock_id":$("#"+el_id+"\\\\[IBLOCK_ID\\\\]").val(),
					"buy_checked":$("#"+el_id+"\\\\[BUY_CHECKED\\\\]").prop("checked"),
					"hide_link":$("#"+el_id+"\\\\[HIDE_LINK\\\\]").prop("checked"),
					"group_id":$("#"+el_id+"\\\\[GROUP_ID\\\\]").val()
				};
				var strResult = JSON.stringify(obResult);
				console.log(strResult);
				$("#"+el_id).val(strResult);
			}</script>
			
			<script>function save_value_el(el_id){
				el_id = el_id.replace(/(\d)]/,"$1\\\\]") ;
				var element_id = $("#"+el_id+"\\\\[ELEMENT_ID\\\\]").val();
				$.post("/bitrix/components/yenisite/catalog.sets/tools.php", {sessid:BX.message["bitrix_sessid"], el_id:element_id},
				function(data) {
					data = $.trim(data) ;
					
					$("#"+el_id+"\\\\[IBLOCK_ID\\\\]").val(data) ;
					var obResult = {
						"element_id":$("#"+el_id+"\\\\[ELEMENT_ID\\\\]").val(),
						"iblock_id":$("#"+el_id+"\\\\[IBLOCK_ID\\\\]").val(),
						"buy_checked":$("#"+el_id+"\\\\[BUY_CHECKED\\\\]").prop("checked"),
						"hide_link":$("#"+el_id+"\\\\[HIDE_LINK\\\\]").prop("checked"),
						"group_id":$("#"+el_id+"\\\\[GROUP_ID\\\\]").val()
					};
					var strResult = "";
					if(Number(obResult["element_id"]) > 0)
						strResult = JSON.stringify(obResult);
					console.log(strResult);
					$("#"+el_id).val(strResult);
				});
			}</script>');
			
			$result_html .= '<div class="head_complete"><div class="element_col">'.$MESS['COMPLETE_SET_COL_ELEMENT'].'</div><div class="buy_col">'.$MESS['COMPLETE_SET_COL_BUY'].'</div><div class="hide_col">'.$MESS['COMPLETE_SET_COL_HIDE'].'</div><div class="group_col">'.$MESS['COMPLETE_SET_COL_GROUP'].'</div></div>';
		}
				$result_html .=
				'
				<div class="row_complete">
					<div class="element_col">
						<input type="hidden" name="'.$name.'" id="'.$dom_id.'" value="'.htmlspecialchars($value).'"/>
						<input type="text" class="element_id" id="'.$dom_id.'[ELEMENT_ID]" value="'.IntVal($arValue['element_id']).'" size="5" onchange="save_value_el(\''.$jq_dom_id.'\')"/>
						<input type="hidden" id="'.$dom_id.'[IBLOCK_ID]" value="'.$arValue['iblock_id'].'" onchange="save_value(\''.$jq_dom_id.'\')"/>
						<input type="button" value="..." onclick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang=ru&amp;IBLOCK_ID=0&amp;n='.$dom_id.'[ELEMENT_ID]&amp;k=n0\', 600, 500);"/>
					</div>
					<div class="buy_col"><input type="checkbox" id="'.$dom_id.'[BUY_CHECKED]"'.$buy_checked. ' onchange="save_value(\''.$jq_dom_id.'\')"/></div>
					<div class="hide_col"><input type="checkbox" id="'.$dom_id.'[HIDE_LINK]"'.$hide_link.'onchange="save_value(\''.$jq_dom_id.'\')"/></div>
					<div class="group_col">'.$cur_select_group.'</div>
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						var max_width = 0 ;
						$(".element_col").each(function(){ if($(this).width() > max_width) max_width = $(this).width() ;}); 
						$(".element_col").css("width", max_width);
						
						max_width = 0 ;
						$(".buy_col").each(function(){ if($(this).width() > max_width) max_width = $(this).width() ;});
						$(".buy_col").css("width", max_width);
						
						max_width = 0 ;
						$(".hide_col").each(function(){ if($(this).width() > max_width) max_width = $(this).width() ;});
						$(".hide_col").css("width", max_width);
						
						max_width = 0 ;
						$(".group_col").each(function(){ if($(this).width() > max_width) max_width = $(this).width() ;});
						$(".group_col").css("width", max_width);
						});
				</script>';
		return $result_html ;
	}
	
	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
 		return $strHTMLControlName['MODE'] == 'FORM_FILL'
			? self::getEditHTML($strHTMLControlName['VALUE'], $value['VALUE'], false)
			: self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE'])
		;
	}
	function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
	{
		if(strlen($value['VALUE']) > 0)
		{
			$arValue = json_decode($value['VALUE'], true) ;
			return ($arValue['element_id']);
		}
		else
			return '';
	}
	// редактирование свойства в списке (главный модуль)
	function GetAdminListEditHTML($arUserField, $arHtmlControl)
	{
		global $MESS ;
		return array(
			'USER_TYPE_ID' => 'completeset',
			'CLASS_NAME' => 'UserCompleteSet',
			'DESCRIPTION' => $MESS['COMPLETE_SET_DESC'],
			'BASE_TYPE'	=> 'string',
		);
	}
}

// добавляем тип для инфоблока
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("UserCompleteSet", "GetIBlockPropertyDescription"));
// добавляем тип для главного модуля
AddEventHandler("main", "OnUserTypeBuildList", array("UserCompleteSet", "GetUserTypeDescription"));
?>