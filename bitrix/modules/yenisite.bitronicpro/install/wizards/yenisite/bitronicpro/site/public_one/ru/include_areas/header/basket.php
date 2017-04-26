<?
global $ys_options;
?>
<?//margin?>
<?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):?>    
	<?if(substr_count($APPLICATION->GetCurUri(), '/personal/') == 0):?>
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
	"RESIZER2_SET" => "5",
	"SHOW_DELAY" => "Y",
	"SHOW_NOTAVAIL" => "Y",
	"SHOW_SUBSCRIBE" => "Y"
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