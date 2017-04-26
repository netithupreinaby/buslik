<?
    global $APPLICATION;
    $aMenuLinksExt=$APPLICATION->IncludeComponent("yenisite:menu.ext", "", array(
	"ID" => $_REQUEST["ID"],
	"IBLOCK_TYPE" => array(
		0 => "catalog_%",
	),
	"IBLOCK_TYPE_MASK" => array( 
		0 => "catalog_%", 
		1 => SITE_ID."_%", 
		),
	"IBLOCK_ID" => array(
	),
	"DEPTH_LEVEL_START" => "1",
	"DEPTH_LEVEL_FINISH" => "3",
	"IBLOCK_TYPE_URL" => "/#IBLOCK_TYPE#/",
	"IBLOCK_TYPE_URL_REPLACE" => "",
	"HIDE_ELEMENT" => "N",
	"ELEMENT_CNT" => "Y",
	"ELEMENT_CNT_AVAILABLE" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600000",
	"IBLOCK_TYPE_SORT_FIELD" => "name",
	"IBLOCK_TYPE_SORT_ORDER" => "asc",
	"IBLOCK_SORT_FIELD" => "sort",
	"IBLOCK_SORT_ORDER" => "asc",
	"SECTION_SORT_FIELD" => "sort",
	"SECTION_SORT_ORDER" => "asc",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	),
	false
);
    $aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>

