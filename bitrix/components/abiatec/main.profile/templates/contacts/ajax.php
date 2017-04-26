<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;
global $USER_FIELD_MANAGER;

$arUser = $USER->GetByID($USER->GetParam('USER_ID'))->GetNext();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields("USER", $arUser["ID"], LANGUAGE_ID);

$availableFields = array(
    "PERSONAL_STATE",
    "PERSONAL_CITY",
    "PERSONAL_STREET",
    "UF_PERSONAL_HOME",
    "UF_PERSONAL_HOUSING",
    "UF_PERSONAL_FLAT",
    "UF_PERSONAL_FLOOR",
    "UF_PERSONAL_INTERCOM",
    "UF_PERSONAL_IS_MAIN",
    "WORK_PHONE",
    "NAME",
    "SECOND_NAME",
    "LAST_NAME",
);

$addressData = array(
    "PERSONAL_STATE" => array(
        0 => 'Брестская',
        1 => 'Витебская',
        2 => 'Гомельская',
        3 => 'Гродненская',
        4 => 'Минская',
        5 => 'Могилевская',
    ),
    "PERSONAL_CITY" => array(
        0 => 'Брест',
        1 => 'Витебск',
        2 => 'Гомель',
        3 => 'Гродно',
        4 => 'Минск',
        5 => 'Могилев',
        6 => 'Борисов',
    ),
);

// Get data to forms from system
if ($_POST['action'] == 'getData') {
    if ($_POST['DATA_ID'] == "") {
        $result['success'] = false;
        echo json_encode ($result);
        die;
    }

    if ($_POST['actionType'] == 'ADDRESS'){

        $states = array(
            0 => 'Брестская',
            1 => 'Витебская',
            2 => 'Гомельская',
            3 => 'Гродненская',
            4 => 'Минская',
            5 => 'Могилевская',
        );

        $cities = array(
            0 => 'Брест',
            1 => 'Витебск',
            2 => 'Гомель',
            3 => 'Гродно',
            4 => 'Минск',
            5 => 'Могилев',
            6 => 'Борисов',
        );

        // Get main address
        $arResult['ADDRESSES'][0]['PERSONAL_STATE'] = $arUser['PERSONAL_STATE'];
        $state = array_keys($addressData['PERSONAL_STATE'], $arUser['PERSONAL_STATE']);
        $arResult['ADDRESSES'][0]['PERSONAL_STATE'] = $state[0];
        $city = array_keys($addressData['PERSONAL_CITY'], $arUser['PERSONAL_CITY']);
        $arResult['ADDRESSES'][0]['PERSONAL_CITY'] = $city[0];
        $arResult['ADDRESSES'][0]['PERSONAL_STREET'] = $arUser['PERSONAL_STREET'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_HOME'] = $arUserFields['UF_PERSONAL_HOME']['VALUE'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_BUILD'] = $arUserFields['UF_PERSONAL_HOUSING']['VALUE'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_FLAT'] = $arUserFields['UF_PERSONAL_FLAT']['VALUE'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_FLOOR'] = $arUserFields['UF_PERSONAL_FLOOR']['VALUE'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_INTERCOM'] = $arUserFields['UF_PERSONAL_INTERCOM']['VALUE'];
        $arResult['ADDRESSES'][0]['UF_PERSONAL_IS_MAIN'] = $arUserFields['UF_PERSONAL_IS_MAIN']['VALUE'];

        $result['success'] = true;
        $result['data'] = $arResult['ADDRESSES'];
    }

    if ($_POST['actionType'] == 'PHONE'){

        // Get main phone
        $arResult['PHONES'][0]['WORK_PHONE'] = $arUser['WORK_PHONE'];

        $result['success'] = true;
        $result['data'] = $arResult['PHONES'];

    }

    if ($_POST['actionType'] == 'FULLNAME') {

        // Get main name
        $arResult['NAMES'][0]['NAME'] = $arUser['NAME'];
        $arResult['NAMES'][0]['SECOND_NAME'] = $arUser['SECOND_NAME'];
        $arResult['NAMES'][0]['LAST_NAME'] = $arUser['LAST_NAME'];

        $result['success'] = true;
        $result['data'] = $arResult['NAMES'];

    }
}

if ($_POST['action'] == 'setData') {
    if ($_POST['recordId'] === "0") {

        $updateFields = array();
        foreach ($_POST as $fieldKey => $fieldValue){
            if (in_array($fieldKey, $availableFields)){
                if ($fieldKey == "PERSONAL_STATE" || $fieldKey == "PERSONAL_CITY"){
                    $updateFields[$fieldKey] = $addressData[$fieldKey][$fieldValue];
                } else {
                    $updateFields[$fieldKey] = $fieldValue;
                }
            }
        }

        $result = array(
            "success" => true,
            "data" => $updateFields,
        );

    } else {
        $result = array(
            'success' => false,
        );
    }


}

if (empty($result)) {
    $result = array(
        'success' => false,
    );
}

echo json_encode($result);
die;
