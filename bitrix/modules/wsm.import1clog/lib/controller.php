<?php

namespace Wsm;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();


#type=catalog&mode=init
#type=catalog&mode=file&filename=v8_xxx
#type=catalog&mode=import&filename=offers.xml

#type=catalog / mode=checkauth /
#type=catalog / mode=init /
#type=catalog / mode=file /
#type=catalog / mode=import /

#deactivate


Loc::loadMessages(__FILE__);

class Import1cLog
{

    const MODULE_ID = 'wsm.import1clog';

    const SESSION_KEY = 'WSM_IMPORT1CLOG';
    const KEY_FILE = 'FILE';
    const KEY_LAST_ID = 'LOG_ID';
    const KEY_LAST_MODE = 'LAST_MODE';
    const KEY_LAST_MODE_TIME = 'LAST_MODE_TIME';
    const KEY_PRODUCT_ADD = 'PRODUCT_ADD';
    const KEY_PRODUCT_UPDATE = 'PRODUCT_UPDATE';
    const KEY_OFFER_ADD = 'OFFER_ADD';
    const KEY_OFFER_UPDATE = 'OFFER_UPDATE';

    /**
     * @param $mode
     * @throws \Exception
     */
    protected static function saveLog($mode, $processing = false)
    {
        #chenge mode, calculate time and add to log
        $desc = '';
        $load_time = getmicrotime(true) - $_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE_TIME];

        switch($mode)
        {
            case 'file':
                break;

            case 'import':

                $desc .= Loc::getMessage('WSM_IMPORT1CLOG_LOG_DESC_ELEMENTS').': '.$_SESSION[self::SESSION_KEY][self::KEY_PRODUCT_ADD].'/'.$_SESSION[self::SESSION_KEY][self::KEY_PRODUCT_UPDATE];
                break;
        }

        /*
         * TODO count import_files
        if($mode == 'file' && !$processing)
        {
            $full_path = $_SERVER['DOCUMENT_ROOT'].'/upload/1c_catalog/import_files/';

            if(is_dir($full_path))
            {
                $count = \Wsm\Import1cLog\Tools::countDirFiles($full_path);
                $desc .= $desc ? "\n" : "";
                $desc .= Loc::getMessage('WSM_IMPORT1CLOG_LOG_DESC_IMPORTFILES').' '.$count;
            }

        }
        */

        if(is_array($_SESSION[self::SESSION_KEY][self::KEY_FILE]))
        {

            switch($mode)
            {
                case 'file':
                case 'import':

                    foreach($_SESSION[self::SESSION_KEY][self::KEY_FILE] as $file_name)
                    {
                        if(empty($file_name))
                            continue;

                        $full_path = $_SERVER['DOCUMENT_ROOT'].'/upload/1c_catalog/'.$file_name;

                        if(is_file($full_path))
                        {
                            $desc .= $desc ? "\n" : "";
                            #$desc .= Loc::getMessage('WSM_IMPORT1CLOG_LOG_DESC_FILE_SIZE').': '.\Wsm\Import1cLog\Tools::getFileSizeText(@filesize($full_path)).' ('.$file_name.')';
                            $desc .= $file_name.' ('.\Wsm\Import1cLog\Tools::getFileSizeText(@filesize($full_path)).')';
                        }

                    }


                    break;
            }

        }

        $CLog = new \Wsm\Import1cLog\Log(\Wsm\Import1cLog\Log::IMPORT);
        $CLog->add('>>>> save log '.$mode . ' '. $desc);

        $rsLog = \Wsm\Import1cLog\LogTable::getList(array(
            'filter' => array(
                'ID' => $_SESSION[self::SESSION_KEY][self::KEY_LAST_ID],
                'MODE' => $mode,
            )
        ));

        if($arLog = $rsLog->fetch())
        {
            $rsLog = \Wsm\Import1cLog\LogTable::update($arLog['ID'], array(
                'LOAD_TIME' => round($load_time, 2),
                'SUCCESS' => !$processing ? 'Y' : 'N',
                'DESCRIPTION' => $desc,
            ));

            if(!$rsLog->isSuccess())
                $CLog->add('>>>> update log err ', $rsLog->getErrorMessages());
        }
        else
        {
            $rsLog = \Wsm\Import1cLog\LogTable::add(array(
                'MODE' => $mode,
                'DATE_START' => \Bitrix\Main\Type\DateTime::createFromTimestamp($_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE_TIME]),
                'LOAD_TIME' => round($load_time, 2),
                'SUCCESS' => !$processing ? 'Y' : 'N',
                'DESCRIPTION' => $desc,
            ));

