<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule("iblock");

function get_arIblockType()
{
  require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/mainpage.php");
  $id = CMainPage::GetSiteByHost();
  $dbSite = CSite::GetByID($id);
  $arSite = $dbSite -> Fetch();
  
  $arIblockType["SITE_ID"] = $id;
  
  return $arIblockType;
}

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
    $obUserField  = new CUserTypeEntity;
    $obUserField->Add($arFields);
    unset($obUserField);
  }
}

class NEWIBLOCKTYPE extends CWizardStep
{
  function InitStep()
  {
    $this->SetStepID("NEWIBLOCKTYPE");
    $this->SetNextStep("IBLOCKTYPEADD");
    $this->SetCancelCaption(GetMessage("EXIT"));
    $this->SetCancelStep("Cancel");
  }

    /*function OnPostForm()
    {
    $wizard = &$this->GetWizard();
    $wizard->SetCurrentStep("NEWIBLOCKTYPE");
    }*/

  function ShowStep()
  {
    $this->content .= '<table class="wizard-data-table">';
    //$this->content .= '<tr><th align="right"></th><td></td></tr>';
        $this->content .= '<tr><th align="right">'.GetMessage('CHOICE_TYPE').':</th><td>';
    $this->content .= $this->ShowRadioField("newib", "old", Array("checked" => "checked"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('CREATE_TYPE').':</th><td>';
    $this->content .= $this->ShowRadioField("newib", "new");
    $this->content .= '</td></tr></table>';
  }
}

class IBLOCKTYPEADD extends CWizardStep
{
  function InitStep()
  {
    $this->SetPrevStep("NEWIBLOCKTYPE");     
    $this->SetStepID("IBLOCKTYPEADD");
    $this->SetNextStep("IBLOCKADD");   
        $this->SetCancelCaption(GetMessage("EXIT"));
    $this->SetCancelStep("ancel");
  }

   /* function OnPostForm()
    {
    $wizard = &$this->GetWizard();
    $wizard->SetCurrentStep("IBLOCKTYPE");
    }*/

  function ShowStep()
  {
  
    $wizard =& $this->GetWizard();
    $newib = $wizard->GetVar("newib");
    $arIblockType = get_arIblockType();
    
    $arr = array();
    $res = CIBlockType::GetList( Array(), Array("ID" => $arIblockType["SITE_ID"]."_%"), true);
    $arr[0] = '';
    while($ar_res = $res->Fetch())
    {
      $arIBType = CIBlockType::GetByIDLang($ar_res["ID"], LANG);
      $arr[$ar_res["ID"]] = $arIBType["NAME"];
    }
    // äëÿ ñîâìåñòèìîñòè:
    $res = CIBlockType::GetList( Array(), Array("ID" => "catalog_%"), true);
    while($ar_res = $res->Fetch())
    {
      $arIBType = CIBlockType::GetByIDLang($ar_res["ID"], LANG);
      $arr[$ar_res["ID"]] = $arIBType["NAME"];
    }
    
    $this->content .= $this->ShowHiddenField("newib", $newib);
    $this->content .= '<table class="wizard-data-table">';
    if($newib=='old')
    {
      $this->content .= '<tr><th align="right">'.GetMessage('CHOICE_IBTYPE').':</th><td>';
      $this->content .= $this->ShowSelectField("ibtype", $arr, array("id" => "ibtype"));
      $this->content .= '</td></tr>';
    }
    else
    {
      $this->content .= '<tr><th align="right">'.GetMessage('CREATE_NEW_TYPE').':</th><td></td></tr>';
      $this->content .= '<tr><th align="right">'.GetMessage('TYPE_NAME').':</th><td>';
      $this->content .= $this->ShowInputField("text", "ibtype_new_name", Array("size" => "20"));
      $this->content .= '</td></tr>';
      $this->content .= '<tr><th align="right">'.GetMessage('TYPE_ID').':</th><td>';
      $this->content .= $this->ShowInputField("text", "ibtype_new_id", Array("size" => "20"));
      $this->content .= '</td></tr>';
    }
    $this->content .= '</table>';
  }
}

class IBLOCKADD extends CWizardStep
{
  function InitStep()
  {
    $wizard =& $this->GetWizard();
    $name = $wizard->GetVar("ibtype_new_name");
    $id_new = $wizard->GetVar("ibtype_new_id");
    $id = $wizard->GetVar("ibtype");
    $newib = $wizard->GetVar("newib");
    $this->SetStepID("IBLOCKADD");
    $this->SetPrevStep("IBLOCKTYPEADD");
    if(($id && $newib=='old') || ($name && $id_new && $newib=='new'))
      $this->SetNextStep("COMPONENTADD");
    $this->SetCancelCaption(GetMessage("EXIT"));
    $this->SetCancelStep("cancel");
  }
  function ShowStep()
  {
    $arIblockType = get_arIblockType();
    $wizard =& $this->GetWizard();
    $name = $wizard->GetVar("ibtype_new_name");
    $id_new = $wizard->GetVar("ibtype_new_id");
    $id = $wizard->GetVar("ibtype");
    $id_new = strtolower($id_new);
    $newib = $wizard->GetVar("newib");
    
    if($name && $id_new && $newib=='new')
    {
      $res = CIBlockType::GetByID($arIblockType["SITE_ID"].'_'.$id_new);
      if($res->GetNext())
      {
        $this->content .= GetMessage('NOTICE_1').$arIblockType["SITE_ID"].'_'.$id_new. GetMessage('NOTICE_2');
        return;
      }
      $arFields = Array(
              'ID'=>$arIblockType["SITE_ID"].'_'.str_replace('-','_',$id_new),
              'SECTIONS'=>'Y',
              'LANG'=>Array(
                LANG =>Array(
                  'NAME'=>$name,
                )
              )
            );

      $obBlocktype = new CIBlockType;
      $id = $obBlocktype->Add($arFields);
      if(!$id)
      {
        $this->content .= GetMessage('NOTICE_3').$arIblockType["SITE_ID"].'_'.$id_new.GetMessage('NOTICE_4'); 
        return;
      }
    }
    elseif(!$name && !$id_new && $newib=='old')
    {      
      if(!$id)
      {
        $this->content .= GetMessage('NOTICE_5');
        return;
      }
    }
    else
    {
      $this->content .=GetMessage('NOTICE_6');
      return;
    }
    $this->content .= $this->ShowHiddenField("iblocktype", $id);
    $this->content .= $this->ShowHiddenField("iblocktype_name", $name);
    $this->content .= '<table class="wizard-data-table">';
    $this->content .= '<tr><th colspan="2" align="center"><b>'.GetMessage('TITLE_1').'</b></th></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_2').':</th><td>';
    $this->content .= $this->ShowInputField("text", "ib_new_name", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_3').':</th><td>';
    $this->content .= $this->ShowInputField("text", "ib_new_code", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_4').':</th><td>';
    $this->content .= $this->ShowInputField("text", "xmlid", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th colspan="2" align="center"><b>'.GetMessage('TITLE_5').'</b></th></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_6').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis1", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_7').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis2", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_8').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis3", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_9').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis4", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_10').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis5", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_11').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis6", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_12').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis7", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_13').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis8", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_14').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis9", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_15').':</th><td>';
    $this->content .= $this->ShowInputField("text", "podpis10", Array("size" => "20"));
    $this->content .= '</td></tr>';
    $this->content .= '<tr><th colspan="2" align="center"><b>'.GetMessage('TITLE_16').'</b></th></tr>';
    $this->content .= '<tr><th align="right">'.GetMessage('TITLE_17').':</th><td>';
    $this->content .= $this->ShowInputField("textarea", "opis", Array("cols" => "50","rows"=>"10"));
    $this->content .= '</td></tr>';
    $this->content .= '</table>';
  }
}


class COMPONENTADD extends CWizardStep
{
  function InitStep()
  {
    $this->SetStepID("COMPONENTADD");//ID 
    //
    $wizard =& $this->GetWizard();
    $name = $wizard->GetVar("ib_new_name");
    
    if(!$name)
        $this->SetPrevStep("IBLOCK");
    $this->SetCancelCaption(GetMessage("EXIT"));
    $this->SetCancelStep("cancel");
  }

