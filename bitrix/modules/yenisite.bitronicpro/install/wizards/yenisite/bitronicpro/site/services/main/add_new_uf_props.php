<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
function AddUF ($IBLOCK_ID, $FIELD_NAME, $LABEL_RU, $LABEL_EN)
{
	if(CModule::IncludeModule("iblock"))
	{
		$arFields = Array(
			"ENTITY_ID" => "IBLOCK_{$IBLOCK_ID}_SECTION",
			"FIELD_NAME" => $FIELD_NAME,
			"USER_TYPE_ID" => "string",
			"EDIT_FORM_LABEL" => Array("ru"=>$LABEL_RU, "en"=>$LABEL_EN)
		);
		$obUserField	= new CUserTypeEntity;
		$obUserField->Add($arFields);
		unset($obUserField);
	}
}
// ! Next PROPERTY create in new script !
if(!CModule::IncludeModule("iblock"))
	return false;
$timestamp = date('Y_m_d_H_i_s') ;	

$arFilter = array() ;
$arTypes = array("catalog_%") ;
$dbSites = CSite::GetList($by="id", $order="asc", Array());

while ($arSite = $dbSites->Fetch())
	$arTypes[] = $arSite['ID']."_%" ;

foreach($arTypes as $type)
{
	$arFilter['TYPE'] = $type ;
	$dbIBlocks = CIBlock::GetList(Array("ID"=>"ASC"), $arFilter, false);

	while($arIBlock = $dbIBlocks->Fetch())
	{
		$dbUF = CUserTypeEntity::GetList( array('ID'=>'ASC'), array("ENTITY_ID" => "IBLOCK_".$arIBlock['ID']."_SECTION") );
		while($arUF = $dbUF->Fetch())
			$arrUFcreated[] = $arUF['FIELD_NAME'] ;
		
		$arNewUF = array ('UF_H1', 'UF_TITLE', 'UF_DESCRIPTION', 'UF_KEYWORDS') ;
		
		foreach ($arNewUF as $uf_code)
		{
			if(!in_array($uf_code, $arrUFcreated))
			{
				AddUF($arIBlock['ID'], $uf_code, GetMessage($uf_code), GetMessage($uf_code)) ;
				
				$str_log = "IBLOCK_TYPE={$arIBlock['IBLOCK_TYPE_ID']}; IBLOCK_ID = {$arIBlock['ID']}; UF_CODE = {$uf_code}\r\n" ;
				file_put_contents ($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/yenisite/bitronic/add_new_props_log".$timestamp.'.log', $str_log, FILE_APPEND) ;
			}
		}
	}
}