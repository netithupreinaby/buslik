<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Ожидания");?>

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

    <p>На этой странице будут отображены ожидания пользователя.</p>

    </section>
</section>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>