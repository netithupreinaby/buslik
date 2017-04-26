<?
IncludeModuleLangFile(__FILE__);
$aMenu = array(
	"parent_menu" => "global_menu_services",
	"text" => GetMessage("GEOIPSTORE_MODULE_NAME"),		
	"icon" => "ys-store_menu_icon",
	"page_icon" => "ys-store_page_icon",		
	"url"  => "",
	"items_id" => "yenisite_geoipstore",
	"items" => array(
		array(
			"text" => GetMessage("GEOIPSTORE_ITEMS"),
			"url" => "/bitrix/admin/ys-geoip-store-items.php?".bitrix_sessid_get()."&lang=".LANG,
			"more_url" =>array(
				"ys-geoip-store-item_edit.php"
			),
        ),
        array(
        	"text" => GetMessage("GEOIPSTORE_CURRENCIES"),
        	"url" => "/bitrix/admin/ys-geoip-store-currencies.php?".bitrix_sessid_get()."&lang=".LANG
        )
   	)
);

return($aMenu);	
?>