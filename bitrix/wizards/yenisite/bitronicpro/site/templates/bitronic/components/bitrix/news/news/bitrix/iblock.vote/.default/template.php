<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="iblock-vote">
	<form method="post" action="<?=POST_FORM_ACTION_URI?>">
		<select name="rating">
			<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
				<option value="<?=$i?>"><?=$name?></option>
			<?endforeach?>
		</select>
		<?echo bitrix_sessid_post();?>
		<input type="hidden" name="back_page" value="<?=$arResult["BACK_PAGE_URL"]?>" />
		<input type="hidden" name="vote_id" value="<?=$arResult["ID"]?>" />
		<input type="submit" name="vote" value="<?=GetMessage("T_IBLOCK_VOTE_BUTTON")?>" />
	</form>
</div>

