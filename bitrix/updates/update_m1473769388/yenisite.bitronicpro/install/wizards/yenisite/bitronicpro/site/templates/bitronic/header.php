<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<!doctype html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<?// new string ?>
    <?IncludeTemplateLangFile(__FILE__);?>
    <?$APPLICATION->ShowHead()?>
    <title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/ui-lightness/jquery-ui-1.9.2.custom.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/selectbox.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/uniform.default.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/style.css");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/jquery.jgrowl.css");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.jgrowl_minimized.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/selectbox.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.uniform.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery-ui-1.9.2.custom.min.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/core.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/system_script.js");?>
    <?$APPLICATION->AddHeadString('<!--[if IE]><link rel="stylesheet"  href="'.SITE_TEMPLATE_PATH.'/static/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->');?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/modernizr-1.7.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/tipped.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/spinners.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/excanvas.js");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/js/tipped/tipped.css");?>
	<?$APPLICATION->AddHeadScript('/bitrix/js/main/cphttprequest.js');?>

	<?if (CModule::IncludeModule('sale')):?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/ajax.js");?>
	<?endif?>

	<script type="text/javascript">
		var SITE_TEMPLATE_PATH="<?=SITE_TEMPLATE_PATH;?>";
		var T_IBLOCK_VOTE_MSG="<?=GetMessage("T_IBLOCK_VOTE_MSG");?>";
	</script>
    <!--[if IE]>
		<link rel="stylesheet"  href="<?=SITE_TEMPLATE_PATH;?>/static/css/style_ie.css" type="text/css" media="screen, projection" />
		<link rel="stylesheet"  href="<?=SITE_TEMPLATE_PATH;?>/style_ie.css" type="text/css" media="screen, projection" />
	<![endif]-->
    <!--[if lt IE 9]>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/html5.js");?>
    <![endif]-->
	<?/* <script src="http://www.zakazniki.ru/api/widget.js" type="text/javascript" charset="UTF-8"></script> */?>
</head>
<?
if (!function_exists(getBitronicSettings)) {
	function getBitronicSettings($key) {
		$k = $key;
		if ($GLOBALS["USER"]->GetID()) {
			$key .= "_UID_".$GLOBALS["USER"]->GetID();
			$value = COption::GetOptionString("yenisite.bitronicpro", $key, "");
			if (!$value)
				$value = COption::GetOptionString("yenisite.bitronicpro", $k, "");
		} else {
			$value = $GLOBALS["APPLICATION"]->get_cookie($key);
			if (!$value)
				$value = COption::GetOptionString("yenisite.bitronicpro", $k, "");
		}
		return $value;
	}
}
?>
<?
/* // for debug
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);
*/
?>
<?
global $ys_n_for_ajax;
global $USER;
global $ys_options;
$ys_options = Array();

$ys_n_for_ajax = COption::GetOptionString("yenisite.bitronicpro", 'ys_n_for_ajax', '0');

$ys_options["min"] = getBitronicSettings("MIN_MAX_MIN");
$ys_options["min"] = $ys_options["min"]?$ys_options["min"]:"1000";

$ys_options["max"]  = getBitronicSettings("MIN_MAX_MAX");
$ys_options["max"] = $ys_options["max"]?$ys_options["max"]:"1400";

$ys_options["color_scheme"] = getBitronicSettings("COLOR_SCHEME");
$ys_options["color_scheme"] = $ys_options["color_scheme"] ? $ys_options["color_scheme"] : COption::GetOptionString("yenisite.market", "color_scheme", "red");

$ys_options["basket_position"] = getBitronicSettings("BASKET_POSITION"); 
$ys_options["basket_position"] = $ys_options["basket_position"]?strtoupper($ys_options["basket_position"]):"LEFT";

$ys_options["bg"] = getBitronicSettings("BACKGROUND_IMAGE");
$ys_options["bg"] = $ys_options["bg"]?"/backgrounds/".$ys_options["bg"]:"";

$ys_options["bgcolor"] = getBitronicSettings("BACKGROUND_COLOR");
$ys_options["bgcolor"] = $ys_options["bgcolor"]?$ys_options["bgcolor"]:"#FFFFFF";

$ys_options["bgrepeat"] = getBitronicSettings("BACKGROUND_REPEAT");
$ys_options["bgrepeat"] = $ys_options["bgrepeat"]?$ys_options["bgrepeat"]:"N";

