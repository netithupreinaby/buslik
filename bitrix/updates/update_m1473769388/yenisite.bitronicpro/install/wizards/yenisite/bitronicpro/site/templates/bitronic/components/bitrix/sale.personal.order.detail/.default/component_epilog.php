<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
	
$APPLICATION->AddChainItem(GetMessage('ORDER_HISTORY'), "/personal/orders.php");
$APPLICATION->AddChainItem($APPLICATION->GetTitle());
?>