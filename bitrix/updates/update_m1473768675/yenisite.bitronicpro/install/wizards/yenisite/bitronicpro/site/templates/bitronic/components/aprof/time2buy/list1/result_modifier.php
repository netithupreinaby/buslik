<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//print_r($arResult);
   
foreach($arResult["ITEMS"] as $arItem)
{
	$res = CIBlockElement::GetByID($arItem["ID"]);
	if($ar_res = $res->GetNextElement())
	{

		$arResult["ITEMS"][$arItem["ID"]]["MORE_PHOTO"] = $ar_res->GetProperty("MORE_PHOTO");	

		$ObRes=$ar_res->GetFields();
		if($ObRes["DETAIL_PICTURE"]["ID"])
			$arResult["ITEMS"][$arItem["ID"]]["MORE_PHOTO"][] = $ObRes["DETAIL_PICTURE"]["ID"];
		
	}

 
}

?>