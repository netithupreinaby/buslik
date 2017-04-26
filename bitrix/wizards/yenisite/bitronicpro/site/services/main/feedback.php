<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");
CModule::IncludeModule("iblock");
//Install AJAX forms
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".CYSBitronicSettings::getModuleId()."/install/version.php");
$ver = $arModuleVersion["VERSION"];
$tempDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver;

function SetIBlockAdminListDisplaySettings($IBlockID, $arIBlockListAdminColumns, $orderByColumnName, $orderDirection, $pageSize, $isToAllUsers = TRUE)
{
    $IBlockType = CIBlock::GetArrayByID($IBlockID, 'IBLOCK_TYPE_ID');
    if(FALSE == $IBlockType)
    {
            return FALSE;
    }

        $arPropertyCode = array();
    $obProperties = CIBlockProperty::GetList(array("sort"=>"asc"), array("IBLOCK_ID"=>$IBlockID));
    while($arProp = $obProperties->GetNext(true, false)) {
            $arPropertyCode[$arProp['CODE']] = $arProp['ID'];
    }

    $arColumnList = array();
    foreach($arIBlockListAdminColumns as $columnCode)
    {
            if(TRUE == array_key_exists($columnCode, $arPropertyCode))
            {
                $arColumnList[] = 'PROPERTY_'.$arPropertyCode[$columnCode];
            }
            else
            {
                $arColumnList[] = $columnCode;
            }
    }
    $columnSettings = implode(',',$arColumnList);

    $arOptions[] = array(
        'c' => 'list',
        'n' => "tbl_iblock_list_".md5($IBlockType.".".$IBlockID),
        'v' => array(
            'columns'=> strtoupper($columnSettings),
            'by'=> strtoupper($orderByColumnName),
            'order'=> strtoupper($orderDirection),
            'page_size' => $pageSize
        ),
    );
    if(TRUE == $isToAllUsers)
    {
            $arOptions[0]['d']='Y';
    }

    CUserOptions::SetOptionsFromArray($arOptions);
}

/**
* Config edit form
*
* @param integer $IBlockID — IBlock ID
* @param string $arIBlockEditAdminFields — CODEs for IBlock's properties and fields
* @param boolean $isToAllUsers - for all users or not
* @return boolean
*/
function SetIBlockAdminEditSettings($IBlockID, $arIBlockEditAdminFields, $isToAllUsers = TRUE)
{
    
    $arFields = CIBlock::GetFields($IBlockID);

    //Get info about IBlock
    $res = CIBlock::GetByID($IBlockID);
    $IBlockInfo = $res->GetNext();
	$IBlockType = $IBlockInfo['IBLOCK_TYPE_ID'];
    
	if($IBlockType == FALSE)
	{
		return FALSE;
	}
    
    $arPropertyCode = array();
	$obProperties = CIBlockProperty::GetList(array("sort"=>"asc"), array("IBLOCK_ID"=>$IBlockID));
	while($arProp = $obProperties->GetNext(true, false)) {
		$arPropertyCode[$arProp['CODE']] = array(   
                                                    'ID' => $arProp['ID'],
                                                    'NAME' => $arProp['NAME'],
                                                    'IS_REQUIRED' => $arProp['IS_REQUIRED'],
                                                );
	}
    
    $arColumnList2 = array();
    foreach($arIBlockEditAdminFields as $tab)
    {
        $fields = array();
        foreach ($tab as $key => $val)
        {
            $arColumnList1 = array();
            
            if (strpos($key, 'edit') !== FALSE)
            {
                $arColumnList1[0] = $key;
                if (preg_match("/^#(\w*)#/", $val, $match))
                {
                    if ($IBlockInfo[$match[1]] === NULL)
                        $arColumnList1[1] = GetMessage('ELEMENT_NAME');
                    else
                        $arColumnList1[1] = $IBlockInfo[$match[1]];
                }
                else
                    $arColumnList1[1] = $val;
            }
            elseif($key == "IBLOCK_ELEMENT_PROP_VALUE" && preg_match("/^--\w*/", $val))
            {
                $arColumnList1[0] = $key;
                $arColumnList1[1] = $val;
            }
            else
            {
                $_val = "";
//                if ($val === "")
//                {
                    if(array_key_exists($key, $arPropertyCode) == TRUE)
                    {
                        if ($val == "")
                            $name = $arPropertyCode[$key]['NAME'];
                        else
                            $name = $val;
                        
                        $name = ($arPropertyCode[$key]['IS_REQUIRED'] == 'Y')?"*".$name:$name;
                        $_val = $name;
                        
                        $arColumnList1[0] = "PROPERTY_".$arPropertyCode[$key]['ID'];
                    }
                    elseif(array_key_exists($key, $arPropertyCode) == FALSE && array_key_exists($key, $arFields) == TRUE)
                    {
                        if ($val == "")
                            $name = $arFields[$key]['NAME'];
                        else
                            $name = $val;
                        
                        $name = ($arFields[$key]['IS_REQUIRED'] == 'Y')?"*".$name:$name;
                        $_val = $name;
                        
                        $arColumnList1[0] = $key;
                    }
//                }
                
                if ($_val !== "")
                    $arColumnList1[1] = $_val;
                
            }
            
            if ($arColumnList1[1] != "")
                $fields[] = implode('--#--', $arColumnList1);
        }
        $formTab[] = implode('--,--', $fields);
    }
    $arColumnList2 = implode('--;--', $formTab) . "--;--";
    $columnSettings = $arColumnList2;
//    AddMessage2Log($columnSettings);
    $arOptions[] = array(
		'c' => 'form',
		'n' => "form_element_".$IBlockID,
		'v' => array(
			'tabs'=> $columnSettings,
		),
	);
    
	if(TRUE == $isToAllUsers)
	{
		$arOptions[0]['d'] = 'Y';
	}
    
	CUserOptions::SetOptionsFromArray($arOptions);
}

