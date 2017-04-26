<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = array(
    array(
        'parent_menu' => 'global_menu_store',
        'sort' => 400,
        'text' => Loc::getMessage('WSM_IMPORT1C_LOG_LOG'),
        'title' => Loc::getMessage('WSM_IMPORT1C_LOG_LOG_TITLE'),
        'url' => 'wsm_import1c_log.php',
        'items_id' => 'menu_wsm_import1c_log',
        "icon" => "mnu_wsm_import1clog_icon",
    ),
);

return $aMenu;
