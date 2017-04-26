<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
	

$APPLICATION->AddChainItem(GetMessage('PROFILES'), "/personal/profiles.php");
$APPLICATION->AddChainItem($APPLICATION->GetTitle());
?>