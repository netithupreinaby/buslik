<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return false ;
	
define("YS_PATH_TO_COMPONENT", '/bitrix/components/yenisite/catalog.accessories/settings/') ;

global $arComponentParameters;
$ar_unset_parameters = array('SET_TITLE', 'COMPARE_PROPERTY_CODE', 'LINE_ELEMENT_COUNT', 'LIST_META_KEYWORDS', 'LIST_META_DESCRIPTION', 'LIST_BROWSER_TITLE', 'ELEMENT_SORT_FIELD', 'ELEMENT_SORT_ORDER', 'DETAIL_PROPERTY_CODE', 'DETAIL_META_KEYWORDS', 'DETAIL_META_DESCRIPTION', 'DETAIL_BROWSER_TITLE',) ;
foreach($ar_unset_parameters as $param_code)
	unset($arComponentParameters['PARAMETERS'][$param_code]) ;
$ar_unset_parameters_groups = array('AJAX_SETTINGS', 'TOP_SETTINGS') ;
foreach($ar_unset_parameters_groups as $group)
	unset($arComponentParameters['GROUPS'][$group]) ;
foreach($arComponentParameters['PARAMETERS'] as $key => $arParam)
	if(in_array($arParam['PARENT'], $ar_unset_parameters_groups))
		unset($arComponentParameters['PARAMETERS'][$key]) ;
		
$arComponentParameters["GROUPS"]["REVIEWS"]= array(
	"NAME" => GetMessage("REVIEWS_GROUP"),
	"SORT" => '4000',
);
$arComponentParameters["GROUPS"]["IBLOCKVOTE"]= array(
	"NAME" => GetMessage("IBLOCKVOTE_GROUP"),
	"SORT" => '4500',
);	
$arComponentParameters["GROUPS"]["STICKERS"]= array(
	"NAME" => GetMessage("STICKER_GROUP"),
	"SORT" => '4900',
);
$arComponentParameters["GROUPS"]["COMPLETE_SET"]= array(
	"NAME" => GetMessage("COMPLETE_SET_GROUP"),
	"SORT" => '4950',
);
$arComponentParameters["GROUPS"]["ACCESSORIES"]= array(
	"NAME" => GetMessage("ACCESSORIES_GROUP"),
	"SORT" => '5000',
);
$arComponentParameters["GROUPS"]["FOR_ORDER"]= array(
	"NAME" => GetMessage("FOR_ORDER_GROUP"),
	"SORT" => '6000',
);
$arComponentParameters["GROUPS"]["META_SECTION"]= array(
	"NAME" => GetMessage("META_SECTION_GROUP"),
	"SORT" => '7000',
);
$arComponentParameters["GROUPS"]["META_DETAIL"]= array(
	"NAME" => GetMessage("META_DETAIL_GROUP"),
	"SORT" => '8000',
);

if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$system_properties = array('SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');
	$arProperties_ALL	= array() ;
	$arProperties_LNS	= array() ; // list, number, string
	$arProperties_E		= array() ; // link element

	$dbProperties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"])));
	while ($arProperty = $dbProperties->GetNext())
	{
		if(!in_array($arProperty['CODE'], $system_properties) && strpos($arProperty['CODE'], 'TURBO_') !== 0)
		{
			$arProperties_ALL[$arProperty["CODE"]] = "[{$arProperty['CODE']}] {$arProperty['NAME']}" ;

			if(in_array($arProperty["PROPERTY_TYPE"], array("L", "N", "S")))
				$arProperties_LNS[$arProperty["CODE"]] = $arProperties_ALL[$arProperty["CODE"]] ;
			if($arProperty['PROPERTY_TYPE'] == 'E')
				$arProperties_E[$arProperty["CODE"]] = $arProperties_ALL[$arProperty["CODE"]] ;
		}
	}

	$arUF_SECTION = array() ;
	$dbUF = CUserTypeEntity::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ENTITY_ID" => "IBLOCK_". $arCurrentValues["IBLOCK_ID"]."_SECTION" ));
	while ($arUF=$dbUF->Fetch())
		$arUF_SECTION [$arUF["FIELD_NAME"]] = "[{$arUF['FIELD_NAME']}] {$arUF['FIELD_NAME']}";
}
$arPrice = array();
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) 
	{
		$arPrice["CATALOG_PRICE_{$arr['ID']}"] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
}

$list = array () ;
if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}
// STORES

