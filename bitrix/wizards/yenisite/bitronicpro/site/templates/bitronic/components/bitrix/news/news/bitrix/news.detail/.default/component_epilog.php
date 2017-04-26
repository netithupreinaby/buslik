<?
global $APPLICATION;
$APPLICATION->AddChainItem($arResult["NAME"]);
$APPLICATION->SetTitle($arResult["NAME"]);
$APPLICATION->SetPageProperty('title', $arResult["NAME"]);

?>