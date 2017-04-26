<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?// ON/OFF COMPOSITE ON THIS PAGE
if (empty($_REQUEST["view"]) || empty($_REQUEST['order']) || empty($_REQUEST['by']) || empty($_REQUEST["page_count"]))
	{if(method_exists($component, 'setFrameMode')) $component->setFrameMode(false);}
else
	{if(method_exists($component, 'setFrameMode')) $component->setFrameMode(true);}
	
if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
{
	$APPLICATION->AddHeadScript($templateFolder.'/js/sku-selectbox.js'); // for sku-selectbox
}

// view
if (!empty($_REQUEST["view"])) {
	// If params is exist ( view?clear_cache=Y )
	if (strpos($_REQUEST["view"], '?') !== false) {
		$tmp = explode('?', $_REQUEST["view"]);
		$_REQUEST["view"] = $tmp[0];
	}
	if (CModule::IncludeModule('sale'))
		$arView = array("block", "list", "table");
	else
		$arView = array("block", "list");
	if (in_array($_REQUEST["view"], $arView)) {
		$view = htmlspecialchars($_REQUEST["view"]);
		$APPLICATION->set_cookie("view", $view);
	} else {
		define("ERROR_404", "Y");
	}
} else {
	$view = $APPLICATION->get_cookie("view") ? $APPLICATION->get_cookie("view") : $arParams["DEFAULT_VIEW"];
}

// pages
foreach ($_REQUEST as $k => $v) {
	if (strpos($k, 'PAGEN_') === 0) {
		if ($_REQUEST[$k] > 0) {
			$pagen_key = $k;

			if (strpos($_REQUEST[$k], '?') !== false) {
				$tmp = explode('?', $_REQUEST[$k]);
				$pagen = htmlspecialchars($tmp[0]);
			} else {
				$pagen = htmlspecialchars($_REQUEST[$k]);
			}

			$APPLICATION->set_cookie($k, $pagen);
		}
	}
}

// sort
$order = $_REQUEST['order'] ? htmlspecialchars($_REQUEST['order']) : $APPLICATION->get_cookie("order");
$by = $_REQUEST['by'] ? htmlspecialchars($_REQUEST['by']) : $APPLICATION->get_cookie("by");

// If params is exist ( asc?clear_cache=Y )
if (strpos($by, '?') !== false) {
	$tmp = explode('?', $by);
	$by = $tmp[0];

	// Need PHP 5.4
	// $by = explode('?', $by)[0];
}

if (!$order) {
	if ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'PRICE')
		$order = $arParams['LIST_PRICE_SORT'];
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'NAME')
		$order = 'name';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_EXT')
		$order = 'property_sale_ext';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_INT')
		$order = 'property_sale_int';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SORT')
		$order = 'sort';
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'RATING')
		$order = 'property_rating';
	else
		$order = 'property_week_counter';
}
$order = $order == 'SHOW_COUNTER' ? 'property_week_counter' : $order ; // it's for compatibility with < 1.3.8 version.
$by = $by ? $by : $arParams['DEFAULT_ELEMENT_SORT_ORDER'];

$order = strtolower($order);
$by = strtolower($by);

if (!in_array($order, array(strtolower($arParams['LIST_PRICE_SORT']), 'name', 'property_sale_ext', 'property_rating', 'property_sale_int', 'sort', 'property_week_counter', 'show_counter')) ||
	!in_array($by, array('asc', 'desc', 'ASC', 'DESC'))) {
	// define("ERROR_404", "Y");
	$order = 'name';
	$by = 'asc';
} else {
	$APPLICATION->set_cookie("order", $order);
	$APPLICATION->set_cookie("by", $by);
}


