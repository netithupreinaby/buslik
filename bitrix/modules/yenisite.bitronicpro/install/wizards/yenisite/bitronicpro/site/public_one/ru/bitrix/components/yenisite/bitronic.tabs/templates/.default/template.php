<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arParams["DEFAULT_TAB"] = $arParams["DEFAULT_TAB"] ? $arParams["DEFAULT_TAB"] : 'NEW';?>
<ul class="slider_cat">
	<?foreach ($arResult['TABS'] as $name=>$tab):?>
		<li <?if($arParams["DEFAULT_TAB"] == $name):?>class="active"<?endif;?>>
			<span class="slider_cat_wr" id="tab_<?=$name?>">
				<a href="#" class="main"><?=GetMessage("TAB_".$name);?>
					<i class="cl"></i>
					<i class="cr"></i>
				</a>
				<?if($tab['COUNT'] > 0):?>
					<a href="<?=$tab['LINK'];?>" class="count"><?=$tab['COUNT']?></a>
				<?else:?>
					<i class="count">0</i>
				<?endif;?>
			</span>
		</li>
	<?endforeach;?>
</ul>