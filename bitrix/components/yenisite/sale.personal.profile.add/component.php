<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
function getOrderProps($PERSON_TYPE_ID)
{
	if (!CModule::IncludeModule("sale"))
	{
		ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
		return;
	}
	if($PERSON_TYPE_ID > 0)
	{
		$arrayTmp = Array();
		$dbOrderPropsGroup = CSaleOrderPropsGroup::GetList(
					array("SORT" => "ASC", "NAME" => "ASC"),
					array("PERSON_TYPE_ID" => $PERSON_TYPE_ID),
					false,
					false,
					array("ID", "PERSON_TYPE_ID", "NAME", "SORT")
				);
		while ($arOrderPropsGroup = $dbOrderPropsGroup->GetNext())
		{
			$arrayTmp[$arOrderPropsGroup["ID"]] = $arOrderPropsGroup;
			$dbOrderProps = CSaleOrderProps::GetList(
				array("SORT" => "ASC", "NAME" => "ASC"),
				array(
						"PERSON_TYPE_ID" => $PERSON_TYPE_ID,
						"PROPS_GROUP_ID" => $arOrderPropsGroup["ID"],
						"USER_PROPS" => "Y", "ACTIVE" => "Y", "UTIL" => "N"
					),
				false,
				false,
				array("ID", "PERSON_TYPE_ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "SORT", "USER_PROPS", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE", "INPUT_FIELD_LOCATION")
			);
			while($arOrderProps = $dbOrderProps->GetNext())
			{
				if ($arOrderProps["REQUIED"]=="Y" || $arOrderProps["IS_EMAIL"]=="Y" || $arOrderProps["IS_PROFILE_NAME"]=="Y" || $arOrderProps["IS_LOCATION"]=="Y" || $arOrderProps["IS_PAYER"]=="Y")
					$arOrderProps["REQUIED"] = "Y";
				if (in_array($arOrderProps["TYPE"], Array("SELECT", "MULTISELECT", "RADIO")))
				{
					$dbVars = CSaleOrderPropsVariant::GetList(($by="SORT"), ($order="ASC"), Array("ORDER_PROPS_ID"=>$arOrderProps["ID"]));
					while ($vars = $dbVars->GetNext())
						$arOrderProps["VALUES"][] = $vars;
				}
				elseif($arOrderProps["TYPE"]=="LOCATION")
				{
					$dbVars = CSaleLocation::GetList(Array("SORT"=>"ASC", "COUNTRY_NAME_LANG"=>"ASC", "CITY_NAME_LANG"=>"ASC"), array("LID" => LANGUAGE_ID, '>CITY_NAME' => ''), false,false,array());
					while($vars = $dbVars->GetNext())
						$arOrderProps["VALUES"][] = $vars;
				}
				$arrayTmp[$arOrderPropsGroup["ID"]]["PROPS"][] = $arOrderProps;
			}
		}
		return $arrayTmp;
	}
	return 0;
}

function getOrderValue($arProps){
	$arValues = array();

	foreach(GetModuleEvents("yenisite.profileadd", "OnBeforeComponentNewProfileGetOrderValue", true) as $arEvent) {
		ExecuteModuleEventEx($arEvent, array(&$arProps, &$arValues));
	}

	foreach ($arProps as $arProp)
	{
		foreach ($arProp['PROPS'] as $valueID)
		{
			if (isset($_POST['ORDER_PROP_'.$valueID['ID']])){
				$arValues['ORDER_PROP_'.$valueID['ID']] = $_POST['ORDER_PROP_'.$valueID['ID']];
			}
		}
	}

	foreach(GetModuleEvents("yenisite.profileadd", "OnComponentNewProfileGetOrderValue", true) as $arEvent) {
		ExecuteModuleEventEx($arEvent, array(&$arProps, &$arValues));
	}

	return $arValues;
}

global $APPLICATION;
if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}
if (!$USER->IsAuthorized())
{
	$APPLICATION->AuthForm(GetMessage("SALE_ACCESS_DENIED"));
}

$arParams['PATH_TO_LIST'] = (isset($arParams['PATH_TO_LIST']) ? trim($arParams['PATH_TO_LIST']) : '');
if ($arParams['PATH_TO_LIST'] == '')
	$arParams['PATH_TO_LIST'] = htmlspecialcharsbx($APPLICATION->GetCurPage());