$ys_options["windowcolor"] = getBitronicSettings("WINDOW_COLOR");
$ys_options["windowcolor"] = $ys_options["windowcolor"]?$ys_options["windowcolor"]:"#FFFFFF";

$ys_options["windowborder"] = getBitronicSettings("WINDOW_BORDER");
$ys_options["windowborder"] = $ys_options["windowborder"]?$ys_options["windowborder"]:"N";

$ys_options["windowopacity"] = getBitronicSettings("WINDOW_OPACITY");
$ys_options["windowopacity"] = $ys_options["windowopacity"]?$ys_options["windowopacity"]:"1";

$ys_options["menu_filter"] = getBitronicSettings("MENU_FILTER");
$ys_options["menu_filter"] = $ys_options["menu_filter"]?$ys_options["menu_filter"]:"top-left";

$ys_options["order"] = getBitronicSettings("ORDER");
$ys_options["order"] = $ys_options["order"]?$ys_options["order"]:"full";

$ys_options["sef"] = getBitronicSettings("SEF");
$ys_options["sef"] = $ys_options["sef"]?$ys_options["sef"]:"N";

$ys_options["smart_filter"] = getBitronicSettings("SMART_FILTER");
$ys_options["smart_filter"] = $ys_options["smart_filter"]?$ys_options["smart_filter"]:"N";

$ys_options["block_view_mode"] = getBitronicSettings("BLOCK_VIEW_MODE");
$ys_options["block_view_mode"] = $ys_options["block_view_mode"]?$ys_options["block_view_mode"]:"popup";

$ys_options["tabs_index"] = getBitronicSettings("TABS_INDEX");
$ys_options["tabs_index"] = $ys_options["tabs_index"]?$ys_options["tabs_index"]:"one_slider";

$ys_options["action_add2b"] = getBitronicSettings("ACTION_ADD2B");
$ys_options["action_add2b"] = $ys_options["action_add2b"]?$ys_options["action_add2b"]:"popup_window";

$ys_options["show_element"] = getBitronicSettings("SHOW_ELEMENT");
$ys_options["show_element"] = $ys_options["show_element"] == 'Y' ? 'Y' : 'N' ;

$ys_options["basket_margin_top"] = $ys_options["basket_position"]=="RIGHT"?"105":"100";

if($ys_options["sef"] == "Y") {
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/sef-utils.js");
}
?>

<body <?if($ys_options["menu_filter"] == "left-top"):?>class='new'<?endif?> style="background: <?=$ys_options["bgcolor"]?><?if($ys_options["bg"] && $ys_options["bg"]!='/backgrounds/none'):?> url('<?=$ys_options["bg"]?>')<?endif;?><?if($ys_options["bgrepeat"]=="Y"):?> repeat<?else:?> no-repeat fixed top center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;<?endif;?>">
<hidden id="action_add2b" name="action_add2b" value="<?=htmlspecialchars($ys_options["action_add2b"]);?>"/>
<?/* start settings */?>
<?$APPLICATION->IncludeComponent("yenisite:bitronic.settings", ".default", array(
		"EDIT_SETTINGS" => array(
		)
	),
	false
);?>

<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/color-".$ys_options["color_scheme"].".css");?>
<?/* end settings */?>

<div class="loader"></div>

<div class="popup" id="add-popup" style="display:none;">
<?/*?>	<a class="close" href="javascript:void(0);"></a> <?*/?>
	<a class="close sym" href="javascript:viod(0);" title="<?=GetMessage('CLOSE')?>">&#206;</a>
	<h2><?=GetMessage('CALL_ORDER');?></h2>
	<div class="pad">		
		<?$APPLICATION->IncludeComponent("bitrix:main.feedback", "feedback", array(
			"USE_CAPTCHA" => "Y",
			"OK_TEXT" => "",
			"EMAIL_TO" => COption::GetOptionString("main","email_from",""),
			"REQUIRED_FIELDS" => array(
				0 => "NAME",
				1 => "MESSAGE",
			),
			"EVENT_MESSAGE_ID" => array(
				0 => "7",
			)
			),
			false
		);?>
	</div>
</div><!--.popup-->
<div class="popup" id="add_2b_popup" style="display:none;"></div>

<div class="panel"><?$APPLICATION->ShowPanel();?></div><!-- panel -->
<!-- #fixbody -->
<div id="fixbody">

