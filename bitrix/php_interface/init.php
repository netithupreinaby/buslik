<?include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/bitronic_ini.php") ;?>
<?include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/helpers.php") ;?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite/catalog.sets/userprop.php");
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/1cLog.txt");

define("RE_SITE_KEY","6LdUphAUAAAAACiEhXBimGFfuTmFgXxvP7WUKJO7");
define("RE_SEC_KEY","6LdUphAUAAAAAEAO6uxRZlMneWWB8ntYLC8df4lj");


AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddElementOrSectionCode"); 
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddElementOrSectionCode"); 
AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", "AddElementOrSectionCode");
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", "AddElementOrSectionCode");
AddEventHandler('form', 'onBeforeResultAdd', 'my_onAfterResultAddUpdate');

//AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddElementBrandsId");
//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddElementBrandsId");

/*AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddElementReferenceId");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddElementReferenceId");*/



AddEventHandler('form', 'onAfterResultAdd', 'onAfterResultAddUpdate');

function onAfterResultAddUpdate($WEB_FORM_ID, $RESULT_ID)
{
  // действие обработчика распространяется только на форму с ID=6

  if ($WEB_FORM_ID == 2) 
  {
    
 	$resultData = CFormResult::GetDataByID($RESULT_ID);


     $el = new CIBlockElement;

     $PROP = array();
     $PROP[616] = $resultData['SIMPLE_QUESTION_125']['0']['USER_TEXT'];
     $PROP[615] = $resultData['SIMPLE_QUESTION_886']['0']['USER_TEXT'];
     $PROP[613] = $resultData['SIMPLE_QUESTION_453']['0']['USER_TEXT'];

     $arLoadProductArray = Array(


         "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
         "IBLOCK_ID" => 48,
         "PROPERTY_VALUES" => $PROP,
         "NAME" => $resultData['SIMPLE_QUESTION_453']['0']['USER_TEXT'],
         "ACTIVE" => "N"

     );

     $el->Add($arLoadProductArray);
  }
}



