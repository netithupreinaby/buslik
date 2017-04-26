<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
<?/*
	<div id="<?echo $CONTAINER_ID?>">
	<form action="<?echo $arResult["FORM_ACTION"]?>">
		<input id="<?echo $INPUT_ID?>" type="text" name="q" value="" size="40" maxlength="50" autocomplete="off" />&nbsp;<input name="s" type="submit" value="<?=GetMessage("CT_BST_SEARCH_BUTTON");?>" />
	</form>
	</div>*/?>
	
	<div id="<?echo $CONTAINER_ID?>">
	
		<form action="<?echo $arResult["FORM_ACTION"]?>" id="search_form">
		  <input type="text"  class="txt" name="q" id="<?echo $INPUT_ID?>" autocomplete="off"  value="<?if (isset($_REQUEST["q"])) echo htmlspecialcharsbx($_REQUEST["q"])?>" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>"   />
		  <select id="search_select">
			<option value='/search/catalog.php'><?=GetMessage('SEARCH_CATALOG');?></option>
			<option value='/search/index.php'><?=GetMessage('SEARCH_SITE');?></option>
		  </select>
		  <a href="javascript:void(0);" class="sym s_submit" title="<?=GetMessage("CT_BST_SEARCH_BUTTON"); ?>">&#0035;</a>
		  </form>
					
	</div>	
	
<?endif?>
<script type="text/javascript">
var jsControl = new JCTitleSearch({
	//'WAIT_IMAGE': '/bitrix/themes/.default/images/wait.gif',
	'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
	'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
	'INPUT_ID': '<?echo $INPUT_ID?>',
	'MIN_QUERY_LEN': 2
});
</script>