// Page count
if (!empty($_REQUEST["page_count"])) {
	if(($pos = strpos($_REQUEST["page_count"],"?"))!==false) $_REQUEST["page_count"] = substr($_REQUEST["page_count"], 0, $pos);
	$_REQUEST["page_count"] = intval($_REQUEST["page_count"]);
	if (in_array($_REQUEST["page_count"], array(20, 40, 60))) {
		$page_count = htmlspecialchars($_REQUEST["page_count"]);
		$APPLICATION->set_cookie("page_count", $page_count);
	} else {
		define("ERROR_404", "Y");
	}
} else {
	$page_count = $APPLICATION->get_cookie("page_count") ? $APPLICATION->get_cookie("page_count") : 20;
}
$page_count = intval($page_count);
if (!in_array($page_count, array(20, 40, 60))) $page_count = 20;
// ---

// ABCD
$letter = (!empty($_REQUEST["letter"])) ? $_REQUEST["letter"] : 'all';


$iblock_section = $arResult['IBLOCK_SECTION'];

global $ys_options;
$ys_options['show_left_text'] = 'Y';
$ys_options['current_dir'] = str_replace('//','/',$arResult['FOLDER'].'/') ;
$ys_options['show_help_menu'] = 'Y';

if($ys_options["smart_filter_type"] == "KOMBOX"){
	$bKomboxFilterInstalled = true;
	if(!CModule::IncludeModule('kombox.filter'))$bKomboxFilterInstalled = false;;
}

$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$folder_sef = '';
if ($sef == "Y")
{
	$folder_sef = $arResult['FOLDER'].'/view-'.$view.'/sort-'.$order.'-'.$by.'/page_count-'.$page_count.'/';
	if($pagen > 1) $folder_sef .= "page-".$pagen."/";
}
?>

<?if ($sef == "Y"):?>
	<input type="hidden" name="ys-sef" value="Y" />
<?endif;?>
	<input type="hidden" name="ys-folder-url" value="<?=$arResult["FOLDER"]?>" />
	<input type="hidden" name="ys-request-uri" value="<?=$_SERVER['REQUEST_URI']?>" />
	<input type="hidden" name="ys-script-name" value="<?=$_SERVER['SCRIPT_NAME']?>" />


<?if (($arResult['SEO_DESCRIPTION'] || $arResult['SEO_DESCRIPTION_IMG']) && $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] != 'Y'):?>
	<div class="s_dscr">
		<?if($arResult['SEO_DESCRIPTION_IMG']):?>
			<img src="<?=CResizer2Resize::ResizeGD2($arResult['SEO_DESCRIPTION_IMG'], $arParams['BLOCK_IMG_SMALL']);?>"/>
		<?endif;?>
		<p><?=$arResult['SEO_DESCRIPTION'];?></p>
	</div>
<?endif;?>
<?
	if ($arParams["SMART_FILTER"] == "Y") {
		$arFilter = array(
			"ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		);
        if(0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        {
            $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
        }
        elseif('' != $arResult["VARIABLES"]["SECTION_CODE"])
        {
            $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
        }

		$obCache = new CPHPCache;
		if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
			$arCurSection = $obCache->GetVars();
		} else {
			$arCurSection = array();
			
			if(CModule::IncludeModule("iblock"))
			{
				$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID","LEFT_MARGIN","RIGHT_MARGIN"));
				$dbRes = new CIBlockResult($dbRes);

				if (defined("BX_COMP_MANAGED_CACHE")) {
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache("/iblock/catalog");

					if ($arCurSection = $dbRes->GetNext()) {
						$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
					}
					$CACHE_MANAGER->EndTagCache();
				} else {
					if (!$arCurSection = $dbRes->GetNext())
						$arCurSection = array();
				}
			}
			$obCache->EndDataCache($arCurSection);
		}
	}
	?>
<?if ($arParams["USE_COMPARE"]=="Y"):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.compare.list",
		"",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"NAME" => $arParams["COMPARE_NAME"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CUR_SECTION" => $arCurSection,
		),
		$component
	);?>
<?endif?>
<?if ($arParams["SHOW_SECTION_LIST"] != "N"):?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "bitronic", array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => intval($arResult["VARIABLES"]["SECTION_ID"]),
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => '1',
		"SECTION_URL" => "",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"ADD_SECTIONS_CHAIN" => "N",
		"VIEW_MODE" => "TEXT",
		"SHOW_PARENT_NAME" => "N"
		),
		$component
	);?>