<!-- #site -->
<div id="site" 
style="
background-color: <?=$ys_options["windowcolor"]?>;
opacity: <?=$ys_options["windowopacity"]?>;
<?if($ys_options["windowborder"]=="Y"):?>
border-radius: 8px 8px 8px 8px;
box-shadow: 0 0 10px black;
height: auto;
margin: 10px auto;
min-width: <?=$ys_options["min"]?>px;
max-width: <?=$ys_options["max"]?>px;
<?endif;?>
">
<div id="mask"></div><!--#mask-->
	<?if(!$USER->IsAuthorized()):?>
		<div class="popup" id="login-popup"> <a class="close sym" href="javascript:void(0);" title="<?=GetMessage('CLOSE')?>">&#206;</a>
			<?$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "top", Array(), false);?>
		</div> <!--.popup-->
	<?endif;?>
<div id="wrapper" <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
<header>

<?//margin?>
<?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):?>    
	<?if(substr_count($APPLICATION->GetCurUri(), '/personal/basket.php') == 0):?>
		<?
		$pto = SITE_DIR."personal/basket.php";    	    
		$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "bitronic", array(
	"PATH_TO_ORDER" => $pto,
	"COLOR_SCHEME" => $ys_options["color_scheme"],
	"NEW_FONTS" => "Y",
	"INCLUDE_JQUERY" => "N",
	"VIEW_PROPERTIES" => "Y",
	"QUANTITY_LOGIC" => "q_products",
	"CHANGE_QUANTITY" => "N",
	"CONTROL_QUANTITY" => "N",
	"IMAGE" => "MORE_PHOTO",
	"CURRENCY" => "ROUBLE_SYMBOL",
	"MARGIN_TOP" => $ys_options["basket_margin_top"],
	"MARGIN_SIDE" => "0",
	"START_FLY_PX" => "100",
	"MARGIN_TOP_FLY_PX" => "10",
	"BASKET_POSITION" => $ys_options["basket_position"],
	"RESIZER2_SET" => "5"
	),
	false
);?>	
	<?endif?>
<?endif;?>  
  
<?if(substr_count($APPLICATION->GetCurUri(), '/account/cart/') == 0):?>
	<?if(!CModule::IncludeModule('catalog') || !CModule::IncludeModule('sale')):?>
		<?$APPLICATION->IncludeComponent("yenisite:catalog.basket.small", "bitronic", array(
			"VALUTA" => GetMessage("RUB"),
			"BASKET_URL" => "/account/cart/",
			"COLOR_SCHEME" => ($ys_options["color_scheme"]=="ice"?"blue":$ys_options["color_scheme"]),
			"INCLUDE_JQUERY" => "Y",
			"CHANGE_QUANTITY" => "N",
			"IMAGE" => "MORE_PHOTO",
			"CURRENCY" => "",
			"BASKET_ICON" => "",
			"MARGIN_SIDE" => "0",
			"MARGIN_TOP" => $ys_options["basket_margin_top"],
			"START_FLY_PX" => "100",
			"MARGIN_TOP_FLY_PX" => "10",
			"BASKET_POSITION" => $ys_options["basket_position"],
			"RESIZER2_SET" => "5",
			"NEW_FONTS" => 'Y',
			),
			false
		);?>	
	<?endif?>