$arTemplateParameters_stores = array(
        "YS_STORES_MUCH_AMOUNT" => array(
            "PARENT" => "STORE_SETTINGS",
            "NAME"   => GetMessage("YS_STORES_MUCH_AMOUNT"),
            "TYPE"   => "STRING",
            "DEFAULT" => "15"
        ),
	);
$arTemplateParameters_detail_settings = array(
		"SHOW_ANNOUNCE" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SHOW_ANNOUNCE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SHOW_ELEMENT" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SHOW_ELEMENT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SHOW_SKLAD" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SHOW_SKLAD"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SHOW_SEOBLOCK" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SHOW_SEOBLOCK"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	);
	
	
// Reviews
			
$arTemplateParameters_reviews = array();
$arReviews = array("TECH", "SITE", "YM","VK","FB","DQ");
$arReviewServices = array();
$Reviewscount= 100;
foreach($arReviews as $str) {
	$enable_text = "REVIEWS_".$str."_ENABLE";
	$sort_index = "REVIEWS_".$str."_SORT";
	$endble_def = "N";
	
	if ($str == "TECH")		{
		$sort_index = "SORT_OPTIONS";
		$endble_def = "Y";
	}
	elseif ($str == "SITE")	{	
		$sort_index = "SORT_REVIEWS";
		$endble_def = "Y";
	}

	$arTemplateParameters_reviews[$enable_text] = array(
			"NAME"	=> GetMessage($enable_text),
			"PARENT" => "REVIEWS",
			"TYPE"	=> "CHECKBOX",
			"DEFAULT" => $endble_def,
			"REFRESH" => "Y",
		);
	if($arCurrentValues[$enable_text] == "Y"):
		$arReviewServices[$str] = GetMessage($enable_text);
		$arTemplateParameters_reviews[$sort_index] = array(
					"PARENT" => "REVIEWS",
					"NAME" => GetMessage("REVIEWS_SORT"),
					"TYPE" => "STRING",
					"DEFAULT" => "".$Reviewscount,
				);
	endif;
	$Reviewscount = $Reviewscount + 100;
}

$arTemplateParameters_reviews["REVIEWS_DEFAULT"] = array(
			"PARENT" => "REVIEWS",
			"NAME"	=> GetMessage("REVIEWS_DEFAULT"),
			"TYPE"	=> "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $arReviewServices,
			"DEFAULT" => 'TECH',
		);	
	
	

	
$arTemplateParameters_iblockvote = array(
		"IBLOCK_MAX_VOTE" => array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("IBLOCK_MAX_VOTE"),
			"TYPE" => "STRING",
			"DEFAULT" => "5",
		),
		"IBLOCK_VOTE_NAMES" => array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("IBLOCK_VOTE_NAMES"),
			"TYPE" => "STRING",
			"VALUES" => array(),
			"MULTIPLE" => "Y",
			"DEFAULT" => array("1","2","3","4","5"),
			"ADDITIONAL_VALUES" => "Y",
		),
		"IBLOCK_SET_STATUS_404" => Array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("CP_BIV_SET_STATUS_404"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"DISPLAY_AS_RATING" => Array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("TP_CBIV_DISPLAY_AS_RATING"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"rating" => GetMessage("TP_CBIV_RATING"),
				"vote_avg" => GetMessage("TP_CBIV_AVERAGE"),
			),
			"DEFAULT" => "rating",
		),
);
	
// RESIZER 2.0:
//IncludeTemplateLangFile(__FILE__);
if(CModule::IncludeModule("yenisite.resizer2")){
$arTemplateParameters_resizer = array(
		"BLOCK_IMG_SMALL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BLOCK_IMG_SMALL"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 3
		),
		"BLOCK_IMG_BIG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BLOCK_IMG_BIG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 4
		),
		"LIST_IMG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("LIST_IMG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 3
		),
		"TABLE_IMG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TABLE_IMG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 5
		),
		"DETAIL_IMG_SMALL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_IMG_SMALL"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 2
		),
		"DETAIL_IMG_BIG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_IMG_BIG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
		),
		"DETAIL_IMG_ICON" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_IMG_ICON"),
			"TYPE" => "LIST",
			"VALUES" => $list,
		),
		"COMPARE_IMG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMPARE_IMG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => 3
		),
		"RESIZER_BOX" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("RESIZER_BOX"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
);
}

