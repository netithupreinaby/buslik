<?php
namespace Wsm\Import1cLog;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class LogTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_wsm_import1clog_log';
    }

    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_ID'),
            )),
            new DatetimeField('DATE_START', array(
                'required' => true,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_DATE_START'),
            )),
            new DatetimeField('DATE_END', array(
                'required' => false,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_DATE_END'),
            )),
            new FloatField('LOAD_TIME', array(
                'required' => true,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_LOAD_TIME'),
            )),

            new StringField('MODE', array(
                'required' => true,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_MODE'),
                'default_value' => function () {
                    return '';
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 20),
                    );
                },
            )),

            new BooleanField('SUCCESS', array(
                'required' => false,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_SUCCESS'),
                'default_value' => function () {
                    return 'Y';
                },
                'values' => array('N', 'Y'),
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 20),
                    );
                },
            )),

            new StringField('DESCRIPTION', array(
                'required' => false,
                'title' => Loc::getMessage('WSM_IMPORT1CLOG_LOG_F_DESCRIPTION'),
                'default_value' => function () {
                    return '';
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                },
            )),

        );
    }

    /**
     * @param $day
     * @throws ArgumentException
     */
    public static function removeOlder($day)
    {
        $day = intval($day);

        if($day < 0)
            throw new ArgumentException('Not correct parametr day');

        $sql = 'DELETE FROM `'.self::getTableName().'` WHERE `DATE_START` < (NOW() - INTERVAL '.$day.' DAY)';
        $connection = Application::getInstance()->getConnection();
        $connection->query($sql);
    }
}
