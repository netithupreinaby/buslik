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

$arSKU = false;
$boolSKU = false;
$boolCatalog = \Bitrix\Main\Loader::includeModule('catalog');
if ($boolCatalog && (isset($arCurrentValues['IBLOCK_ID']) && 0 < intval($arCurrentValues['IBLOCK_ID'])))
{
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);
}
	
$arComponentParameters["GROUPS"]["STORE_SETTINGS"]= array(
	"NAME" => GetMessage("STORE_SETTINGS")
);			
$arComponentParameters["GROUPS"]["TABS"]= array(
	"NAME" => GetMessage("TABS_GROUP"),
	"SORT" => '3500',
);		
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
$arComponentParameters["GROUPS"]["META_COMPARE"]= array(
	"NAME" => GetMessage("META_COMPARE_GROUP"),
	"SORT" => '8100',
);

if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$system_properties = array('ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY','SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');
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
	$defPrice = 'CATALOG_PRICE_1';
}
else
{
	$defPrice = 'PROPERTY_PRICE_BASE';
}

$list = array () ;
if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

$arTemplateParameters_detail_settings = array(
		/*"AJAX_FILTER" => Array(
			"PARENT" => "FILTER_SETTINGS",
			"NAME" => GetMessage("AJAX_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),*/
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
		"SHOW_SEOBLOCK" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SHOW_SEOBLOCK"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SLIDER_USE_MOUSEWHEEL" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SLIDER_USE_MOUSEWHEEL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"PARENT_PHOTO_SKU" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("PARENT_PHOTO_SKU"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	);
	
	
	
//	STORE_SETTINGS
$artemplateparameters_store_settings = array(
	"FILTER_BY_QUANTITY"	=> array(
		"PARENT" => "STORE_SETTINGS",
		"NAME"	=> GetMessage("FILTER_BY_QUANTITY"),
		"TYPE"	=> "CHECKBOX",
		"REFRESH" => "Y", //update setting kombox smart filter
		"DEFAULT" => "",
	),
	"YS_STORES_MUCH_AMOUNT" => array(
		"PARENT" => "STORE_SETTINGS",
		"NAME"   => GetMessage("YS_STORES_MUCH_AMOUNT"),
		"TYPE"   => "STRING",
		"DEFAULT" => "15"
	),
	"SHOW_SKLAD" => array(
		"PARENT" => "STORE_SETTINGS",
		"NAME" => GetMessage("SHOW_SKLAD"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	
);

if(is_array($arComponentParameters["PARAMETERS"]['HIDE_NOT_AVAILABLE'])){
	$arComponentParameters["PARAMETERS"]['HIDE_NOT_AVAILABLE']['PARENT'] = 'STORE_SETTINGS';

	$arComponentParameters["PARAMETERS"]['HIDE_NOT_AVAILABLE']['REFRESH'] = 'Y'; //update setting kombox smart filter
}
	
	
// Detail Tabs
$arTemplateParameters_tabs = array();
$arTabs = array("TECH", "REVIEWS","VIDEO","MANUAL");
$arTabList = array();
$TabsCount=100;
foreach($arTabs as $str) {
	$enable_text = "TABS_".$str."_ENABLE";
	$sort_index = "TABS_".$str."_SORT";
	$endble_def = "Y";
	if($str == "VIDEO" || $str == "MANUAL")
		$endble_def = "N";

	$arTemplateParameters_tabs[$enable_text] = array(
			"NAME"	=> GetMessage($enable_text),
			"PARENT" => "TABS",
			"TYPE"	=> "CHECKBOX",
			"DEFAULT" => $endble_def,
			"REFRESH" => "Y",
		);
	if($arCurrentValues[$enable_text] == "Y"):
		$arTabList[$str] = GetMessage($enable_text);
		$arTemplateParameters_tabs[$sort_index] = array(
					"PARENT" => "TABS",
					"NAME" => GetMessage("TABS_SORT"),
					"TYPE" => "STRING",
					"DEFAULT" => "".$TabsCount,
				);
	endif;
	$TabsCount = $TabsCount + 100;
}
	
$arTemplateParameters_tabs["TABS_DEFAULT"] = array(
	"PARENT" => "TABS",
	"NAME"	=> GetMessage("TABS_DEFAULT"),
	"TYPE"	=> "LIST",
	"MULTIPLE" => "N",
	"VALUES" => $arTabList,
	"DEFAULT" => 'TECH',
);		
	
// Reviews
			
$arTemplateParameters_reviews = array();
$arReviews = array("SITE", "YM2", "YM","MR","VK","FB","DQ");
$arReviewServices = array();
$Reviewscount=100;
foreach($arReviews as $str) {
	$enable_text = "REVIEWS_".$str."_ENABLE";
	$sort_index = "REVIEWS_".$str."_SORT";
	$endble_def = "N";
	
	if ($str == "SITE")	{	
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
		$arTemplateParameters_reviews[$sort_index] = array(
					"PARENT" => "REVIEWS",
					"NAME" => GetMessage("REVIEWS_SORT"),
					"TYPE" => "STRING",
					"DEFAULT" => "".$Reviewscount,
				);
	endif;
	$Reviewscount = $Reviewscount + 100;
}
$arTemplateParameters_reviews["PREORDER"] = Array(
	"PARENT" => "REVIEW_SETTINGS",
	"NAME" => GetMessage("REVIEWS_PREORDER"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N"
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
		"DEFAULT" => $defPrice
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
	"SHOW_SECTION_LIST" => 	array(
		"PARENT" => "SECTIONS_SETTINGS",
		"NAME" => GetMessage("SHOW_SECTION_LIST"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"EXPAND_PROPS" => 	array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("FILTER_EXPAND_PROPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"FILTER_ENABLE_EXPANSION" => 	array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("FILTER_ENABLE_EXPANSION"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"FILTER_START_EXPANDED" => 	array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("FILTER_START_EXPANDED"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"FILTER_VISIBLE_PROPS_COUNT" => 	array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("FILTER_VISIBLE_PROPS_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "5",
	),
	"FILTER_VISIBLE_ROWS_COUNT" => 	array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("FILTER_VISIBLE_ROWS_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "2",
	),
	"SHOW_ALL_WO_SECTION" => array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("SHOW_ALL_WO_SECTION"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
);
// because in bitronic use only SECTION_TOP_DEPTH = 1
unset($arComponentParameters['PARAMETERS']['SECTION_TOP_DEPTH']);


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
		"TYPE"	 => "STRING",
		"MULTIPLE" => "Y"
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

$arTemplateParameters_descr = array(
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
);

if(CModule::IncludeModule("yenisite.meta"))
{
// META SECTION
$arTemplateParameters_meta_section = array(
		
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
}
else
{
// META DETAIL
$arTemplateParameters_meta_detail = array(
	"DETAIL_META_KEYWORDS" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_KEYWORDS"),
		"TYPE" => "STRING",
		"DEFAULT" => "#NAME#, #PROPERTY_PRODUCER#",
	),
	
	"DETAIL_META_DESCRIPTION" => array(
		"PARENT" => "META_DETAIL",
		"NAME" => GetMessage("META_DESCRIPTION"),
		"TYPE" => "STRING",
		"DEFAULT" => "#IBLOCK_NAME# #SECTION_NAME# #NAME#",
	),
		
);
$arTemplateParameters_meta_section = array();
}

// META COMPARE
$arTemplateParameters_meta_compare = array(
	
	"COMPARE_META_H1" => array(
		"PARENT" => "META_COMPARE",
		"NAME" => GetMessage("META_H1"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_COMPARE_THAT_BETTER"),
	),
	
	"COMPARE_META_TITLE_PROP" => array(
		"PARENT" => "META_COMPARE",
		"NAME" => GetMessage("META_TITLE_PROP"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_COMPARE_THAT_BETTER_BUY"),
	),

	"COMPARE_META_KEYWORDS" => array(
		"PARENT" => "META_COMPARE",
		"NAME" => GetMessage("META_KEYWORDS"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_COMPARE_COMPARE"),
	),
		
	"COMPARE_META_DESCRIPTION" => array(
		"PARENT" => "META_COMPARE",
		"NAME" => GetMessage("META_DESCRIPTION"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("META_COMPARE_COMPARE"),
	),
	
);

if ($boolSKU)
{
	$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
			continue;
		$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
			$arProp['CODE'] = $arProp['ID'];
		$arAllOfferPropList[$arProp['CODE']] = $strPropName;
		if ('F' == $arProp['PROPERTY_TYPE'])
			$arFileOfferPropList[$arProp['CODE']] = $strPropName;
		if ('N' != $arProp['MULTIPLE'])
			continue;
		if (
			'L' == $arProp['PROPERTY_TYPE']
			|| 'E' == $arProp['PROPERTY_TYPE']
			|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		)
			$arTreeOfferPropList[$arProp['CODE']] = $strPropName;
	}
	$arTemplateParameters_selectbox_sku['PRODUCT_DISPLAY_MODE'] = array(
		'PARENT' => 'OFFERS_SETTINGS',
		'NAME' => GetMessage('CP_BCS_TPL_PRODUCT_DISPLAY_MODE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'Y',
		'DEFAULT' => 'D',
		'VALUES' => array(
			'D' => GetMessage('CP_BCS_TPL_DML_DEFAULT'),
			'N' => GetMessage('CP_BCS_TPL_DML_SIMPLE'),
			'SB' => GetMessage('CP_BCS_TPL_DML_EXT')
		)
	);
	if($arCurrentValues['PRODUCT_DISPLAY_MODE'] == "SB" || $arCurrentValues['PRODUCT_DISPLAY_MODE'] == "D" || empty($arCurrentValues['PRODUCT_DISPLAY_MODE']))
	{
		$arTemplateParameters_selectbox_sku['OFFER_TREE_PROPS'] = array(
			'PARENT' => 'OFFERS_SETTINGS',
			'NAME' => GetMessage('CP_BCS_TPL_OFFER_TREE_PROPS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arTreeOfferPropList
		);
	}
}
else	$arTemplateParameters_selectbox_sku = array();	
	
$arTemplateParameters = array_merge(
	$arTemplateParameters_stickers,
	$arTemplateParameters_section_sort,
	$arTemplateParameters_complete_set,
	$arTemplateParameters_accessories,
	$arTemplateParameters_detail_settings,
	$arTemplateParameters_resizer,
	$arTemplateParameters_descr,
	$arTemplateParameters_meta_section,
	$arTemplateParameters_meta_detail,
	$arTemplateParameters_meta_compare,
	$arTemplateParameters_tabs,
	$arTemplateParameters_reviews,
	$arTemplateParameters_iblockvote,
	$artemplateparameters_store_settings,
	$arTemplateParameters_selectbox_sku
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
			"DEFAULT" => array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY', 'SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating')
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
} /*else {
	foreach ($arCurrentValues['PRICE_CODE'] as $price) {
		$prices[] = $price;
	}
}*/


/* ==================== kypiVkredit START */ 
if (CModule::IncludeModule("sale") && CModule::IncludeModule("tcsbank.kupivkredit"))
{
	$db_ptype = CSalePaySystem::GetList(Array("SORT"=>"ASC"), Array());
	while ($ptype = $db_ptype->Fetch())
	{
		$arPayments[$ptype["ID"]] = "[{$ptype['ID']}] {$ptype['NAME']}" ;
	}		
	$arTemplateParameters['VKREDIT_BUTTON_ON'] = array(
				"PARENT" => "DETAIL_SETTINGS",
				"NAME" => GetMessage("VKREDIT_BUTTON_ON"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"REFRESH" => "Y",
			);	
	if($arCurrentValues['VKREDIT_BUTTON_ON'] == "Y")
	{
		$arTemplateParameters['VKREDIT_PAYMENT'] = array(
				"PARENT" => "DETAIL_SETTINGS",
				"NAME" => GetMessage("VKREDIT_PAYMENT"),
				"TYPE" => "LIST",
				"VALUES" => $arPayments,
				"DEFAULT" => "1"
			);
	}
}
/* ==================== kypiVkredit END */ 



/* ==================== Kombox smart filter */ 
if (CModule::IncludeModule("kombox.filter"))
{
	CModule::IncludeModule("yenisite.bitronic");
	CModule::IncludeModule("yenisite.bitronicpro");
	CModule::IncludeModule("yenisite.bitroniclite");

	global $ys_options;
	$ys_options = CYSBitronicSettings::getAllSettings();

	if($ys_options["smart_filter_type"] == "KOMBOX" || 1){
		$arComponentParameters["GROUPS"]["KOMBOX_FILTER_SETTINGS"]= array(
			"NAME" => GetMessage("KOMBOX_FILTER_SETTINGS_GROUP"),
			"SORT" => '10',
		);
		
		$arTemplateParameters["KOMBOX_USE_COMPOSITE_FILTER"] = array(
			"PARENT" => "KOMBOX_FILTER_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_USE_COMPOSITE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		);
		
		$arProperty = array();
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
		while ($arr=$rsProp->Fetch())
		{
			if($arr["PROPERTY_TYPE"] != "F")
				$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}

		$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
		$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
		$arProperty_Offers = array();
		if($OFFERS_IBLOCK_ID)
		{
			$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$OFFERS_IBLOCK_ID));
			while($arr=$rsProp->Fetch())
			{
				if($arr["PROPERTY_TYPE"] != "F")
					$arProperty_Offers[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
			}
		}
		
		$arTemplateParameters["KOMBOX_CLOSED_PROPERTY_CODE"] = array(
			"PARENT" => "KOMBOX_FILTER_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_CLOSED_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty,
			"ADDITIONAL_VALUES" => "Y",
		);
		$arTemplateParameters["KOMBOX_CLOSED_OFFERS_PROPERTY_CODE"] = array(
			"PARENT" => "KOMBOX_FILTER_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_CLOSED_OFFERS_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_Offers,
			"ADDITIONAL_VALUES" => "Y",
		);
		$arTemplateParameters["KOMBOX_SORT"] = array(
			"PARENT" => "KOMBOX_FILTER_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SORT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		);
		
		$arFields = array(
			'SECTIONS' => GetMessage('KOMBOX_CMP_FILTER_FIELDS_SECTIONS')
		);

		if (CModule::IncludeModule("catalog"))
		{
			$arFields['STORES'] = GetMessage('KOMBOX_CMP_FILTER_FIELDS_STORES');
			$arFields['QUANTITY'] = GetMessage('KOMBOX_CMP_FILTER_FIELDS_QUANTITY');
		}
		
		//sort fields
		if(isset($arCurrentValues['KOMBOX_SORT']) && 'Y' == $arCurrentValues['KOMBOX_SORT'])
		{
			$arTemplateParameters['KOMBOX_SORT_ORDER'] = array(
				"PARENT" => "KOMBOX_FILTER_SETTINGS",
				"NAME" => GetMessage("KOMBOX_CMP_FILTER_SORT_ORDER"),
				"TYPE" => "LIST",
				"DEFAULT" => "ASC",
				"VALUES" => array(
					"ASC" => GetMessage("KOMBOX_CMP_FILTER_SORT_ORDER_ASC"),
					"DESC" => GetMessage("KOMBOX_CMP_FILTER_SORT_ORDER_DESC")
				)
			);
		}

		//fields
		$arTemplateParameters['KOMBOX_FIELDS'] = array(
			'PARENT' => 'KOMBOX_FILTER_SETTINGS',
			'NAME' => GetMessage('KOMBOX_CMP_FILTER_FIELDS'),
			'TYPE' => 'LIST',
			'DEFAULT' => '',
			"MULTIPLE" => "Y",
			"VALUES" => $arFields,
			'REFRESH' => 'Y',
		);
		
		if (is_array($arCurrentValues['KOMBOX_FIELDS']) && count($arCurrentValues['KOMBOX_FIELDS']))
		{
			$sort = 0;
			foreach($arCurrentValues['KOMBOX_FIELDS'] as $field)
			{
				if(isset($arCurrentValues['KOMBOX_SORT']) && 'Y' == $arCurrentValues['KOMBOX_SORT'])
				{
					if(in_array($field, $arCurrentValues['KOMBOX_FIELDS']) && isset($arFields[$field]))
					{
						$arTemplateParameters['KOMBOX_SORT_'.$field] = array(
							'PARENT' => 'KOMBOX_FILTER_SETTINGS',
							'NAME' => GetMessage('KOMBOX_CMP_FILTER_SORT_'.$field),
							'TYPE' => 'STRING',
							'DEFAULT' => ++$sort*10
						);
					}
				}
				
				if($field == 'SECTIONS')
				{
					$arTemplateParameters['KOMBOX_TOP_DEPTH_LEVEL'] = array(
						'PARENT' => 'KOMBOX_FILTER_SETTINGS',
						'NAME' => GetMessage('KOMBOX_CMP_FILTER_TOP_DEPTH_LEVEL'),
						'TYPE' => 'STRING',
						'DEFAULT' => '0'
					);
				}
			}
		}
		
		if($boolCatalog)
		{
			$arStores = array();
			$rsStores = CCatalogStore::GetList(
				array(),
				array('ACTIVE' => 'Y'),
				false,
				false,
				array('ID', 'TITLE')
			);
			
			while ($arStore = $rsStores->Fetch())
			{
				$arStores[$arStore['ID']] = $arStore['TITLE'];
			}
			
			$arTemplateParameters['KOMBOX_STORES_ID'] = array(
				'PARENT' => 'KOMBOX_FILTER_SETTINGS',
				'NAME' => GetMessage('KOMBOX_CMP_FILTER_STORES_ID'),
				'TYPE' => 'LIST',
				'DEFAULT' => '',
				"MULTIPLE" => "Y",
				"VALUES" => $arStores
			);
		}
	}
}
/* ==================== Kombox smart filter */
?>