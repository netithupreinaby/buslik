<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php');
/*if(!$image = yenisite_GetPicSrc ($arResult, 'MORE_PHOTO'))
{
	if($arResult['PROPERTIES']['CML2_LINK']['VALUE'])
	{
		$dbProduct = CIBlockElement::GetByID($arResult['PROPERTIES']['CML2_LINK']['VALUE']) ;
		if($arProduct = $dbProduct->GetNext())
		{
			$image = yenisite_GetPicSrc ($arProduct, 'MORE_PHOTO') ;
		}
	}
}
if(is_numeric($image))
	$image = CFile::GetPath($image);*/
	$image = yenisite_GetPicSrc($arResult, 'DETAIL_PICTURE');
if (CModule::IncludeModule('yenisite.resizer2')) 
{
	$image_src = CFile::GetPath($image);
	$arResult['IMAGE_SRC'] = CResizer2Resize::ResizeGD2($image_src, 3);
}
?>