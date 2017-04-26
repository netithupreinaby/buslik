<?php

namespace Wsm\Import1cLog;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

Loc::loadMessages(__FILE__);

class Agent
{
    const MODULE_ID = "wsm.import1clog";
    const AGENT_TIMEOUT = 300;
    const AGENT_FUNCTION = '\Wsm\Import1cLog::checkAgent();';

    public static function activate()
    {
        $arAgentFilter = array(
            'NAME'      => self::AGENT_FUNCTION,
            'MODULE_ID' => self::MODULE_ID,
        );

        $res = \CAgent::GetList(array(),$arAgentFilter);
        if($res->SelectedRowsCount() == 0)
        {
            $site_format = FieldType\DateTime::getFormat();

            $agent_id = \CAgent::AddAgent(
                self::AGENT_FUNCTION,
                self::MODULE_ID,          	// идентификатор модуля
                "N",                        // агент не критичен к кол-ву запусков
                self::AGENT_TIMEOUT,        // интервал запуска
                date($site_format, time() + self::AGENT_TIMEOUT),     // дата первой проверки на запуск
                "Y",                        // агент активен
                date($site_format, time() + self::AGENT_TIMEOUT),     // дата первого запуска
                30);
        }
        elseif($arAgent = $res->GetNext(false, false))
        {
            \CAgent::Update($arAgent['ID'], array(
                'ACTIVE' => 'Y',
                'AGENT_INTERVAL' => self::AGENT_TIMEOUT,
            ));
        }
    }

    public static function deactivate()
    {
        $arAgentFilter = array(
            'NAME' => self::AGENT_FUNCTION,
            'MODULE_ID' => self::MODULE_ID
        );

        $res = \CAgent::GetList(array(),$arAgentFilter);
        if($arAgent = $res->GetNext(false, false))
        {
            \CAgent::Update($arAgent['ID'], array(
                'ACTIVE' => 'N'
            ));
        }
    }


    public static function remove()
    {
        \CAgent::RemoveAgent(self::AGENT_FUNCTION, self::MODULE_ID);
    }
}