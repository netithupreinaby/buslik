<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(CModule::IncludeModule("yenisite.resizer2")){
    $arSets = CResizer2Set::GetList();
    while($arr = $arSets->Fetch())
    {
	    $list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
    }

    $arTemplateParameters = array(
		
		    "COMPARE_IMG" => array(
			    "PARENT" => "BASE",
			    "NAME" => "RESIZER2 SET",
			    "TYPE" => "LIST",
			    "VALUES" => $list,
			    "DEFAULT" => 3
		    ),
			"SETTINGS_HIDE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SETTINGS_HIDE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty,
			"SIZE" => "27",
			"DEFAULT" => array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY', 'SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating')
			
			
		),
		
    );
}
?>
