<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule('catalog')) return;	

$wizard =& $this->GetWizard();
$architect = $wizard->GetVar("architect", true);
$redaction = $wizard->GetVar("redaction", true);

if($architect != 'multi') return;	

$arFieldsUp = array(

			  "FIELDS" => array(
									"CODE" => array(
										"IS_REQUIRED" => "Y",
										"DEFAULT_VALUE" => array(
											"UNIQUE" => "Y",
											"TRANSLITERATION" => "Y",
											"TRANS_LEN" => 100,
											"TRANS_CASE" => "L",
											"TRANS_SPACE" => "-",
											"TRANS_OTHER" => "-",
											"TRANS_EAT" => "Y"
										)
									),
									"SECTION_CODE" => array(
										"IS_REQUIRED" => "Y",
										"DEFAULT_VALUE" => array(
											"UNIQUE" => "Y",
											"TRANSLITERATION" => "Y",
											"TRANS_LEN" => 100,
											"TRANS_CASE" => "L",
											"TRANS_SPACE" => "-",
											"TRANS_OTHER" => "-",
											"TRANS_EAT" => "Y"
										)
									),
									"PREVIEW_PICTURE" => array(
                                                "IS_REQUIRED" => "N",
                                                "DEFAULT_VALUE" => Array
                                                    (
                                                        "FROM_DETAIL" => "Y",
                                                        "SCALE" => "Y",
                                                        "WIDTH" => 160,
                                                        "HEIGHT" => 160,
                                                        "IGNORE_ERRORS" => "Y",
                                                        "METHOD" => "resample",
                                                        "COMPRESSION" => 95,
                                                    )

                                            ),
									"DETAIL_PICTURE" => array(
                                                "IS_REQUIRED" => "N",
                                                "DEFAULT_VALUE" => Array
                                                    (
                                                        "SCALE" => "Y",
                                                        "WIDTH" => 800,
                                                        "HEIGHT" => 600,
                                                        "IGNORE_ERRORS" => "Y",
                                                        "METHOD" => "resample",
                                                        "COMPRESSION" => 95,
                                                    )

                                            )                                            
									
								)

);
	
if(!CModule::IncludeModule("iblock"))
	return;

if(!CModule::IncludeModule("catalog"))
	return;
	
if(!CModule::IncludeModule("sale"))
	return;

if(SITE_CHARSET == "UTF-8") {
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/csv/iblocks-utf.csv";
} else {
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/csv/iblocks.csv";
}

/*$arUrlRewrite = array(); 
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}*/
	
WizardServices::IncludeServiceLang("demo_data.php", "ru");	

