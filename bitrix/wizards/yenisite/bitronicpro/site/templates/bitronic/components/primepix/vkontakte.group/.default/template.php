<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
<div id="vkontakte-group<?=$arResult['SUFFIX']?>"></div>
<script type="text/javascript">
	VK.Widgets.Group("vkontakte-group<?=$arResult['SUFFIX']?>", <?=$arResult['OPTIONS']?>);
</script>
