<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?global $ys_options;?>
<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_rubric", array(
	"ROOT_MENU_TYPE" => "rubric",
	"MENU_CACHE_TYPE" => "N",
	"MENU_CACHE_TIME" => "3600",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "2",
	"CHILD_MENU_TYPE" => "left",
	"USE_EXT" => "Y",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N",
	"RESIZER2_SET" => "4",
	"THEME" => $ys_options["color_scheme"]
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