$handle = @fopen($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
		list($type, $name, $code) = explode(";", $buffer);
		
$type = trim($type);
$type = str_replace("catalog",WIZARD_SITE_ID,$type);
$name = trim($name);
$code = trim($code);
		
			$res = CIBlock::GetList(array(), array("TYPE" => $type, "CODE" => $code))->Fetch();
			if(!$res){
			
				$ib = new CIBlock;
				$arFields = Array(
				  "ACTIVE" => "Y",
				  "NAME" => $name,
				  "CODE" => str_replace("_", "-", $code),
				  "LIST_PAGE_URL" => WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-","",str_replace("_", "-", $type))."/".str_replace("_", "-", $code)."/",
				  "SECTION_PAGE_URL" => "", //"/".str_replace("catalog_","",$iblocktype)."/".$code_new."/#CODE#/",
				  "DETAIL_PAGE_URL" => WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-","", str_replace("_", "-", $type))."/".str_replace("_", "-", $code)."/#ELEMENT_CODE#.html",
				  "IBLOCK_TYPE_ID" => $type,
				  "SITE_ID" => Array(WIZARD_SITE_ID),
				  //"DESCRIPTION" => $DESCRIPTION,
				  "DESCRIPTION_TYPE" => 'text',
				  //"XML_ID" => $xmlid
				  );
				  
				  $ID = $ib->Add($arFields);				  
				    
					$ibu = new CIBlock;
					$ibu->Update($ID, $arFieldsUp);

				  
				  CIBlock::SetPermission($ID, Array("2"=>"R"));
				  CModule::IncludeModule('catalog');
				  CCatalog::Add(array("IBLOCK_ID" => $ID, "YANDEX_EXPORT" => "Y"));			
				//echo "Создаем инфоблок {$type}:{$code}[{$name}]";
				
				
				  $ibp = new CIBlockProperty;
				  $ibp->Add($arFields);

				  /* Строка */            
				  $arProp = Array("NAME" => GetMessage('SIZES'), "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "SIZES", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage("PROP_11") , "ACTIVE" => "Y", "SORT" => "8500", "CODE" => "H1", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage("PROP_15") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "WEEK", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);
				  
					 /* Число */
				  //$arProp = Array("NAME" => "Наличие по Красноярску", "XML_ID" => "94458132-c4a3-11e0-acdd-001d7200ea3f",  "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "QUANTITY_KRSK", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
				  //$ibp->Add($arProp);
				  $arProp = Array("NAME" => GetMessage("PROP_14") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "WEEK_COUNTER", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);	  

				  /* Список */
				  $arProp = Array("NAME" => GetMessage("PROP_12") , "ACTIVE" => "Y", "SORT" => "10500", "CODE" => "YML", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage("PROP_13") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "FOR_ORDER", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);
			  
				  $arProp = Array("NAME" => GetMessage('NEW'), "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "NEW", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);

				  $arProp = Array("NAME" => GetMessage('HIT'), "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "HIT", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage('BESTSELLER'), "ACTIVE" => "Y", "SORT" => "9001", "CODE" => "BESTSELLER", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage("SALE_INT") , "ACTIVE" => "Y", "SORT" => "9002", "CODE" => "SALE_INT", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);	  
				  
				  $arProp = Array("NAME" => GetMessage("SALE_EXT") , "ACTIVE" => "Y", "SORT" => "9003", "CODE" => "SALE_EXT", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);	  
				  
				  
				  $arProp = Array("NAME" => GetMessage('SALE'), "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "SALE", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);
				  
				  $arProp = Array("NAME" => GetMessage('SHOW_MAIN'), "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "SHOW_MAIN", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
				  $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
				  $ibp->Add($arProp);

				  /* Файл */
				  $arProp = Array("NAME" =>GetMessage('PHOTO'), "MULTIPLE"=>"Y", "ACTIVE" => "Y", "SORT" => "11000", "CODE" => "MORE_PHOTO", "PROPERTY_TYPE" => "F", "IBLOCK_ID" => $ID);
				  $ibp->Add($arProp);          
				  
				  $res1 = CIBlock::GetList(array(), array("CODE" => "producer"))->Fetch();

				  /*REVIEWS*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("REVIEWS_YM"), 'CODE' => 'TURBO_YANDEX_LINK', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14101');
				  $ibp->Add($arProp);	
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("REVIEWS_MR"), 'CODE' => 'MAILRU_ID', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14102');
				  $ibp->Add($arProp);
				  
				  /*ARTICLE*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("ARTICLE"), 'CODE' => 'ARTICLE', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14200');
				  $ibp->Add($arProp);	
				  
				  /*HOLIDAY*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("HOLIDAY"), 'CODE' => 'HOLIDAY', 'PROPERTY_TYPE' => 'E', 'SORT'=>'14300');
					$res = CIBlock::GetList(Array(), Array('TYPE'=>'dict', "CODE"=>'holiday'), true);
					if($ar_res = $res->Fetch())
						$arProp["LINK_IBLOCK_ID"] = $ar_res['ID'];
				  $ibp->Add($arProp);
					
				  /*SERVICE*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("SERVICE"), 'CODE' => 'SERVICE', 'PROPERTY_TYPE' => 'E', 'SORT'=>'14350', "MULTIPLE"=>"Y");
					$res = CIBlock::GetList(Array(), Array('TYPE'=>'dict', "CODE"=>'service'), true);
					if($ar_res = $res->Fetch())
						$arProp["LINK_IBLOCK_ID"] = $ar_res['ID'];
				  $ibp->Add($arProp);
				  
				  /*VIDEO*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("VIDEO"), 'CODE' => 'VIDEO', 'PROPERTY_TYPE' => 'S', 'MULTIPLE' => 'Y', 'MULTIPLE_CNT' => '2', 'SORT'=>'14400');
				  $ibp->Add($arProp);

				  /*3D MODEL*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("3DMODEL"), 'CODE' => 'ID_3D_MODEL', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14500');
				  $ibp->Add($arProp);
				  
				  /*MANUAL*/
				  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("MANUAL"), 'CODE' => 'MANUAL', 'PROPERTY_TYPE' => 'F', 'SORT'=>'14500', "MULTIPLE"=>"Y", "WITH_DESCRIPTION"=>"Y");
				  $ibp->Add($arProp);
				  
				  /* привязка к элементам в виде списка */
				 // $arProp = Array("NAME" => "Производитель", "MULTIPLE"=>"N", "ACTIVE" => "Y", "SORT" => "20000", "CODE" => "PRODUCER", "PROPERTY_TYPE" => "E",  "USER_TYPE" => "Elist",   "IBLOCK_ID" => $ID, "LINK_IBLOCK_ID" => $res1[ID]);
				  $arProp = Array("NAME" =>GetMessage('PRODUCER'), "MULTIPLE"=>"N", "ACTIVE" => "Y", "SORT" => "20000", "CODE" => "PRODUCER", "PROPERTY_TYPE" => "E", "USER_TYPE" => "EList", "IBLOCK_ID" => $ID, "LINK_IBLOCK_ID" => $res1[ID]);
				  $ibp->Add($arProp);
			  
				  $res1 = CIBlock::GetList(array(), array("CODE" => "countries"))->Fetch();
			   
				  $arProp = Array("NAME" =>GetMessage('COUNTRY'), "MULTIPLE"=>"N", "ACTIVE" => "Y", "SORT" => "20000", "CODE" => "COUNTRY", "PROPERTY_TYPE" => "E", "USER_TYPE" => "EList", "IBLOCK_ID" => $ID, "LINK_IBLOCK_ID" => $res1[ID]);
				  $ibp->Add($arProp);
				  
				  create_dir_and_index($type, $code, $name, $ID, $arUrlRewrite);
				  
			}
        
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

function create_dir_and_index ($iblock_type, $iblock_code, $iblock_name, $iblock_id, $arUrlRewrite)
{
	/*$arNewUrlRewrite = array(
		array(
			"CONDITION"	=>	"#^".WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/".str_replace("_", "-", $iblock_code)."/#",
			"RULE"	=>	"",
			"ID"	=>	"bitrix:catalog",
			"PATH"	=>	 WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/".str_replace("_", "-", $iblock_code)."/",
		),
	);

	foreach ($arNewUrlRewrite as $arUrl)
	{
		if (!in_array($arUrl, $arUrlRewrite))
		{
			CUrlRewriter::Add($arUrl);
		}
	}*/

	mkdir($_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type)), 0755);

    mkdir($_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/".str_replace("_", "-", $iblock_code), 0755);

	
$index =	'<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("'.$iblock_name.'");?>
<?$APPLICATION->IncludeComponent("bitrix:catalog", "catalog", array(
	"IBLOCK_TYPE" => "'.str_replace('-','_',$iblock_type).'",
	"IBLOCK_ID" => "'.$iblock_id.'",
	"BLOCK_IMG_SMALL" => "3",
	"BLOCK_IMG_BIG" => "4",
	"LIST_IMG" => "3",
	"TABLE_IMG" => "5",
	"DETAIL_IMG_SMALL" => "2",
	"DETAIL_IMG_BIG" => "7",
	"DETAIL_IMG_ICON" => "6",
	"COMPARE_IMG" => "4",
	"BASKET_URL" => "/personal/basket.php",
	"ACTION_VARIABLE" => "action",
	"PRODUCT_ID_VARIABLE" => "id",
	"SECTION_ID_VARIABLE" => "SECTION_ID",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "'.WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type)).'/'.str_replace("_", "-", $iblock_code).'/",	
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"USE_FILTER" => "Y",
	"FILTER_NAME" => "arrFilter",
	"FILTER_FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"FILTER_PROPERTY_CODE" => array(		
		0 => "PRODUCER",
		1 => "COUNTRY",
	),
	"FILTER_PRICE_CODE" => array(
		0 => "BASE",
	),
	"USE_REVIEW" => "Y",
	"MESSAGES_PER_PAGE" => "10",
	"USE_CAPTCHA" => "Y",
	"REVIEW_AJAX_POST" => "N",
	"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
	"FORUM_ID" => "1",
	"URL_TEMPLATES_READ" => "",
	"SHOW_LINK_TO_FORUM" => "Y",
	"POST_FIRST_MESSAGE" => "N",
	"PREORDER" => "N",
	"USE_COMPARE" => "Y",
	"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
	"COMPARE_FIELD_CODE" => array(
		0 => "NAME",
		1 => "",
	),
	"COMPARE_PROPERTY_CODE" => array(
		0 => "COUNTRY",
		1 => "PRODUCER",
		2 => "",
	),
	"COMPARE_ELEMENT_SORT_FIELD" => "sort",
	"COMPARE_ELEMENT_SORT_ORDER" => "asc",
	"DISPLAY_ELEMENT_SELECT_BOX" => "N",
	"PRICE_CODE" => array(
		0 => "BASE",
		1 => "WHOLESALE",
		2 => "RETAIL",
	),
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"SHOW_TOP_ELEMENTS" => "Y",
	"TOP_ELEMENT_COUNT" => "9",
	"TOP_LINE_ELEMENT_COUNT" => "3",
	"TOP_ELEMENT_SORT_FIELD" => "sort",
	"TOP_ELEMENT_SORT_ORDER" => "asc",
	"TOP_PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"PAGE_ELEMENT_COUNT" => "20",
	"LINE_ELEMENT_COUNT" => "3",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"LIST_PROPERTY_CODE" => array(
		0 => "COUNTRY",
		1 => "PRODUCER",
		2 => "",
	),
	"INCLUDE_SUBSECTIONS" => "Y",
	"LIST_META_KEYWORDS" => "-",
	"LIST_META_DESCRIPTION" => "-",
	"LIST_BROWSER_TITLE" => "-",
	"DETAIL_PROPERTY_CODE" => array(		
		1 => "PRODUCER",
		2 => "COUNTRY",
	),
	"DETAIL_META_KEYWORDS" => "-",
	"DETAIL_META_DESCRIPTION" => "-",
	"DETAIL_BROWSER_TITLE" => "-",
	"LINK_IBLOCK_TYPE" => "",
	"LINK_IBLOCK_ID" => "",
	"LINK_PROPERTY_SID" => "",
	"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
	"USE_ALSO_BUY" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "Товары",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "Y",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "Y",
	"AJAX_OPTION_ADDITIONAL" => "",
	"SEF_URL_TEMPLATES__SEF" => array(
		"sections" => "",
		"section" => "",
		"element" => "#ELEMENT_CODE#.html",
		"compare" => "compare.php?action=#ACTION_CODE#",
	),
"VARIABLE_ALIASES" => array("SECTION_ID" => "SECTION_ID","ELEMENT_ID" => "ELEMENT_ID",)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';

$section = '
<?
$sSectionName = "'.str_replace('"',"", $iblock_name).'";
$arDirProperties = Array(
	"title"	=> "'.$iblock_name.'"
);
?>
';

$arIBType = CIBlockType::GetByIDLang($iblock_type, 'ru');


 
 $filename = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/".str_replace("_", "-", $iblock_code)."/index.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $index);
 fclose($fh);
 
 $filename = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/".str_replace("_", "-", $iblock_code)."/.section.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $section);
 fclose($fh);

 
  $section2 = '
	<?
	$sSectionName = "'.$arIBType["NAME"].'";
	$arDirProperties = array(	
		"title"	=> "'.$iblock_name.'"
	);
	?>
';

$index2 = '<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
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
	"THEME" => $ys_options["color_scheme"],
	"RESIZER2_SET" => "7"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
';
 
  $filename = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/index.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $index2);
 fclose($fh);
 
 $filename = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/.section.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $section2);
 fclose($fh);
 
 
 $rubric ='<?
    global $APPLICATION;
    $aMenuLinksExt=$APPLICATION->IncludeComponent("yenisite:menu.ext", "", array(
	"ID" => $_REQUEST["ID"],
	//"IBLOCK_TYPE" => array(
    //    0 => "catalog_".str_replace(array("/", "-"),array("", "_"), $APPLICATION->GetCurDir()),
	//),
	"IBLOCK_TYPE" => "'.str_replace('-','_',$iblock_type).'",
	"IBLOCK_ID" => array(
	),
	"DEPTH_LEVEL_START" => "2",
	"DEPTH_LEVEL_FINISH" => "3",
	"IBLOCK_TYPE_URL" => "/#IBLOCK_TYPE#/",
	"IBLOCK_TYPE_URL_REPLACE" => "",
	"SECTION_ELEMENT_CNT" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600"
	),
	false
);
    $aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>
 ';
 
 $filename = $_SERVER["DOCUMENT_ROOT"].WIZARD_SITE_DIR.str_replace(WIZARD_SITE_ID."-", "", str_replace("_", "-", $iblock_type))."/.rubric.menu_ext.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $rubric);
 fclose($fh);
 
 
/* 
 $filename = $_SERVER["DOCUMENT_ROOT"]."/".str_replace("catalog-", "", str_replace("_", "-", $iblock_type))."/.section.php";
 $fh = fopen($filename, "w+");
 fwrite($fh, $section2);
 fclose($fh);
 */
 


}



	
?>
