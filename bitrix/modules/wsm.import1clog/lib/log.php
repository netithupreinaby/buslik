<?php
namespace Wsm\Import1cLog;

use Bitrix\Main;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

class Log
{
    const IMPORT = 'import';
    const MODULE = 'module';

    const DEBUG = false;
    const LOG_PATH = '';

    private $path;
    private $file;

    /**
     * @var array Wsm\Import1cLog\Log
     */
    private static $instance = array();

    /**
     * @return \Wsm\Import1cLog\Log
     */
    public static function getInstance($type)
    {
        if (!isset(static::$instance[$type]))
            static::$instance[$type] = new static($type);

        return static::$instance[$type];
    }

    function __construct($type = 'module')
    {
        if(!preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $type))
            throw new Main\SystemException('Not a valid tag name for log');

        $this->path = $_SERVER['DOCUMENT_ROOT'].self::LOG_PATH.'/';
        $this->file = $type;

        if(self::DEBUG || defined('WSM_IMPORT1CLOG_DEBUG'))
            CheckDirPath($this->path);
    }

    public function add()
    {
        if(!self::DEBUG && !defined('WSM_IMPORT1CLOG_DEBUG'))
            return false;

        $arArgs = func_get_args();
        $trace = debug_backtrace();
        $trace = $trace[1];

        $sResult = $trace['class'].'::'.$trace['function'].', line: '.$trace['line']. ' do: ';

        foreach($arArgs as $arArg)
            $sResult .= print_r($arArg, true).' / ';

        $sResult = date('H:i:s ').$sResult.chr(13);

        $hfile = fopen($this->path.'/wsm_import1clog_'.$this->file.'_'.date('Y-m-d').".log","a");
        fwrite($hfile, $sResult);
        fclose($hfile);
    }
}