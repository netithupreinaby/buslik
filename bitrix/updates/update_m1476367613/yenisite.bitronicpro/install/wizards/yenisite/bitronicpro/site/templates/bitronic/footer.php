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
			</div>
		<?else:?>
				</div>
			</div>
		<?endif?>
	
		<?if($ys_options["not_show_menu"] != "Y"):?>	
			<?if (($APPLICATION->GetCurDir()== "/personal/")|| ($APPLICATION->GetCurDir()== "/personal/profile/")):?>
				<?/*<br /> <br />
				</div>*/?> <!-- 2 -->
			<?else:?>
				<div class="sidebar" id="sideLeft">
					<?$APPLICATION->ShowViewContent('DETAIL_PIC');?>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_vertical", array(
	"ROOT_MENU_TYPE" => "catalog",
	"MENU_CACHE_TYPE" => "A",
	"MENU_CACHE_TIME" => "604800",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "2",
	"CHILD_MENU_TYPE" => "",
	"USE_EXT" => "Y",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N",
	"INCLUDE_JQUERY" => "Y",
	"THEME" => $ys_options["color_scheme"],
	"SHOW_BY_CLICK" => "Y",
	"VIEW_HIT" => "Y",
	"PRICE_CODE" => "BASE",
	"CURRENCY" => "RUB"
	),
	false
);?>
<?if($ys_options["menu_filter"] == 'left-top' && $APPLICATION->GetCurDir() == SITE_DIR):?>
<?$APPLICATION->IncludeComponent("bitrix:voting.current", "bitronic", array(
	"CHANNEL_SID" => "DEMO_1",
	"VOTE_ID" => "1",
	"VOTE_ALL_RESULTS" => "N",
	"AJAX_MODE" => "Y",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?endif;?>
					<?if($ys_options['show_help_menu'] == 'Y'):?>
						<div style="clear:both;"></div>
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_vertical", array(
							"ROOT_MENU_TYPE" => "help",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "604800",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => array(
							),
							"MAX_LEVEL" => "2",
							"CHILD_MENU_TYPE" => "",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
							"INCLUDE_JQUERY" => "Y",
							"THEME" => $ys_options["color_scheme"],
							"SHOW_BY_CLICK" => "N"
							),
							false
						);?>
					<?endif;?>
			<?endif?>
		<?endif?>
	<?else:?>
		<?if($APPLICATION->GetCurDir() != SITE_DIR):?>
			</div><!-- .ys_article -->
		<?endif;?>
		</div>
		<div class="sidebar" id="sideLeft">
			<?$APPLICATION->ShowViewContent('DETAIL_PIC');?>
			<?$APPLICATION->ShowViewContent('filter');?>
			<?if($ys_options['show_help_menu'] == 'Y'):?>
				<div style="clear:both;"></div>
				<?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_vertical", array(
					"ROOT_MENU_TYPE" => "help",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_TIME" => "604800",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
					),
					"MAX_LEVEL" => "2",
					"CHILD_MENU_TYPE" => "",
					"USE_EXT" => "N",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
					"INCLUDE_JQUERY" => "Y",
					"THEME" => $ys_options["color_scheme"],
					"SHOW_BY_CLICK" => "N"
					),
					false
				);?>
			<?endif;?>
	<?endif?>
	<?if($APPLICATION->GetCurDir() != SITE_DIR && $APPLICATION->GetCurDir() != "/personal/" && $APPLICATION->GetCurDir() != "/personal/profile/"):?>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => "/include_areas/social_boxes.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
	<?endif?>
	<?if($ys_options['show_left_text'] == 'Y'):?>
		<div class="left_text1">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => $ys_options['current_dir']."index_left.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
		</div> <!-- .left_text1 -->
	<?endif;?>
		<?if(($APPLICATION->GetCurDir()!= "/personal/") && ($APPLICATION->GetCurDir()!= "/personal/profile/")):?>
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
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "top",
				"MAX_LEVEL" => "1",
				"CHILD_MENU_TYPE" => "",
				"USE_EXT" => "N",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "604800",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => "",
				),
				false
			);?>
		</div> <!--.footer-nav--> 
	</div> <!--.f_nav_wrapper-->
	<div class="columns_wrapper">
		<div class="columns"  <?if($ys_options["min"] && $ys_options["max"]):?> style="min-width: <?=intval($ys_options["min"]-40)?>px; max-width: <?=$ys_options["max"]?>px;"<?endif?>>
		
		<?global $APPLICATION;
		if(substr_count($APPLICATION->GetCurDir(), "/") == 1 && !$NOT_SHOW_MENU):
		?>
		
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_catalog", array(
				"ROOT_MENU_TYPE" => "catalog",
				"MENU_CACHE_TYPE" => "Y",
				"MENU_CACHE_TIME" => "36000000",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array(
			),
				"MAX_LEVEL" => "3",
				"CHILD_MENU_TYPE" => "catalog",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N"
			),
			false
			);?>
		<?endif;?>
		
			<div class="column w25">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/copy.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<br /><br />
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/feedback.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
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
				<div class="worktime">
                    <div class="timeline">
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div class="weekend"></div>
						<div class="weekend"></div>
					</div>
					<div class="timedesc"><?$APPLICATION->IncludeComponent("bitrix:main.include", "",
						Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/worktime.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
                    </div>
				</div> <!--.worktime-->
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/icq.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<br />
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
					Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/qrcode.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false);?>
					<?/*</div>*/?>
			</div><!--.column w40-->
		<div class="column w20">
			<div class="pricelist">
				<span class="sym">&#0061;</span>
				<a href="/pricelist/" ><?=GetMessage("PRICELIST_DOWNLOAD")?></a>
			</div>
			<br />
			<?if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale')):?>	  
				<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "footer", array(
				"USE_PERSONALIZATION" => "Y",
				"SHOW_HIDDEN" => "N",
				"PAGE" => "/personal/subscribe.php",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "604800"
				),
				false
				);?>
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
</body>
</html>