$EVENTS = Array(
                "FOUND_CHEAP"   => '#DEFAULT_EMAIL_FROM#',
                "CALLBACK_PHONE"=> '#DEFAULT_EMAIL_FROM#',
                "ELEMENT_EXIST" => '#EMAIL#',
                "PRICE_LOWER"   => '#EMAIL#'
                );



$FIELDS['found_cheap'] = Array(
                                array(
                                    "NAME" => GetMessage("LINK"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "100",
                                    "CODE" => "LINK",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
								array(
                                    "NAME" => GetMessage("NAME"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "101",
                                    "CODE" => "NAME",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "N",
                                ),
								array(
                                    "NAME" => GetMessage("PHONE"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "105",
                                    "CODE" => "PHONE",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
								array(
                                    "NAME" => GetMessage("EMAIL"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "110",
                                    "CODE" => "EMAIL",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "N",
                                ),
                                array(
                                    "NAME" => GetMessage("ELEMENT_ID"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "120",
                                    "CODE" => "ELEMENT_ID",
                                    "PROPERTY_TYPE" => "E",
                                    "IS_REQUIRED" => "Y",
                                ),
                            );

$FIELDS['callback_phone'] = Array(
                                array(
                                    "NAME" => GetMessage("NAME"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "100",
                                    "CODE" => "NAME",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
                                array(
                                    "NAME" => GetMessage("PHONE"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "110",
                                    "CODE" => "PHONE",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
                            );

$FIELDS['element_exist'] = Array(
                                array(
                                    "NAME" => GetMessage("EMAIL"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "100",
                                    "CODE" => "EMAIL",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
                                array(
                                    "NAME" => GetMessage("ELEMENT_ID"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "110",
                                    "CODE" => "ELEMENT_ID",
                                    "PROPERTY_TYPE" => "E",
                                    "IS_REQUIRED" => "Y",
                                ),
                            );

$FIELDS['price_lower'] = Array(
                                array(
                                    "NAME" => GetMessage("EMAIL"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "100",
                                    "CODE" => "EMAIL",
                                    "PROPERTY_TYPE" => "S",
                                    "IS_REQUIRED" => "Y",
                                ),
                                array(
                                    "NAME" => GetMessage("PRICE"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "105",
                                    "CODE" => "PRICE",
                                    "PROPERTY_TYPE" => "N",
                                    "IS_REQUIRED" => "Y",
                                ),
                                array(
                                    "NAME" => GetMessage("ELEMENT_ID"),
                                    "ACTIVE" => "Y",
                                    "SORT" => "110",
                                    "CODE" => "ELEMENT_ID",
                                    "PROPERTY_TYPE" => "E",
                                    "IS_REQUIRED" => "Y",
                                ),
                            );

//-------------------------------
//Create IBlock Type
$arFields = Array(
                'ID'=>'yenisite_feedback',
                'SECTIONS'=>'Y',
                'IN_RSS'=>'N',
                'SORT'=>100,
                'LANG'=>Array(
                        'en'=>Array(
                        'NAME'=>'Feedback',
                        'SECTION_NAME'=>'Section',
                        'ELEMENT_NAME'=>'Message'),
                        'ru'=>Array(
                        'NAME'=> GetMessage("FEEDBACK"),
                        'SECTION_NAME'=>GetMessage("SECTION_NAME"),
                        'ELEMENT_NAME'=>GetMessage("ELEMENT_NAME")
                        )
    )
);


if (!CModule::IncludeModule("iblock"))
{
    die("ERROR");
}

$obBlocktype = new CIBlockType;
$res_tib = $obBlocktype->Add($arFields);
if(!$res_tib)
    $res_tib = "yenisite_feedback";

//Check IBlocks exist
foreach ($EVENTS as $EVENT_NAME => $EMAIL)
{
    $code = strtolower($EVENT_NAME);
    
    $pathToAjax = $tempDir . "/ajax/".$code.".php";
    CWizardUtil::ReplaceMacros($pathToAjax, Array("IBLOCK_TYPE" => $res_tib));
    
    $test_ib = CIBlock::GetList(Array(), Array('TYPE' => $res_tib, 'CODE' => $code . "%"), false);

    $i = 0;
    while($ar_res = $test_ib->Fetch())
    {
        $res_ib = $ar_res['ID'];
        $i++;
    }

    if ($i == 0)
        $iblock_codes[$code] = TRUE;
    else
        $iblock_codes[$code] = FALSE;
    
    //Create IBlock
    if ($iblock_codes[$code] === TRUE)
    {
        $ib = new CIBlock;
        $arFields = Array(
                          "ACTIVE" => "Y",
                          "NAME" => GetMessage($EVENT_NAME),
                          "CODE" => $code,
                          "LIST_PAGE_URL" => "",
                          "DETAIL_PAGE_URL" => "",
                          "IBLOCK_TYPE_ID" => $res_tib,
                          "INDEX_ELEMENT" => "Y",
                          "SITE_ID" => Array(WIZARD_SITE_ID),
                          "SORT" => "500",
                          "PICTURE" => "",
                          "DESCRIPTION" => "",
                          "DESCRIPTION_TYPE" => "text",
                          "GROUP_ID" => Array("2"=>"R"),
//                          "ELEMENT_NAME" => $element_name,
//                          "ELEMENTS_NAME" => $elements_name,
//                          "ELEMENT_ADD" => GetMessage($EVENT_NAME . "_ELEMENT_ADD"),
//                          "ELEMENT_EDIT" => GetMessage("ELEMENT_EDIT") . " ". $element_name2,
//                          "ELEMENT_DELETE" => GetMessage("ELEMENT_DELETE") . " ". $element_name2,
                          );

        //var_dump($arFields);
        $res_ib = $ib->Add($arFields);
        
//        AddMessage2Log($res_ib);
        
        if (is_numeric($res_ib))
        {
            $ibp = new CIBlockProperty;
            $_fields = array();
            $_fields['edit1'] = '#ELEMENT_NAME#';
            $_fields['ACTIVE'] = '';
            $_fields['NAME'] = '';
            
            foreach ($FIELDS[$code] as $field)
            {
                $field['IBLOCK_ID'] = $res_ib;
                $ibp->Add($field);
                $_fields[$field['CODE']] = "";
            }
//            AddMessage2Log(print_r($_fields2, true));

            $arFieldsIP = Array(
                            "NAME" => GetMessage("IP"),
                            "ACTIVE" => "Y",
                            "SORT" => "115",
                            "CODE" => "IP",
                            "PROPERTY_TYPE" => "S",
                            "IBLOCK_ID" => $res_ib,
                            "IS_REQUIRED" => "N",
                        );

            $ibp->Add($arFieldsIP);
            $_fields['IP'] = "";
            $_fields2 = array($_fields);
            SetIBlockAdminEditSettings($res_ib, $_fields2);
            
            switch($code)
            {
                case 'found_cheap':
                    $print_fields = array('ID', 'NAME', 'PHONE', 'EMAIL', 'LINK', 'ELEMENT_ID', 'IP');
                    break;
                case 'callback_phone':
                    $print_fields = array('ID', 'NAME', 'PHONE', 'IP');
                    break;
                case 'element_exist':
                    $print_fields = array('ID', 'NAME', 'ELEMENT_ID', 'IP');
                    break;
                case 'price_lower':
                    $print_fields = array('ID', 'NAME', 'ELEMENT_ID', 'PRICE', 'IP');
                    break;
            }
            $print_fields[] = 'timestamp_x';
            $print_fields[] = 'ACTIVE';
//            $print_fields = array('ID', 'NAME', '');
            SetIBlockAdminListDisplaySettings($res_ib, $print_fields, 'ID', 'DESC', 20, TRUE);
        }
    }
    
    CWizardUtil::ReplaceMacros($pathToAjax, Array("IBLOCK_ID" => $res_ib));
    if ($EVENT_NAME == 'PRICE_LOWER')
    {
        CWizardUtil::ReplaceMacros($pathToAjax, Array("I_WANT_TEXT" => GetMessage($EVENT_NAME . "_I_WANT_TEXT")));
        CWizardUtil::ReplaceMacros($pathToAjax, Array("DIFF_TEXT" => GetMessage($EVENT_NAME . "_DIFF_TEXT")));
        CWizardUtil::ReplaceMacros($pathToAjax, Array("RUB" => GetMessage($EVENT_NAME . "_RUB")));
    }
    CWizardUtil::ReplaceMacros($pathToAjax, Array("EVENT_NAME" => $EVENT_NAME));
    CWizardUtil::ReplaceMacros($pathToAjax, Array("TITLE" => GetMessage($EVENT_NAME . "_TITLE")));
    CWizardUtil::ReplaceMacros($pathToAjax, Array("SUCCESS_TEXT" => GetMessage($EVENT_NAME . "_SUCCESS_TEXT")));
}


foreach ($EVENTS as $EVENT_NAME => $EMAIL)
{
    //Check exist EventType.
    //If exist - not create new EventType
    $rsET = CEventType::GetByID($EVENT_NAME, "ru");
    $arET = $rsET->Fetch();
    
    if ($arET === FALSE)
    {
        foreach(Array("en","ru") as $key => $value)
        {
            $et = new CEventType;
//            $del_res = $et->Delete($EVENT_NAME);
            $et->Add(array(
                "LID"           => $value,
                "EVENT_NAME"    => $EVENT_NAME,
                "NAME"          => GetMessage($EVENT_NAME),
                "DESCRIPTION"   => GetMessage($EVENT_NAME . "_DESC"),
                ));
        }
    }
}


//Create Messages
foreach($EVENTS as $EVENT_NAME => $EMAIL)
{
    //Check exist EventMessage
    //If not exist - create new EventMessage
    $arFilter = array("TYPE_ID" => $EVENT_NAME);
    $rsMess = CEventMessage::GetList($by, $order, $arFilter);
    $mess = $rsMess->Fetch();
    
    if (!$mess)
    {
        $arr = Array(
                "ACTIVE" => "Y",
                "EVENT_NAME" => $EVENT_NAME,
                "LID" => Array(WIZARD_SITE_ID),
                "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
                "EMAIL_TO" => $EMAIL,
                "BCC" => "",
                "SUBJECT" => GetMessage($EVENT_NAME . "_SUBJECT"),
                "BODY_TYPE" => "text",
                "MESSAGE" => GetMessage($EVENT_NAME . "_TEXT"),
            );

        $emess = new CEventMessage;
        $res_etsh = $emess->Add($arr);
    }
//    AddMessage2Log(print_r($res_etsh, true));
}

// Register Handlers
if (CModule::IncludeModule("catalog"))
{
	UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnAfterIBlockElementUpdateHandler");
	RegisterModuleDependences("catalog", "OnBeforePriceUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnBeforePriceUpdateHandler");
    RegisterModuleDependences("catalog", "OnProductUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnProductUpdateHandler");
}
else
{
	UnRegisterModuleDependences("catalog", "OnBeforePriceUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnBeforePriceUpdateHandler");
    UnRegisterModuleDependences("catalog", "OnProductUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnProductUpdateHandler");
    RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", CYSBitronicSettings::getModuleId(), "CYSElementEvents", "OnAfterIBlockElementUpdateHandler");
}
?>