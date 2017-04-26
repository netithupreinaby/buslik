<?php
define('ADMIN_MODULE_NAME', 'wsm.import1clog');
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Wsm\Import1cLog\LogTable;

$rsModule = Main\Loader::includeModule(ADMIN_MODULE_NAME);

if($rsModule != Main\Loader::MODULE_INSTALLED && $rsModule != Main\Loader::MODULE_DEMO)
    die();

#CJSCore::Init(array("jquery"));

Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(Loc::getMessage("wsm_import1clog_log_list_title"));


$POST_RIGHT = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
if($POST_RIGHT=="D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$sTableID = "table_".LogTable::getTableName().'_1';
$oSort = new CAdminSorting($sTableID, "ID", "DESC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = Array(
    "find_ID",
);

$lAdmin->InitFilter($arFilterFields);

$arFilter = array(
    'ID' => $find_ID,
);

foreach($arFilter as $key => $value)
    if((!strlen($value) && !is_array($value)) || $value == '%%')
        unset($arFilter[$key]);

$arHeaders=array(
    array(
        "id"=>"ID",
        "sort"=>"ID",
        "default"=>true,
    ),

    array(
        "id"=>"DATE_START",
        "sort"=>"DATE_START",
        "default"=>true,
    ),
    /*array(
        "id"=>"DATE_END",
        "sort"=>"DATE_END",
        "default"=>true,
    ),*/
    array(
        "id"=>"MODE",
        "sort"=>"MODE",
        "default"=>true,
    ),
    array(
        "id"=>"SUCCESS",
        "sort"=>"SUCCESS",
        "default"=>true,
    ),
    array(
        "id"=>"LOAD_TIME",
        "sort"=>"LOAD_TIME",
        "default"=>true,
    ),


    array(
        "id"=>"DESCRIPTION",
        "default"=>true,
    ),

);


$arrFields = LogTable::getMap();
foreach($arrFields as $inedx => $f)
{
    if($f instanceof Main\Entity\ScalarField)
    {
        foreach($arHeaders as $inedx_h => $h)
        {
            if(!$h['content'] && $h['id'] == $f->getColumnName())
                $arHeaders[$inedx_h]['content'] = $f->getTitle() ? $f->getTitle() : $h['id'];
        }
    }

}

$lAdmin->AddHeaders($arHeaders);

$rsData = LogTable::getList(array(
    'select' => array('*'),
    'filter' => $arFilter,
    'order' => array($by => $order)));

$rsAdminData = new CAdminResult($rsData, $sTableID);
$rsAdminData->NavStart();


// navigation setup
$lAdmin->NavText($rsAdminData->GetNavPrint(Loc::getMessage("SEARCH_PHL_PHRASES")));

$c = 0;
while($arRes = $rsAdminData->NavNext(true, "data_"))
{
    $row =& $lAdmin->AddRow($arRes['ID'], $arRes);
    $row->AddViewField('LOAD_TIME', $data_LOAD_TIME.' '.Loc::getMessage('WSM_IMPORT1CLOG_DESC_SEKUND'));
    $row->AddViewField('SUCCESS', Loc::getMessage('WSM_IMPORT1CLOG_DESC_SUCCESS_'.$data_SUCCESS));
    $row->AddViewField('DESCRIPTION', str_replace("\n",'<br>',$data_DESCRIPTION));
}

$lAdmin->AddFooter(array(array("title"=>Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsAdminData->SelectedRowsCount())));
$lAdmin->CheckListMode();

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>