  function ShowStep()
  {
    $wizard =& $this->GetWizard();
    $arIblockType = get_arIblockType();
    
    $name = $wizard->GetVar("ib_new_name");
    $xmlid = $wizard->GetVar("xmlid");
    $code_new = strtolower($wizard->GetVar("ib_new_code"));
    $iblocktype = $wizard->GetVar("iblocktype");
    $DESCRIPTION = $wizard->GetVar("opis");
    $podpis1 = $wizard->GetVar("podpis1");
    $podpis2 = $wizard->GetVar("podpis2");
    $podpis3 = $wizard->GetVar("podpis3");
    $podpis4 = $wizard->GetVar("podpis4");
    $podpis5 = $wizard->GetVar("podpis5");
    $podpis6 = $wizard->GetVar("podpis6");
    $podpis7 = $wizard->GetVar("podpis7");
    $podpis8 = $wizard->GetVar("podpis8");
    $podpis9 = $wizard->GetVar("podpis9");
    $podpis10 = $wizard->GetVar("podpis10");
    if(!$name || !$code_new)
    {
      $this->content .= GetMessage("BACK"); 
      return;
    }
    else
    {
      $ib = new CIBlock;

      $ibtype_folder_name = str_replace($arIblockType["SITE_ID"]."-","",str_replace("catalog-", "", str_replace("_", "-", $iblocktype)));
      $iblock_code = str_replace("_", "-", $code_new) ;
      $link_to_iblock = '/'.$ibtype_folder_name.'/'.$iblock_code.'/' ;
      $arFields = Array(
        "ACTIVE" => "Y",
        "NAME" => $name,
        "CODE" => $iblock_code,
        "LIST_PAGE_URL" => $link_to_iblock,
        "SECTION_PAGE_URL" => $link_to_iblock."#SECTION_CODE#/",
        "DETAIL_PAGE_URL" => $link_to_iblock."#SECTION_CODE#/#ELEMENT_CODE#.html",
        "IBLOCK_TYPE_ID" => $iblocktype,
        "SITE_ID" => $arIblockType["SITE_ID"],
        "DESCRIPTION" => $DESCRIPTION,
        "DESCRIPTION_TYPE" => 'text',
        "XML_ID" => $xmlid,
        "FIELDS" => array(
            "CODE" => array(
              "IS_REQUIRED" => "Y",
              "DEFAULT_VALUE" => array(
                "UNIQUE" => "Y",
                "TRANSLITERATION" => "Y",
                "TRANS_LEN" => 100,
                "TRANS_CASE" => "L",
                "TRANS_SPACE" => "-",
                "TRANS_OTHER" => "-",
                "TRANS_EAT" => "Y"
              )
            ),
            "SECTION_CODE" => array(
              "IS_REQUIRED" => "Y",
              "DEFAULT_VALUE" => array(
                "UNIQUE" => "Y",
                "TRANSLITERATION" => "Y",
                "TRANS_LEN" => 100,
                "TRANS_CASE" => "L",
                "TRANS_SPACE" => "-",
                "TRANS_OTHER" => "-",
                "TRANS_EAT" => "Y"
              )
            )
          )
        );
      $ID = $ib->Add($arFields);
      CIBlock::SetPermission($ID, Array("2"=>"R"));

      AddUF($ID, 'UF_TITLE', GetMessage('UF_TITLE'), GetMessage('UF_TITLE')) ;
      AddUF($ID, 'UF_H1', GetMessage('UF_H1'), GetMessage('UF_H1')) ;
      AddUF($ID, 'UF_DESCRIPTION', GetMessage('UF_DESCRIPTION'), GetMessage('UF_DESCRIPTION')) ;
      AddUF($ID, 'UF_KEYWORDS', GetMessage('UF_KEYWORDS'), GetMessage('UF_KEYWORDS')) ;

      if(CModule::IncludeModule('catalog')){
        CCatalog::Add(array("IBLOCK_ID" => $ID, "YANDEX_EXPORT" => "Y"));
      }
      $dop = array(
          "SECTIONS_NAME" => $podpis1,
          "SECTION_NAME" => $podpis2,
          "SECTION_ADD" => $podpis3,
          "SECTION_EDIT" => $podpis4,
          "SECTION_DELETE" => $podpis5,
          "ELEMENTS_NAME" => $podpis6,
          "ELEMENT_NAME" => $podpis7,
          "ELEMENT_ADD" => $podpis8,
          "ELEMENT_EDIT" => $podpis9,
          "ELEMENT_DELETE" => $podpis10
        );

      CIBlock::SetMessages($ID, $dop);

      $ibp = new CIBlockProperty;
      $ibp->Add($arFields);

        
      /* META */
      $arProp = Array("NAME" => GetMessage("PROP_11") , "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "H1", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      $arProp = Array("NAME" => "TITLE" , "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "TITLE", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      $arProp = Array("NAME" => "KEYWORDS" , "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "KEYWORDS", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      $arProp = Array("NAME" => "DESCRIPTION" , "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "DESCRIPTION", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      /* for new functions */
      $arProp = Array("NAME" => GetMessage("PROP_14") , "ACTIVE" => "Y", "SORT" => "11000", "CODE" => "WEEK_COUNTER", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      $arProp = Array("NAME" => GetMessage("PROP_15") , "ACTIVE" => "Y", "SORT" => "11000", "CODE" => "WEEK", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
	  
      $arProp = Array("NAME" => GetMessage("PROP_SALE_INT") , "ACTIVE" => "Y", "SORT" => "11000", "CODE" => "SALE_INT", "PROPERTY_TYPE" => "N", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      /* */
      $arProp = Array("NAME" => GetMessage("PROP_12") , "ACTIVE" => "Y", "SORT" => "10500", "CODE" => "YML", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);
      
      $arProp = Array("NAME" => GetMessage("PROP_13") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "FOR_ORDER", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);
      
      
        
      /*  */            
      $arProp = Array("NAME" => GetMessage("PROP_1") , "ACTIVE" => "Y", "SORT" => "10000", "CODE" => "SIZES", "PROPERTY_TYPE" => "S", "IBLOCK_ID" => $ID);
      $ibp->Add($arProp);
      
      

      /*  */
      $arProp = Array("NAME" => GetMessage("PROP_4") , "ACTIVE" => "Y", "SORT" => "9500", "CODE" => "SHOW_MAIN", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);
	  
      $arProp = Array("NAME" => GetMessage("PROP_2") , "ACTIVE" => "Y", "SORT" => "9500", "CODE" => "NEW", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);

      $arProp = Array("NAME" => GetMessage("PROP_3") , "ACTIVE" => "Y", "SORT" => "9500", "CODE" => "HIT", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);
        
      $arProp = Array("NAME" => GetMessage("PROP_10") , "ACTIVE" => "Y", "SORT" => "9500", "CODE" => "SALE", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);
	  
      $arProp = Array("NAME" => GetMessage("PROP_BESTSELLER") , "ACTIVE" => "Y", "SORT" => "9500", "CODE" => "BESTSELLER", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);	  
      
      // $arProp = Array("NAME" => GetMessage("PROP_10") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "SALE", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "C", "IBLOCK_ID" => $ID);
      // $arProp["VALUES"][0] = Array( "VALUE" => "Y", "DEF" => "N", "SORT" => "9000" );
      // $ibp->Add($arProp);
        
      $arProp = Array("NAME" => GetMessage("PROP_9") , "ACTIVE" => "Y", "SORT" => "9000", "CODE" => "WARRANTY", "PROPERTY_TYPE" => "L", "LIST_TYPE" => "L", "IBLOCK_ID" => $ID);
      $arProp["VALUES"][0] = Array( "VALUE" => GetMessage("PROP_VALUE_1"), "DEF" => "N", "SORT" => "9000" );
      $arProp["VALUES"][1] = Array( "VALUE" => GetMessage("PROP_VALUE_2"), "DEF" => "N", "SORT" => "9000" );
      $arProp["VALUES"][2] = Array( "VALUE" => GetMessage("PROP_VALUE_3"), "DEF" => "N", "SORT" => "9000" );
      $ibp->Add($arProp);

      /*  */
      $arProp = Array("NAME" => GetMessage("PROP_5") , "MULTIPLE"=>"Y", "ACTIVE" => "Y", "SORT" => "12000",
        "CODE" => "MORE_PHOTO", "PROPERTY_TYPE" => "F", "IBLOCK_ID" => $ID,
        "WITH_DESCRIPTION" => "Y", "XML_ID" => "CML2_PICTURES");
      $ibp->Add($arProp);       
        
      $res1 = CIBlock::GetList(array(), array("CODE" => "producer"))->Fetch();
      
      /*       */
      $arProp = Array("NAME" => GetMessage("PROP_6") , "MULTIPLE"=>"N", "ACTIVE" => "Y", "SORT" => "20000", "CODE" => "PRODUCER", "PROPERTY_TYPE" => "E", "USER_TYPE" => "EList", "IBLOCK_ID" => $ID, "LINK_IBLOCK_ID" => $res1[ID]);
      $ibp->Add($arProp);
        
      $res1 = CIBlock::GetList(array(), array("CODE" => "countries"))->Fetch();

      $arProp = Array("NAME" => GetMessage("PROP_7") , "MULTIPLE"=>"N", "ACTIVE" => "Y", "SORT" => "20000", "CODE" => "COUNTRY", "PROPERTY_TYPE" => "E", "USER_TYPE" => "EList", "IBLOCK_ID" => $ID, "LINK_IBLOCK_ID" => $res1[ID]);
      $ibp->Add($arProp);

	  /*REVIEWS*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("REVIEWS_YM"), 'CODE' => 'TURBO_YANDEX_LINK', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14101');
	  $ibp->Add($arProp);	
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("REVIEWS_MR"), 'CODE' => 'MAILRU_ID', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14102');
	  $ibp->Add($arProp);
	  
	  /*ARTICLE*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("ARTICLE"), 'CODE' => 'ARTICLE', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14200');
	  $ibp->Add($arProp);	
	  
	  /*HOLIDAY*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("HOLIDAY"), 'CODE' => 'HOLIDAY', 'PROPERTY_TYPE' => 'E', 'SORT'=>'14300');
		$res = CIBlock::GetList(Array(), Array('TYPE'=>'dict', "CODE"=>'holiday'), true);
		if($ar_res = $res->Fetch())
			$arProp["LINK_IBLOCK_ID"] = $ar_res['ID'];
	  $ibp->Add($arProp);
	  
	  /*SERVICE*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("SERVICE"), 'CODE' => 'SERVICE', 'PROPERTY_TYPE' => 'E', 'SORT'=>'14350');
		$res = CIBlock::GetList(Array(), Array('TYPE'=>'dict', "CODE"=>'service'), true);
		if($ar_res = $res->Fetch())
			$arProp["LINK_IBLOCK_ID"] = $ar_res['ID'];
	  $ibp->Add($arProp);

	  /*VIDEO*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("VIDEO"), 'CODE' => 'VIDEO', 'PROPERTY_TYPE' => 'S', 'MULTIPLE' => 'Y', /*'MULTIPLE_CNT' => '2',*/ 'SORT'=>'14400');
	  $ibp->Add($arProp);

	  /*3D MODEL*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("3DMODEL"), 'CODE' => 'ID_3D_MODEL', 'PROPERTY_TYPE' => 'S', 'SORT'=>'14500');
	  $ibp->Add($arProp);

	  /*MANUAL*/
	  $arProp = Array('IBLOCK_ID' => $ID, 'NAME' => GetMessage("MANUAL"), 'CODE' => 'MANUAL', 'PROPERTY_TYPE' => 'F', 'SORT'=>'14500', "MULTIPLE"=>"Y", "WITH_DESCRIPTION"=>"Y");
	  $ibp->Add($arProp);
	  
	  ///////////////////////////////////////////////////////////////////////////////////////////////
	  // LINK CREATED PROPERTIES FOR IBLOCK (NOT FOR CONCRETE SECTION OF IBLOCK) AND NOT SHOW IN SMART FILTER//
	  ///////////////////////////////////////////////////////////////////////////////////////////////
	  $props = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $ID, "CHECK_PERMISSIONS" => "N"));
	  while($p = $props->Fetch())
	  {
	  	CIBlockSectionPropertyLink::Delete(0, $p["ID"]);
	  	CIBlockSectionPropertyLink::Add(0, $p["ID"], array("SMART_FILTER" => 'N'));
	  }
		
      create_dir_and_index($iblocktype, $ibtype_folder_name, $ID, $iblock_code, $name,   $link_to_iblock);
    }
    $this->content .= GetMessage("MASTER_COMPLETE"); 
  }
}


class CancelStep extends CWizardStep
{

  function InitStep()
  {
    $this->SetStepID("ancel");
    $this->SetCancelCaption(GetMessage("EXIT"));
    $this->SetCancelStep("ancel");
  }

  function ShowStep()
  {
    $this->content .= GetMessage("MASTER_CANCEL");
  }
}

class FinalStep extends CWizardStep
{

  function InitStep()
  {
    $this->SetStepID("final");
    $this->SetCancelCaption(GetMessage("CLOSE"));
    $this->SetCancelStep("final");
  }

  function ShowStep()
  {
    $this->content .= GetMessage("MASTER_SUCCESS");
  }
}



function create_dir_and_index($iblock_type, $ibtype_folder_name, $iblock_id, $iblock_code, $iblock_name,  $link_to_iblock)
{
  if (CModule::IncludeModule("yenisite.bitronic")) {
    $module = "yenisite.bitronic";
  } else if (CModule::IncludeModule("yenisite.bitroniclite")) {
    $module = "yenisite.bitroniclite";
  } else if (CModule::IncludeModule("yenisite.bitronicpro")) {
    $module = "yenisite.bitronicpro";
  }
  if (class_exists('CYSBitronicSettings'))
	$module = CYSBitronicSettings::getModuleId();
  $arIblockType = get_arIblockType();
  $sefOpt = COption::GetOptionString($module, "sef_mode", false, $arIblockType["SITE_ID"]);

  //$type_dir = str_replace("-", "_", $iblock_type);
  //$block_dir = str_replace("-", "_", $iblock_type);

  $type_dir = $_SERVER["DOCUMENT_ROOT"]."/".$ibtype_folder_name;
  $iblock_dir = $type_dir.'/'.$iblock_code;

  mkdir($type_dir, 0755);
  mkdir($iblock_dir, 0755);

  $type_dir .= '/';
  $iblock_dir .= '/';

  $arIBType = CIBlockType::GetByIDLang($iblock_type, 'ru');

  $iblock_name = str_replace('"',"", $iblock_name);
  
  if(!file_exists($type_dir.'index.php'))
  {
    $root_index = '<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetPageProperty("title", "'.$arIBType["NAME"].'");?>
    <?$APPLICATION->IncludeComponent("bitrix:menu", "bitronic_rubric", array(
      "ROOT_MENU_TYPE" => "rubric",
      "MENU_CACHE_TYPE" => "N",
      "MENU_CACHE_TIME" => "3600",
      "MENU_CACHE_USE_GROUPS" => "Y",
      "MENU_CACHE_GET_VARS" => array(
      ),
      "MAX_LEVEL" => "2",
      "CHILD_MENU_TYPE" => "left",
      "USE_EXT" => "Y",
      "DELAY" => "N",
      "ALLOW_MULTI_SELECT" => "N",
      "RESIZER2_SET" => "4"
      ),
      false
    );?>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
    
    $filename = $type_dir."index.php";
    $fh = fopen($filename, "w+");
    fwrite($fh, $root_index);
    fclose($fh);
  }

  if(!file_exists($type_dir.'.rubric.menu_ext.php'))
  {
    $rubric_menu_ext = '<?global $APPLICATION;  
      $aMenuLinksExt=$APPLICATION->IncludeComponent("yenisite:menu.ext", "", array(
          "ID" => $_REQUEST["ID"],
          "IBLOCK_TYPE" => array(
            0 => "'.$arIblockType["SITE_ID"].'_".str_replace(array("/", "-"),array("", "_"), $APPLICATION->GetCurDir()),
          ),
          "IBLOCK_ID" => array(
          ),
          "DEPTH_LEVEL_START" => "2",
          "DEPTH_LEVEL_FINISH" => "3",
          "DEPTH_LEVEL_SECTIONS" => "1",
          "IBLOCK_TYPE_URL" => "/#IBLOCK_TYPE#/",
          "IBLOCK_TYPE_URL_REPLACE" => "",
          "ELEMENT_CNT" => "Y",
          "ELEMENT_CNT_AVAILABLE" => "Y",
          "CACHE_TYPE" => "A",
          "CACHE_TIME" => "3600"
          ),
          false
        );
      $aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
    ?>';

    $filename = $type_dir.".rubric.menu_ext.php";
    $fh = fopen($filename, "w+");
    fwrite($fh, $rubric_menu_ext);
    fclose($fh);
  }
  if(!file_exists($type_dir.'.section.php'))
  {
    $root_section = '<?
    $sSectionName="'.$arIBType["NAME"].'";
    ?>';
    $filename = $type_dir.".section.php";
    $fh = fopen($filename, "w+");
    fwrite($fh, $root_section);
    fclose($fh);
  }

  $tmp = COption::GetOptionString('yenisite.market', 'color_scheme');

  if(strlen($tmp) != 0)
    $catalog_template = "catalog";
  else
    $catalog_template = ".default";

  if (!CModule::IncludeModule('yenisite.geoipstore')) {
    $prises = 'array(
        0 => "BASE",
        1 => "WHOLESALE",
        2 => "RETAIL",
        3 => "'.GetMessage("PRICE_1").'",
      )';
  } else {
    $prises = '$prices';
  }
    
  if($sefOpt == "Y")
  {
    $index =    '<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetTitle("'.$iblock_name.'");?>
    <?$APPLICATION->IncludeComponent("bitrix:catalog", "'.$catalog_template.'", array(
      "IBLOCK_TYPE" => "'.str_replace('-','_',$iblock_type).'",
      "IBLOCK_ID" => "'.$iblock_id.'",
      "BLOCK_IMG_SMALL" => "3",
      "BLOCK_IMG_BIG" => "4",
      "LIST_IMG" => "3",
      "TABLE_IMG" => "5",
      "DETAIL_IMG_SMALL" => "2",
      "DETAIL_IMG_BIG" => "7",
      "DETAIL_IMG_ICON" => "6",
      "COMPARE_IMG" => "4",
      "BASKET_URL" => "/personal/basket.php",
      "ACTION_VARIABLE" => "action",
      "PRODUCT_ID_VARIABLE" => "id",
      "SECTION_ID_VARIABLE" => "SECTION_ID",
      "SEF_MODE" => "N",
      "SEF_FOLDER" => "'.$link_to_iblock.'",  
      "AJAX_MODE" => "N",
      "AJAX_OPTION_JUMP" => "N",
      "AJAX_OPTION_STYLE" => "Y",
      "AJAX_OPTION_HISTORY" => "N",
      "CACHE_TYPE" => "A",
      "CACHE_TIME" => "36000",
      "CACHE_FILTER" => "N",
      "CACHE_GROUPS" => "Y",
      "SET_TITLE" => "N",
      "SET_STATUS_404" => "Y",
      "USE_FILTER" => "Y",
      "FILTER_NAME" => "arrFilter",
      "FILTER_FIELD_CODE" => array(
        0 => "",
        1 => "",
      ),
      "FILTER_PROPERTY_CODE" => array(    
        0 => "PRODUCER",
        1 => "COUNTRY",
      ),
      "FILTER_PRICE_CODE" => '.$prises.
      ',
      "USE_REVIEW" => "Y",
      "MESSAGES_PER_PAGE" => "10",
      "USE_CAPTCHA" => "Y",
      "REVIEW_AJAX_POST" => "N",
      "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
      "FORUM_ID" => "1",
      "URL_TEMPLATES_READ" => "",
      "SHOW_LINK_TO_FORUM" => "Y",
      "POST_FIRST_MESSAGE" => "N",
	  "PREORDER" => "N",
      "USE_COMPARE" => "Y",
      "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
      "COMPARE_FIELD_CODE" => array(
        0 => "NAME",
        1 => "",
      ),
      "COMPARE_PROPERTY_CODE" => array(
        0 => "COUNTRY",
        1 => "PRODUCER",
        2 => "",
      ),
      "COMPARE_ELEMENT_SORT_FIELD" => "sort",
      "COMPARE_ELEMENT_SORT_ORDER" => "asc",
      "DISPLAY_ELEMENT_SELECT_BOX" => "N",
      "PRICE_CODE" => '.$prises.
      ',
      "USE_PRICE_COUNT" => "N",
      "SHOW_PRICE_COUNT" => "1",
      "PRICE_VAT_INCLUDE" => "Y",
      "PRICE_VAT_SHOW_VALUE" => "N",
      "SHOW_TOP_ELEMENTS" => "Y",
      "TOP_ELEMENT_COUNT" => "9",
      "TOP_LINE_ELEMENT_COUNT" => "3",
      "TOP_ELEMENT_SORT_FIELD" => "sort",
      "TOP_ELEMENT_SORT_ORDER" => "asc",
      "TOP_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "PAGE_ELEMENT_COUNT" => "20",
      "LINE_ELEMENT_COUNT" => "3",
      "ELEMENT_SORT_FIELD" => "sort",
      "ELEMENT_SORT_ORDER" => "asc",
      "LIST_PROPERTY_CODE" => array(
        0 => "",
        1 => "PHOTO",
        2 => "",
      ),
      "LIST_OFFERS_FIELD_CODE" => array(
        0 => "NAME",
        1 => "PREVIEW_PICTURE",
        2 => "DETAIL_PICTURE",
        3 => "",
      ),
      "LIST_OFFERS_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "DETAIL_OFFERS_FIELD_CODE" => array(
        0 => "NAME",
        1 => "PREVIEW_PICTURE",
        2 => "DETAIL_PICTURE",
        3 => "",
      ),
      "DETAIL_OFFERS_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "INCLUDE_SUBSECTIONS" => "Y",
      "SECTION_META_KEYWORDS" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "SECTION_META_DESCRIPTION" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "SECTION_META_TITLE_PROP" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "DETAIL_PROPERTY_CODE" => array(    
        1 => "PRODUCER",
        2 => "COUNTRY",
      ),
      "DETAIL_META_KEYWORDS" => "'.GetMessage("BUY").' #NAME#",
      "DETAIL_META_DESCRIPTION" => "'.GetMessage("BUY").' #NAME#",
      "DETAIL_META_TITLE_PROP" => "'.GetMessage("BUY").' #NAME#",
	  "COMPARE_META_H1" => "'.GetMessage("META_COMPARE_THAT_BETTER").'",
      "COMPARE_META_TITLE_PROP" => "'.GetMessage("META_COMPARE_THAT_BETTER_BUY").'",
      "COMPARE_META_KEYWORDS" => "'.GetMessage("META_COMPARE_COMPARE").'",
	  "COMPARE_META_DESCRIPTION" => "'.GetMessage("META_COMPARE_COMPARE").'",
      "LINK_IBLOCK_TYPE" => "",
      "LINK_IBLOCK_ID" => "",
      "LINK_PROPERTY_SID" => "",
      "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
      "USE_ALSO_BUY" => "N",
      "DISPLAY_TOP_PAGER" => "N",
      "DISPLAY_BOTTOM_PAGER" => "Y",
      "PAGER_TITLE" => "'.$iblock_name.'",
      "PAGER_SHOW_ALWAYS" => "N",
      "PAGER_TEMPLATE" => "",
      "PAGER_DESC_NUMBERING" => "Y",
      "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
      "PAGER_SHOW_ALL" => "N",
      "AJAX_OPTION_ADDITIONAL" => "",
      "USE_STORE" => "Y",
      "USE_STORE_PHONE" => "N",
      "USE_STORE_SCHEDULE" => "N",
      "USE_MIN_AMOUNT" => "Y",
      "MIN_AMOUNT" => "10",
      "STORE_PATH" => "/store/#store_id#",
      "MAIN_TITLE" => "'.GetMessage("ON_STORE").'",
      "YS_STORES_MUCH_AMOUNT" => "15",
      "STORE_CODE" => "'.$stores.'",
      "SEF_URL_TEMPLATES__SEF" => array(
        "sections" => "",
        "section" => "#SECTION_CODE#/",
        "element" => "#ELEMENT_CODE#.html",
        "compare" => "compare.php?action=#ACTION_CODE#",
      ),
      "VARIABLE_ALIASES" => array(
        "SECTION_ID" => "SECTION_ID",
        "ELEMENT_ID" => "ELEMENT_ID",
      )
      ),
      false
    );?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
  }
  else
  {
    $index =    '<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetTitle("'.$iblock_name.'");?>
    <?$APPLICATION->IncludeComponent("bitrix:catalog", "'.$catalog_template.'", array(
      "IBLOCK_TYPE" => "'.$iblock_type.'",
      "IBLOCK_ID" => "'.$iblock_id.'",
      "BLOCK_IMG_SMALL" => "3",
      "BLOCK_IMG_BIG" => "4",
      "LIST_IMG" => "3",
      "TABLE_IMG" => "5",
      "DETAIL_IMG_SMALL" => "2",
      "DETAIL_IMG_BIG" => "7",
      "DETAIL_IMG_ICON" => "6",
      "COMPARE_IMG" => "4",
      "BASKET_URL" => "/personal/basket.php",
      "ACTION_VARIABLE" => "action",
      "PRODUCT_ID_VARIABLE" => "id",
      "SECTION_ID_VARIABLE" => "SECTION_ID",
	  "PRODUCT_QUANTITY_VARIABLE" => "quantity",
	  "USE_PRODUCT_QUANTITY" => "N",
      "SEF_MODE" => "Y",
      "SEF_FOLDER" => "'.$link_to_iblock.'",  
      "AJAX_MODE" => "N",
      "AJAX_OPTION_JUMP" => "N",
      "AJAX_OPTION_STYLE" => "Y",
      "AJAX_OPTION_HISTORY" => "N",
      "CACHE_TYPE" => "A",
      "CACHE_TIME" => "36000",
      "CACHE_FILTER" => "N",
      "CACHE_GROUPS" => "Y",
      "SET_TITLE" => "N",
      "SET_STATUS_404" => "Y",
      "USE_FILTER" => "Y",
      "FILTER_NAME" => "arrFilter",
      "FILTER_FIELD_CODE" => array(
        0 => "",
        1 => "",
      ),
      "FILTER_PROPERTY_CODE" => array(    
        0 => "PRODUCER",
        1 => "COUNTRY",
      ),
      "FILTER_PRICE_CODE" => '.$prises.
      ',
      "USE_REVIEW" => "Y",
      "MESSAGES_PER_PAGE" => "10",
      "USE_CAPTCHA" => "Y",
      "REVIEW_AJAX_POST" => "N",
      "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
      "FORUM_ID" => "1",
      "URL_TEMPLATES_READ" => "",
      "SHOW_LINK_TO_FORUM" => "Y",
      "POST_FIRST_MESSAGE" => "N",
      "USE_COMPARE" => "Y",
      "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
      "COMPARE_FIELD_CODE" => array(
        0 => "NAME",
        1 => "",
      ),
      "COMPARE_PROPERTY_CODE" => array(
        0 => "COUNTRY",
        1 => "PRODUCER",
        2 => "",
      ),
      "COMPARE_ELEMENT_SORT_FIELD" => "sort",
      "COMPARE_ELEMENT_SORT_ORDER" => "asc",
      "DISPLAY_ELEMENT_SELECT_BOX" => "N",
      "PRICE_CODE" => '.$prises.
      ',
      "USE_PRICE_COUNT" => "N",
      "SHOW_PRICE_COUNT" => "1",
      "PRICE_VAT_INCLUDE" => "Y",
      "PRICE_VAT_SHOW_VALUE" => "N",
      "SHOW_TOP_ELEMENTS" => "Y",
      "TOP_ELEMENT_COUNT" => "9",
      "TOP_LINE_ELEMENT_COUNT" => "3",
      "TOP_ELEMENT_SORT_FIELD" => "sort",
      "TOP_ELEMENT_SORT_ORDER" => "asc",
      "TOP_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "PAGE_ELEMENT_COUNT" => "20",
      "LINE_ELEMENT_COUNT" => "3",
      "ELEMENT_SORT_FIELD" => "sort",
      "ELEMENT_SORT_ORDER" => "asc",
      "LIST_PROPERTY_CODE" => array(
        0 => "",
        1 => "PHOTO",
        2 => "",
      ),
      "LIST_OFFERS_FIELD_CODE" => array(
        0 => "NAME",
        1 => "PREVIEW_PICTURE",
        2 => "DETAIL_PICTURE",
        3 => "",
      ),
      "LIST_OFFERS_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "DETAIL_OFFERS_FIELD_CODE" => array(
        0 => "NAME",
        1 => "PREVIEW_PICTURE",
        2 => "DETAIL_PICTURE",
        3 => "",
      ),
      "DETAIL_OFFERS_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
      ),
      "INCLUDE_SUBSECTIONS" => "Y",
      "SECTION_META_KEYWORDS" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "SECTION_META_DESCRIPTION" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "SECTION_META_TITLE_PROP" => "'.GetMessage("BUY").' #IBLOCK_NAME# #NAME#",
      "DETAIL_PROPERTY_CODE" => array(    
        1 => "PRODUCER",
        2 => "COUNTRY",
      ),
      "DETAIL_META_KEYWORDS" => "'.GetMessage("BUY").' #NAME#",
      "DETAIL_META_DESCRIPTION" => "'.GetMessage("BUY").' #NAME#",
      "DETAIL_META_TITLE_PROP" => "'.GetMessage("BUY").' #NAME#",
	  "COMPARE_META_H1" => "'.GetMessage("META_COMPARE_THAT_BETTER").'",
      "COMPARE_META_TITLE_PROP" => "'.GetMessage("META_COMPARE_THAT_BETTER_BUY").'",
      "COMPARE_META_KEYWORDS" => "'.GetMessage("META_COMPARE_COMPARE").'",
	  "COMPARE_META_DESCRIPTION" => "'.GetMessage("META_COMPARE_COMPARE").'",
      "LINK_IBLOCK_TYPE" => "",
      "LINK_IBLOCK_ID" => "",
      "LINK_PROPERTY_SID" => "",
      "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
      "USE_ALSO_BUY" => "N",
      "DISPLAY_TOP_PAGER" => "N",
      "DISPLAY_BOTTOM_PAGER" => "Y",
      "PAGER_TITLE" => "'.$iblock_name.'",
      "PAGER_SHOW_ALWAYS" => "N",
      "PAGER_TEMPLATE" => "",
      "PAGER_DESC_NUMBERING" => "Y",
      "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
      "PAGER_SHOW_ALL" => "N",
      "AJAX_OPTION_ADDITIONAL" => "",
      "USE_STORE" => "Y",
      "USE_STORE_PHONE" => "N",
      "USE_STORE_SCHEDULE" => "N",
      "USE_MIN_AMOUNT" => "Y",
      "MIN_AMOUNT" => "10",
      "STORE_PATH" => "/store/#store_id#",
      "MAIN_TITLE" => "'.GetMessage("ON_STORE").'",
      "YS_STORES_MUCH_AMOUNT" => "15",
      "STORE_CODE" => "'.$stores.'",
      "SEF_URL_TEMPLATES" => array(
        "sections" => "",
        "section" => "#SECTION_CODE#/",
        "element" => "#ELEMENT_CODE#.html",
        "compare" => "compare.php?action=#ACTION_CODE#",
      ),
      "VARIABLE_ALIASES" => array(
        "compare" => array(
          "ACTION_CODE" => "action",
        ),
      )
      ),
      false
    );?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
  }

  $section = '
  <?
  $sSectionName = "'.$iblock_name.'";
  $arDirProperties = Array(
    "title" => "'.$iblock_name.'",
    "keywords"  => "'.$iblock_name.'",
    "description" => "'.$iblock_name.'"
  );
  ?>
  ';



   $section2 = '
    <?
    $sSectionName = "'.$arIBType["NAME"].'";
    $arDirProperties = array( 
      "title" => "'.$iblock_name.'"
    );
    ?>
  ';

   $filename = $iblock_dir."index.php";
   $fh = fopen($filename, "w+");
   fwrite($fh, $index);
   fclose($fh);
   
   $filename = $iblock_dir."index.php";
   $fh = fopen($filename, "w+");
   fwrite($fh, $index);
   fclose($fh);
   
   $filename = $iblock_dir.".section.php";
   $fh = fopen($filename, "w+");
   fwrite($fh, $section);
   fclose($fh);
 
  $arUrlRewrite = array(); 
  if (file_exists($_SERVER["DOCUMENT_ROOT"]."/urlrewrite.php"))
  {
    include($_SERVER["DOCUMENT_ROOT"]."/urlrewrite.php");
  }

  $sef = $link_to_iblock;

  if($sefOpt == "Y")
  {
    $arNewUrlRewrite = array(
      array(
        "CONDITION" =>  "#^".$sef."(.*)/view-(\w*)/sort-(\w*)-(\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
        "RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$6&page_count=$5",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/view-(\w*)/sort-(\w*)-(\w*)/page_count-([0-9]+)/#",
        "RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/view-(\w*)/sort-(\w*)-(\w*)/page-([0-9]+)/#",
        "RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$5",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/view-(\w*)/sort-(\w*)-(\w*)/#",
        "RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/page-([0-9]+)/#",
        "RULE"  =>  "SECTION_CODE=$1&PAGEN_1=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/page_count-([0-9]+)/#",
        "RULE"  =>  "SECTION_CODE=$1&page_count=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/sort-(.*[^-])-(.*)/#",
        "RULE"  =>  "SECTION_CODE=$1&order=$2&by=$3",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/view-(\w*)/#",
        "RULE"  =>  "SECTION_CODE=$1&view=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/(.*).html(.*)#",
        "RULE"  =>  "ELEMENT_CODE=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."view-(\w*)/sort-(\w*)-(\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
        "RULE"  =>  "order=$2&by=$3&view=$1&PAGEN_1=$5&page_count=$4",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."view-(\w*)/sort-(\w*)-(\w*)/page-([0-9]+)/#",
        "RULE"  =>  "order=$2&by=$3&view=$1&PAGEN_1=$4",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."view-(\w*)/sort-(\w*)-(\w*)/page_count-([0-9]+)/#",
        "RULE"  =>  "order=$2&by=$3&view=$1&page_count=$4",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."view-(\w*)/sort-(\w*)-(\w*)/#",
        "RULE"  =>  "order=$2&by=$3&view=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."compare/(.*)/#",
        "RULE"  =>  "action=COMPARE&compareQuery=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."view-(\w*)/#",
        "RULE"  =>  "view=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."page-([0-9]+)/#",
        "RULE"  =>  "PAGEN_1=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."page_count-([0-9]+)/#",
        "RULE"  =>  "page_count=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."sort-(.*[^-])-(.*)/#",
        "RULE"  =>  "order=$1&by=$2",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*).html(.*)#",
        "RULE"  =>  "ELEMENT_CODE=$1",
        "PATH"  =>   $sef."index.php",
      ),
      array(
        "CONDITION" =>  "#^".$sef."(.*)/(.*)#",
        "RULE"  =>  "SECTION_CODE=$1",
        "PATH"  =>   $sef."index.php",
      ),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
			"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6&PAGEN_1=$7",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
			"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&letter=$5&PAGEN_1=$6",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
			"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?letter-([^/]{1,3})/page_count-([0-9]+)/(.*)#",
			"RULE" 	=> "SECTION_CODE=$1&letter=$2&page_count=$3",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
			"RULE" 	=> "SECTION_CODE=$1&page_count=$2&letter=$3",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
			"RULE" 	=> "SECTION_CODE=$1&letter=$2&PAGEN_1=$3",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		),
		array(
			"CONDITION" =>  "#^".$sef."([^/]*)/?letter-([^/]{1,3})/(.*)#",
			"RULE" 	=> "SECTION_CODE=$1&letter=$2",
			"ID" 	=> "",
			"PATH"  =>   $sef."index.php",
		)
    );
  }
  else
  {
    $arNewUrlRewrite = array(
    array(
      "CONDITION" =>  "#^".$sef."#",
      "RULE"  =>  "",
      "ID"  =>  "bitrix:catalog",
      "PATH"  =>   $sef."index.php",
      ),
    );
  }

  foreach ($arNewUrlRewrite as $arUrl)
  {
    if (!in_array($arUrl, $arUrlRewrite))
    {
      CUrlRewriter::Add($arUrl);
    }
  }
  /* 
   $filename = $_SERVER["DOCUMENT_ROOT"]."/".str_replace("catalog-", "", str_replace("_", "-", $iblock_type))."/.section.php";
   $fh = fopen($filename, "w+");
   fwrite($fh, $section2);
   fclose($fh);
   */
}
?>