<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$id_list = array () ;
foreach($arResult['ITEMS']['AnDelCanBuy'] as $item)
{
	if(strpos($item['PRODUCT_XML_ID'], '#')=== false)
		$id_list[] = $item['PRODUCT_ID'];
	else
	{
		// for SKU
		$arProductID = explode('#', $item['PRODUCT_XML_ID']);
		$id_list[] = $arProductID[0];
	}
}
$slider_id = "basket";
require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/ajax/accessories.php");