<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	"catalog",
	Array(
		"ACCESSORIES_FILTER_NAME" => "arrFilter",
		"ACCESSORIES_LINK" => array("",""),
		"ACCESSORIES_ON" => "N",
		"ACCESSORIES_PAGE_ELEMENT_COUNT" => "10",
		"ACCESSORIES_PROPS" => "",
		"ACTION_VARIABLE" => "action",
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/account/basket/basket.php",
		"BLOCK_IMG_BIG" => "1",
		"BLOCK_IMG_SMALL" => "1",
		"BLOCK_VIEW_MODE" => "Всплывашка",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000",
		"CACHE_TYPE" => "A",
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"COMPARE_FIELD_CODE" => array("",""),
		"COMPARE_IMG" => "1",
		"COMPARE_META_DESCRIPTION" => "Сравнение ",
		"COMPARE_META_H1" => "Что лучше ",
		"COMPARE_META_KEYWORDS" => "Сравнение ",
		"COMPARE_META_TITLE_PROP" => "Что лучше купить ",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_OFFERS_FIELD_CODE" => array("",""),
		"COMPARE_OFFERS_PROPERTY_CODE" => array("",""),
		"COMPARE_PROPERTY_CODE" => array("","PRODUCER","COUNTRY",""),
		"COMPLETE_SET_DESCRIPTION" => "PREVIEW_TEXT",
		"COMPLETE_SET_NO_INCLUDE_PRICE" => "N",
		"COMPLETE_SET_PROPERTIES" => array(""),
		"COMPLETE_SET_RESIZER_SET" => "1",
		"CONVERT_CURRENCY" => "N",
		"DEFAULT_ELEMENT_SORT_BY" => "PROPERTY_WEEK_COUNTER",
		"DEFAULT_ELEMENT_SORT_ORDER" => "ASC",
		"DEFAULT_VIEW" => "block",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_IMG_BIG" => "1",
		"DETAIL_IMG_ICON" => "1",
		"DETAIL_IMG_SMALL" => "1",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_META_DESCRIPTION_FORCE" => "DESCRIPTION",
		"DETAIL_META_H1" => "#NAME#",
		"DETAIL_META_H1_FORCE" => "H1",
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_KEYWORDS_FORCE" => "KEYWORDS",
		"DETAIL_META_SPLITTER" => ",",
		"DETAIL_META_TITLE_PROP" => "Купить #NAME#",
		"DETAIL_META_TITLE_PROP_FORCE" => "TITLE",
		"DETAIL_OFFERS_FIELD_CODE" => array("",""),
		"DETAIL_OFFERS_PROPERTY_CODE" => array("",""),
		"DETAIL_PROPERTY_CODE" => array("","PRODUCER","COUNTRY",""),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_AS_RATING" => "rating",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "shows",
		"ELEMENT_SORT_FIELD2" => "shows",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"EXPAND_PROPS" => "N",
		"FILTER_BY_QUANTITY" => "N",
		"FILTER_ENABLE_EXPANSION" => "Y",
		"FILTER_FIELD_CODE" => array("",""),
		"FILTER_NAME" => "arrFilter",
		"FILTER_OFFERS_FIELD_CODE" => array("",""),
		"FILTER_OFFERS_PROPERTY_CODE" => array("",""),
		"FILTER_PRICE_CODE" => array("40 Буслик Минск, Маяковского-Денисовская"),
		"FILTER_PROPERTY_CODE" => array("VID_SMESI","VID_UPAKOVKI","VOZRAST_DETSKOE_PITANIE","CML2_BAR_CODE","CML2_ARTICLE","CML2_ATTRIBUTES","CML2_TRAITS","CML2_BASE_UNIT","CML2_TAXES","CML2_MANUFACTURER","VIDEO","POL_POTREBITELYA_OBSHCHEE_SVOYSTVO","SEZON_OBSHCHEE_SVOYSTVO","UPAKOVKA_SOKA","VOZRASTNAYA_GRUPPA_AVTOKRESLA","KOLICHESTVO_LISTOV_ALBOMY_TETRADI","RAZMER_ALBOMA","IZDATELSTVO","VID_KUKLY","USTROYSTVO_KUKLY","POTREBITELI_KOSMETIKA","MATERIAL_ODEZHDY","SOSTAV_DLYA_INTERNET_MAGAZINA","MATERIAL_VERKHA_OBUVI","SEZON_OBUVI","SEZON_ODEZHDY","SHKOLNAYA_FORMA","VIDY_PYURE","UPAKOVKA_PYURE","MATERIAL_BUTYLOCHKI","RAZMER_VANNOCHKI","TIP_GRAVYURY","MATERIAL_MODELI_TRANSPORTA","PERSONAZHI","MATERIAL_KONSTRUKTOR","UPRAVLENIE_MODELI_TRANSPORTA","TIP_MYAGKOY_IGRUSHKI","MATERIAL_KUBIKI","VID_RAZVIVAYUSHCHIKH_KOVRIKOV","MATERIAL_KARUSELI","SOSTAV_KASHI","UPAKOVKA_KASH","MATERIAL_IZDELIYA_CHULOCHNYE","TIP_IZDELIYA","TIP_KOLYASKI","KOLICHESTVO_PREDMETOV_KOMPLEKTY_V_KROVATKU","RAZMER_KONKOV","KOMPLEKTATSIYA_KROVATKI","MEKHANIZM_KACHANIYA","RAZMER_MATRASA","TIP_USTROYSTVA_MOLOKOOTSOSY","VID_MYACHEY","MATERIAL_NAGRUDNIKA_SLYUNYAVCHIKA","MATERIAL_SOSKI_PUSTYSHKI","MATERIAL_PODKLADKI","OSOBENNOSTI_KONSTRUKTSII","VID_PAZLA","RAZMER_PODGUZNIKOV","RAZNOVIDNOST_PODGUZNIKOV","VOZRAST_SOSKI_PUSTYSHKI","VOZRASTNAYA_KATEGORIYA_RYUKZAKI","LINOVKA_TETRADEY","NOVINKA","POPULYARNYYTOVAR","LUCHSHEEPREDLOZHENIE","IDTORGOVOYMARKIFILTR","IDTORGOVOYMARKI","IDBRENDA","IDSERII","GEROY","PREDPRIYATIE_IZGOTOVITEL","STRANA_PROIZVODSTVA","OSNOVNOY_KOMPONENT","ANALITICHESKIY_RAZREZ1","ANALITICHESKIY_RAZREZ2","ANALITICHESKIY_RAZREZ3","ANALITICHESKIY_RAZREZ4","ANALITICHESKIY_RAZREZ5","PREIMUSHCHESTVO1","PREIMUSHCHESTVO2","PREIMUSHCHESTVO4","PREIMUSHCHESTVO4_1","PREIMUSHCHESTVO5","DOBAVKA1","DOBAVKA2","DOBAVKA3","DOBAVKA4","VOZRAST_POTREBITELYA_OBSHCHEE_SVOYSTVO","KLASS_OBSHCHEE_SVOYSTVO","PRICE_BASE","PRODUCER","COUNTRY",""),
		"FILTER_START_EXPANDED" => "N",
		"FILTER_VISIBLE_PROPS_COUNT" => "5",
		"FILTER_VISIBLE_ROWS_COUNT" => "2",
		"FORUM_ID" => "1",
		"FOR_ORDER_DESCRIPTION" => "",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"HIDE_BUY_IF_PROPS" => "Y",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_ORDER_PRICE" => "N",
		"IBLOCK_ID" => "58",
		"IBLOCK_MAX_VOTE" => "5",
		"IBLOCK_SET_STATUS_404" => "N",
		"IBLOCK_TYPE" => "1c_catalog",
		"IBLOCK_VOTE_NAMES" => array("1","2","3","4","5",""),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "3",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID" => "",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_PROPERTY_SID" => "",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_IMG" => "1",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_META_KEYWORDS" => "-",
		"LIST_OFFERS_FIELD_CODE" => array("",""),
		"LIST_OFFERS_LIMIT" => "5",
		"LIST_OFFERS_PROPERTY_CODE" => array("",""),
		"LIST_PRICE_SORT" => "CATALOG_PRICE_1",
		"LIST_PROPERTY_CODE" => array("","PRODUCER","COUNTRY",""),
		"MESSAGES_PER_PAGE" => "10",
		"MESSAGE_404" => "",
		"OFFERS_CART_PROPERTIES" => array(),
		"OFFERS_SORT_FIELD" => "shows",
		"OFFERS_SORT_FIELD2" => "shows",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "asc",
		"OFFER_TREE_PROPS" => array(),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "Y",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "20",
		"PARENT_PHOTO_SKU" => "N",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"POST_FIRST_MESSAGE" => "N",
		"PREORDER" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_DISPLAY_MODE" => "D",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"RESIZER_BOX" => "N",
		"REVIEWS_DQ_ENABLE" => "N",
		"REVIEWS_FB_ENABLE" => "N",
		"REVIEWS_MR_ENABLE" => "N",
		"REVIEWS_SITE_ENABLE" => "Y",
		"REVIEWS_VK_ENABLE" => "N",
		"REVIEWS_YM2_ENABLE" => "N",
		"REVIEWS_YM_ENABLE" => "N",
		"REVIEW_AJAX_POST" => "N",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_META_DESCRIPTION" => "#IBLOCK_NAME# #NAME#",
		"SECTION_META_DESCRIPTION_FORCE" => "UF_DESCRIPTION",
		"SECTION_META_H1" => "#NAME#",
		"SECTION_META_H1_FORCE" => "UF_H1",
		"SECTION_META_KEYWORDS" => "#NAME#",
		"SECTION_META_KEYWORDS_FORCE" => "UF_KEYWORDS",
		"SECTION_META_SPLITTER" => ",",
		"SECTION_META_TITLE_PROP" => "Купить #NAME#",
		"SECTION_META_TITLE_PROP_FORCE" => "UF_TITLE",
		"SECTION_SHOW_DESCRIPTION" => "N",
		"SECTION_SHOW_DESCRIPTION_DOWN" => "N",
		"SECTION_TOP_DEPTH" => "2",
		"SEF_MODE" => "N",
		"SETTINGS_HIDE" => array(),
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_STATUS_ABCD" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_ANNOUNCE" => "N",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_ELEMENT" => "N",
		"SHOW_LINK_TO_FORUM" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SECTION_LIST" => "Y",
		"SHOW_SEOBLOCK" => "N",
		"SHOW_SKLAD" => "N",
		"SHOW_TOP_ELEMENTS" => "Y",
		"SLIDER_USE_MOUSEWHEEL" => "Y",
		"SORT_REVIEWS" => "100",
		"STICKER_BESTSELLER" => "3",
		"STICKER_HIT" => "100",
		"STICKER_NEW" => "14",
		"STORE_CODE" => "$stores",
		"TABLE_IMG" => "1",
		"TABS_DEFAULT" => "TECH",
		"TABS_MANUAL_ENABLE" => "N",
		"TABS_REVIEWS_ENABLE" => "Y",
		"TABS_REVIEWS_SORT" => "200",
		"TABS_TECH_ENABLE" => "Y",
		"TABS_TECH_SORT" => "100",
		"TABS_VIDEO_ENABLE" => "N",
		"TOP_ELEMENT_COUNT" => "9",
		"TOP_ELEMENT_SORT_FIELD" => "shows",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"TOP_LINE_ELEMENT_COUNT" => "3",
		"TOP_OFFERS_FIELD_CODE" => array("",""),
		"TOP_OFFERS_LIMIT" => "5",
		"TOP_OFFERS_PROPERTY_CODE" => array("",""),
		"TOP_PROPERTY_CODE" => array("",""),
		"URL_TEMPLATES_READ" => "",
		"USE_ALSO_BUY" => "N",
		"USE_CAPTCHA" => "N",
		"USE_COMPARE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"USE_REVIEW" => "Y",
		"USE_STORE" => "N",
		"VARIABLE_ALIASES" => Array("ELEMENT_ID"=>"ELEMENT_ID","SECTION_ID"=>"SECTION_ID"),
		"YS_STORES_MUCH_AMOUNT" => "15"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>