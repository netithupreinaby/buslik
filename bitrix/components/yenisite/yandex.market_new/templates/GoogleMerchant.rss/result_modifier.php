<?
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */
foreach ($arResult["OFFER"] as &$arOffer) {
//fill product category
	$categoryId = $arOffer["CATEGORY"];
	$arOffer["CATEGORY"] = $arResult["CATEGORIES"][$categoryId]["NAME"];
	
	while ($categoryId = $arResult["CATEGORIES"][$categoryId]["PARENT"]) {
		$arOffer["CATEGORY"] = $arResult["CATEGORIES"][$categoryId]["NAME"] . ' &gt; ' . $arOffer["CATEGORY"];
	}

//title length fix
	$arOffer['MODEL'] = substr($arOffer['MODEL'], 0, 150);

//fill product brand
	$code = $arParams["DEVELOPER"];
	$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
	$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
	$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
	unset($props);

	if (empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) && !empty($arOffer['GROUP_ID'])) {
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		unset($props);
	}

//fill product gtin
	if ($code = $arParams["GOOGLE_GTIN"]) {
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		unset($props);

		if (empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) && !empty($arOffer['GROUP_ID'])) {
			$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
			$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
			unset($props);
		}
	}
}

?>