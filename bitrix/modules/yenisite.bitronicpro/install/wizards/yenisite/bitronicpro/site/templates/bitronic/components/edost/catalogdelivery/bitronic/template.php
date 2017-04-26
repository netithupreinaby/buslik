<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?$APPLICATION->AddHeadScript($templateFolder."/js/jquery.ajaxQueue.js");?>

<script type="text/javascript">
var edost_goods_id = 0;
var edost_start = true;
var edost_catalog_location = '';
var edost_location_old = '';

function edost_GetCookie(name) {
	var r = document.cookie.match ( '(^|;) ?' + name + '=([^;]*)(;|$)' );
	if (r) return (unescape(r[2]));
	else return '';
}

function edost_SetCatalogLocation(n) {
//	alert(n + ' | ' + edost_catalog_location[0] + ' - ' + edost_catalog_location[1]);

//    return false; // !!!!!

	if (n != '') edost_catalog_location = n.split("|");
	if (edost_catalog_location[0] == undefined || edost_catalog_location[0] == '' || edost_catalog_location[1] == undefined || edost_catalog_location[1] == '') return false;

<? if ($arResult["EDOST_LOCATIONS"] == 'Y') { ?>
	edost_SetLocation(edost_GetLocation(edost_catalog_location[0]), true, 5);
<? }
else { ?>
	var selEl = $("select[name='edost_country_sel'] option[value='" + edost_catalog_location[1] + "']");
	if (selEl.val() == edost_catalog_location[1]) selEl.attr("selected", "selected");

	var E = document.getElementById("edost_city_sel");
	if (E) {
		var selEl = $("select[name='edost_city_sel'] option[value='" + edost_catalog_location[0] + "']");
		if ( selEl.val() == edost_catalog_location[0] ) {
			selEl.attr("selected", "selected");
			edost_Calc();
		}
	}
	else edost_LoadCities();
<? } ?>

	return true;

}

function edost_catalogdelivery_show(cur_goods_id, cur_goods_name) {

	
	$( '#edost_catalogdelivery' ).dialog({
		modal: true,
		height: <?=$arResult["FRAME_Y"]?>,
		width: <?=$arResult["FRAME_X"]?>,
		autoOpen: false,
		<? if ($arResult["SHOW_BUTTON"] == 'Y') { ?>
		buttons: {
			"<?echo GetMessage("EDOST_CATALOG_DELIVERY_BUTTON_RECALC")?>": function() {
				edost_Calc();
			},
			"<?echo GetMessage("EDOST_CATALOG_DELIVERY_BUTTON_CLOSE")?>": function() {
				$( this ).dialog( "close" );
			}
		}
		<? } ?>
	});

	if (edost_GetCookie('edostcdaddcart') == 1) $("#edost_add_cart").attr('checked', true);


	edost_catalog_location = edost_GetCookie('edostcdcity');
//	edost_catalog_location = '';
	if (edost_catalog_location != '') edost_catalog_location = edost_catalog_location.split("|");

	if (edost_start) {
		edost_goods_id = cur_goods_id;

<? if ($arResult["EDOST_LOCATIONS"] == 'Y') { ?>
		if (edost_SetCatalogLocation('') == false) {
			$("#location_info").html('<img style="vertical-align: middle;" src="<?=$arResult["PATH_DELIVERY_IMG"]?>/loading_small.gif" width="20" height="20" border="0"><font color="#888888"><b> <?=GetMessage('EDOST_CATALOG_DELIVERY_GET_LOCATION')?></b></font>');
			$("#location_info").show();

			$.get("<?=$arResult["PATH_DELIVERY_LOCATION"]?>", {edost_locations: "1"}, function(res){
				$("#location_info").html('');

				if (res != undefined && res != '') res = (window.JSON && window.JSON.parse ? JSON.parse(res) : eval('(' + res + ')'));
				if (res != undefined && res['stat'] == '1') {
					if (res['location'] != undefined) edost_SetCatalogLocation(res['location']);
					else edost_SetLocation(edost_GetLocation(res['ID']), (res['set_location'] == 'Y' ? true : false), 1);
				}
				else edost_SetLocation(-2);
			});
		}
<? }
else { ?>
		if (edost_SetCatalogLocation('') == false) {
			$.get("<?=$arResult["PATH_DELIVERY_LOCATION"]?>", function(res){
				if (res != undefined && res != '' && res != 'none') edost_SetCatalogLocation(res);
			});
		}
<? } ?>

	}
	else {
		var sToCityNow = $("select[name='edost_city_sel']").find(':selected').val();
		if (edost_catalog_location[0] != edost_location_old[0] || edost_goods_id != cur_goods_id) {
			edost_goods_id = cur_goods_id;
			edost_Calc();
		}
	}

	$('#edost_catalogdelivery').dialog('open');
	if (cur_goods_name == undefined) cur_goods_name = '';
	$( "#edost_catalogdelivery" ).dialog({ title: '<?echo GetMessage("EDOST_CATALOG_DELIVERY_TITLEWND")?>: '+ cur_goods_name });

}

