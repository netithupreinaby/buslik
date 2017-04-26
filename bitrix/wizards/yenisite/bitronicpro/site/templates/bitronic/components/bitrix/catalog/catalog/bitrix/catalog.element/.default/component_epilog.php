<?
global $APPLICATION, $elementSection;
if($arResult['SECTION']["NAME"]) {

	$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
	$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect");
	foreach($arResult["SECTION"]["PATH"] as $arPath)
	{
	    if ($sef == 'Y') {
	    	$arTmp = explode('/', $arPath["~SECTION_PAGE_URL"]);

	    	if ($arch == 'multi') {
	    		$arTmp = array_slice($arTmp, 0, 3);
	    	} else {
	    		$arTmp = array_slice($arTmp, 0, 2);
	    	}

	    	$arPath["~SECTION_PAGE_URL"] = implode('/', $arTmp);
	    	$arPath["~SECTION_PAGE_URL"] .= '/' . $arPath['CODE'] . '/';
	    }

	    if($arPath["NAME"])
    		$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
	}
}
$APPLICATION->AddChainItem($arResult['NAME']);
$elementSection = $arResult["SECTION"]["ID"];

if(!CModule::IncludeModule("yenisite.meta"))
{
	$APPLICATION->SetPageProperty("title", empty($arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']) ? $arResult['NAME'] : $arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']);
	$APPLICATION->SetTitle(empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['NAME'] : $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
	$APPLICATION->SetPageProperty("keywords", $arResult['IPROPERTY_VALUES']['ELEMENT_META_KEYWORDS']);
	$APPLICATION->SetPageProperty("description", $arResult['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION']);
}
?>

<?if($arParams['RESIZER_BOX']=='Y'):?>
<div id="ys-resizer-content" style="display:none">
<?
	$parentId = (isset($arResult["PROPERTIES"]["CML2_LINK"]["VALUE"]) ? $arResult["PROPERTIES"]["CML2_LINK"]["VALUE"] : false);
	$APPLICATION->IncludeComponent("yenisite:resizer2.box", "bitronic_cloudZoom", array(
	"IBLOCK_TYPE" => $arResult["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arResult["IBLOCK_ID"],
	"ELEMENT_ID" => $arResult["ID"],
	"PARENT_ELEMENT_ID" => $parentId,
	"PROPERTY_CODE" => "MORE_PHOTO",
	"DROP_PREVIEW_DETAIL" => "N",
	"SET_DETAIL" => "2",
	"SET_BIG_DETAIL" => "1",
	"SET_SMALL_DETAIL" => "6",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "604800",
	"SHOW_DESCRIPTION" => "Y",
	"SHOW_DELAY_DETAIL" => "300",
	"HIDE_DELAY_DETAIL" => "600",
	"SOFT_FOCUS" => "false",
	"ZOOM_WIDTH" => "350",
	"ZOOM_HEIGHT" => "350",
	"POSITION" => "right",
	"ADJUST_X" => "0",
	"ADJUST_Y" => "0",
	"TINT" => "000",
	"TINT_OPACITY" => "0.5",
	"LENS_OPACITY" => "0.5",
	"SMOOTH_MOVE" => "3",
	"SHOW_TITLE" => "true",
	"TITLE_OPACITY" => "0.5"
	),
	false
);?>
</div>
<script>$(document).ready(function() {
		if($('#product_photo_<?=$arResult['ID'];?>').width()>0)
			replaceResizerBox();
		else
			$('#product_photo_<?=$arResult['ID'];?>').one('load', replaceResizerBox);
	});
	function replaceResizerBox()
	{
		$('#ys-resizer-placeholder').replaceWith($('#ys-resizer-content').removeAttr('style'));
		var stickers = $('.item_detail_pic .stick_img').children().not('#product_photo_<?=$arResult['ID'];?>').each(function(){
			var left = $(this).position().left + 'px';
			$(this).removeClass('mark').css({'left':left, 'z-index':'50'});
		});
		$('.yenisite-bigphoto').css('position','relative').append(stickers);

		$('.item_detail_pic').remove();
	}
</script>
<?endif;?>
<? $this->__parent->arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"] = $arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"];?>
<? $this->__parent->arResult["PROPERTIES"]["MAILRU_ID"]["VALUE"] = $arResult["PROPERTIES"]["MAILRU_ID"]["VALUE"];?>
