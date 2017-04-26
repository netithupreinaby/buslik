<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>







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
						</form>	
                        </div><!---.f_pop-->
						<form method='get'>
                        <div class="f_date">                    
							<input type='hidden' name='q' value='<?=$arResult["REQUEST"]["QUERY"]?>' />
							<input type='hidden' name='sort' value='date' />
							<?if($_REQUEST[sort] == 'date') $active='active'; else $active=''?>
                            <span class="lab <?=$active?>"><?=GetMessage('SEARCH_SORT_BY_DATE');?></a></span>							
                           <button class="button11 sym <?=$active?>">&#123;</button></form>
                        </div><!---.f_price-->
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
		<div class="sidebar" id="sideLeft">
        	<ul class="search-menu">
				<? global $APPLICATION; $page = $APPLICATION->GetCurUri(); if(substr_count($page, '/catalog.php') == 0):?>
					<li><a href="javascript:void(0);" class="active"><?=GetMessage('SITE_SEARCH');?></a></li>
					<li><a href="<?=str_replace('/search/', '/search/catalog.php', $page);?>"><?=GetMessage('CATALOG_SEARCH');?></a></li>
				<?else:?>
					<li><a href="<?=str_replace('/catalog.php', '/', $page);?>" ><?=GetMessage('SITE_SEARCH');?></a></li>
					<li><a href="javascript:void(0);" class="active"><?=GetMessage('CATALOG_SEARCH');?></a></li>
				<?endif?>
				
            </ul>
		</div><!-- .sidebar#sideLeft -->
<? $this->EndViewTarget(); ?>	
