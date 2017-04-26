<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult as &$arItem)
{
	if($arItem["DEPTH_LEVEL"]==1)
	{
		$arItem["LINK"] = str_replace(SITE_ID.'_','',$arItem["LINK"]);
		$arItem["LINK"] = str_replace('catalog_','',$arItem["LINK"]);
		$arItem["LINK"] = str_replace('_','-',$arItem["LINK"]);
	}
}
?>