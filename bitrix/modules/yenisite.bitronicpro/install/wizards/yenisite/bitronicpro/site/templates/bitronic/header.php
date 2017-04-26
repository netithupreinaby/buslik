<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<!doctype html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<?// new string ?>
    <?IncludeTemplateLangFile(__FILE__);?>
    <?$APPLICATION->ShowHead()?>
    <title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/ui-lightness/jquery-ui-1.10.3.custom.min.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/selectbox.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/uniform.default.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/style.css");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/jquery.jgrowl.css");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/jquery.qtip.css");?>
	<?CJSCore::RegisterExt('jgrowl', array('js' => SITE_TEMPLATE_PATH."/static/js/jquery.jgrowl_minimized.js"));?>
	<?CJSCore::RegisterExt('system_script', array(
		'js' => SITE_TEMPLATE_PATH."/static/js/system_script.js", 
	  	'lang' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php', 
		'rel' => array('jquery','jgrowl'),
	));
  	CJSCore::Init(array('system_script'));?>
    <?//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.validate.js");?>
	<?//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.validate.locale/messages_ru.js");?>
	<?CJSCore::RegisterExt('validate', array(
        'js' => SITE_TEMPLATE_PATH."/static/js/jquery.validate.js", 
        'lang' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php', 
     ));
    CJSCore::Init(array('validate'));?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.qtip.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/selectbox.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery.uniform.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/jquery-ui-1.10.3.custom.min.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/core.js");?>
    <?$APPLICATION->AddHeadString('<!--[if IE]><link rel="stylesheet"  href="'.SITE_TEMPLATE_PATH.'/static/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->');?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/modernizr-1.7.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/tipped.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/spinners.min.js");?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/tipped/excanvas.js");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/js/tipped/tipped.css");?>
	<?$APPLICATION->AddHeadScript('/bitrix/js/main/cphttprequest.js');?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/ajax.js");?>
	
	<?if (CModule::IncludeModule('sale')):?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/static/js/ajax_basket.js");?>
	<?endif?>

	<script type="text/javascript">
		var SITE_ID = "<?=SITE_ID?>";
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
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");
if(!CModule::IncludeModule(CYSBitronicSettings::getModuleId()))
{
		//YOU MUST DIE!!!
}

include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');

if (!function_exists(getBitronicSettings)) {
	function getBitronicSettings($key) {
		$k = $key;
		$value = CYSBitronicSettings::getSetting($key, "");
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
//$ys_options = Array();
$ys_options = CYSBitronicSettings::getAllSettings();

$ys_n_for_ajax = COption::GetOptionString(CYSBitronicSettings::getModuleId(), 'ys_n_for_ajax', '0');

$ys_options["basket_margin_top"] = $ys_options["basket_position"]=="RIGHT"?"105":"100";

$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);

?>

<body <?if($ys_options["menu_filter"] == "left-top"):?>class='new'<?endif?> style="background: <?=$ys_options["bgcolor"]?><?if($ys_options["bg"] && $ys_options["bg"]!='/backgrounds/none'):?> url('<?=$ys_options["bg"]?>')<?endif;?><?if($ys_options["bgrepeat"]=="Y"):?> repeat<?else:?> no-repeat fixed top center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;<?endif;?>">
<hidden id="action_add2b" name="action_add2b" value="<?=htmlspecialchars($ys_options["action_add2b"]);?>"></hidden>

<div class="loader"></div>

<div class="popup" id="add_2b_popup" style="display:none;"></div>

<div class="panel"><?$APPLICATION->ShowPanel();?></div><!-- panel -->

<?/* start settings */
?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/settings.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>

<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/static/css/color-".$ys_options["color_scheme"].".css");?>
<?/* end settings */?>

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
	<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("login-popup");?>
	<?if(!$USER->IsAuthorized()):?>
		<div class="popup" id="login-popup"></div> <!--.popup-->
	<?endif;?>
	<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("login-popup");?>
<div id="wrapper" <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
<header>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/basket.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>

	<div class="box">
		<div id="top">
			<div id="user" class="user">
				<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("user");?>
				<?if($USER->IsAuthorized()):?>
					<div class="uid"> <a href="#" class="uid"><span class="ws">&#0046;</span><span class="uiname"><?=GetMessage('PERSONAL_CABINET')." ".$USER->GetFullName()." [".$USER->GetLogin()."]"?></span></a>
						<div class="cor"></div>
						
						<?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):$path=$APPLICATION->GetCurUri();?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/menu_user.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
						<?else:?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/menu_user_light.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
						<?endif?>
						
					</div><!--.uid--> 
				<?else:?>
					<a href="#" class="enter"><span class="ws">&#0046;</span><span><?=GetMessage('LOGIN')?></span></a>
				<?endif // $USER->IsAuthorized()?>
				<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("user");?>
			</div> <!--.user-->
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/menu_top.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			<?
			global $menus, $bShowTitle;
			$bShowTitle = false;
			foreach($menus as $m){  if(substr_count($APPLICATION->GetCurUri(), $m) > 0 && $m != "/") {$bShowTitle = true; }  }
			?>
		
		</div><!--#top--> 

		
			<div class="search-box<?=$ys_options["basket_position"] != 'RIGHT' ? ' search-box-right' : '';?>">
				<div class="shop-info">
					<? if (CModule::IncludeModule('yenisite.worktime')): ?>
					<div class="worktime">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/worktime.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					</div> <!--.worktime-->
					<?endif?>
                    <div id="ys-callback_phone-popup" class="popup"></div>
					<div class="phones" title="<?=GetMessage('CALL_ORDER')?>">
                        <span class="sym">&#124;</span>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
							Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/phones.php", "EDIT_TEMPLATE" => "include_areas_template.php"	), false);?>
					</div> <!--.phones--> 
				</div> <!--.shop-info-->
				<div class="search">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/search.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
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
					<div class="ys-del-to" >
						<?if ( CModule::IncludeModule('yenisite.geoip') ):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/geoip_city.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
						<?endif;?>
					</div>
					<div class="ys-del-from" >
						<?if (CModule::IncludeModule('yenisite.geoipstore') ):?>
							<?$arRes = $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/geoip_store.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
						<?endif;?>
					</div>
				</div>

			</div> <!--#logo-->

			<div style="clear:both;"></div>

	  <!--<div id="nav">-->
			<?if($ys_options["menu_filter"] == "top-left"):?>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/header/menu.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
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