<?endif?>
<?if ($arParams["USE_FILTER"] == "Y" && !defined("ERROR_404")):?>
	

	<?if ($ys_options["menu_filter"] != "left-top"):?>
		<?if($ys_options["smart_filter_type"] != "KOMBOX"):?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter",
				"empty",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arCurSection["ID"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SAVE_IN_SESSION" => "N",
					"INSTANT_RELOAD" => "Y",
					"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
					"AJAX_FILTER" => "Y",
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE'],
				),
				$component
			);?>
		<?else:?>
			<?if(!isset($_REQUEST["ajax"]) && $arParams["KOMBOX_BITRONIC_AJAX"] !== "Y") $this->SetViewTarget("filter");?>
			<?if($bKomboxFilterInstalled):?>
				<?$arKomboxResult = $APPLICATION->IncludeComponent(
					"kombox:filter", 
					"bitronic-vertical", 
					array(
						"IS_SEF" => "N",
						"MESSAGE_ALIGN" => "RIGHT",
						"MESSAGE_TIME" => "0",
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $arCurSection["ID"],
						"SECTION_CODE" => "",
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"CLOSED_PROPERTY_CODE" => $arParams["KOMBOX_CLOSED_PROPERTY_CODE"],
						"CLOSED_OFFERS_PROPERTY_CODE" => $arParams["KOMBOX_CLOSED_OFFERS_PROPERTY_CODE"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"FIELDS" => $arParams["KOMBOX_FIELDS"],
						"SORT" => $arParams["KOMBOX_SORT"],
						"SORT_ORDER" => $arParams["KOMBOX_SORT_ORDER"],
						"SORT_SECTIONS" => $arParams["KOMBOX_SORT_SECTIONS"],
						"SORT_STORES" => $arParams["KOMBOX_SORT_STORES"],
						"SORT_QUANTITY" => $arParams["KOMBOX_SORT_QUANTITY"],
						"SORT_AVAILABLE" => $arParams["KOMBOX_SORT_AVAILABLE"],
						"TOP_DEPTH_LEVEL" => $arParams["KOMBOX_TOP_DEPTH_LEVEL"],
						"STORES_ID" => $arParams["STORE_CODE"],
						"USE_COMPOSITE_FILTER" => $arParams["KOMBOX_USE_COMPOSITE_FILTER"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SAVE_IN_SESSION" => "N",
						"INCLUDE_JQUERY" => "N",
						"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"XML_EXPORT" => "Y",
						"SECTION_TITLE" => "-",
						"SECTION_DESCRIPTION" => "-",
						"BITRONIC_AJAX" => $arParams["KOMBOX_BITRONIC_AJAX"],
						"PAGE_URL" => $arParams["KOMBOX_BITRONIC_AJAX"] == "Y" ? $arParams["FOLDER"] : ""
					),
					$component
				);?>
			<?else:?>
			<p>
				<font class="errortext">
				<?echo GetMessage("KOMBOX_FILTER_NOT_INSTALLED");?>
				</font>
			</p>
			<?endif;?>
			<?if(!isset($_REQUEST["ajax"]) && $arParams["KOMBOX_BITRONIC_AJAX"] !== "Y") $this->EndViewTarget("filter");?>
		<?endif;?>
	<?endif;?>

	<?if ($ys_options["menu_filter"] == "left-top"):?>
		<?if($ys_options["smart_filter_type"] != "KOMBOX"):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.smart.filter",
				"bitronic-horizontal",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arCurSection["ID"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SAVE_IN_SESSION" => "N",
					"INSTANT_RELOAD" => "Y",
					"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
					"AJAX_FILTER" => "Y",
					"EXPAND_PROPS" => $arParams["EXPAND_PROPS"],
					"ENABLE_EXPANSION" => $arParams['FILTER_ENABLE_EXPANSION'],
					"START_EXPANDED" => $arParams['FILTER_START_EXPANDED'],
					"VISIBLE_ROWS_COUNT" => $arParams['FILTER_VISIBLE_ROWS_COUNT'],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE']
				),
				$component
			);?>
		<?else:?>
			<?if($bKomboxFilterInstalled):?>
				<?$arKomboxResult = $APPLICATION->IncludeComponent(
					"kombox:filter", 
					"bitronic-horizontal", 
					array(
						"IS_SEF" => "N",
						"MESSAGE_ALIGN" => "LEFT",
						"MESSAGE_TIME" => "0",
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $arCurSection["ID"],
						"SECTION_CODE" => "",
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"CLOSED_PROPERTY_CODE" => $arParams["KOMBOX_CLOSED_PROPERTY_CODE"],
						"CLOSED_OFFERS_PROPERTY_CODE" => $arParams["KOMBOX_CLOSED_OFFERS_PROPERTY_CODE"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"FIELDS" => $arParams["KOMBOX_FIELDS"],
						"SORT" => $arParams["KOMBOX_SORT"],
						"SORT_ORDER" => $arParams["KOMBOX_SORT_ORDER"],
						"SORT_SECTIONS" => $arParams["KOMBOX_SORT_SECTIONS"],
						"SORT_STORES" => $arParams["KOMBOX_SORT_STORES"],
						"SORT_QUANTITY" => $arParams["KOMBOX_SORT_QUANTITY"],
						"SORT_AVAILABLE" => $arParams["KOMBOX_SORT_AVAILABLE"],
						"TOP_DEPTH_LEVEL" => $arParams["KOMBOX_TOP_DEPTH_LEVEL"],
						"STORES_ID" => $arParams["STORE_CODE"],
						"USE_COMPOSITE_FILTER" => $arParams["KOMBOX_USE_COMPOSITE_FILTER"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SAVE_IN_SESSION" => "N",
						"INCLUDE_JQUERY" => "N",
						"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"XML_EXPORT" => "N",
						"SECTION_TITLE" => "-",
						"SECTION_DESCRIPTION" => "-",
						"BITRONIC_AJAX" => $arParams["KOMBOX_BITRONIC_AJAX"],
						"PAGE_URL" => $arParams["KOMBOX_BITRONIC_AJAX"] == "Y" ? $arParams["FOLDER"] : ""
					),
					$component
				);?>
			<?else:?>
			<p>
				<font class="errortext">
				<?echo GetMessage("KOMBOX_FILTER_NOT_INSTALLED");?>
				</font>
			</p>
			<?endif;?>
		<?endif;?>
	<?endif;?>
<?endif?>

<?
if($ys_options["smart_filter_type"] == "KOMBOX" && $bKomboxFilterInstalled)
{
	if(in_array('N', $arKomboxResult['CHECKED']['AVAILABLE']))
		$arResult['q_checked'] = 'Y';
	else
		$arResult['q_checked'] = 'N';
		
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");
}
global $arrFilter;
// delete empty filters element
// $arrFilter = array_diff($arrFilter, array(0, null));
foreach ($arrFilter as $k => $v)
{
	if(empty($v))
			unset($arrFilter[$k]);
}
$arr = array();
if(is_array($arrFilter["PROPERTY"]))
{
	foreach ($arrFilter["PROPERTY"] as $k => $arrf) {
		if($arrf[0] == GetMessage('CC_BCF_ALL')) $arrFilter["PROPERTY"][$k] = array();
	}
}
?>

<?if($arParams["SET_STATUS_ABCD"] == "Y"):?>
	<?$APPLICATION->IncludeComponent("yenisite:catalog.abcd", ".default", array(
			"SECTION_ID" => $arCurSection["ID"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"GENERATION" => "Y",
			"LIST_ENABLE" => "Y",
			"SHOW_NUMBER" => "Y",
			"GROUP_NUMBER" => "N",
			"SHOW_RUS" => "Y",
			"GROUP_RUS" => "N",
			"SHOW_ENG" => "Y",
			"GROUP_ENG" => "N",
			"SHOW_ALL" => "Y",
			"INCLUDE_SUBSECTIONS" => (!empty($arParams["INCLUDE_SUBSECTIONS"])) ? $arParams["INCLUDE_SUBSECTIONS"] : "Y",
			"SEF" => $sef,
			"FOLDER_SEF" => $folder_sef, 
		),
		false
	);?>
<?endif;?>

	<form name="sort_form">
		<?if(method_exists($this, 'createFrame'))  $frame = $this->createFrame()->begin(''); ?>
		<?if (!empty($_REQUEST["set_filter"])):?>
			<?foreach ($_REQUEST as $k => $v):?>
				<?if (strpos($k, 'arrFilter') !== false):?>
					<input type='hidden' value='<?=$v?>' name='<?=$k?>' />
				<?endif;?>
			<?endforeach;?>
		<?else:?>
			<?if(strlen($arResult["VARIABLES"]["SECTION_CODE"])>0):?>
				<input type='hidden' value='<?=$arResult["VARIABLES"]["SECTION_CODE"]?>' name='SECTION_CODE' id='SECTION_CODE'/>
			<?elseif(intval($arResult["VARIABLES"]["SECTION_ID"])>0):?>
				<input type='hidden' value='<?=intval($arResult["VARIABLES"]["SECTION_ID"])?>' name='SECTION_ID' id='SECTION_ID'/>
			<?endif?>
		<?endif?>

		<?$arFiltersName = array('arrFilter_pf', 'arrFilter_cf', 'arrFilter_ff');?>
		<?foreach ($arFiltersName as $filter_name):?>
			<?if (is_array($_GET[$filter_name])):?>
				<?foreach ($_GET[$filter_name] as $key => $arrFilter_x):?>
					<?foreach ($arrFilter_x as $key2 => $value):?>
						<?if (isset($value)):?>
							<input type='hidden' value='<?=$value?>' name='<?=$filter_name;?>[<?=$key;?>][<?=$key2;?>]' />
						<?endif;?>
					<?endforeach;?>
				<?endforeach;?>
			<?endif;?>
		<?endforeach;?>
		<?$arFilterParams = array('del_filter', 'set_filter', 'f_Quantity');?>
		<?foreach ($arFilterParams as $filter_param):?>
			<?if(isset ($_GET[$filter_param])):?>
				<input type='hidden' value='<?=htmlspecialchars($_GET[$filter_param]);?>' name='<?=$filter_param;?>' />
			<?endif;?>
		<?endforeach;?>

		<?// FOR SAVE KOMBOX FILTER PARAMS WHEN USE AJAX CATALOG
		if($ys_options["smart_filter_type"] == "KOMBOX" && isset($_GET) && is_array($_GET)):?>
			<?foreach ($_GET as $key => $value):?>
				<?if (strpos($key, 'arrFilter') !== false ||
						in_array($key, $arFilterParams) ||
						in_array($key, array('letter','order','by','view','PAGEN_','page_count', 'SECTION_CODE')) ||
						(
							strpos(strtolower($key), "cml2_") === false &&
							!in_array(strtolower($key), array_map('strtolower', $arParams['SETTINGS_HIDE'])) &&	
							!in_array(strtolower($key), array_map('strtolower', $arResult['YS_SHOW_PROPERTIES']))
						)
					) continue;?>
					
				<?if (isset($value)):?>
					<?if(!is_array($value)) $value = array($value);?>
					<?$i=0;foreach($value as $v):?>
						<input type='hidden' value='<?=$v?>' name='<?=$key?>[<?=$i?>]' id="arrFilter_<?=$key?>_<?=$v?>" />
					<?$i++;endforeach;unset($i);?>
				<?endif;?>
			<?endforeach;?>
		<?endif?>
	
		<?if($arParams["SET_STATUS_ABCD"] == "Y"):?>
			<input id='abcd' type='hidden' value='<?=(!empty($_REQUEST["letter"])) ? $_REQUEST["letter"] : 'all'?>' name='letter' />
		<?endif;?>
		<?if(method_exists($this, 'createFrame'))  $frame->end();?>
		<input id='order_field' type='hidden' value='<?=$order?>' name='order' />
		<input id='by_field' type='hidden' value='<?=$by?>' name='by' />
		<div class="filter">
			<div class="f_view">
				<span class="lab"><?=GetMessage('VIEW');?>:</span>
				<button title="<?=GetMessage("DEFAULT_VIEW_BLOCK"); ?>" onclick="setViewField('block', event); return false;" class="button12 sym  <?=($view=='block')?"active":"";?>">&#0065;</button>
				<?if ($sef == "Y"):?>
					<a href="<?=$arResult['FOLDER']?>/view-block/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>
				<?endif;?>
				<button title="<?=GetMessage("DEFAULT_VIEW_LIST"); ?>" onclick="setViewField('list', event); return false;" class="button12 sym  <?=($view=='list')?"active":"";?>">&#0066;</button>
				<?if ($sef == "Y"):?>
					<a href="<?=$arResult['FOLDER']?>/view-list/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>
				<?endif;?>
				<?if (CModule::IncludeModule('sale')):?>
					<button title="<?=GetMessage("DEFAULT_VIEW_TABLE"); ?>" onclick="setViewField('table', event); return false;" class="button12 sym  <?=($view=='table')?"active":"";?>">&#0067;</button>
					<?if ($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-table/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>
					<?endif;?>
				<?endif;?>
			</div><!---.f_pop-->
			<div class="inler">
				<div class="f_label"><?=GetMessage('SORT');?>:</div>
				<div class="f_price">
					<span class="lab <?=($order==strtolower($arParams['LIST_PRICE_SORT']))?"active":"";?>"><?=GetMessage('PO_PRICE');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('<?=strtolower($arParams['LIST_PRICE_SORT']);?>', 'DESC', event); return false;" class="button11 sym <?=($order==strtolower($arParams['LIST_PRICE_SORT']) && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-<?=strtolower($arParams['LIST_PRICE_SORT'])?>-desc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('<?=strtolower($arParams['LIST_PRICE_SORT']);?>', 'ASC', event); return false;" class="button11 sym <?=($order==strtolower($arParams['LIST_PRICE_SORT']) && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-<?=strtolower($arParams['LIST_PRICE_SORT'])?>-asc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_price-->
				<div class="f_name">
					<span class="lab <?=($order=='name')?"active":"";?>"><?=GetMessage('PO_NAME');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('name', 'DESC', event); return false;" class="button11 sym <?=($order=='name' && ($by=='DESC' || $by=='desc'))?"active":"";?>" >&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-name-desc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('name', 'ASC', event); return false;" class="button11 sym <?=($order=='name' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-name-asc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_name-->
				<div class="f_pop">
					<span class="lab  <?=($order=='property_week_counter')?"active":"";?>"><?=GetMessage('PO_POPULAR');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('property_week_counter', 'DESC', event); return false;" class="button11 sym <?=($order=='property_week_counter' && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_week_counter-desc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('property_week_counter', 'ASC', event); return false;" class="button11 sym <?=($order=='property_week_counter' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_week_counter-asc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_pop-->
				<div class="f_sales">
					<span class="lab  <?=($order=='property_sale_int')?"active":"";?>"><?=GetMessage('PO_SALE');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('property_sale_int', 'DESC', event); return false;" class="button11 sym <?=($order=='property_sale_int' && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_sale_int-desc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('property_sale_int', 'ASC', event); return false;" class="button11 sym <?=($order=='property_sale_int' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_sale_int-asc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_sales-->
				<div class="f_sales">
					<span class="lab  <?=($order=='property_rating')?"active":"";?>"><?=GetMessage('PO_RATING');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('property_rating', 'DESC', event); return false;" class="button11 sym <?=($order=='property_rating' && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_rating-desc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('property_rating', 'ASC', event); return false;" class="button11 sym <?=($order=='property_rating' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-property_rating-asc/page_count-<?=$page_count?>/letter-<?=$letter?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_rating-->

				<!--<form name="view_form">-->

				<input id='view_field' type='hidden' value='<?=$view?>' name='view' />
				<?if($pagen_key):?>
					<input id='PAGEN_field' type='hidden' value='<?=$pagen?>' name='<?=$pagen_key?>' />
				<?else: // for ajax?>
					<input id='PAGEN_field' type='hidden' value='' name='PAGEN_1' />
				<?endif;?>
			</div>
			<?if($view == 'table'):?>
				<div class="button_in_basket_head_table">	
					<button class="ajax_add2basket_t button2 button_in_basket" type="button"><span><?=GetMessage('BUTTON_BUY_VIEW_TABLE');?></span></button>
				</div>
			<?endif;?>
		</div><!--.filter-->
	</form><!--</form>-->

<?
/* 2.0 */
$arSets = array();
$arSets['BLOCK_IMG_SMALL'] = $arParams['BLOCK_IMG_SMALL'];
$arSets['BLOCK_IMG_BIG'] = $arParams['BLOCK_IMG_BIG'];
$arSets['LIST_IMG'] = $arParams['LIST_IMG'];
$arSets['TABLE_IMG'] = $arParams['TABLE_IMG'];

$arSets['DETAIL_IMG_SMALL'] = $arParams['DETAIL_IMG_SMALL'];
$arSets['DETAIL_IMG_BIG'] = $arParams['DETAIL_IMG_BIG'];
$arSets['DETAIL_IMG_ICON'] = $arParams['DETAIL_IMG_ICON'];
?>
<?
$cacheCountFilter = 0;//($ys_options["smart_filter_type"] != "KOMBOX") ? 0 : 4;
if((	(	(isset($arrFilter['>CATALOG_QUANTITY']) 
				|| isset($arrFilter['<=CATALOG_QUANTITY']) 
				|| isset($arrFilter['CATALOG_AVAILABLE'])
			)
			&& count($arrFilter) ==$cacheCountFilter+1
		)
		|| ($ys_options["smart_filter_type"] == "KOMBOX" && !$arKomboxResult['SET_FILTER'])
	
    ) 
	|| $arParams["CACHE_FILTER"] =='Y'
  )
	$arParams["CACHE_FILTER"] = 'Y';
else
	$arParams["CACHE_FILTER"] = 'N';

//need to clear cache on smart_filter_type change
$smartFilterType = $ys_options['smart_filter_type'];
if (CModule::IncludeModule('kombox.filter') && $smartFilterType == 'KOMBOX') {
	if (CKomboxFilter::IsSefMode($APPLICATION->GetCurPage(false))) {
		$smartFilterType .= '_SEF';
	}
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	$view,
	Array(
		"BLOCK_VIEW_MODE" => $ys_options["block_view_mode"],
		"USE_PRODUCT_QUANTITY" => ($view == 'table')?"Y":"N",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $order,
		"ELEMENT_SORT_ORDER" => $by,
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" =>$arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => (!empty($arParams["INCLUDE_SUBSECTIONS"])) ? $arParams["INCLUDE_SUBSECTIONS"] : "Y",
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => $page_count,
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],

		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"SHOW_ALL_WO_SECTION" => (!empty($arParams["SHOW_ALL_WO_SECTION"])) ? $arParams["SHOW_ALL_WO_SECTION"] : "Y",

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => ($sef == "Y") ? 'bitronic_sef' : $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => (!empty($arParams["LIST_PRICE_SORT"]))? $arParams["LIST_PRICE_SORT"] : $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => (!empty($arParams["LIST_PRICE_SORT"]))? "asc" : $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"RESIZER_SETS" => $arSets,
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],

		"HIDE_ORDER_PRICE" => $arParams["HIDE_ORDER_PRICE"],

		//stickers :
		"STICKER_NEW" => $arParams['STICKER_NEW'],
		"STICKER_NEW_DELTA_TIME" => $arResult['STICKER_NEW_DELTA_TIME'],

		"STICKER_HIT" => $arParams['STICKER_HIT'],
		"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],

		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

		'FILTER_TYPE' => $smartFilterType, //fix to clear cache on filter type change
		'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
		'q_checked' => $arResult['q_checked'],
		
		//
		"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT'],
		"DEFAULT_VIEW" => $arParams['DEFAULT_VIEW'],
		"AUTO_SLIDE" => $arParams['AUTO_SLIDE'],
		"DELAY_SLIDE" => $arParams['DELAY_SLIDE'],

		'STORE_CODE' => $arParams["STORE_CODE"],
		
		// selectBox SKU
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	),
	$component
);
?>
<?$this->SetViewTarget('filter');?>
<?if($arParams["USE_FILTER"] == "Y" && !defined("ERROR_404")):?>
	<?if($ys_options["menu_filter"] == "top-left"):?>
		<?if($ys_options["smart_filter_type"] != "KOMBOX"):?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "bitronic-vertical", array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arCurSection["ID"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => "N",
					"SAVE_IN_SESSION" => "N",
					"INSTANT_RELOAD" => "Y",
					"AJAX_FILTER" => "Y",
					"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
					"EXPAND_PROPS" => $arParams["EXPAND_PROPS"],
					"ENABLE_EXPANSION" => $arParams['FILTER_ENABLE_EXPANSION'],
					"START_EXPANDED" => $arParams['FILTER_START_EXPANDED'],
					"VISIBLE_PROPS_COUNT" => $arParams['FILTER_VISIBLE_PROPS_COUNT'],
					"HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE']
				),
				$component
			);?>
		<?endif;?>
	<?endif?>
<?endif;?>

<?$this->EndViewTarget();?>
<?if($arResult['SEO_DESCRIPTION'] && $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] == 'Y'):?>
	<div class="s_dscr_down">
		<?=$arResult['SEO_DESCRIPTION'];?>
	</div>
<?endif;?>
	<div class="cb_text1">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file",	"PATH" => $cur_dir."index_center.php",	"EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
	</div>
	<div class="cb_text2">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file",	"PATH" => $cur_dir."index_right.php",	"EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
	</div>


<?
// META
if($iblock_section && CModule::IncludeModule("yenisite.meta"))
{
	$arParams['SECTION_META_TITLE_H1'] = $arParams['SECTION_META_H1_FORCE'] ? $arParams['SECTION_META_H1_FORCE'] : 'UF_H1' ;
	$arParams['SECTION_META_TITLE_PROP_FORCE'] = $arParams['SECTION_META_TITLE_PROP_FORCE'] ? $arParams['SECTION_META_TITLE_PROP_FORCE'] : 'UF_TITLE' ;
	$arParams['SECTION_META_KEYWORDS_FORCE'] = $arParams['SECTION_META_KEYWORDS_FORCE'] ? $arParams['SECTION_META_KEYWORDS_FORCE'] : 'UF_KEYWORDS' ;
	$arParams['SECTION_META_DESCRIPTION_FORCE'] = $arParams['SECTION_META_DESCRIPTION_FORCE'] ? $arParams['SECTION_META_DESCRIPTION_FORCE'] : 'UF_DESCRIPTION' ;
	$APPLICATION->IncludeComponent(
		"yenisite:catalog.section_meta",
		"",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_CODE" => $iblock_section,
			"META_SPLITTER" => $arParams["SECTION_META_SPLITTER"],
			"META_TITLE" => $arParams["SECTION_META_H1"],
			"META_TITLE_FORCE" => $arParams["SECTION_META_H1_FORCE"],
			"META_TITLE_PROP" => $arParams["SECTION_META_TITLE_PROP"],
			"META_TITLE_PROP_FORCE" => $arParams["SECTION_META_TITLE_PROP_FORCE"],
			"META_KEYWORDS" => $arParams["SECTION_META_KEYWORDS"],
			"META_KEYWORDS_FORCE" => $arParams["SECTION_META_KEYWORDS_FORCE"],
			"META_DESCRIPTION" => $arParams["SECTION_META_DESCRIPTION"],
			"META_DESCRIPTION_FORCE" =>  $arParams["SECTION_META_DESCRIPTION_FORCE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
		),
		$component
	);
}?>