// Filter
$arTemplateParameters_filter_section = array(
	"FILTER_BY_QUANTITY"	=> array(
			"PARENT" => "FILTER_SETTINGS",
			"NAME"	=> GetMessage("FILTER_BY_QUANTITY"),
			"TYPE"	=> "CHECKBOX",
			"DEFAULT" => "",
		),
);

// Section sort

$arTemplateParameters_section_sort = array(
	"BLOCK_VIEW_MODE" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME"   => GetMessage("BLOCK_VIEW_MODE"),
		"TYPE"   => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "N",
		"VALUES" => Array(GetMessage('BLOCK_VIEW_MODE_POPUP')=>'popup', GetMessage('BLOCK_VIEW_MODE_NOPOPUP')=>'nopopup'),
		"DEFAULT" => "popup"
	),	
	
	"LIST_PRICE_SORT" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME"   => GetMessage("PRICE_SORT"),
		"TYPE"   => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "N",
		"VALUES" => $arPrice,
		"DEFAULT" => "CATALOG_PRICE_1"
	),		
	"DEFAULT_ELEMENT_SORT_BY" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" 	 => GetMessage('DEFAULT_ELEMENT_SORT_BY'),
		"TYPE"	 => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => Array(
				'PROPERTY_WEEK_COUNTER' => GetMessage('ELEMENT_SORT_HIT') ,
				'PRICE' => GetMessage('ELEMENT_SORT_PRICE'),
				'NAME'  => GetMessage('ELEMENT_SORT_NAME'),
				'SALE_INT' => GetMessage('ELEMENT_SORT_SALE_INT'),
				'SALE_EXT' => GetMessage('ELEMENT_SORT_SALE_EXT'),
				'SORT' => GetMessage('ELEMENT_SORT_SORT'),
				'RATING' => GetMessage('ELEMENT_SORT_RATING'),
			),
		"DEFAULT" => 'PROPERTY_WEEK_COUNTER',
	),
	"DEFAULT_ELEMENT_SORT_ORDER" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" 	 => GetMessage('DEFAULT_ELEMENT_SORT_ORDER'),
		"TYPE"	 => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => Array(
				'ASC' => GetMessage('ELEMENT_SORT_ASC'),
				'DESC' => GetMessage('ELEMENT_SORT_DESC'),
			),
		"DEFAULT" => 'DESC',
	),
);

// smart stickers

$arTemplateParameters_stickers = array(
	"STICKER_NEW" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_NEW'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '14',
	),
	"STICKER_HIT" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_HIT'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '100',
	),
	"STICKER_BESTSELLER" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '3',
	),
);
// Complete set

$arTemplateParameters_complete_set = array(
	"COMPLETE_SET_PROPERTIES" => array(
		"PARENT" => "COMPLETE_SET",
		"NAME" 	 => GetMessage('COMPLETE_SET_PROPERTIES'),
		"TYPE"	 => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"MULTIPLE" => "Y",
		"VALUES" => $arProperties_LNS
	),
	"COMPLETE_SET_DESCRIPTION" => array(
		"PARENT" => "COMPLETE_SET",
		"NAME" 	 => GetMessage('COMPLETE_SET_DESCRIPTION'),
		"TYPE"	 => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => Array(
			"PREVIEW_TEXT" => GetMessage('COMPLETE_SET_DESCRIPTION_PT'),
			"DETAIL_TEXT" => GetMessage('COMPLETE_SET_DESCRIPTION_DT'),
		),
		"DEFAULT" => "PREVIEW_TEXT"
	),
	"COMPLETE_SET_RESIZER_SET" => array(
		"PARENT" => "COMPLETE_SET",
		"NAME" 	 => GetMessage('COMPLETE_SET_RESIZER_SETS'),
		"TYPE"	 => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => $list,
		"DEFAULT" => "5"
	),
	"COMPLETE_SET_NO_INCLUDE_PRICE" => array(
		"PARENT" => "COMPLETE_SET",
		"NAME" 	 => GetMessage('COMPLETE_SET_NO_INCLUDE_PRICE'),
		"TYPE"	 => "CHECKBOX",
		"DEFAULT" => ""
	),
	"DEFAULT_VIEW" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" 	 => GetMessage('DEFAULT_VIEW'),
		"TYPE"	 => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => Array(
				'block' => GetMessage('DEFAULT_VIEW_BLOCK'),
				'list' => GetMessage('DEFAULT_VIEW_LIST'),
				'table' => GetMessage('DEFAULT_VIEW_TABLE'),
			),
		"DEFAULT" => 'block',
	),
);


