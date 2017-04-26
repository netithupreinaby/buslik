<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
// view
if (!empty($_REQUEST["view"])) {
	// If params is exist ( view?clear_cache=Y )
	if (strpos($_REQUEST["view"], '?') !== false) {
		$tmp = explode('?', $_REQUEST["view"]);
		$_REQUEST["view"] = $tmp[0];
	}

	if (in_array($_REQUEST["view"], array("block", "list", "table"))) {
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
		$order = 'NAME';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_EXT')
		$order = 'PROPERTY_SALE_EXT';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_INT')
		$order = 'PROPERTY_SALE_INT';
	elseif ($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SORT')
		$order = 'SORT';
	else
		$order = 'PROPERTY_WEEK_COUNTER';
}
$order = $order == 'SHOW_COUNTER' ? 'PROPERTY_WEEK_COUNTER' : $order ; // it's for compatibility with < 1.3.8 version.
$by = $by ? $by : $arParams['DEFAULT_ELEMENT_SORT_ORDER'];

if (!in_array($order, array($arParams['LIST_PRICE_SORT'], 'NAME', 'PROPERTY_SALE_EXT', 'PROPERTY_SALE_INT', 'SORT', 'PROPERTY_WEEK_COUNTER', 'SHOW_COUNTER')) ||
	!in_array($by, array('asc', 'desc', 'ASC', 'DESC'))) {
	define("ERROR_404", "Y");
} else {
	$APPLICATION->set_cookie("order", $order);
	$APPLICATION->set_cookie("by", $by);
}

// Page count
if (!empty($_REQUEST["page_count"])) {
	if (in_array($_REQUEST["page_count"], array(20, 40, 60))) {
		$page_count = htmlspecialchars($_REQUEST["page_count"]);
		$APPLICATION->set_cookie("page_count", $page_count);
	} else {
		define("ERROR_404", "Y");
	}
} else {
	$page_count = $APPLICATION->get_cookie("page_count") ? $APPLICATION->get_cookie("page_count") : 20;
}
// ---

$iblock_section = $arResult['IBLOCK_SECTION'];

global $ys_options;
$ys_options['show_left_text'] = 'Y';
$ys_options['current_dir'] = $arResult['FOLDER'] ;
$ys_options['show_help_menu'] = 'Y';
?>

<?if ($ys_options["sef"] == "Y"):?>
	<input type="hidden" name="ys-sef" value="Y" />
<?endif;?>

<?if ($arResult['SEO_DESCRIPTION'] && $arParams['SECTION_SHOW_DESCRIPTION_DOWN'] == 'N'):?>
	<div class="s_dscr">
		<?=$arResult['SEO_DESCRIPTION'];?>
	</div>
<?endif;?>

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
	    ),
	    $component
    );?>
<?endif?>

