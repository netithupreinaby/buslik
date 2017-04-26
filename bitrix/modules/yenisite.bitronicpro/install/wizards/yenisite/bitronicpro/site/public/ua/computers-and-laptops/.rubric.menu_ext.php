<?
    global $APPLICATION;
    $aMenuLinksExt=$APPLICATION->IncludeComponent("yenisite:menu.ext", "", array(
	"ID" => $_REQUEST["ID"],
	"IBLOCK_TYPE" => array(
        0 => SITE_ID."_".str_replace(array("/", "-"),array("", "_"), $APPLICATION->GetCurDir()),
	),
	"IBLOCK_ID" => array(
	),
	"DEPTH_LEVEL_START" => "2",
	"DEPTH_LEVEL_FINISH" => "3",
	"DEPTH_LEVEL_SECTIONS" => "1",
	"ELEMENT_CNT_AVAILABLE" => "Y",
	"IBLOCK_TYPE_URL" => "/#IBLOCK_TYPE#/",
	"IBLOCK_TYPE_URL_REPLACE" => "",
	"ELEMENT_CNT" => "Y",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600"
	),
	false
);
    $aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>
