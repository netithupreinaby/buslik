<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Вход на сайт");?>
<?php if (!$USER->isAuthorized()) LocalRedirect('/user/?login=yes') ?>

<section class="main-content">
    <section class="cart">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "dropdownPersonal",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "user",
                "USE_EXT" => "Y",
                "COMPONENT_TEMPLATE" => "topmenu2"
            ),
            false
        );?>

        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "profileTabs",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "COMPONENT_TEMPLATE" => "topmenu2"
            ),
            false
        );?>


        <?$APPLICATION->IncludeComponent(
            "abiatec:main.profile",
            "contacts",
            array(
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CHECK_RIGHTS" => "N",
                "SEND_INFO" => "N",
                "SET_TITLE" => "Y",
                "USER_PROPERTY" => array(
                    0 => "UF_PERSONAL_HOME",
                    1 => "UF_PERSONAL_HOUSING",
                    2 => "UF_PERSONAL_FLAT",
                    3 => "UF_DELIVERY_ADDRESS_",
                    4 => "UF_PERSONAL_CHILDREN",
                    5 => "UF_PERSONAL_EMAIL_SU",
                    6 => "UF_PERSONAL_SMS_SUBS",
                ),
                "USER_PROPERTY_NAME" => "",
                "COMPONENT_TEMPLATE" => "login",
                "TEMPLATE_TYPE" => "CONTACTS"
            ),
            false
        );?>

    </section>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>