<?if ($arParams["USE_FILTER"] == "Y" && !defined("ERROR_404")):?>
	<?
	if ($arParams["SMART_FILTER"] == "Y") {
		$arFilter = array(
			"ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		);
		if (strlen($arResult["VARIABLES"]["SECTION_CODE"])>0) {
			$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
		} elseif ($arResult["VARIABLES"]["SECTION_ID"]>0) {
			$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
		}
			
		$obCache = new CPHPCache;
		if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
			$arCurSection = $obCache->GetVars();
		} else {
			$arCurSection = array();
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));
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
			$obCache->EndDataCache($arCurSection);
		}
	}
	?>
    <?if ($arParams["SMART_FILTER"] != 'Y'):?>
	    <?$APPLICATION->IncludeComponent(
		    "yenisite:catalog.filter_complete",
		    "empty",
		    Array(
			    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
			    "FILTER_NAME" => $arParams["FILTER_NAME"],
			    "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
	     		"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
			    "PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
			    "OFFERS_FIELD_CODE" => $arParams["FILTER_OFFERS_FIELD_CODE"],
			    "OFFERS_PROPERTY_CODE" => $arParams["FILTER_OFFERS_PROPERTY_CODE"],
			    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
			    "CACHE_TIME" => $arParams["CACHE_TIME"],
			    "DROP_MIN_MAX" => "Y",
			    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			    "IBLOCK_SECTION" => $arResult["VARIABLES"]["SECTION_ID"]?$arResult["VARIABLES"]["SECTION_ID"]:$arResult["VARIABLES"]["SECTION_CODE"],
				'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
		    ),
		    $component
	    );
	    ?>
	<?elseif ($arParams["SMART_FILTER"] == 'Y' && $ys_options["menu_filter"] == "top-left"):?>
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
			),
			$component
		);?>
    <?endif;?>

    <?if ($ys_options["menu_filter"] == "left-top"):?>
        <?if ($arParams["SMART_FILTER"] != 'Y'):?>
	        <?$APPLICATION->IncludeComponent(
		        "yenisite:catalog.filter_complete",
		        "bitronic_horizontal_new",
		        Array(
			        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
			        "FILTER_NAME" => $arParams["FILTER_NAME"],
			        "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
	         		"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
			        "PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
			        "OFFERS_FIELD_CODE" => $arParams["FILTER_OFFERS_FIELD_CODE"],
			        "OFFERS_PROPERTY_CODE" => $arParams["FILTER_OFFERS_PROPERTY_CODE"],
			        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
			        "CACHE_TIME" => $arParams["CACHE_TIME"],
			        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			        "DROP_MIN_MAX" => "Y",
			        "THEME" => $ys_options["color_scheme"],
			        "IBLOCK_SECTION" => $arResult["VARIABLES"]["SECTION_ID"]?$arResult["VARIABLES"]["SECTION_ID"]:$arResult["VARIABLES"]["SECTION_CODE"],
					'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
		        ),
		        $component
	        );
	        ?>
        <?else:?>
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
				),
				$component
			);?>
		<?endif;?>
    <?endif;?>
<?endif?>

<?
global $arrFilter;
$arr = array();

foreach ($arrFilter["PROPERTY"] as $k => $arrf) {
	if($arrf[0] == GetMessage('CC_BCF_ALL')) $arrFilter["PROPERTY"][$k] = array();
}

/*
if (CURRENCY_SKIP_CACHE) define("CURRENCY_SKIP_CACHE", false);
foreach ($arrFilter as $key => $val) {
	$match = array();   
	preg_match_all('/CATALOG_PRICE_(\S+)/msi', $key, $match);
	if(count($match[1][0]) > 0) {
		unset($ardopFilter[$key]);
		$dopFilter = array(
			"LOGIC" => "OR",
		);

		$obCurrency = CCurrency::GetList();
		while ($arCurrency = $obCurrency->Fetch()) {
			if ($arParams["CURRENCY_ID"] == $arCurrency['CURRENCY']) {
				$dopFilter[] = array(
					$key => $val,
					'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
				);
			} else {
				if (is_array($val)) {
					$dopFilter[] = array(
						$key => array(
						CCurrencyRates::ConvertCurrency($val[0], $arParams["CURRENCY_ID"], $arCurrency['CURRENCY']),
						CCurrencyRates::ConvertCurrency($val[1], $arParams["CURRENCY_ID"], $arCurrency['CURRENCY']),
						),
						'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
					);
				} else {
					$dopFilter[] = array(
						$key => CCurrencyRates::ConvertCurrency($val,$arParams["CURRENCY_ID"],$arCurrency['CURRENCY']),
						'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
					);
				}
			}
		}
	}
}
$arrFilter[] = $dopFilter;
*/
?>

<?if($arParams["SET_STATUS_ABCD"] == "Y"):?>
	<?$APPLICATION->IncludeComponent("yenisite:catalog.abcd", ".default", array(
		"SECTION_ID" => $iblock_section,
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FILTER_NAME" => "arrFilter",
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
		"INCLUDE_SUBSECTIONS" => "Y"
		),
		false
	);?>
