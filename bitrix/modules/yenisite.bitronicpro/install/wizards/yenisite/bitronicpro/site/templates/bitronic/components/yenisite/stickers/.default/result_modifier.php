<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arResult["STICKER_TOP"] = 0;
if(CModule::IncludeModule("iblock"))
{
	$hol_id = $arParams["ELEMENT"]["PROPERTIES"]["HOLIDAY"]["VALUE"];
	if(intval($hol_id) > 0)
	{
		$res = CIBlockElement::GetByID(intval($hol_id));
		if($ar_res = $res->GetNext()){
			$arResult["HOLIDAY"] = array();
			$arResult["HOLIDAY"]["PIC"] = CFile::GetPath($ar_res['DETAIL_PICTURE']);
			$arResult["HOLIDAY"]["NAME"] = $ar_res['NAME'];
			if($arParams["WIDTH"])
			{
				$arResult["HOLIDAY"]["WIDTH"] = $arParams["WIDTH"];
			}
			elseif(CModule::IncludeModule('yenisite.resizer2'))
			{//autoresize
				$arParams["IMAGE_SET"] = $arParams["IMAGE_SET"] ? $arParams["IMAGE_SET"] : 3;
				$arParams["STICK_SCALE"] = $arParams["STICK_SCALE"] ? $arParams["STICK_SCALE"] : 0.5;
				
				$image_path = CFile::GetPath(yenisite_GetPicSrc($arParams["ELEMENT"]));
				$image_path = CResizer2Resize::ResizeGD2($image_path, $arParams["IMAGE_SET"]);
				$ximage_width = getimagesize($_SERVER["DOCUMENT_ROOT"].$image_path);
				$arResult["HOLIDAY"]["WIDTH"] = $ximage_width[0] * $arParams["STICK_SCALE"];
				/*
				$arResult["STICKER_TOP"] = intval($arResult["HOLIDAY"]["WIDTH"]);
				if($arResult["HOLIDAY"]["WIDTH"]+(30*4)>$ximage_width[0])
					$arResult["STICKER_PERCENT"] = 1;
				*/
			}

		}
	}
}
?>
