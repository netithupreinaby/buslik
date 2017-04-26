<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arParams['IT_IS_AJAX_CALL'] != 'Y') {
	$curParamsSerialize = serialize($arParams) ;
	$arCompareListParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "CompareListParams_{$arParams['IBLOCK_ID']}", '');
	if ($curParamsSerialize != $arCompareListParams) {
		COption::SetOptionString(CYSBitronicSettings::getModuleId(), "CompareListParams_{$arParams['IBLOCK_ID']}", $curParamsSerialize);
	}
}

$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);

// COMPARE items only in common section of depth_level=1
if ($arch != 'multi' && intval($arParams["CUR_SECTION"]["LEFT_MARGIN"]) > 0 && intval($arParams["CUR_SECTION"]["RIGHT_MARGIN"]) > 0)
{
	$obCache = new CPHPCache();
	$life_time =  (intval($arParams["CACHE_TIME"])>0) ? $arParams["CACHE_TIME"] : 2592000;
	$cache_id = "ys-compare-section-url-".$arParams["CUR_SECTION"]['ID'];
	if ($obCache->InitCache($life_time, $cache_id, 'ys-cache')) {
		$vars = $obCache->GetVars();
		$compareSec = $vars['COMPARE_SEC'];
		$arChildSect = $vars['CHILD_SECS'];
	}
	else{

		$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), array(
		  'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		  "<=LEFT_BORDER" => $arParams["CUR_SECTION"]["LEFT_MARGIN"],
		  ">=RIGHT_BORDER" => $arParams["CUR_SECTION"]["RIGHT_MARGIN"],
		  "DEPTH_LEVEL" => 2
		));

		if($arSect = $rsSect->Fetch())
		{
		   $compareSec = $arSect;
		   $rsSect = \CIBlockSection::GetList(array('left_margin' => 'asc'), array(
			  'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			  ">LEFT_BORDER" => $arSect["LEFT_MARGIN"],
			  "<RIGHT_BORDER" => $arSect["RIGHT_MARGIN"],
			  ">DEPTH_LEVEL" => $arSect["DEPTH_LEVEL"]
			));
			$arChildSect = array($arSect['ID']);
			while($arSect = $rsSect->Fetch())
			{
			   $arChildSect[] = $arSect['ID'];
			} 
		}
		
		if ($obCache->StartDataCache($life_time, $cache_id, "ys-cache")) {
			$obCache->EndDataCache(array("COMPARE_SEC" => $compareSec, "CHILD_SECS" =>  $arChildSect));
		}
	}
	
	foreach ($arResult as $key=>$val) {
		if(!in_array($val['IBLOCK_SECTION_ID'],$arChildSect))
		{	
			unset($arResult[$key]);
			if ($sef != "Y")
				unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$val['ID']]);
		}
	}
}

if ($sef == "Y") {
	$i = 0;
	foreach ($arResult as &$arElement) {
		$str .= $arElement["CODE"];

		if ($i != count($arResult) - 1) {
			$str .= "-vs-";
		}
		$i++;

		$tmp = explode('/', $arElement["DETAIL_PAGE_URL"]);
		$tmpAr = array_slice($tmp, 0, 3);
		$tmpDet = implode('/', $tmpAr);
		if ($arch != 'multi') {
		
			$arElement["DETAIL_PAGE_URL"] = $tmpDet . '/' . $arElement["CODE"] . '.html';
		} else {
			if (!empty($arElement['IBLOCK_SECTION_ID'])) {
				$arElement["DETAIL_PAGE_URL"] = yenisite_sectionUrl($arElement['IBLOCK_SECTION_ID'], $arElement['ID']) . $arElement["CODE"] . '.html';
			} else {
				$arElement["DETAIL_PAGE_URL"] = $tmpDet . '/' . $arElement["CODE"] . '.html';
			}
		}
	}

	foreach ($arResult as &$arElement) {
		$tmp = explode('/', $arElement["DETAIL_PAGE_URL"]);

		if ($arch != 'multi') {
			$tmpAr = array_slice($tmp, 0, 3);
			$tmpDet = implode('/', $tmpAr);
			$arElement["DETAIL_PAGE_URL"] = $tmpDet . '/' . $arElement["CODE"] . '.html';

			$tmp = array_slice($tmp, 0, 2);
		} else {
			$tmp = array_slice($tmp, 0, 3);
		}

		$tmp = implode('/', $tmp);

		$arElement["COMP_URL"] = $tmp . "/compare/". $str . "/";
		break;
	}
}
?>

<script type="text/javascript">
	$(document).ready(processCompareLink).ajaxComplete(processCompareLink);
	
	function processCompareLink() {
		$('.compare_list a.button_in_compare span[id^="c-"]').html('<?=GetMessage('CATALOG_COMPARE');?>');
		$('.compare_list a.button_in_compare').removeClass('button_in_compare') ;
		$('.yen_compare_pic').each(function() {
			var element_id = $(this).attr('id').replace('cadded-','');
			if ($('#c-'+element_id).length) {
				$('#c-'+element_id).parent().addClass('button_in_compare') ;
				if ($('#c-'+element_id).hasClass('frame_add_compare')) {
					$('#c-'+element_id).siblings('.ws').css('display','none');
					$('#c-'+element_id).html('<?=GetMessage('YS_IN_COMPARE_BUTTON_SHORT');?>');
				} else {
					$('#c-'+element_id).html('<?=GetMessage('YS_IN_COMPARE_BUTTON');?>');
				}
			}
		});
	}
</script>