<?endif;?>

    <form name="sort_form">
		<?if ($ys_options["smart_filter"] == "Y" && !empty($_REQUEST["set_filter"])):?>
			<?foreach ($_REQUEST as $k => $v):?>
				<?if (strpos($k, 'arrFilter') !== false):?>
					<input type='hidden' value='<?=$v?>' name='<?=$k?>' />
				<?endif;?>
			<?endforeach;?>
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
        <input id='order_field' type='hidden' value='<?=$order?>' name='order' />
        <input id='by_field' type='hidden' value='<?=$by?>' name='by' />
        <div class="filter">
			<div class="f_view">
			<span class="lab"><?=GetMessage('VIEW');?>:</span>
				<?if ($ys_options["sef"] == "Y"):?>
					<button title="<?=GetMessage("DEFAULT_VIEW_BLOCK"); ?>" class="button12 sym  <?=($view=='block')?"active":"";?>">&#0065;</button>
					<a href="<?=$arResult['FOLDER']?>/view-block/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>

					<button title="<?=GetMessage("DEFAULT_VIEW_LIST"); ?>" class="button12 sym  <?=($view=='list')?"active":"";?>">&#0066;</button>
					<a href="<?=$arResult['FOLDER']?>/view-list/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>

					<button title="<?=GetMessage("DEFAULT_VIEW_TABLE"); ?>" class="button12 sym  <?=($view=='table')?"active":"";?>">&#0067;</button>
					<a href="<?=$arResult['FOLDER']?>/view-table/sort-<?=$order?>-<?=$by?>/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;"></a>
				<?else:?>
					<button title="<?=GetMessage("DEFAULT_VIEW_BLOCK"); ?>" onclick="setViewField('block'); return false;" class="button12 sym  <?=($view=='block')?"active":"";?>">&#0065;</button>
					<button title="<?=GetMessage("DEFAULT_VIEW_LIST"); ?>" onclick="setViewField('list'); return false;" class="button12 sym  <?=($view=='list')?"active":"";?>">&#0066;</button>
					<button title="<?=GetMessage("DEFAULT_VIEW_TABLE"); ?>" onclick="setViewField('table'); return false;" class="button12 sym  <?=($view=='table')?"active":"";?>">&#0067;</button>
				<?endif;?>
		   </div><!---.f_pop-->
			<div class="inler">
	        <div class="f_label"><?=GetMessage('SORT');?>:</div>

				<div class="f_price">
					<span class="lab <?=($order==$arParams['LIST_PRICE_SORT'])?"active":"";?>"><?=GetMessage('PO_PRICE');?></span>
					<?if($ys_options["sef"] == "Y"):?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-<?=$arParams['LIST_PRICE_SORT']?>-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>

						<button title="<?=GetMEssage("ASCENDING"); ?>" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-<?=$arParams['LIST_PRICE_SORT']?>-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?else:?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'DESC'); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && $by=='DESC')?"active":"";?>">&#123;</button>
						<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'ASC'); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && $by=='ASC')?"active":"";?>">&#125;</button>
					<?endif;?>
				</div><!---.f_price-->
				<div class="f_name">
					<span class="lab <?=($order=='NAME')?"active":"";?>"><?=GetMessage('PO_NAME');?></span>
					<?if($ys_options["sef"] == "Y"):?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" class="button11 sym <?=($order=="NAME" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-NAME-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>

						<button title="<?=GetMEssage("ASCENDING"); ?>" class="button11 sym <?=($order=="NAME" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-NAME-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?else:?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('NAME', 'DESC'); return false;" class="button11 sym <?=($order=='NAME' && $by=='DESC')?"active":"";?>" >&#123;</button>
						<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('NAME', 'ASC'); return false;" class="button11 sym <?=($order=='NAME' && $by=='ASC')?"active":"";?>">&#125;</button>
					<?endif;?>
				</div><!---.f_name-->
				<div class="f_pop">
					<span class="lab  <?=($order=='PROPERTY_WEEK_COUNTER')?"active":"";?>"><?=GetMessage('PO_POPULAR');?></span>
					<?if($ys_options["sef"] == "Y"):?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" class="button11 sym <?=($order=="PROPERTY_WEEK_COUNTER" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-PROPERTY_WEEK_COUNTER-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>

						<button title="<?=GetMEssage("ASCENDING"); ?>" class="button11 sym <?=($order=="PROPERTY_WEEK_COUNTER" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-PROPERTY_WEEK_COUNTER-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?else:?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'DESC'); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && $by=='DESC')?"active":"";?>">&#123;</button>
						<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'ASC'); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && $by=='ASC')?"active":"";?>">&#125;</button>
					<?endif;?>
				</div><!---.f_pop-->
				<div class="f_sales">
					<span class="lab  <?=($order=='PROPERTY_SALE_INT')?"active":"";?>"><?=GetMessage('PO_SALE');?></span>
					<?if($ys_options["sef"] == "Y"):?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" class="button11 sym <?=($order=="PROPERTY_SALE_INT" && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-PROPERTY_SALE_INT-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>

						<button title="<?=GetMEssage("ASCENDING"); ?>" class="button11 sym <?=($order=="PROPERTY_SALE_INT" && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
						<a href="<?=$arResult['FOLDER']?>/view-<?=$view?>/sort-PROPERTY_SALE_INT-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?else:?>
						<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('PROPERTY_SALE_INT', 'DESC'); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && $by=='DESC')?"active":"";?>">&#123;</button>
						<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('PROPERTY_SALE_INT', 'ASC'); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && $by=='ASC')?"active":"";?>">&#125;</button>
					<?endif;?>
				</div><!---.f_sales-->

	        <!--<form name="view_form">-->

	        <input id='view_field' type='hidden' value='<?=$view?>' name='view' />
			<?if($pagen>0):?>
				<input id='PAGEN_field' type='hidden' value='<?=$pagen?>' name='<?=$pagen_key?>' />
			<?endif;?>	
			</div>

	        <!--</form>-->

        </div><!--.filter-->
    </form>
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
if(((isset($arrFilter['>CATALOG_QUANTITY']) || isset($arrFilter['<=CATALOG_QUANTITY']))&& count($arrFilter) ==1 || count($arrFilter) == 0) || $arParams["CACHE_FILTER"] =='Y')
	$arParams["CACHE_FILTER"] = 'Y';
