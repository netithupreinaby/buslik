<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<p><?=GetMessage('SEARCH_QUERY').'<b>'.$arResult["REQUEST"]["QUERY"].'</b>'?></p>
<?if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
	?>
	<div class="search-language-guess">
		<?echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
	</div><?
endif;?>

<div class="search_results">
	<div class="filter">
		<div class="f_label"><?=GetMessage('SORT');?>:</div>
		<form method='get'>
			<div class="f_relev">
				<input type='hidden' name='q' value='<?=$arResult["REQUEST"]["QUERY"]?>' />
				<input type='hidden' name='sort' value='rank' />
				<?if($_REQUEST[sort] == 'rank' || !isset($_REQUEST[sort])) $active='active'; else $active=''?>
				<span class="lab <?=$active?>"><?=GetMessage('SEARCH_SORT_BY_RANK');?></span>
				<button class="button11 sym <?=$active?>">&#123;</button>
			</div><!---.f_relev-->
		</form>	
		<form method='get'>
			<div class="f_date">                    
				<input type='hidden' name='q' value='<?=$arResult["REQUEST"]["QUERY"]?>' />
				<input type='hidden' name='sort' value='date' />
				<?if($_REQUEST[sort] == 'date') $active='active'; else $active=''?>
				<span class="lab <?=$active?>"><?=GetMessage('SEARCH_SORT_BY_DATE');?></a></span>
				<button class="button11 sym <?=$active?>">&#123;</button></form>
			</div><!---.f_date-->
		</form>
	</div><!--.filter-->

	<?foreach($arResult["SEARCH"] as $arItem):?>	
	<?//print_r($arItem)?>	
                    <div class="search-item">
                        <h2><a href="<?=$arItem[URL]?>"><?=$arItem[TITLE_FORMATED];?></a></h2>
                        <p><?=$arItem[BODY_FORMATED]?></p>
                        <span class="edit-date"><?=GetMessage('SEARCH_MODIFIED')?>: <?=$arItem[FULL_DATE_CHANGE]?></span>
                        <?=GetMessage('PATH');?>: <?=$arItem[CHAIN_PATH]?>
                    </div><!--.search-item-->

	<?endforeach?>
	
	<?=$arResult["NAV_STRING"]?>


	<? $this->SetViewTarget('filter'); ?>
		<ul class="search-menu">
			<? global $APPLICATION; $page = $APPLICATION->GetCurUri(); 
			if(substr_count($page, '/catalog.php') == 0):?>
				<li><a href="javascript:void(0);" class="active"><?=GetMessage('SITE_SEARCH');?></a></li>
				<li><a href="<?=str_replace('/search/', '/search/catalog.php', $page);?>"><?=GetMessage('CATALOG_SEARCH');?></a></li>
			<?else:?>
				<li><a href="<?=str_replace('/catalog.php', '/', $page);?>" ><?=GetMessage('SITE_SEARCH');?></a></li>
				<li><a href="javascript:void(0);" class="active"><?=GetMessage('CATALOG_SEARCH');?></a></li>
			<?endif?>
		</ul>
	<? $this->EndViewTarget(); ?>	
</div><!--.search_results-->