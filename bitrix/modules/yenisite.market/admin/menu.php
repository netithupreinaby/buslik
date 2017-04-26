<?
/* Меню модуля yenisite.market */
global $USER;
if(!$USER->IsAdmin())
	return;
	
	IncludeModuleLangFile(__FILE__);
	if (!CModule::IncludeModule("iblock")) {
		$bIblock = false;
		$iblock = false;
	} else{
		$bIblock = true;
		$res = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
		$iblock = $res->GetNext();
	}

	$mess = (!$iblock ? GetMessage("ORDER_PROPERTIES").'('.GetMessage("MARKET_IBLOCK_NOT_INSTALL").')' : GetMessage("ORDER_PROPERTIES"));
	$mess = (!$bIblock ? GetMessage("ORDER_PROPERTIES").'('.GetMessage("IBLOCKS_NOT_INSTALL").')' : GetMessage("ORDER_PROPERTIES"));
	$url = (!$iblock ? '#' : "/bitrix/admin/iblock_edit.php?type=yenisite_market&tabControl_active_tab=edit2&lang=ru&ID=".$iblock["ID"]);

	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"text" => GetMessage("MODULE_NAME_MARKET"),		
		"icon" => "market_menu_icon",
        "page_icon" => "market_page_icon",
		"url"  => "",
		"items_id" => "yenisite_market",
		"items" => array(
			array(
				"text" => GetMessage("CATALOG_LIST"),
				"url" => "/bitrix/admin/yci_market_catalog.php?lang=".LANG,
			),
			array(
				"text" => $mess,
                                "url" => $url,

			),
                        array(
				"text" => GetMessage("PRICE_TYPE"),
				"url" => "/bitrix/admin/yci_market_price.php?lang=".LANG,
                                "more_url" => array(
                                        "yci_market_price_edit.php",
                                ),
                        ),
                        array(
				"text" => GetMessage("CSV_IMPORT"),
				"url" => "/bitrix/admin/yci_market_import_list.php?lang=".LANG,
                                "more_url" => array(
                                            "yci_market_import.php",
                                    ),
                        ),
						   array(
				"text" => GetMessage("F1"),
				"url" => "/bitrix/admin/yci_market_f1.php?lang=".LANG,
                                
                        ),


			),

			

	);
	return($aMenu);	
?>
