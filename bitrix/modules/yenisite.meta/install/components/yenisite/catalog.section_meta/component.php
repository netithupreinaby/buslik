<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
{
	return;
}

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 86400;

$title = array();
$title2 = array();
$description = array();
$keywords = array();
$arParams["SECTION_ID"] = htmlspecialchars($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = htmlspecialchars($arParams["SECTION_CODE"]);

if(!$arParams["SECTION_ID"] && !$arParams["SECTION_CODE"])
	return;

if($arParams["META_TITLE"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_TITLE"] , $title);
	$title = $title[1];
}

if($arParams["META_TITLE_PROP"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_TITLE_PROP"] , $title2);
	$title2 = $title2[1];
}

if($arParams["META_KEYWORDS"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_KEYWORDS"] , $keywords);
	$keywords = $keywords[1];
}

if($arParams["META_DESCRIPTION"])
{
	preg_match_all("|#(.*)#|U", $arParams["META_DESCRIPTION"] , $description);
	$description = $description[1];
}

$arResult["META_TITLE"] = $arParams["META_TITLE"];
$arResult["META_TITLE_PROP"] = $arParams["META_TITLE_PROP"];
$arResult["META_KEYWORDS"] = $arParams["META_KEYWORDS"];
$arResult["META_DESCRIPTION"] = $arParams["META_DESCRIPTION"];

if($this->StartResultCache(false, "META"))
{
	$arSelected = array_unique(array_merge($title2, $title, $keywords, $description));
	$arSelect = array ("UF_*") ;
	$arFilter["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
	if($arParams['SECTION_CODE'])
	{
		$arFilter['CODE'] = $arParams['SECTION_CODE'] ;
	}
	elseif(!ereg("^[0-9]*$", $arParams["SECTION_ID"]))
	{
		$arFilter["CODE"] = $arParams["SECTION_ID"];
	}
	elseif(intval($arParams['SECTION_ID']))
	{
		$arFilter["ID"] = $arParams["SECTION_ID"];
	}

	$obel = CIBlockSection::GetList(array(), $arFilter, false, $arSelect)->GetNext();

	if($obel)
	{
		$arResult["ID"] = $obel["ID"];

		$sections = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arResult['ID']);
		$arSections = array();
		$allSections = '';
		$i = 0;
		
		while($section = $sections->GetNext())
		{
			$arSections["SECTION_NAME_LEVEL{$i}"] = $section['NAME'] ;
			$allSections .= $i>0 ? $arParams['META_SPLITTER'].' '.$section['NAME'] : $section['NAME'];
			$i++ ;
		}
		
		foreach($arSelected as $item)
		{
			switch($item)
			{
				case "NAV_CHAIN":
					$value = $allSections; 
					break;
					
				case "NAME":
				case "SECTION_NAME":
					$sec = CIBlockSection::GetByID($arResult["ID"])->GetNext();
					$value = $sec["NAME"];
					break;				
				
				case "SECTION_CODE":
					$sec = CIBlockSection::GetByID($arResult["ID"])->GetNext();
					$value = $sec["CODE"];
					break;			
				
				case "IBLOCK_NAME":
					$iblock = CIBlock::GetByID($obel["IBLOCK_ID"])->GetNext();
					$value = $iblock["NAME"];
					break;
				
				default:
					if(substr_count($item, 'SECTION_NAME_LEVEL') > 0 && $arSections[$item])
						$value = $arSections[$item];
					else
						$value = $obel[$item];
					break;
			}

			$arResult["META_TITLE"] = str_replace("#".$item."#", $value , $arResult["META_TITLE"]);
			if($obel[$arParams["META_TITLE_FORCE"]])
				$arResult["META_TITLE"] = $obel[$arParams["META_TITLE_FORCE"]];
				
			$arResult["META_TITLE_PROP"] = str_replace("#".$item."#", $value , $arResult["META_TITLE_PROP"]);
			if($obel[$arParams["META_TITLE_PROP_FORCE"]])
				$arResult["META_TITLE_PROP"] = $obel[$arParams["META_TITLE_PROP_FORCE"]];
			
			$arResult["META_KEYWORDS"] = str_replace("#".$item."#", $value , $arResult["META_KEYWORDS"]);
			if($obel[$arParams["META_KEYWORDS_FORCE"]])
				$arResult["META_KEYWORDS"] = $obel[$arParams["META_KEYWORDS_FORCE"]];
				
			$arResult["META_DESCRIPTION"] = str_replace("#".$item."#", $value , $arResult["META_DESCRIPTION"]);
			if($obel[$arParams["META_DESCRIPTION_FORCE"]])
				$arResult["META_DESCRIPTION"] = $obel[$arParams["META_DESCRIPTION_FORCE"]];

			$this->SetResultCacheKeys(array(
					"ID",
					"META_TITLE",
					"META_TITLE_PROP",
					"META_KEYWORDS",
					"META_DESCRIPTION",
				));
		}
	}
	$this->IncludeComponentTemplate();
}

if($arResult["ID"])
{

	global $APPLICATION;
	
	if($arResult["META_TITLE_PROP"])			
		$APPLICATION->SetPageProperty("title", $arResult["META_TITLE_PROP"]);
	if($arResult["META_KEYWORDS"])
		$APPLICATION->SetPageProperty("keywords", $arResult["META_KEYWORDS"]);
		
	if($arResult["META_DESCRIPTION"])
		$APPLICATION->SetPageProperty("description", $arResult["META_DESCRIPTION"]);
	
	if($arResult["META_TITLE"])	
		$APPLICATION->SetTitle($arResult["META_TITLE"]);
}

return 0;
?>