else
	$arParams["CACHE_FILTER"] = 'N';
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
 		"PROPERTY_CODE" =>$arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => "Y",
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
		
		"SHOW_ALL_WO_SECTION" => "Y",
		
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
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
		
		'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
		//
		"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT'],
		"DEFAULT_VIEW" => $arParams['DEFAULT_VIEW'],
		"AUTO_SLIDE" => $arParams['AUTO_SLIDE'],
		"DELAY_SLIDE" => $arParams['DELAY_SLIDE'],

		'STORE_CODE' => $arParams["STORE_CODE"],
	),
	$component
);
?>
<?$this->SetViewTarget('filter');?>
<?if($arParams["USE_FILTER"] == "Y" && !defined("ERROR_404")):?>
	<?if($ys_options["menu_filter"] == "top-left"):?>		
		<?if ($arParams["SMART_FILTER"] != 'Y'):?>	
			<?$APPLICATION->IncludeComponent(
				"yenisite:catalog.filter_complete",
				"bitronic",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
					"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
					"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
					"OFFERS_FIELD_CODE" => $arParams["FILTER_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["FILTER_OFFERS_PROPERTY_CODE"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"DROP_MIN_MAX" => "Y",
					"IBLOCK_SECTION" => $iblock_section,
					"THEME" => $ys_options["color_scheme"],
					'FILTER_BY_QUANTITY' => $arParams['FILTER_BY_QUANTITY'],
				),
				$component
			);
			?>
		<?else:?>
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
					"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
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
if($iblock_section)
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