<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
// ! Next PROPERTY create in new script !
if(!CModule::IncludeModule("iblock"))
	return false;
$timestamp = date('Y_m_d_H_i_s') ;	
$arNewProps = array(
	'WEEK'=>array('NAME'=>GetMessage('PROPNAME_WEEK'), 'ACTIVE'=>'Y', 'SORT'=>'9010', 'PROPERTY_TYPE'=>'S'),
	'WEEK_COUNTER'=>array('NAME'=>GetMessage('PROPNAME_WEEK_COUNTER'), 'ACTIVE'=>'Y', 'SORT'=>'9011', 'PROPERTY_TYPE'=>'N'),
	'BESTSELLER'=>array('NAME'=>GetMessage('PROPNAME_BESTSELLER'), 'ACTIVE'=>'Y', 'SORT'=>'9012', 'PROPERTY_TYPE'=>'L', 'LIST_TYPE' => 'C', 'VALUES' => Array('0'=>Array('VALUE'=>'Y', 'DEF'=>'N', 'SORT'=>'100'))),
	'SALE_INT'=>array('NAME'=>GetMessage('PROPNAME_SALE_INT'), 'ACTIVE'=>'Y', 'SORT'=>'9013', 'PROPERTY_TYPE'=>'N'), 
	'SALE_EXT'=>array('NAME'=>GetMessage('PROPNAME_SALE_EXT'), 'ACTIVE'=>'Y', 'SORT'=>'9014', 'PROPERTY_TYPE'=>'N'),
	'H1'=>array('NAME'=>GetMessage('PROPNAME_H1'), 'ACTIVE'=>'Y', 'SORT'=>'10000', 'PROPERTY_TYPE'=>'S'),
	'TITLE'=>array('NAME'=>GetMessage('PROPNAME_TITLE'), 'ACTIVE'=>'Y', 'SORT'=>'10000', 'PROPERTY_TYPE'=>'S'),
	'KEYWORDS'=>array('NAME'=>GetMessage('PROPNAME_KEYWORDS'), 'ACTIVE'=>'Y', 'SORT'=>'10000', 'PROPERTY_TYPE'=>'S'),
	'DESCRIPTION'=>array('NAME'=>GetMessage('PROPNAME_DESCRIPTION'), 'ACTIVE'=>'Y', 'SORT'=>'10000', 'PROPERTY_TYPE'=>'S'),
	'COMPLETE_SETS'=>array('NAME'=>GetMessage('PROPNAME_COMPLETE_SETS'), 'ACTIVE'=>'Y', 'SORT'=>'12000', 'PROPERTY_TYPE'=>'S', 'MULTIPLE'=>'Y', 'USER_TYPE'=>'UserCompleteSet'),
	
	//add in 1.9.0
	'TURBO_YANDEX_LINK'=>Array('NAME' => GetMessage("PROPNAME_REVIEWS_YM"), 'ACTIVE'=>'Y', 'SORT'=>'14101', 'PROPERTY_TYPE' => 'S'),
	'MAILRU_ID'=>Array('NAME' => GetMessage("PROPNAME_REVIEWS_MR"), 'ACTIVE'=>'Y', 'SORT'=>'14102', 'PROPERTY_TYPE' => 'S'),
	'ARTICLE'=>Array('NAME' => GetMessage("PROPNAME_ARTICLE"), 'ACTIVE'=>'Y', 'SORT'=>'14200', 'PROPERTY_TYPE' => 'S'),
	'HOLIDAY'=>Array('NAME' => GetMessage("PROPNAME_HOLIDAY"), 'ACTIVE'=>'Y', 'SORT'=>'14300', 'PROPERTY_TYPE' => 'E'),
	'VIDEO'=>Array('NAME' => GetMessage("PROPNAME_VIDEO"), 'ACTIVE'=>'Y', 'SORT'=>'14400', 'PROPERTY_TYPE' => 'S', 'MULTIPLE' => 'Y'/*, 'MULTIPLE_CNT' => '2'*/),
	
	//add in 1.9.1
	'ID_3D_MODEL'=>Array('NAME' => GetMessage("PROPNAME_3DMODEL"), 'ACTIVE'=>'Y', 'SORT'=>'14500', 'PROPERTY_TYPE' => 'S'),
	
	//add in 1.12.0
	'MANUAL' => Array('NAME' => GetMessage("PROPNAME_MANUAL"), 'ACTIVE'=>'Y', 'PROPERTY_TYPE' => 'F', 'SORT'=>'14500', "MULTIPLE"=>"Y", "WITH_DESCRIPTION"=>"Y"),
	'SERVICE' => Array('NAME' => GetMessage("PROPNAME_SERVICE"), 'PROPERTY_TYPE' => 'E', 'SORT'=>'14350', "MULTIPLE"=>"Y"),
);
/* SERVICE */
$res = CIBlock::GetList(Array(), Array('TYPE'=>'dict', "CODE"=>'service'), true);
if($ar_res = $res->Fetch())
	$arNewProps['SERVICE']["LINK_IBLOCK_ID"] = $ar_res['ID'];

$arFilter = array() ;
$arTypes = array("catalog", "catalog_%");
$dbSites = CSite::GetList($by="id", $order="asc", Array());

while ($arSite = $dbSites->Fetch())
	$arTypes[] = $arSite['ID']."_%" ;

foreach($arTypes as $type)
{
	$arFilter['TYPE'] = $type ;
	$dbIBlocks = CIBlock::GetList(Array("ID"=>"ASC"), $arFilter, false);

	$ibp = new CIBlockProperty;
	while($arIBlock = $dbIBlocks->Fetch())
	{
		$dbProps = CIBlock::GetProperties($arIBlock['ID'], Array(), Array());
		$arPropsCode = array() ;
		while($arProp = $dbProps->Fetch())
			$arPropsCode[] = $arProp['CODE'] ;

		foreach($arNewProps as $code => $arProp)
		{
			if(!in_array($code, $arPropsCode))
			{
				$str_log = "IBLOCK_TYPE={$arIBlock['IBLOCK_TYPE_ID']}; IBLOCK_ID = {$arIBlock['ID']}; " ;
				$arProp['CODE'] = $code ;
				$arProp['IBLOCK_ID'] = $arIBlock['ID'] ;
				$str_log .= "arProp[CODE] = {$arProp['CODE']}; ";
				$created_prop = $ibp->Add($arProp) ;
				$str_log .= "created_prop ={$created_prop};";
				if(IntVal($created_prop)<= 0)
					$str_log .= ' >>> '.$ibp->LAST_ERROR ;
				else
					$str_log .= ' >>> ok';
				$str_log .= "\r\n" ;
				file_put_contents ($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/yenisite/bitronic/add_new_props_log".$timestamp.'.log', $str_log, FILE_APPEND) ;
			}
		}
	}
}