<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";


$strReturn = '';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{

		if($index > 0)
			$strReturn .= ' &rarr; ';

		$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
		if(substr_count($arResult[$index]["LINK"], '/') == 2){
			CModule::IncludeModule('iblock');
			$r = explode("/", $arResult[$index]["LINK"]);
			$r = $r[1];
			$res = CIBlockType::GetByID(''.str_replace("-", "_", $r))->Fetch();
		//$arResult[$index]["LINK"] = 'catalog_'.str_replace("-", "_", $r);
			if($res && $arResult[$index]["LINK"] != '/news/') $arResult[$index]["LINK"] = "";
		}
			if($arResult[$index]["LINK"] <> "" &&  $index < count($arResult) -1  )
				$strReturn .= '<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a>';
			else
				$strReturn .= '<span>'.$title.'</span>';
}
$strReturn .= '';
return $strReturn;
?>