function AddElementOrSectionCode(&$arFields) { 
	
   $params = array(
      "max_len" => "100", 
      "change_case" => "L", 
      "replace_space" => "_", 
      "replace_other" => "_", 
      "delete_repeat_replace" => "true", 
      "use_google" => "false", 
   );
 
   if (strlen($arFields["NAME"])>0 && strlen($arFields["CODE"])<=4) {
      $arFields['CODE'] = CUtil::translit($arFields["NAME"], "ru", $params);    
   }



}

   function AddElementBrandsId(&$arFields){

     $propertiesId = array(687,688,689);
     processLinkProperties($propertiesId,$arFields);

   }

   function processLinkProperties($propertiesId,$arFields){

      foreach($propertiesId as $propertyId){

         if($arFields['PROPERTY_VALUES'][$propertyId]['n0']['VALUE']) {
            $arFields['PROPERTY_VALUES'][$propertyId]['n0']['VALUE'] = getBrandInternalId($arFields,$propertyId);
         }

      }


   }

   function getBrandInternalId($arFields,$propertyId){

      if(!$_SESSION['BRANDS_ID'][$propertyId]) {

         $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
         $arFilter = Array('EXTERNAL_ID' => $arFields['PROPERTY_VALUES'][$propertyId]['n0']['VALUE'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
         $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
         $ob = $res->GetNext();
         $brandId = $_SESSION['BRANDS_ID'][$arFields['PROPERTY_VALUES'][$propertyId]['n0']['VALUE']] = $ob['ID'];

      }else{

         $brandId= $_SESSION['BRANDS_ID'][$propertyId];

      }

      return $brandId;

   }
   
   function AddElementReferenceId(){
	   
	  if(empty($_SESSION['countries'])){

		

      }
	   
   }

   function getReference($referenceId){

      CModule::IncludeModule('highloadblock');
      $hlbl = $referenceId;
      $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
      $entity = HL\HighloadBlockTable::compileEntity($hlblock);
      $entity_data_class = $entity->getDataClass();
      $main_query_requests = new Entity\Query($entity_requests_data_class);
      $main_query_requests->setSelect(array('ID','UF_NAME','UF_STRANAIZGOTOVITEL'));
      $result_requests = $main_query_requests->exec();
      $result_requests = new CDBResult($result_requests);

      while ($row_requests=$result_requests->Fetch()) {
      $requests[] = $row_requests; //массив выбранных элементов
      }

   }


   function my_onAfterResultAddUpdate($WEB_FORM_ID, $data,$arValues)
   {

      global $APPLICATION;


      if ($WEB_FORM_ID == 1 ) {

          $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_");
          $arFilter = Array("ID" => $arValues["ELEMENT_ID"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
          $res = CommonMethods::getObjectByIdIblock($arValues["ELEMENT_ID"], '30');
          $db_props = CIBlockElement::GetProperty(30, $arValues["ELEMENT_ID"], array("sort" => "asc"), Array("CODE" => "PLACES_COUNT"));
          if ($ar_props = $db_props->Fetch()){
              $placesCount = $ar_props["VALUE"];
              if($placesCount) $placesCount=$placesCount-1;
              CIBlockElement::SetPropertyValues($arValues["ELEMENT_ID"], 30, $placesCount, 'PLACES_COUNT');

          }



     }elseif($WEB_FORM_ID == 4){

        $arFilter = Array('IBLOCK_ID'=>71,'CODE' => $arValues["ELEMENT_ID"]);
        $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
        $section = $db_list->GetNext();

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), array('EXTERNAL_ID'=>$arValues["ELEMENT_ID"]), Array("ID","NAME","IBLOCK_ID"));
        $arResElement = $res->GetNext();
        if(!$section) {

            if($arResElement);

            $bs = new CIBlockSection;
            $arFields = Array(
                "ACTIVE" => 'Y',
                "IBLOCK_ID" => 71,
                "NAME" => $arResElement['~NAME'],
                "CODE" => $arValues["ELEMENT_ID"]

            );


           $sectionId = $bs->Add($arFields);



        }else{

            $sectionId = $section['ID'];


        }


          $el = new CIBlockElement;

          $PROP = array();
          $PROP[1000] = $arValues['real_customer_type'];
          $PROP[1001] = $arValues['form_text_13'];
          $PROP[1002] = $arValues['rating'];
          $PROP[1003] = $arValues['form_textarea_14'];
          $PROP[1004] = $arValues['form_text_9'];
          $PROP[1074] = ConvertTimeStamp(time(), "FULL");

          $selectId = 12;
          $rsField = CFormField::GetByID($selectId);
          $dataSelect = $rsField->Fetch();


          $arLoadProductArray = Array(

              "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
              "IBLOCK_SECTION_ID" => $sectionId,          // элемент лежит в корне раздела
              "IBLOCK_ID"      => 71,
              "PROPERTY_VALUES"=> $PROP,
              "NAME"           => $arValues['form_text_13'],
              "ACTIVE"         => "N",            // активен

          );

        $PRODUCT_ID = $el->Add($arLoadProductArray);
        /**-------подсчет рейтинга для товара----------**/
        /*$arComents = array();
        $fullRating = 0;
        $arFilter = array('IBLOCK_ID'=>71,"SECTION_ID"=>$section['ID'],'ACTIVE'=>'Y');
        $res = CIBlockElement::GetList(Array('DATE_ACTIVE_FROM'=>'DESC'), $arFilter, false,array(),array("ID","NAME","DATE_ACTIVE_FROM","PROPERTY_RATING"));

        while ($arComent = $res->GetNext()) {
            $fullRating += $arComent['PROPERTY_RATING_VALUE'];
            $arComents[]=$arComent;
        }

        $arComents = array_filter($arComents);

        $realRating = $fullRating/count($arComents);
          $resultMy = CIBlockElement::SetPropertyValues($arResElement['ID'], $arResElement['IBLOCK_ID'],  $realRating, 'RATING');*/


     }else{




      }
       
       
   }



   

?>