function edost_LoadCities() {

	var sToCountry = $("#edost_country_sel").val();
	if (sToCountry == -1) return;

	$("#edost_cities").html('<img style="vertical-align: top;" src="<?=$arResult["PATH_DELIVERY_IMG"]?>/loading_small.gif" width="20" height="20" border="0">');

	$("#edost_cities").load("<?=$arResult["PATH_DELIVERY_LOADCITIES"]?>", {edost_to_country:sToCountry}, function(){
		if (edost_start) {
			edost_start = false;

			if (edost_catalog_location[0] != undefined) {
				var selEl = $("select[name='edost_city_sel'] option[value='" + edost_catalog_location[0] + "']");
				if ( selEl.val() == edost_catalog_location[0] ) selEl.attr("selected", "selected");
			}
		}

		edost_Calc();
	});

}

function edost_Calc() {
	edost_start = false;

	var sToCity = $("#edost_city_sel").val();

	if (sToCity > 0) {
		var add_cart = 0;
		if ($("#edost_add_cart").is(':checked')) add_cart = 1;  // учитывать товары в корзине

		$("#edost_rz").html('');

		var r = '';
		if($("#edost_to_city").val() == '') r=r+'<?echo GetMessage("EDOST_CATALOG_DELIVERY_ERR_CITYNOTFILL")?><br>';
		if (r != '') return false;

		var sToCountry = $("#edost_country_sel").val();

		qty = $("#edost_qty").val();

		if ( $("#edost_add_cart").is(':checked') ) sAddCartCheckbox = 1;
		else sAddCartCheckbox = 0;

		$("#edost_rz").html('<table valign="top" width="95%" style="margin: 0px; padding: 10px 0px 10px 0px;" cellspacing="0" cellpadding="0" border="0"><tr style="padding: 0px; margin: 5px;"><td align="center"><img style="vertical-align: top;" src="<?=$arResult["PATH_DELIVERY_IMG"]?>/loading.gif" width="64" height="64" border="0"></td></tr></table>');

		edost_location_old = new Array(sToCity, sToCountry);
		document.cookie = "edostcdcity="+sToCity+"|"+sToCountry+"; path=/; expires=Thu, 01-Jan-2015 00:00:01 GMT";
		document.cookie = "edostcdaddcart="+sAddCartCheckbox+"; path=/; expires=Thu, 01-Jan-2015 00:00:01 GMT";

		$("#edost_rz").load("<?=$arResult["PATH_DELIVERY_GOODSCALC"]?>", {edost_to_country: sToCountry, edost_to_city:sToCity, edost_goods_id:edost_goods_id, edost_qty:qty, edost_add_cart:add_cart}, function(){});
	}
	else $("#edost_rz").html('');
}

</script>


<div id="edost_catalogdelivery" style="display: none;" title="<?echo GetMessage("EDOST_CATALOG_DELIVERY_TITLEWND")?>: ">

<form name="calc" method="post" onSubmit="return false;">

	<? if (isset($arResult["INFO"])) { ?>
	<table width="95%" style="margin: 0px 0px 5px 0px; padding: 4px;" cellspacing="0" cellpadding="0"><tr style="padding: 0px; margin: 5px;"><td>
	<?=html_entity_decode($arResult["INFO"])?>
	</td></tr></table>
	<? } ?>


<? if ($arResult["EDOST_LOCATIONS"] == 'Y') { ?>
	<input name="edost_country_sel" id="edost_country_sel" value="" type="hidden">
	<input name="edost_city_sel" id="edost_city_sel" value="" type="hidden">
<? } ?>


	<table width="95%" style="padding: 0px; margin: 0px;" cellpadding="2" cellspacing="0" border="0">
