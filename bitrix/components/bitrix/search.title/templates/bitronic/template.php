<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?><?

//$INPUT_ID = trim($arParams["~INPUT_ID"]);
//if(strlen($INPUT_ID) <= 0)
	$INPUT_ID =  (!empty($arParams["INPUT_ID_CSS"])) ? $arParams["INPUT_ID_CSS"] : "ys-title-search-input";
//$INPUT_ID = CUtil::JSEscape($INPUT_ID);
	$AJAX_BASKET = $arParams["AJAX_BASKET"];
//$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
//if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = (!empty($arParams["CONTAINER_ID_CSS"])) ? $arParams["CONTAINER_ID_CSS"] : "ys-title-search";
//$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($this->__folder)
	$pathToTemplateFolder = $this->__folder ;
else
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));

$before_search = "{$pathToTemplateFolder}/result_modifier.php";

if($arParams["SHOW_INPUT"] !== "N"):?>

<div class="search_form" id="<?echo $CONTAINER_ID?>">
	<div class="input-group">
		<form action="<?=$before_search;?>" id="search_form">

			<input type="hidden" name="site_id" value="<?=SITE_ID;?>">
			<input type="hidden" name="ys_st_before" value="y">
			<input type="hidden" name="ys_st_ajax_call" value="y">
			<input type="text" class="form-control" name="q" id="<?echo $INPUT_ID?>" autocomplete="off"  value="<?if (isset($_REQUEST["q"])) echo htmlspecialcharsbx($_REQUEST["q"])?>" placeholder="<?=GetMessage("SEARCH_PLACEHOLDER")?>"   />
			<select id="search_select" name="search_category">
				<?if( $arParams["NUM_CATEGORIES"] > 1):?>
					<option value='all'><?=GetMessage('CATEGORY_ALL');?></option>
				<?endif;?>
				<?for($i = 0; $i < $arParams["NUM_CATEGORIES"]; $i++):
					$category_title = trim($arParams["CATEGORY_".$i."_TITLE"]);
					if(empty($category_title))
					{
						if(is_array($arParams["CATEGORY_".$i]))
							$category_title = implode(", ", $arParams["CATEGORY_".$i]);
						else
							$category_title = trim($arParams["CATEGORY_".$i]);
					}
					if(empty($category_title))
						continue;
				?>
					<option value='<?=$i;?>'><?=$category_title;?></option>
				<?endfor;?>
			</select>
			<a href="javascript:void(0);" class="s_submit">&#0035;</a>
			
			
			<div class="input-group-btn">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						Все категории <span class="glyphicon glyphicon-chevron-down" style="font-size: 11px;"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</div>
				<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
			</div>
		 </form>	
	</div>
</div>





	<?if(false){?>
	<div id="<?echo $CONTAINER_ID?>">
		<form action="<?=$before_search;?>" id="search_form">
			<input type="hidden" name="site_id" value="<?=SITE_ID;?>">
			<input type="hidden" name="ys_st_before" value="y">
			<input type="hidden" name="ys_st_ajax_call" value="y">
			<input type="text" class="txt" name="q" id="<?echo $INPUT_ID?>" autocomplete="off"  value="<?if (isset($_REQUEST["q"])) echo htmlspecialcharsbx($_REQUEST["q"])?>" placeholder="<?=GetMessage("SEARCH_PLACEHOLDER")?>"   />
			<?/*<input type="hidden" name="search_page" value="<?=$arResult["FORM_ACTION"];?>">*/?>
			<select id="search_select" name="search_category">
				<?if( $arParams["NUM_CATEGORIES"] > 1):?>
					<option value='all'><?=GetMessage('CATEGORY_ALL');?></option>
				<?endif;?>
				<?for($i = 0; $i < $arParams["NUM_CATEGORIES"]; $i++):
					$category_title = trim($arParams["CATEGORY_".$i."_TITLE"]);
					if(empty($category_title))
					{
						if(is_array($arParams["CATEGORY_".$i]))
							$category_title = implode(", ", $arParams["CATEGORY_".$i]);
						else
							$category_title = trim($arParams["CATEGORY_".$i]);
					}
					if(empty($category_title))
						continue;
				?>
					<option value='<?=$i;?>'><?=$category_title;?></option>
				<?endfor;?>
			</select>
			<a href="javascript:void(0);" class="s_submit">&#0035;</a>
		</form>
		<?if($arParams["EXAMPLE_ENABLE"] == "Y"):
			$list = $arParams["EXAMPLES"];
			if(!end($list))
				array_pop($list);
			$example = $list[array_rand($list)];
			if($example):
		?>
			<span class="example"><?=GetMessage('EXAMPLE');?><a href="javascript:void(0);"><?=$example;?></a></span>
		<?endif;endif;?>
	</div>
	<?}?>
<?endif;?>
<script type="text/javascript">
var ys_st_jsControl = new ys_st_JCTitleSearch({
	//'WAIT_IMAGE': '/bitrix/themes/.default/images/wait.gif',
	'AJAX_PAGE' : '<?="{$pathToTemplateFolder}/result_modifier.php";?>',
	'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
	'INPUT_ID': '<?echo $INPUT_ID?>',
	'MIN_QUERY_LEN': 2,
	'SITE_ID': '<?=SITE_ID;?>',
	'CLEAR_CACHE': '<?=$_REQUEST['clear_cache'];?>',
	'AJAX_BASKET': '<?=$AJAX_BASKET;?>'
});
</script>
