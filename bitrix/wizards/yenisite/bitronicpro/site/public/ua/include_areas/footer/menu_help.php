<?
global $ys_options;
?>
<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_vertical", array(
	"ROOT_MENU_TYPE" => "help",
	"MENU_CACHE_TYPE" => "A",
	"MENU_CACHE_TIME" => "604800",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "2",
	"CHILD_MENU_TYPE" => "",
	"USE_EXT" => "N",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N",
	"INCLUDE_JQUERY" => "Y",
	"THEME" => $ys_options["color_scheme"],
	"SHOW_BY_CLICK" => "N"
	),
	false
);?>