<?
if ($arResult["EDOST_LOCATIONS"] == 'Y') {
	$GLOBALS["APPLICATION"]->IncludeComponent('edost:locations', '',
		array(
			"EDOST_CATALOGDELIVERY" => 'Y',
			"CODE" => "LOCATION",
			"NAME" => GetMessage("EDOST_CATALOG_DELIVERY_TO2"),
			"FIELD_NAME" => 'FIELD_LOCATION',

			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "180",
			"CACHE_GROUPS" => "Y",
		),
		null, array('HIDE_ICONS' => 'Y')
	);
}
else {
?>
		<tr height="25">
			<td width="130" align="right" valign="top" style="padding: 2px; margin: 0px;">
				<table style="padding: 0px; margin: 0px;" height="25" border="0" cellspacing="0" cellpadding="0"><tr><td style="vertical-align: middle; padding: 0px; margin: 0px;">
					<?echo GetMessage("EDOST_CATALOG_DELIVERY_TO")?>
				</td></tr></table>
			</td>
			<td align="left" style="vertical-align: middle; padding: 2px; margin: 0px;">

				<? if (count($arResult["COUNTRY_LIST"]) > 1) { ?>
				<select name="edost_country_sel" id="edost_country_sel" onChange="edost_LoadCities()">
					<option value="-1" style="color: rgb(255, 0, 0);"><?echo GetMessage('SAL_CHOOSE_COUNTRY')?></option>
					<?foreach ($arResult["COUNTRY_LIST"] as $arCountry):?>
					<option value="<?=$arCountry["ID"]?>"><?=$arCountry["NAME_LANG"]?></option>
					<?endforeach;?>
				</select>
				<? }
				else if (count($arResult["COUNTRY_LIST"]) == 1) { ?>
				<input name="edost_country_sel" id="edost_country_sel" value="<?=$arResult["COUNTRY_LIST"][0]["ID"]?>" type="hidden"><?
				}
				else { ?>
				<input name="edost_country_sel" id="edost_country_sel" value="-1" type="hidden"><?
				} ?>

				<span id="edost_cities" name="edost_cities">
				<? if (count($arResult["COUNTRY_LIST"]) == 1) {
					if (count($arResult["CITY_LIST"]) > 0) { ?>
					<select name="edost_city_sel" id="edost_city_sel" onchange="edost_Calc()">
						<option value="-1" style="color: rgb(255, 0, 0);"><?=GetMessage('SAL_CHOOSE_CITY')?></option>
						<? if ($arResult["COUNTRY_ID"] > 0) { ?>
						<option value="<?=$arResult["COUNTRY_ID"]?>" style="color: rgb(136, 136, 136);"><?=GetMessage('SAL_CHOOSE_CITY_OTHER')?></option>
						<? } ?>
						<? foreach ($arResult["CITY_LIST"] as $arCity) { ?>
						<option value="<?=$arCity["ID"]?>"><?=$arCity["CITY_NAME"]?></option>
						<? } ?>
					</select>
					<? }
					else { ?>
					<input name="edost_city_sel" id="edost_city_sel" value="<?=$arResult["COUNTRY_ID"]?>" type="hidden">
					<? }
				}?>
				</span>

			</td>
		</tr>
<? } ?>

<? if( $arResult["SHOW_QTY"] == "Y" || $arResult["SHOW_ADD_CART"] == "Y") { ?>
		<tr>
			<td width="130" align="right">
				<?=($arResult["SHOW_QTY"] == "Y" ? GetMessage('EDOST_CATALOG_DELIVERY_QTY') : '')?>
			</td>
			<td align="left" style="vertical-align: middle; padding: 2px; margin: 0px;">
				<table style="padding: 0px; margin: 0px;" height="25" border="0" cellspacing="0" cellpadding="0"><tr>
					<? if ($arResult["SHOW_QTY"] == "Y") { ?>
					<td align="left" style="vertical-align: middle; padding: 0px; margin: 0px;">
						<input name="edost_qty" id="edost_qty" value="1" size="4">
					</td>
					<td width="40"></td>
					<? } ?>
					<? if ($arResult["SHOW_ADD_CART"] == "Y") { ?>
					<td style="vertical-align: middle; padding: 0px; margin: 0px;">
						<input type="checkbox" id="edost_add_cart"> <?=GetMessage("EDOST_CATALOG_DELIVERY_ADD_CART")?> <span id="edost_cart_weight"></span>
					</td>
					<? } ?>
				</tr></table>
			</td>
		</tr>
<? } ?>
	</table>


	<table width="95%" style="margin: 10px 0px 0px 0px; padding: 0px;" cellpadding="0" cellspacing="0" border="0">
		<tr><td>
			<span id="edost_rz" name="edost_rz"></span>
		</td></tr>
	</table>

</form>

</div>