// Accessories
$arTemplateParameters_accessories = array(
		"ACCESSORIES_ON"	=> array(
			"PARENT" => "ACCESSORIES",
			"NAME"	=> GetMessage("ACCESSORIES_ON"),
			"TYPE"	=> "CHECKBOX",
			"DEFAULT" => "",
		),
		"ACCESSORIES_LINK"	=> array(
			"PARENT" => "ACCESSORIES",
			"NAME"	=> GetMessage("ACCESSORIES_LINK"),
			"TYPE"	=> "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arProperties_E
		),
		"ACCESSORIES_PROPS" => array(
			"PARENT" => "ACCESSORIES",
			"NAME" => GetMessage("ACCESSORIES_PROPS"),
			"TYPE" => "CUSTOM",
			"JS_FILE" => YS_PATH_TO_COMPONENT.'settings.js',
			"JS_EVENT" => 'OnYenisiteAccessoriesSettingEdit',
			"JS_DATA" => LANGUAGE_ID.'||'.GetMessage('ACCESSORIES_DATA_SET').'||'.$arCurrentValues["IBLOCK_ID"],
		),

		"ACCESSORIES_FILTER_NAME" => array(
			"PARENT" => "ACCESSORIES",
			"NAME" => GetMessage("ACCESSORIES_FILTER_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		"ACCESSORIES_PAGE_ELEMENT_COUNT" => array(
			"PARENT" => "ACCESSORIES",
			"NAME" => GetMessage("ACCESSORIES_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
);

// META SECTION
$arTemplateParameters_meta_section = array(
	"SECTION_SHOW_DESCRIPTION" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("SECTION_SHOW_DESCRIPTION"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => ""
		),

	"SECTION_SHOW_DESCRIPTION_DOWN" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("SECTION_SHOW_DESCRIPTION_DOWN"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => ""
	),
	
	"SECTION_META_SPLITTER" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_SPLITTER"),
		"TYPE" => "STRING",
		"DEFAULT" => ",",
		),
		
	"SECTION_META_H1" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_H1"),
		"TYPE" => "STRING",
		"DEFAULT" => "#NAME#",
	),
	
	"SECTION_META_H1_FORCE" =>array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_H1_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "UF_H1",
		"VALUES" => $arUF_SECTION,
	),
	
	"SECTION_META_TITLE_PROP" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_TITLE_PROP"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_TITLE_PROP_BUY")."#NAME#",
	),
	
	"SECTION_META_TITLE_PROP_FORCE" =>array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_TITLE_PROP_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "UF_TITLE",
		"VALUES" => $arUF_SECTION,
	),		

	"SECTION_META_KEYWORDS" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_KEYWORDS"),
		"TYPE" => "STRING",
		"DEFAULT" => "#NAME#",
	),
	
	"SECTION_META_KEYWORDS_FORCE" =>array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_KEYWORDS_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "UF_KEYWORDS",
		"VALUES" => $arUF_SECTION,
	),		
	
	"SECTION_META_DESCRIPTION" => array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_DESCRIPTION"),
		"TYPE" => "STRING",
		"DEFAULT" => "#IBLOCK_NAME# #NAME#",
	),
	
	"SECTION_META_DESCRIPTION_FORCE" =>array(
		"PARENT" => "META_SECTION",
		"NAME" => GetMessage("META_DESCRIPTION_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "UF_DESCRIPTION",
		"VALUES" => $arUF_SECTION,
	),
);
// META DETAIL
$arTemplateParameters_meta_detail = array(
	"DETAIL_META_SPLITTER" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_SPLITTER"),
		"TYPE" => "STRING",
		"DEFAULT" => ",",
	),
		
	"DETAIL_META_H1" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_H1"),
		"TYPE" => "STRING",
		"DEFAULT" => "#NAME#",
	),
	
	"DETAIL_META_H1_FORCE" =>array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_H1_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "H1",
		"VALUES" => $arProperties_LNS,
	),
	
	"DETAIL_META_TITLE_PROP" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_TITLE_PROP"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_TITLE_PROP_BUY")."#NAME#",
	),
	
	"DETAIL_META_TITLE_PROP_FORCE" =>array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_TITLE_PROP_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "TITLE",
		"VALUES" => $arProperties_LNS,
	),		

	"DETAIL_META_KEYWORDS" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_KEYWORDS"),
		"TYPE" => "STRING",
		"DEFAULT" => "#NAME#, #PROPERTY_PRODUCER#",
	),
	
	"DETAIL_META_KEYWORDS_FORCE" =>array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_KEYWORDS_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "KEYWORDS",
		"VALUES" => $arProperties_LNS,
	),		
	
	"DETAIL_META_DESCRIPTION" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_DESCRIPTION"),
		"TYPE" => "STRING",
		"DEFAULT" => "#IBLOCK_NAME# #SECTION_NAME# #NAME#",
	),
	
	"DETAIL_META_DESCRIPTION_FORCE" =>array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_DESCRIPTION_FORCE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "DESCRIPTION",
		"VALUES" => $arProperties_LNS,
	),
);

