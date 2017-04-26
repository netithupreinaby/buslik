<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('yenisite.resizer2');
if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date); 
		list($day, $month, $year) = explode(".", $date); 
		list($hour, $minute, $second) = explode(":", $time); 
		return mktime($hour, $minute, $second, $month, $day, $year); 
	}
}
foreach($arResult["ITEMS"] as &$item) {
	$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];
	
	if(!CModule::IncludeModule('catalog')){		
		CModule::IncludeModule('yenisite.market');
		$prices = CMarketPrice::GetItemPriceValues($item["ID"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}
	
}

include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');

if($_REQUEST["ys_ms_ajax_call"] !== "y")
{

	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	{
		$arrFilter = array();
	}
	else
	{
		global $$arParams["FILTER_NAME"];
		$arrFilter = ${$arParams["FILTER_NAME"]};
		if(!is_array($arrFilter))
			$arrFilter = array();
	}

	
	$ibs = array();
	if(is_array($arParams["IBLOCK_TYPE"]))
	{
		foreach($arParams["IBLOCK_TYPE"] as $key=>$val)
		{
			if($val){
				$val = str_replace("#SITE_ID#", SITE_ID, $val);
				$res = CIBlock::GetList(array(), array("TYPE" => $val));
				while($ib = $res->GetNext())
					$ibs[] =  $ib[ID];
			}
		}
		array_unique($ibs);
	}else{
		$res = CIBlock::GetList(array(), array("TYPE" => $arParams["IBLOCK_TYPE"]));
		while($ib = $res->GetNext())
			$ibs[] =  $ib[ID];
	}

	if($_REQUEST["ys_ms_ajax_call"] === "y")
		$site_id = $_REQUEST["site_id"];
	else
		$site_id = SITE_ID;
		
	$arResult['COUNT'] = CIBlockElement::GetList(
						Array(),
						Array(
							'ACTIVE'=>'Y', 
							'SITE_ID'=>$site_id,
							'IBLOCK_ID' => $ibs,
							$arrFilter
						), 
						Array(),
						false
					);

}

?>