$arParams["PATH_TO_DETAIL"] = trim($arParams["PATH_TO_DETAIL"]);
if ($arParams["PATH_TO_DETAIL"] == '')
	$arParams["PATH_TO_DETAIL"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?ID=#ID#");

$arParams["SET_TITLE"] = ($arParams["SET_TITLE"] == "N" ? "N" : "Y" );
if($arParams["SET_TITLE"] == 'Y')
	$APPLICATION->SetTitle(GetMessage("SPPA_TITLE"));

if($USER->IsAuthorized())
{
	switch($_REQUEST['action'])
	{
		case 'step2':
			if($_POST['PT'] > 0)
			{
				$arResult["ORDER_PROPS"] = getOrderProps($_POST['PT']);
				$arResult["ORDER_PROPS_VALUES"] = getOrderValue($arResult['ORDER_PROPS']);
				$arResult['STEP'] = 'ORDER_PROPS';
				$arResult['HIDDEN'][0]['NAME'] = 'action';
				$arResult['HIDDEN'][0]['VALUE'] = 'create';

				$arResult["PERSON_TYPE"] = CSalePersonType::GetList(Array(), Array('LID'=>SITE_ID, 'ACTIVE'=>'Y', 'ID'=>$_POST['PT']))->GetNext();
			}
		break;
		case 'create':
			if ($_SERVER["REQUEST_METHOD"]=="POST" && (strlen($_POST["save"]) > 0 || strlen($_POST["apply"]) > 0) && check_bitrix_sessid())
			{
				$NAME = Trim($_POST["NAME"]);
				if (strlen($NAME) <= 0)
					$errorMessage .= GetMessage("SALE_NO_NAME")."<br />";

				$dbOrderProps = CSaleOrderProps::GetList(
					array("SORT" => "ASC", "NAME" => "ASC"),
					array(
							"PERSON_TYPE_ID" => $_POST['PT'],
							"USER_PROPS" => "Y"
						),
					false,
					false,
					array("ID", "PERSON_TYPE_ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "SORT", "USER_PROPS", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE")
				);
				
				while ($arOrderProps = $dbOrderProps->GetNext())
				{
					$bErrorField = False;
					$curVal = $_POST["ORDER_PROP_".$arOrderProps["ID"]];
					if ($arOrderProps["TYPE"] == "LOCATION" && $arOrderProps["IS_LOCATION"] == "Y")
					{
						$DELIVERY_LOCATION = IntVal($curVal);
						if (IntVal($curVal) <= 0)
							$bErrorField = True;
					}
					elseif ($arOrderProps["IS_PROFILE_NAME"] == "Y" || $arOrderProps["IS_PAYER"] == "Y" || $arOrderProps["IS_EMAIL"] == "Y")
					{
						if ($arOrderProps["IS_PROFILE_NAME"] == "Y")
						{
							$PROFILE_NAME = Trim($curVal);
							if (strlen($PROFILE_NAME) <= 0)
								$bErrorField = True;
						}
						if ($arOrderProps["IS_PAYER"] == "Y")
						{
							$PAYER_NAME = Trim($curVal);
							if (strlen($PAYER_NAME) <= 0)
								$bErrorField = True;
						}
						if ($arOrderProps["IS_EMAIL"] == "Y")
						{
							$USER_EMAIL = Trim($curVal);
							if (strlen($USER_EMAIL) <= 0 || !check_email($USER_EMAIL))
								$bErrorField = True;
						}
					}
					elseif ($arOrderProps["REQUIED"] == "Y")
					{
						if ($arOrderProps["TYPE"] == "TEXT" || $arOrderProps["TYPE"] == "TEXTAREA" || $arOrderProps["TYPE"] == "RADIO" || $arOrderProps["TYPE"] == "SELECT")
						{
							if (strlen($curVal) <= 0)
								$bErrorField = True;
						}
						elseif ($arOrderProps["TYPE"] == "LOCATION")
						{
							if (IntVal($curVal) <= 0)
								$bErrorField = True;
						}
						elseif ($arOrderProps["TYPE"] == "MULTISELECT")
						{
							if (!is_array($curVal) || count($curVal) <= 0)
								$bErrorField = True;
						}
					}
					if ($bErrorField)
						$errorMessage .= GetMessage("SALE_NO_FIELD")." \"".$arOrderProps["NAME"]."\".<br />";
				}
				if (strlen($errorMessage) <= 0)
				{
					$arFields = array(
						"NAME" => $NAME,
						"USER_ID" => $USER->GetID(),
						"PERSON_TYPE_ID" => $_POST['PT']
					);
					$ID = CSaleOrderUserProps::Add($arFields);
					if($ID > 0)
					{
						CSaleOrderUserPropsValue::DeleteAll($ID);
						CSaleOrderUserProps::Update($ID, $arFields);
						$dbOrderProps = CSaleOrderProps::GetList(
							array("SORT" => "ASC", "NAME" => "ASC"),
							array(
									"PERSON_TYPE_ID" => $_POST['PT'],
									"USER_PROPS" => "Y"
								),
							false,
							false,
							array("ID", "PERSON_TYPE_ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "SORT", "USER_PROPS", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE")
						);
						while ($arOrderProps = $dbOrderProps->GetNext())
						{
							$curVal = $_POST["ORDER_PROP_".$arOrderProps["ID"]];
							if ($arOrderProps["TYPE"]=="MULTISELECT")
							{
								$curVal = "";
								for ($i = 0; $i < count($_POST["ORDER_PROP_".$arOrderProps["ID"]]); $i++)
								{
									if ($i > 0)
										$curVal .= ",";
									$curVal .= $_POST["ORDER_PROP_".$arOrderProps["ID"]][$i];
								}
							}

							if (isset($_POST["ORDER_PROP_".$arOrderProps["ID"]]))
							{
								$arFields = array(
										"USER_PROPS_ID" => $ID,
										"ORDER_PROPS_ID" => $arOrderProps["ID"],
										"NAME" => $arOrderProps["NAME"],
										"VALUE" => $curVal
									);
								CSaleOrderUserPropsValue::Add($arFields);
							}
						}
					}
					if (strlen($_POST["save"]) > 0 && strlen($errorMessage) <= 0)
					{
						if($arParams["PATH_TO_LIST"])
							LocalRedirect($arParams["PATH_TO_LIST"]);
						else
						{
							$arResult['ID'] = $ID;
							$arResult['STEP'] = 'FINISH';
						}
					}
					elseif(strlen($_POST["apply"]) > 0 &&  strlen($errorMessage) <= 0 && $arParams["PATH_TO_DETAIL"])
					{
						LocalRedirect(CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_DETAIL"], Array("ID" => $ID)));
					}
				}
				else
				{
					$arResult["ERROR_MESSAGE"] = $errorMessage;
					$arResult['ORDER_PROPS'] = getOrderProps($_POST['PT']);
					$arResult["ORDER_PROPS_VALUES"] = getOrderValue($arResult['ORDER_PROPS']);
					$arResult['NAME'] = $_POST['NAME'];
					$arResult['STEP'] = 'ORDER_PROPS';
					$arResult['HIDDEN'][0]['NAME'] = 'action';
					$arResult['HIDDEN'][0]['VALUE'] = 'create';
					$arResult["PERSON_TYPE"] = CSalePersonType::GetList(Array(), Array('LID'=>SITE_ID, 'ACTIVE'=>'Y', 'ID'=>$_POST['PT']))->GetNext();
				}
			}
			else
			{
				$arResult["ERROR_MESSAGE"] = $errorMessage;
				$arResult['ORDER_PROPS'] = getOrderProps($_POST['PT']);
				$arResult["ORDER_PROPS_VALUES"] = getOrderValue($arResult['ORDER_PROPS']);
				$arResult['NAME'] = $_POST['NAME'];
				$arResult['STEP'] = 'ORDER_PROPS';
				$arResult['HIDDEN'][0]['NAME'] = 'action';
				$arResult['HIDDEN'][0]['VALUE'] = 'create';
				$arResult["PERSON_TYPE"] = CSalePersonType::GetList(Array(), Array('LID'=>SITE_ID, 'ACTIVE'=>'Y', 'ID'=>$_POST['PT']))->GetNext();
			}
		break;
		default:
			$arResult['STEP'] = 'PERSON_TYPE';
			$dbPersonType = CSalePersonType::GetList(Array(), Array('LID'=>SITE_ID, 'ACTIVE'=>'Y'));
			$arResult['HIDDEN'][0]['NAME'] = 'action';
			$arResult['HIDDEN'][0]['VALUE'] = 'step2';
			$selectrowcount = $dbPersonType->SelectedRowsCount();
			while($arPersonType = $dbPersonType->GetNext())
			{
				if($selectrowcount == 1)
				{
					$arResult['ORDER_PROPS'] = getOrderProps($arPersonType['ID']);
					$arResult['STEP'] = 'ORDER_PROPS';
					$arResult['HIDDEN'][0]['NAME'] = 'action';
					$arResult['HIDDEN'][0]['VALUE'] = 'create';
					$arResult["PERSON_TYPE"] = CSalePersonType::GetList(Array(), Array('LID'=>SITE_ID, 'ACTIVE'=>'Y', 'ID'=>$arPersonType['ID']))->GetNext();
				}
				else
				{
					$arResult['PERSON_TYPE'][$arPersonType['ID']] = $arPersonType;
					$arResult['PERSON_TYPE'][$arPersonType['ID']]['INPUT']['TYPE'] = 'RADIO';
					$arResult['PERSON_TYPE'][$arPersonType['ID']]['INPUT']['NAME'] = 'PT';
					$arResult['PERSON_TYPE'][$arPersonType['ID']]['INPUT']['VALUE'] = $arPersonType['ID'];
				}
			} 
		break;
	}
}
$this->IncludeComponentTemplate();?>