            if($rsLog->isSuccess())
                $_SESSION[self::SESSION_KEY][self::KEY_LAST_ID] = $rsLog->getId();
            else
                $CLog->add('>>>> add log err ', $rsLog->getErrorMessages());
        }


    }

    public function OnPrologHandler()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $CLog = new \Wsm\Import1cLog\Log(\Wsm\Import1cLog\Log::IMPORT);

        if($request->get('type') == 'catalog')
        {
            $CLog->add('type='.$request->get('type'), 'mode='.$request->get('mode'), 'filename='.$request->get('filename'));

            if(\Bitrix\Main\Loader::includeModule(self::MODULE_ID))
            {
                $CEventManager = \Bitrix\Main\EventManager::getInstance();

                $CEventManager->addEventHandler("catalog", "OnSuccessCatalogImport1C", array('\Wsm\Import1cLog', 'OnSuccessCatalogImport1CHandler'));
                $CEventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", array('\Wsm\Import1cLog', 'OnAfterIBlockElementAddHandler'));
                $CEventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", array('\Wsm\Import1cLog', 'OnAfterIBlockElementUpdateHandler'));
            }

        }
        else
            return;

        $start_mode = 'init';

        if($request->get('mode') == $start_mode)
        {
            $CLog->add('agent deactivate');
            \Wsm\Import1cLog\Agent::deactivate();

            $_SESSION[self::SESSION_KEY] = array(
                self::KEY_FILE => array(),
                self::KEY_PRODUCT_ADD => 0,
                self::KEY_PRODUCT_UPDATE => 0,
                self::KEY_OFFER_ADD => 0,
                self::KEY_OFFER_UPDATE => 0,
                self::KEY_LAST_MODE => 'init',
                self::KEY_LAST_MODE_TIME => getmicrotime(true),
            );

            $CLog->add('start', $_SESSION[self::SESSION_KEY]);
        }
        elseif($request->get('mode') != $start_mode && isset($_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE]))
        {
            $last_mode = $_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE];

            if($request->get('mode') != $last_mode)
            {
                $CLog->add('mode change from '.$last_mode.' '.$request->get('mode'));
                self::saveLog($last_mode);

                #reset file list
                $_SESSION[self::SESSION_KEY][self::KEY_FILE] = array();

                #save last mode
                $_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE] = $request->get('mode');
            }
            elseif(isset($_SESSION[self::SESSION_KEY][self::KEY_LAST_ID]) && $_SESSION[self::SESSION_KEY][self::KEY_LAST_ID] > 0)
            {
                $CLog->add('mode processing '.$request->get('mode'));
                self::saveLog($last_mode, true);
            }

            if(!is_array($_SESSION[self::SESSION_KEY][self::KEY_FILE]))
                $_SESSION[self::SESSION_KEY][self::KEY_FILE] = array();

            if($request->get('filename') && strpos($request->get('filename'), 'import_files') === false && !in_array($request->get('filename'), $_SESSION[self::SESSION_KEY][self::KEY_FILE]))
                $_SESSION[self::SESSION_KEY][self::KEY_FILE][] = $request->get('filename');

        }


    }

    function OnAfterIBlockElementAddHandler($arFields)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if($request->get('mode') == 'import' && $arFields['RESULT'] > 0)
        {
            $_SESSION[self::SESSION_KEY][self::KEY_PRODUCT_ADD]++;
        }
    }

    function OnAfterIBlockElementUpdateHandler($arFields)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if($request->get('mode') == 'import' && $arFields["RESULT"])
        {
            $_SESSION[self::SESSION_KEY][self::KEY_PRODUCT_UPDATE]++;
        }
    }

    function OnSuccessCatalogImport1CHandler($arParams, $path)
    {
        $CLog = new \Wsm\Import1cLog\Log(\Wsm\Import1cLog\Log::IMPORT);
        $CLog->add('on success', $_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE]);

        if(isset($_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE]))
            self::saveLog($_SESSION[self::SESSION_KEY][self::KEY_LAST_MODE]);

        //$_SESSION[self::SESSION_KEY] = null;

        $CLog->add('activate agent');
        \Wsm\Import1cLog\Agent::activate();
    }

    public function checkAgent()
    {
        $CLog = new \Wsm\Import1cLog\Log(\Wsm\Import1cLog\Log::IMPORT);

        $day = (int)\Bitrix\Main\Config\Option::get(self::MODULE_ID, "remove_log_day", 0);
        $CLog->add('remove day', $day);

        if($day > 0)
            \Wsm\Import1cLog\LogTable::removeOlder($day);

        \Wsm\Import1cLog\Agent::deactivate();
        $CLog->add('agent deactivate');

        return \Wsm\Import1cLog\Agent::AGENT_FUNCTION;
    }
}