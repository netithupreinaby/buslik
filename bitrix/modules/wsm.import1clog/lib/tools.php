<?php
namespace Wsm\Import1cLog;

use Bitrix\Main;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

class Tools
{
    /**
     * @param $file_size
     * @return string
     */
    public static function getFileSizeText($file_size)
    {
        $i = 0;
        while($file_size > 1024)
        {
            $i++;
            $file_size = $file_size / 1024;
        }

        $unit = '';
        switch($i)
        {
            case 0:
                $unit = 'B';
                break;

            case 1:
                $unit = 'KB';
                break;

            case 2:
                $unit = 'MB';
                break;

            case 3:
                $unit = 'GB';
                break;
        }

        $file_size = round($file_size, $i < 2 ? 0 : 2);
        return $file_size.' '.$unit;
    }

    /**
     * @param $dir
     * @return int
     */
    public static function countDirFiles($dir)
    {
        $c=0;
        $d=dir($dir);

        while($str=$d->read())
        {
            if($str{0}!='.')
            {
                if(is_dir($dir.'/'.$str))
                    $c+=count_files($dir.'/'.$str);
                else
                    $c++;
            }
        }

        $d->close();
        return $c;
    }
}