<?endif?>
	<div class="box">
		<div id="top">
			<div class="user">
				<?if($USER->IsAuthorized()):?>
					<div class="uid"> <a href="#" class="uid"><span class="ws">&#0046;</span><span class="uiname"><?=$USER->GetFullName()." [".$USER->GetLogin()."]"?></span></a>
						<div class="cor"></div>
						<ul class="user_menu closed">
							<a class="yen-um-close" onClick="yenisite_um_close()">&#205;</a>
							  <?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):$path=$APPLICATION->GetCurUri();?>
							<li><?if(strpos ($path,"personal/orders.php")>0):?>  <i class="sym">&#0058;</i><span><?=GetMessage('ORDER_HISTORY')?></span>
								<?else:?> <a href="<?=SITE_DIR?>personal/orders.php"><i class="sym">&#0058;</i><span><?=GetMessage('ORDER_HISTORY')?></span></a>
								<?endif;?>
							</li>
							<li><?if(strpos ($path,"personal/profiles.php")>0):?>  <i class="sym">&#0046;</i><span><?=GetMessage('USER_PROFILES')?></span>
								<?else:?> <a href="<?=SITE_DIR?>personal/profiles.php"><i class="sym">&#0046;</i><span><?=GetMessage('USER_PROFILES')?></span></a>
								<?endif;?>
							</li>
							<li><?if(strpos ($path,"personal/profile/")>0):?>  <i class="sym">&#094;</i><span><?=GetMessage('USER_SETTINGS')?></span>
								<?else:?> <a href="<?=SITE_DIR?>personal/profile/"><i class="sym">&#094;</i><span><?=GetMessage('USER_SETTINGS')?></span></a>
								<?endif;?>
							</li>
							<li><?if(strpos ($path,"personal/subscribe.php")>0):?> <i class="sym">&#0056;</i><span><?=GetMessage('SUBSCRIBE_SETTINGS')?>		</span>
								<?else:?> <a href="<?=SITE_DIR?>personal/subscribe.php"><i class="sym">&#0056;</i><span><?=GetMessage('SUBSCRIBE_SETTINGS')?></span></a>
								<?endif;?>
							</li>
							<li><a href="?logout=yes"><i class="sym fs15">&#0096;</i><span><?=GetMessage('EXIT')?></span></a></li>
							<?else:?>
							<li><a href="<?=SITE_DIR?>account/orders/"><i class="sym">&#0058;</i><span><?=GetMessage('ORDER_HISTORY')?></span></a></li>
							<li><a href="<?=SITE_DIR?>personal/profile/"><i class="sym">&#0058;</i><span><?=GetMessage('USER_SETTINGS')?></span></a></li>
							<li><a href="?logout=yes"><i class="sym fs15">&#0096;</i><span><?=GetMessage('EXIT')?></span></a></li>
							<?endif?>
						</ul>
					</div><!--.uid--> 
				<?endif // $USER->IsAuthorized()?>
				<a href="#" class="enter"><span class="ws">&#0046;</span><span><?=GetMessage('LOGIN')?></span></a>
			</div> <!--.user-->
			<?$APPLICATION->IncludeComponent("bitrix:menu", "top", Array(
				"ROOT_MENU_TYPE" => "top",	//     
				"MAX_LEVEL" => "1",	//   
				"CHILD_MENU_TYPE" => "",	//     
				"USE_EXT" => "N",	//      ._.menu_ext.php
				"MENU_CACHE_TYPE" => "A",	//  
				"MENU_CACHE_TIME" => "604800",	//   (.)
				"MENU_CACHE_USE_GROUPS" => "Y",	//   
				"MENU_CACHE_GET_VARS" => "",	//   
				),
				false
			);?>
			<?
			global $menus, $bShowTitle;
			$bShowTitle = false;
			foreach($menus as $m){  if(substr_count($APPLICATION->GetCurUri(), $m) > 0 && $m != "/") {$bShowTitle = true; }  }
			?>
		
		</div><!--#top--> 

		
			<div class="search-box<?=$ys_options["basket_position"] != 'RIGHT' ? ' search-box-right' : '';?>">
				<div class="shop-info">
					<div class="worktime">
                        <?$APPLICATION->IncludeComponent("yenisite:bitronic.worktime", "", array(
	"TIME_WORK" => "08:00-17:00",
	"TIME_WEEKEND" => "08:00-16:00",
	"LUNCH" => GetMessage("LUNCH"),
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "360000",
	"MONDAY" => "Y",
	"TUESDAY" => "Y",
	"WEDNESDAY" => "Y",
	"THURSDAY" => "Y",
	"FRIDAY" => "Y",
	"SATURDAY" => "N",
	"SUNDAY" => "N"
	),
	false
);?>
					</div> <!--.worktime-->
					<div class="phones">
                        <span class="sym">&#124;</span>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
							Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/phones.php", "EDIT_TEMPLATE" => "include_areas_template.php"	), false);?>
					</div> <!--.phones--> 
				</div> <!--.shop-info-->
				<div class="search">
					<?$APPLICATION->IncludeComponent("bitrix:search.title", "catalog_title_bitronic", array(
							"NUM_CATEGORIES" => "1",
							"TOP_COUNT" => "10",
							"ORDER" => "date",
							"USE_LANGUAGE_GUESS" => "Y",
							"CHECK_DATES" => "N",
							"SHOW_OTHERS" => "N",
							"PAGE" => SITE_DIR."search/catalog.php",
							"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
							"CATEGORY_0" => array(
							0 => "iblock_catalog_computers_and_laptops",
							1 => "iblock_catalog",
							),
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "search",
							"PRICE_CODE" => array(
							0 => "1",
							),
							"PROPERTY_CODE" => array(
							)
						),
						false
					);?>
				</div> <!--.search--> 
			</div> <!--.search-box-->
			<div class="basket-box<?=$ys_options["basket_position"] == 'RIGHT' ? ' basket-box-right' : '';?>">
				<?$APPLICATION->ShowViewContent('COMPARE_LIST');?>
			</div><!--.basket-box-->
			<div id="logo"<?=$ys_options["basket_position"] == 'RIGHT' ? 'class="logo-left"' : '';?>>
				<a href="<?=SITE_DIR;?>">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/logo.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				</a> 
				<div>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/pod_logo.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				</div>

				<div>
					<?
					global $prices, $stores;
					if (CModule::IncludeModule('sale')) {
						$prices = array(0 => 'BASE');
					} else {
						$prices = array(0 => 'PRICE_BASE');
					}

					?>
					<span class="ys-del-to" >
						<?if ( CModule::IncludeModule('yenisite.geoip') ):?>
							<?$APPLICATION->IncludeComponent("yenisite:geoip.city", "", array(
								"CACHE_TYPE" => "N",
								"CACHE_TIME" => "360000",
								"COLOR_SCHEME" => $ys_options["color_scheme"],
								"INCLUDE_JQUERY" => "N",
								"NEW_FONTS" => "Y"
								),
								false
							);?>
						<?endif;?>
					</span>
					<span class="ys-del-from" >
						<?if ( CModule::IncludeModule('yenisite.geoipstore') ):?>
							<?$arRes=$APPLICATION->IncludeComponent("yenisite:geoip.store", ".default", array(
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "360000",
	"COLOR_SCHEME" => $ys_options["color_scheme"],
	"INCLUDE_JQUERY" => "N",
	"NEW_FONTS" => "Y"
	),
	false
);
							$prices = $arRes['PRICES'];
							$stores = $arRes['STORES'];
							?>
						<?endif;?>
					</span>
				</div>

			</div> <!--#logo-->

			<div style="clear:both;"></div>

	  <!--<div id="nav">-->
			<?if($ys_options["menu_filter"] == "top-left"):?>
				<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_horizontal", array(
	"ROOT_MENU_TYPE" => "catalog",
	"MENU_CACHE_TYPE" => "A",
	"MENU_CACHE_TIME" => "604800",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "2",
	"CHILD_MENU_TYPE" => "",
	"USE_EXT" => "Y",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N",
	"INCLUDE_JQUERY" => "Y",
	"THEME" => $ys_options["color_scheme"],
	"VIEW_HIT" => "Y",
	"PRICE_CODE" => "BASE",
	"CURRENCY" => "RUB"
	),
	false
);?>
			<?endif?>
	  <!--</div>--><!--#nav--> 
	</div><!--.box--> 
