<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'createFrame')) $this->createFrame()->begin(''); 
$APPLICATION->AddHeadString('<script>var orphus_email="'.$arParams["EMAIL"].'";</script>',true);
?>
<?$APPLICATION->AddHeadScript($this->__folder."/orphus/orphus.js");?>
<!--<script type="text/javascript" src=<?=$this->__folder."/orphus/orphus.js"?>></script>-->
<a href="http://orphus.ru" id="orphus" target="_blank"><img alt="<?=GetMessage("ORPHUS_SYSTEM");?>" src=<?=$this->__folder."/orphus/orphus.gif"?> border="0" width="125" height="115" /></a>

	
