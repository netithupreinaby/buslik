<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'wsm.import1clog');

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\String;

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("WSM_IMPORT1CLOG_TAB_MAIN"),
        "TITLE" => Loc::getMessage("WSM_IMPORT1CLOG_TAB_MAIN_TITLE"),
    ),
));

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid())
{

    if (!empty($restore))
    {
        Option::delete(ADMIN_MODULE_NAME);

        \CAdminMessage::showMessage(array(
            "MESSAGE" => Loc::getMessage("WSM_IMPORT1CLOG_OPTIONS_RESTORED"),
            "TYPE" => "OK",
        ));
    }
    else
    {
        if ($request->getPost('remove_log_day') && ($request->getPost('remove_log_day') > 0) && ($request->getPost('remove_log_day') < 100000))
        {
            Option::set(ADMIN_MODULE_NAME, "remove_log_day", $request->getPost('remove_log_day'));

            \CAdminMessage::showMessage(array(
                "MESSAGE" => Loc::getMessage("WSM_IMPORT1CLOG_OPTIONS_SAVED"),
                "TYPE" => "OK",
            ));
        }
        else
        {
            \CAdminMessage::showMessage(Loc::getMessage("WSM_IMPORT1CLOG_OPTIONS_INVALID_VALUE"));
        }
    }

}

$tabControl->begin();
?>

<form method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    ?>
    <tr>
        <td width="40%">
            <label for="remove_log_day"><?=Loc::getMessage("WSM_IMPORT1CLOG_OPTIONS_REMOVE_LOG_DAY") ?>:</label>
        <td width="60%">
            <input type="text"
                   size="5"
                   maxlength="5"
                   name="remove_log_day"
                   value="<?=(int)Option::get(ADMIN_MODULE_NAME, "remove_log_day");?>"
                />
        </td>
    </tr>

    <?php
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
        />
    <input type="submit"
           name="restore"
           title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
           onclick="return confirm('<?= AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>')"
           value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS") ?>"
        />
    <?php
    $tabControl->end();
    ?>
</form>