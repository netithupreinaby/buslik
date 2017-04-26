<?
global $MESS;
$strPath2Lang = str_replace('\\', '/', __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
include($strPath2Lang."/install/version.php");

//require_once(dirname(__FILE__)."/../classes/general/yenisite_market.php");



Class yenisite_market extends CModule
{
        var $MODULE_ID = "yenisite.market";

        var $MODULE_VERSION;

        var $MODULE_VERSION_DATE;

        var $MODULE_NAME;

        var $MODULE_DESCRIPTION;



        function yenisite_market()
        {        		

                $arModuleVersion = array();

				$path = str_replace("\\", "/", __FILE__);

				$path = substr($path, 0, strlen($path) - strlen("/index.php"));

				include($path."/version.php");

				$this->MODULE_VERSION = $arModuleVersion["VERSION"];

				$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

				$this->PARTNER_NAME =  GetMessage("yenisite.market_PARTNER_NAME");
				$this->PARTNER_URI = GetMessage("yenisite.market_PARTNER_URI");
				$this->MODULE_NAME =  GetMessage("yenisite.market_MODULE_NAME");
				$this->MODULE_DESCRIPTION = GetMessage("yenisite.market_MODULE_DESC");

			return true;

        }

		

        function DoInstall()
        {

            
            CModule::IncludeModule("iblock");
            require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/mainpage.php");
			$SITE_ID = CMainPage::GetSiteByHost();
			
			/*  CREATE DICTIONARY OF DELIVERY   */
			$db_iblock_type = CIBlockType::GetByID("dict");
			if(!$ar_iblock_type = $db_iblock_type->Fetch())
					{
					
					   $arFields = Array(
							'ID'=>'dict',
							'SECTIONS'=>'Y',
							'IN_RSS'=>'N',
							'SORT'=>100,
							'LANG'=>Array(
								'ru'=>Array(
									'NAME'=>GetMessage("IBLOCK_TYPE_DICT"),
								)
							)
						);

						$obBlocktype = new CIBlockType;
						$res = $obBlocktype->Add($arFields);
					}
			ImportXMLFile(dirname(__FILE__).'/public_html/delivery.xml', 'dict', array($SITE_ID), "N", "N");
			ImportXMLFile(dirname(__FILE__).'/public_html/payment.xml', 'dict', array($SITE_ID), "N", "N");
			/*           ------------           */
			
            $db_iblock_type = CIBlockType::GetByID("yenisite_market");
            if(!$ar_iblock_type = $db_iblock_type->Fetch())
            {
            
               $arFields = Array(
                    'ID'=>'yenisite_market',
                    'SECTIONS'=>'N',
                    'IN_RSS'=>'N',
                    'SORT'=>100,
                    'LANG'=>Array(
                        'ru'=>Array(
                            'NAME'=>GetMessage("IBLOCK_TYPE_NAME"),
                        )
                    )
                );

                $obBlocktype = new CIBlockType;
                $res = $obBlocktype->Add($arFields);
            }


           
            $db_iblock = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));            
            if(!$ar_iblock = $db_iblock->Fetch())
            {
               
                $site_id = array();
                $rsSites = CSite::GetList($by="sort", $order="desc", Array());
                while($arSite = $rsSites->Fetch())
                    $site_id[] = $arSite["ID"];

                $arFields = Array(
                  "ACTIVE" => "Y",
                  "NAME" => GetMessage("MARKET_IBLOCK_NAME"),
                  "CODE" => "YENISITE_MARKET_ORDER",
                  "IBLOCK_TYPE_ID" => "yenisite_market",
                  "SITE_ID" => $site_id,
                  "DESCRIPTION" => GetMessage("IBLOCK_DESCRIPTION"),
                  "GROUP_ID" => Array("1"=>"D", "2"=>"R")
                 );
                 $ib = new CIBlock;
                 $ID = $ib->Add($arFields);
            }
            else
                $ID = $ar_iblock["ID"];
				
			$dop = array(                
					"ELEMENTS_NAME" => GetMessage("ELEMENTS"),
					"ELEMENT_NAME" => GetMessage("ELEMENT"),
					"ELEMENT_ADD" => GetMessage("ELEMENTADD"),
					"ELEMENT_EDIT" => GetMessage("ELEMENTEDIT"),
					"ELEMENT_DELETE" => GetMessage("ELEMENTDELETE"),
                );
		
			CIBlock::SetMessages($ID,  $dop);
			
			$ef = 'edit1--#--'.GetMessage("EF_TABNAME").'--,--NAME--#--'.GetMessage("EF_NAME").'--';
			$sl = 'ID';

            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "SITE_ID")); $pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_SITE_ID"),
                  "ACTIVE" => "Y",
                  "SORT" => "5549",
                  "CODE" => "SITE_ID",
                  "PROPERTY_TYPE" => "S",
                  "FILTRABLE" => "Y",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            } else {
              $PropID = $pro['ID'];
            }

      $ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_SITE_ID").'--';  
      $sl .= ',PROPERTY_'.$PropID;

            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "FIO")); $pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_FIO"),
                  "ACTIVE" => "Y",
                  "SORT" => "5550",
                  "CODE" => "FIO",
                  "PROPERTY_TYPE" => "S",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }			
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_FIO").'--';	
			$sl .= ',PROPERTY_'.$PropID;
			$sl .= ',DATE_CREATE';
			$sl .= ',CREATED_BY';
			
            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "EMAIL"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_EMAIL"),
                  "ACTIVE" => "Y",
                  "SORT" => "5551",
                  "CODE" => "EMAIL",
                  "PROPERTY_TYPE" => "S",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_EMAIL").'--';	
			$sl .= ',PROPERTY_'.$PropID;			
			
            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "ABOUT"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_ABOUT"),
                  "ACTIVE" => "Y",
                  "SORT" => "5553",
                  "CODE" => "ABOUT",
                  "PROPERTY_TYPE" => "S",
				  "USER_TYPE" => "HTML",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_ABOUT").'--';		
			$sl .= ',PROPERTY_'.$PropID;				

            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "PHONE"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_PHONE"),
                  "ACTIVE" => "Y",
                  "SORT" => "5552",
                  "CODE" => "PHONE",
				  "IS_REQUIRED" => 'Y',
                  "PROPERTY_TYPE" => "S",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_PHONE").'--';		
			$sl .= ',PROPERTY_'.$PropID;			
			
			$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "ITEMS"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_ITEMS"),
                  "ACTIVE" => "Y",
                  "SORT" => "5558",
				  "FILTRABLE" => "Y",
                  "CODE" => "ITEMS",
				  "MULTIPLE" => "Y",
                  "PROPERTY_TYPE" => "E",
                  "IBLOCK_ID" => $ID
                );				
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_ITEMS").'--';		
			$sl .= ',PROPERTY_'.$PropID;			
			$sl .= ',PREVIEW_TEXT';

            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "STATUS"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {

                $arFields = Array(
                  "NAME" => GetMessage("PROP_STATUS"),
                  "ACTIVE" => "Y",
                  "SORT" => "5555",
				  "FILTRABLE" => "Y",
                  "CODE" => "STATUS",
                  "PROPERTY_TYPE" => "L",
                  "IBLOCK_ID" => $ID
                  );

				$arFields["VALUES"][0] = Array(
                  "VALUE" => GetMessage("PROP_DOBAVLEN"),
                  "DEF" => "Y",
                  "SORT" => "100"
                );
				//$ef .= ',--PROPERTY_'.$PropID.'--#--'.$arFields['NAME'].'--';			
				  
                $arFields["VALUES"][1] = Array(
                  "VALUE" => GetMessage("PROP_PRINAT"),
                  "DEF" => "N",
                  "SORT" => "200"
                );

                $arFields["VALUES"][2] = Array(
                  "VALUE" => GetMessage("PROP_OPLACHEN"),
                  "DEF" => "N",
                  "SORT" => "300"
                );

                $arFields["VALUES"][3] = Array(
                  "VALUE" => GetMessage("PROP_OTPRAVLEN"),
                  "DEF" => "N",
                  "SORT" => "400"
                );

                $arFields["VALUES"][4] = Array(
                  "VALUE" => GetMessage("PROP_DOSTAVLEN"),
                  "DEF" => "N",
                  "SORT" => "500"
                );

                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_STATUS").'--';								
			$sl .= ',PROPERTY_'.$PropID;
			
			$res = CIBlock::GetList(Array(), 
				Array(
					'TYPE'=>'dict', 
					'SITE_ID'=>$SITE_ID, 
					'ACTIVE'=>'Y', 
					"CNT_ACTIVE"=>"Y", 
					"CODE"=>'delivery'
				), true
			);
			
			if($ar_res = $res->Fetch())
			{
				$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "DELIVERY_E"));$pro = 0;
				if(!$pro = $properties->GetNext())
				{
					$arFields = Array(
							  "NAME" => GetMessage("PROP_DELIVERY"),
							  "ACTIVE" => "Y",
							  "SORT" => "5555",
							  "CODE" => "DELIVERY_E",
							  "PROPERTY_TYPE" => "E",
							  "LINK_IBLOCK_ID" => $ar_res['ID'],
							  "IBLOCK_ID" => $ID
							  );
					$ibp = new CIBlockProperty;
					$PropID = $ibp->Add($arFields);
				}
			}
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_DELIVERY").'--';							
			$sl .= ',PROPERTY_'.$PropID;
			
			$res = CIBlock::GetList(Array(), 
				Array(
					'TYPE'=>'dict', 
					'SITE_ID'=>$SITE_ID, 
					'ACTIVE'=>'Y', 
					'CODE'=>'payment'
				), false
			);
			
			if($ar_res = $res->Fetch())
			{
				$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "PAYMENT_E"));$pro = 0;
				if(!$pro = $properties->GetNext())
				{
					$arFields = Array(
						"NAME" => GetMessage("PROP_PAYMENT"),
						"ACTIVE" => "Y",
						"SORT" => "5555",
						"CODE" => "PAYMENT_E",
						"PROPERTY_TYPE" => "E",
						"LINK_IBLOCK_ID" => $ar_res['ID'],
						"IBLOCK_ID" => $ID
					);
					$ibp = new CIBlockProperty;
					$PropID = $ibp->Add($arFields);
				}
			}
			
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_PAYMENT").'--';							
			$sl .= ',PROPERTY_'.$PropID;
			
			/*$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "PAYMENT"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_PAYMENT"),
                  "ACTIVE" => "Y",
                  "SORT" => "5555",
                  "CODE" => "PAYMENT",
                  "PROPERTY_TYPE" => "L",
                  "IBLOCK_ID" => $ID
                  );

				$arFields["VALUES"][0] = Array(
                  "VALUE" => GetMessage("PROP_WEBMONEY"),
                  "DEF" => "Y",
                  "SORT" => "100"
                );
				  
                $arFields["VALUES"][1] = Array(
                  "VALUE" => GetMessage("PROP_YANDEX"),
                  "DEF" => "N",
                  "SORT" => "200"
                );

                $arFields["VALUES"][2] = Array(
                  "VALUE" => GetMessage("PROP_NAL"),
                  "DEF" => "N",
                  "SORT" => "300"
                );

                $arFields["VALUES"][3] = Array(
                  "VALUE" => GetMessage("PROP_SCHET"),
                  "DEF" => "N",
                  "SORT" => "400"
                );

                $arFields["VALUES"][4] = Array(
                  "VALUE" => GetMessage("PROP_SBERBANK"),
                  "DEF" => "N",
                  "SORT" => "500"
                );
				
				$arFields["VALUES"][5] = Array(
                  "VALUE" => GetMessage("PROP_CARD"),
                  "DEF" => "N",
                  "SORT" => "500"
                );

                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_PAYMENT").'--';								
			$sl .= ',PROPERTY_'.$PropID;
            */
			$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ID, "CODE" => "AMOUNT"));$pro = 0;
            if(!$pro = $properties->GetNext())
            {
                $arFields = Array(
                  "NAME" => GetMessage("PROP_AMOUNT"),
                  "ACTIVE" => "Y",
                  "SORT" => "5556",
                  "CODE" => "AMOUNT",
				  "IS_REQUIRED" => 'N',
                  "PROPERTY_TYPE" => "S",
                  "IBLOCK_ID" => $ID
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
			if($pro) $PropID = $pro['ID'];
			$ef .= ',--PROPERTY_'.$PropID.'--#--'.GetMessage("PROP_AMOUNT").'--';		
			$sl .= ',PROPERTY_'.$PropID;
			
			
            $rsET = CEventType::GetByID("SALE_ORDER", "ru");			
            if(!$rsET->Fetch())
            {
                $et = new CEventType;
                $et->Add(array(
                        "LID"           => "ru",
                        "EVENT_NAME"    => "SALE_ORDER",
                        "NAME"          => GetMessage("EVENT_NAME"),
                        "DESCRIPTION"   => "#TEXT#, #EMAIL#"
                        ));

                $sites = array();
                $rsSites = CSite::GetList($by="sort", $order="desc", Array());
                while($arSite = $rsSites->Fetch())
                {
                  $sites[] = $arSite["ID"];
                }


                $arr["ACTIVE"] = "Y";
                $arr["EVENT_NAME"] = "SALE_ORDER";
                $arr["LID"] = $sites;
                $arr["EMAIL_FROM"] = "#DEFAULT_EMAIL_FROM#";
                $arr["EMAIL_TO"] = "#EMAIL#";                
                $arr["SUBJECT"] = GetMessage("SUBJECT");
                $arr["BODY_TYPE"] = "html";
                $arr["MESSAGE"] = "#TEXT#";

                $emess = new CEventMessage;
                $emess->Add($arr);
            }
			
			$rsET = CEventType::GetByID("SALE_ORDER_ADMIN", "ru");			
            if(!$rsET->Fetch())
            {
                $et = new CEventType;
                $et->Add(array(
                        "LID"           => "ru",
                        "EVENT_NAME"    => "SALE_ORDER_ADMIN",
                        "NAME"          => GetMessage("EVENT_NAME_ADMIN"),
                        "DESCRIPTION"   => "#TEXT#, #EMAIL#"
                        ));

                $sites = array();
                $rsSites = CSite::GetList($by="sort", $order="desc", Array());
                while($arSite = $rsSites->Fetch())
                {
                  $sites[] = $arSite["ID"];
                }


                $arr["ACTIVE"] = "Y";
                $arr["EVENT_NAME"] = "SALE_ORDER_ADMIN";
                $arr["LID"] = $sites;
                $arr["EMAIL_FROM"] = "#DEFAULT_EMAIL_FROM#";
                $arr["EMAIL_TO"] = "#EMAIL#";                
                $arr["SUBJECT"] = GetMessage("SUBJECT");
                $arr["BODY_TYPE"] = "html";
                $arr["MESSAGE"] = "#TEXT#";

                $emess = new CEventMessage;
                $emess->Add($arr);
            }
			

			$ef .= ',--PREVIEW_TEXT--#--'.GetMessage("EF_PREVIEWTEXT").'';	
			
      //Set fields on iblock element edit form
			CUserOptions::SetOption("form", "form_element_".$ID, array ( 'tabs' => $ef));
      //Set columns on iblock element list admin page
			CUserOptions::SetOption("list", "tbl_iblock_list_".md5("yenisite_market.".$ID), array ( 'columns' => $sl, 'by' => 'timestamp_x', 'order' => 'desc', 'page_size' => '20',  ));

			
            
			$t = copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_catalog.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_catalog.php');

            $t =  copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_price.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_price.php');
			
			$t =  copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_f1.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_f1.php');

      $t =  copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_price_edit.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_price_edit.php');

    // CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite", true, true);

      CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/components/bitrix", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix", true, true);

      CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/components/yenisite", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite", true, true);
			
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true, true);
			
			// ### COPY FILES FOR PLATRON ### //
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/yenisite.market/install/public_html/platron", $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/tools/yenisite.market/platron", true, true);
			
			// ### COPY FILES FOR ROBOKASSA ### //
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/yenisite.market/install/public_html/roboxchange", $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/tools/yenisite.market/roboxchange", true, true);
			
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/yenisite.market/payment/roboxchange/images", $_SERVER["DOCUMENT_ROOT"]. BX_ROOT . "/images/yenisite.market/roboxchange", true, true);
			
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/yenisite.market/payment/roboxchange/robox_payment.css", $_SERVER["DOCUMENT_ROOT"]. BX_ROOT . "/css/yenisite.market/roboxchange/robox_payment.css", true, true);
			
			
			if(SITE_CHARSET == "UTF-8")			
				CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/public_html/utf8", $_SERVER["DOCUMENT_ROOT"]."/", true, true);
			else
				CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.market/install/public_html/w1251", $_SERVER["DOCUMENT_ROOT"]."/", true, true);

			/*   */			
			require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/wizard.php");
			CWizardUtil::ReplaceMacros(
				 $_SERVER["DOCUMENT_ROOT"]."/account/orders/index.php",
				 Array(
					"IBLOCK_ID" => $ID,
				 )
			);
      CWizardUtil::ReplaceMacros(
         $_SERVER["DOCUMENT_ROOT"]."/account/orders/detail.php",
         Array(
          "IBLOCK_ID" => $ID,
         )
      );

			
			
            global $DB;            
            $strSql = "CREATE TABLE IF NOT EXISTS yen_market_catalog(id INT(10) NOT NULL AUTO_INCREMENT, iblock_id INT(10) NOT NULL, `use_quantity` TINYINT(1) NOT NULL DEFAULT 0, PRIMARY KEY(id)) CHARACTER  SET utf8 COLLATE utf8_general_ci; ";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
            $strSql = "CREATE TABLE IF NOT EXISTS yen_market_catalog_properties(id INT(10) NOT NULL AUTO_INCREMENT, code VARCHAR(50) NOT NULL, PRIMARY KEY(id)) CHARACTER  SET utf8 COLLATE utf8_general_ci; ";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
            $strSql = "CREATE TABLE IF NOT EXISTS yen_market_catalog_price(id INT(10) NOT NULL AUTO_INCREMENT, code VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, groups VARCHAR(255), base VARCHAR(3), PRIMARY KEY(id)) CHARACTER  SET utf8 COLLATE utf8_general_ci; ";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
			
			$strSql = "SELECT * from yen_market_catalog_price";
            $res = $DB->Query($strSql, false, $err_mess.__LINE__);
			if(!$res->Fetch())
			{
				$strSql = "INSERT INTO yen_market_catalog_price(name, code, base, groups) values('".GetMessage("PRICE_NAME")."','PRICE_BASE', 'Y','1#2')";
				$res = $DB->Query($strSql, false, $err_mess.__LINE__);
			}
			
				$t = copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_import_list.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_import_list.php');
				$t = copy($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/yenisite.market/admin/yci_market_import.php', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/yci_market_import.php');
				
				CopyDirFiles(dirname(__FILE__)."/install/components/market.yandex_market", $_SERVER["DOCUMENT_ROOT"]."/yenisite/components/market.yandex_market", true, true); 	
				
				global $DB;
				
				$strSql = "
					CREATE TABLE IF NOT EXISTS `yen_market_import_profile` (
														  `id` smallint(10) NOT NULL AUTO_INCREMENT,
														  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
														  `file` varchar(200) CHARACTER SET utf8 NOT NULL,
														  `iblock_type` varchar(50) CHARACTER SET utf8 NOT NULL,
														  `iblock_id` smallint(10) NOT NULL,
														  `delimiter` varchar(50) CHARACTER SET utf8 NOT NULL,
														  `first_names` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `metki` varchar(300) CHARACTER SET utf8 NOT NULL,
														  `first_names2` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `fields_type` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `file_fields` varchar(1000) CHARACTER SET utf8 NOT NULL,
														  `PATH2IMAGE_FILES` varchar(100) CHARACTER SET utf8 NOT NULL,
														  `PATH2PROP_FILES` varchar(100) CHARACTER SET utf8 NOT NULL,
														  `IMAGE_RESIZE` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `outFileAction` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `inFileAction` varchar(2) CHARACTER SET utf8 NOT NULL,
														  `max_execution_time` int(10) NOT NULL,
														  `data` text CHARACTER SET utf8 NOT NULL,
														  PRIMARY KEY (`id`)
				) CHARACTER  SET utf8 COLLATE utf8_general_ci;";
				
				$res = $DB->Query($strSql, false, $err_mess.__LINE__);	
			
            RegisterModule($this->MODULE_ID);
			
			// ############################################################### //
			// ### ADD IBLOCK catalog AND 1c_catalog IN MARKET'S CATALOGS  ### //
			// ############################################################### //
			CModule::IncludeModule(MODULE_ID);
			
			$res = CIBlock::GetList(
				Array(),
				Array(
					'ACTIVE'=>'Y', 
					'CODE'=>array('catalog','1c_catalog')
				), false
			);
			
			while($ar_res = $res->Fetch())
			{			
				$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$ar_res['ID'], "CODE" => "PRICE_BASE"));
				if(!$properties->GetNext())
				{
					$arFields = Array(
					  "NAME" => GetMessage("PRICE_NAME"),
					  "ACTIVE" => "Y",
					  "SORT" => "5555",
					  "CODE" => "PRICE_BASE",
					  "PROPERTY_TYPE" => "N",
					  "IBLOCK_ID" => $ar_res['ID']
					  );
					$ibp = new CIBlockProperty;
					$PropID = $ibp->Add($arFields);
				}
				
				$strSql = "INSERT INTO yen_market_catalog(iblock_id) values(".$ar_res['ID'].")";
				$DB->Query($strSql, false, $err_mess.__LINE__);
			}
			// ############################################################### //
			
			LocalRedirect('/bitrix/admin/yci_market_f1.php?lang=ru');

        }

        function DoUninstall()
        {           
            UnRegisterModule($this->MODULE_ID);            

        }


}

?>

