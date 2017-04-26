<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="beono-flashmessage" id="beono_flashmessage_<?=$arResult['MESSAGE']['ID']?>">
	<div class="beono-flashmessage-text"><?=$arResult['MESSAGE']['TEXT'];?></div>
	<a href="javascript:beono_flashmessage_close('<?=$arResult['MESSAGE']['ID']?>', '<?=$arResult['COOKIE_PREFIX']?>');" class="beono-flashmessage-close">&times;</a>
</div>

<?if(isset($_GET['noads'])):?>
	<script> beono_flashmessage_close('<?=$arResult['MESSAGE']['ID']?>', '<?=$arResult['COOKIE_PREFIX']?>'); </script>
<?endif?>