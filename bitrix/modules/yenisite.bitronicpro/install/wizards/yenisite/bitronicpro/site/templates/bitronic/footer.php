<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?IncludeTemplateLangFile(__FILE__);?>
<?
global $ys_options;
if(defined('ERROR_404') && ERROR_404 == 'Y'):?>
			</div> <!-- .content -->
		</div> <!-- #container -->
	</div> <!-- #middle -->
<?else:?>
	<?if($ys_options["menu_filter"] == "left-top"):?>
		<?if($APPLICATION->GetCurDir() != SITE_DIR):?>

				</div>
			</div> <!-- #container -->
		<?else:?>
				</div>
			</div> <!-- #container -->
		<?endif?>
	
		<?if($ys_options["not_show_menu"] != "Y"):?>	
			<div class="sidebar" id="sideLeft">
				<?$APPLICATION->ShowViewContent('DETAIL_PIC');?>
			<?if (($APPLICATION->GetCurDir()== "/personal/")|| ($APPLICATION->GetCurDir()== "/personal/profile/")):?>
				<?/*<br /> <br />
				</div>*/?> <!-- 2 -->
			<?else:?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/menu.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					<?if($ys_options["menu_filter"] == 'left-top' && $APPLICATION->GetCurDir() == SITE_DIR):?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/vote.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/index/lucky_discount.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					<?endif;?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/viewed_product.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					<?if($ys_options['show_help_menu'] == 'Y'):?>
						<div style="clear:both;"></div>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/menu_help.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					<?endif;?>
			<?endif?>
		<?endif?>
	<?else:?>
		<?if($APPLICATION->GetCurDir() != SITE_DIR):?>
			</div><!-- .ys_article -->
		<?endif;?>
		</div> <!-- #container -->
		<div class="sidebar" id="sideLeft">
			<?$APPLICATION->ShowViewContent('DETAIL_PIC');?>
			<?$APPLICATION->ShowViewContent('filter');?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/viewed_product.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			<?if($ys_options['show_help_menu'] == 'Y'):?>
				<div style="clear:both;"></div>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/menu_help.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			<?endif;?>
	<?endif?>
	<?if($APPLICATION->GetCurDir() != SITE_DIR && $APPLICATION->GetCurDir() != "/personal/" && $APPLICATION->GetCurDir() != "/personal/profile/"):?>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/social_boxes.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
	<?endif?>
	<?if($ys_options['show_left_text'] == 'Y'):?>
		<div class="left_text1">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => $ys_options['current_dir']."index_left.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
		</div> <!-- .left_text1 -->
	<?endif;?>
		<?if($APPLICATION->GetCurDir()!= "/personal/profile/"):?>
			</div><!-- .sidebar #sideLeft -->
		<?endif;?>
	</div> <!-- #middle -->	
	<?$APPLICATION->ShowViewContent('SLIDER');?>
<?endif; // 404?>
</div> <!-- #wrapper -->

<footer>
	<div class="pay"  <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
			Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/payment.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
	</div> <!--.pay-->
	<div class="f_nav_wrapper">
		<div class="footer-nav"  <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
			<div class="social">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/social.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
			</div> <!--.social-->
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/menu_bottom.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
		</div> <!--.footer-nav--> 
	</div> <!--.f_nav_wrapper-->
	<div class="columns_wrapper">
		<div class="columns"  <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
		
		<?global $APPLICATION;
		if(substr_count($APPLICATION->GetCurDir(), "/") == 1 && !$NOT_SHOW_MENU):
		?>
		
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/menu_bottom_catalog.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			
		<?endif;?>
		
			<div class="column w25">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/copy.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<br /><br />
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/feedback.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/reformal.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				<div id="bx-composite-banner" style="padding-bottom: 15px;"></div>
			</div> <!--.column w25-->
			<div class="column w40">
				<address>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/address.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				</address>
				<div class="phones">
                    <span class="sym">&#124;</span>
					<?$file=$_SERVER["DOCUMENT_ROOT"]."/".SITE_DIR."include_areas/phones_footer.php";
					if (file_exists($file)):?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
							Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/phones_footer.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<?else:?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
							Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/phones.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<?endif;?>
				</div> <!--.phones-->
				<? if (CModule::IncludeModule('yenisite.worktime')): ?>
					<div class="worktime">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/worktime.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
					</div> <!--.worktime-->
					<?endif?>
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/icq.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<br />
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/qrcode.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<?/*</div>*/?>
			</div><!--.column w40-->
		<div class="column w20">
			<?if(CModule::IncludeModule('yenisite.pricegen')):?>
			<div class="pricelist">
				<span class="sym">&#0061;</span>
				<a href="/pricelist/" ><?=GetMessage("PRICELIST_DOWNLOAD")?></a>
			</div>
			<?endif?>
			<br />
			<?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):?>	  
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("PATH" => SITE_DIR."include_areas/footer/subscribe.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS"=>"Y"));?>
			<?endif?>
		</div>
		
			<div class="column w15">
				<div class="creator">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/developers.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				</div>
				<!--.creator-->
				<div class="site-counters"> 
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/counters.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>  
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/counter_ya_metrika.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>						
				</div>
				<!--.site-counters--> 
				<div class="validator">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/validator.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
				</div>
			</div>
			<!--.column-->
			<div style="clear:both;"></div>
		</div><!--.columns--> 
	</div> <!--.columns_wrapper--> 
</footer>
<!-- #footer -->
</div><!-- #site -->
</div><!-- #fixbody -->
<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
	Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/footer/flashmessage.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));?>
</body>
</html>