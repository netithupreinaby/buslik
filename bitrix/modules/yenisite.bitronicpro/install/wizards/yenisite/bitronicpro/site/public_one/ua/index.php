<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Бітронік - інтернет-магазин електроніки на Бітрікс.");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Головна сторінка");
?>
<?global $ys_options;?>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/index/top_block.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/index/main_spec.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
	
<article class="article2">
	<?if($ys_options["menu_filter"] != 'left-top'):?>
		<div class="column c-short">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/vote.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/lucky_discount.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
		</div>
	<?endif?>
	<div class="column <?if($ys_options["menu_filter"] == 'left-top'):?>c-wide2<?else:?>c-wide<?endif;?>">
		<?
		if(CModule::IncludeModule("aprof.time2buy")):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/time2buy.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
		<?else:?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/campaigns.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
		<?endif;?>
		<div style="clear:both;"></div>
	</div>
	<!--.column-->
	<div class="column <?if($ys_options["menu_filter"] == 'left-top'):?>c-short2<?else:?>c-short<?endif;?>"> <a href="/news/rss/" class="rss"><span class="sym">&#241;</span> RSS</a>
	    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/news.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
	</div>
	<!--.column-->
	<div style="clear:both;"></div>
	<div class="stock-info">
		<?$APPLICATION->IncludeFile("/include_areas/main_info.php", Array(), Array("MODE"=>"html") );?>			
	</div>
	<!--.stock-info--> 
</article>
<!-- #content-->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>