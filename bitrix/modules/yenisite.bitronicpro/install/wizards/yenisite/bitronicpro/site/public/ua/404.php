<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CHTTP::SetStatus("404 Not Found");
define("ERROR_404","Y");

$APPLICATION->SetPageProperty("title", "404 Not Found");

?><input type="hidden" name="404" value="Y" /><?
$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_sitemap_top", array(
	"ROOT_MENU_TYPE" => "top",
	),
	false
);

$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_sitemap_ext", array(
	"ROOT_MENU_TYPE" => "catalog",
	"USE_EXT" => "Y",
	),
	false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

<script type="text/javascript">
 $(document).ready(
        function() {
			$("#slider").hide();
			$("#sideLeft").hide();
			}		            
	);
</script>
