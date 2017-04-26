<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult as $key => $val)
{
	$img = "";
	if ($val["DETAIL_PICTURE"] > 0)
		$img = $val["DETAIL_PICTURE"];
	elseif ($val["PREVIEW_PICTURE"] > 0)
		$img = $val["PREVIEW_PICTURE"];
	if (function_exists('yenisite_GetPicSrc'))
		$img = yenisite_GetPicSrc($val);
	if(CModule::IncludeModule("yenisite.resizer2") && intval($arParams['IMAGE_SET'])>0)
		$file['src'] = CResizer2Resize::ResizeGD2(CFile::GetPath($img), $arParams['IMAGE_SET']);
	else
		$file = CFile::ResizeImageGet($img, array('width'=>$arParams["VIEWED_IMG_WIDTH"], 'height'=>$arParams["VIEWED_IMG_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, true);

	$val["PICTURE"] = $file;
	$arResult[$key] = $val;
}
?>