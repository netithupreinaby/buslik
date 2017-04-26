<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Темы вопросов");?>
<section class="main-content wishes">
	<?$APPLICATION->IncludeComponent(
		"bitrix:breadcrumb",
		"",
		Array(
			"PATH" => "",
			"SITE_ID" => "s1",
			"START_FROM" => "0"
		)
	);?> 

	<div class="l-menu-wrap clearfix ">
		<p class="page-title">
			<span>Темы вопросов</span>
		</p>
		<div class="sidebar">
			<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"left",
			Array(
				"ROOT_MENU_TYPE" => "left",
				"MAX_LEVEL" => "2",
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "N",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N",
				"MENU_CACHE_TYPE" => "N",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array()
			)
		);?> 
		</div>
            <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "faq",
            Array(
            "ADD_SECTIONS_CHAIN" => "Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COUNT_ELEMENTS" => "Y",
            "IBLOCK_ID" => "48",
            "IBLOCK_TYPE" => "content",
            "SECTION_CODE" => "",
            "SECTION_FIELDS" => array("",""),
            "SECTION_URL" => "",
            "SECTION_USER_FIELDS" => array("",""),
            "SHOW_PARENT_NAME" => "Y",
            "TOP_DEPTH" => "2",
            "VIEW_MODE" => "LINE"
            )
            );?>
            <?$APPLICATION->IncludeComponent(
                "abiatec:form.recaptcha",
                "faq",
                Array(
                    "AJAX_MODE" => "Y",
                    "SEF_MODE" => "N",
                    "WEB_FORM_ID" => "2",
                    "LIST_URL" => "",
                    "EDIT_URL" => "",
                    "SUCCESS_URL" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "N",
                    "USE_EXTENDED_ERRORS" => "N",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "3600",
                    "VARIABLE_ALIASES" => Array(
                        "WEB_FORM_ID" => "WEB_FORM_ID",
                        "RESULT_ID" => "RESULT_ID"

                    )
                ),
                false
            );?>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>