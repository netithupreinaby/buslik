<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div id="icq-status">
        <img src="<?=$arParams["IMG_PATH"];?>" alt="" style="vertical-align: text-bottom;"> <span><?=$arParams["ICQ_ID"];?>: <?=$arParams["ICQ_NAME"];?></span><br />
</div>
