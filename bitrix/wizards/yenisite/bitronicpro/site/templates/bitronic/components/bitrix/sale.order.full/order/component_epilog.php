<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
	
$APPLICATION->SetPageProperty("title", GetMessage('ORDER_TITLE'));
$APPLICATION->SetTitle(GetMessage('ORDER_TITLE'));
$APPLICATION->AddChainItem(GetMessage('MY_BASKET'), $APPLICATION->GetCurDir().$arParams['PATH_TO_BASKET']);
$APPLICATION->AddChainItem(GetMessage('ORDER_TITLE'));
?>