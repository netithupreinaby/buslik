<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="bx-auth-serv-icons">
<?foreach($arParams["~AUTH_SERVICES"] as $service):?>
	<a title="<?=htmlspecialchars($service["NAME"])?>" href="javascript:void(0)" onclick="BxShowAuthFloat('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')"><i class="bx-ss-icon <?=htmlspecialchars($service["ICON"])?>"></i></a>
<?endforeach?>
</div>