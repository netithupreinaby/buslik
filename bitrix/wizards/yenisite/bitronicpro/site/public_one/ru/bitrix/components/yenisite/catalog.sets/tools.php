<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;
if(!check_bitrix_sessid() || !CModule::IncludeModule("iblock")) die();
if($_POST['el_id'])
{
	$arElement = CIBlockElement::GetByID(IntVal($_POST['el_id']))->Fetch();
	echo $arElement['IBLOCK_ID'] ;
}
?>