</header> <!-- #header-->

<div id="middle"> <?/*closed in footer*/?>
	<?if(defined('ERROR_404') && ERROR_404 == 'Y'):?>
		<div id="container">
			<div class="content">
				<h1><?$APPLICATION->ShowTitle();?></h1>
	<?else:?>
		<?if($ys_options["menu_filter"] == "left-top"):?>
			<?if($APPLICATION->GetCurDir() != SITE_DIR):?>
				<div id="container">
				<!--<article>-->
					<div class="content">
						<div class="crumbs">
							<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(),	false );?>
						</div><!--.crumbs-->
							<?if(CSite::InDir('/personal/')):?>
								<h1><?$APPLICATION->ShowTitle();?></h1>
							<?else:?>
								<h1><?$APPLICATION->ShowTitle(false);?></h1>
							<?endif;?>
			<?else:?>
				<div id="container">
					<div class="content">
			<?endif?>	
		<?else:?>
			<div id="container">
			<?if($APPLICATION->GetCurDir() != SITE_DIR):?>
				<div class="ys_article">
					<div class="crumbs">
						<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(),	false );?>
					</div><!--.crumbs-->
					<?if(CSite::InDir('/personal/')):?>
						<h1><?$APPLICATION->ShowTitle();?></h1>
					<?else:?>
						<h1><?$APPLICATION->ShowTitle(false);?></h1>
					<?endif;?>
			<?endif?>
		<?endif;?>			
	<?endif?>
<div></div>