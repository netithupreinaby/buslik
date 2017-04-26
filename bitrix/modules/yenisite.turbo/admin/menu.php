<?
global $USER;
if(!$USER->IsAdmin())
	return;
	
/* Menu of the module yenisite.turbo */
	IncludeModuleLangFile(__FILE__);
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"text" => GetMessage("TURBO_MODULE_NAME"),
		"icon" => "turbo_menu_icon",
		"page_icon" => "turbo_page_icon",
		"url"  => "",
		"items_id" => "yenisite_turbo",
		"items" => array(
			array(
				"text" => GetMessage("SETS"),
				"url" => "/bitrix/admin/yci_turbo_sets.php?".bitrix_sessid_get()."&lang=".LANG,
 				"more_url" => array(
					"yci_turbo_set_edit.php",
					"yci_turbo_set_start.php",
					"yci_turbo_report.php"
				),
			),
			array(
				"text" => GetMessage("SEARCH_YM_ID"),
				"url" => "/bitrix/admin/yci_turbo_search.php?".bitrix_sessid_get()."&lang=".LANG,
				"more_url" => array(),
			),
		)
	);
	return($aMenu);	
?>