$arTemplateParameters = array_merge(
	$arTemplateParameters_filter_section,
	$arTemplateParameters_stickers,
	$arTemplateParameters_section_sort,
	$arTemplateParameters_complete_set,
	$arTemplateParameters_accessories,
	$arTemplateParameters_detail_settings,
	$arTemplateParameters_resizer,
	$arTemplateParameters_meta_section,
	$arTemplateParameters_meta_detail,
	$arTemplateParameters_reviews,
	$arTemplateParameters_iblockvote,
	$arTemplateParameters_stores
);
// echo '<pre>' ; print_r($arTemplateParameters) ; echo '</pre>' ;

//Abcd
$arTemplateParameters['SET_STATUS_ABCD'] = Array(
			"PARENT" => "LIST_SETTINGS",
			"NAME" => GetMessage("SET_STATUS_ABCD"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
$arTemplateParameters['SETTINGS_HIDE'] = array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SETTINGS_HIDE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperties_ALL,
			"SIZE" => "8",
			"DEFAULT" => array('HIT','SHOW_MAIN','MORE_PHOTO','SALE','NEW','PHOTO','DESCRIPTION','WARRANTY','SIZES','KEYWORDS','TITLE','PRODUCER','COUNTRY','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK')
		);
$arTemplateParameters['HIDE_ORDER_PRICE'] = array(
			"PARENT" => "FOR_ORDER",
			"NAME" => GetMessage("HIDE_ORDER_PRICE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => ""
		);

$arTemplateParameters['FOR_ORDER_DESCRIPTION'] = array(
			"PARENT" => "FOR_ORDER",
			"NAME" => GetMessage("FOR_ORDER_DESCRIPTION"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		);
		
$arTemplateParameters['PRODUCT_PROPERTIES'] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("PRODUCT_PROPERTIES"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperties_ALL,
			"SIZE" => "4",
			"DEFAULT" => ""
		);
		
$arTemplateParameters['HIDE_BUY_IF_PROPS'] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("HIDE_BUY_IF_PROPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		);

if (CModule::IncludeModule('yenisite.geoipstore')) {
	$arComponentParameters['PARAMETERS']['PRICE_CODE']['TYPE'] = 'STRING';
	$arComponentParameters['PARAMETERS']['PRICE_CODE']['DEFAULT'] = '$prices';
	$arComponentParameters['PARAMETERS']['PRICE_CODE']['NAME'] = GetMessage("PRICE_CODES");

	unset($arComponentParameters['PARAMETERS']['PRICE_CODE']['VALUES']);
	unset($arComponentParameters['PARAMETERS']['PRICE_CODE']['MULTIPLE']);

	$arComponentParameters['PARAMETERS']['FILTER_PRICE_CODE']['TYPE'] = 'STRING';
	$arComponentParameters['PARAMETERS']['FILTER_PRICE_CODE']['DEFAULT'] = '$prices';
	$arComponentParameters['PARAMETERS']['FILTER_PRICE_CODE']['NAME'] = GetMessage("PRICE_CODES");

	unset($arComponentParameters['PARAMETERS']['FILTER_PRICE_CODE']['VALUES']);
	unset($arComponentParameters['PARAMETERS']['FILTER_PRICE_CODE']['MULTIPLE']);

	$arTemplateParameters['STORE_CODE'] = array(
		"PARENT" => "STORE_SETTINGS",
		"NAME" => GetMessage("STORE_CODE"),
		"TYPE" => "STRING",
		"DEFAULT" => '$stores',
	);
}
?>