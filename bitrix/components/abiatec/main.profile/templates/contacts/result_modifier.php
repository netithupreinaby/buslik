<?php

/**
 * @param array $addressArray array with all necessary fields
 * @return void echoes full address
 */
function showFullAddress($addressArray) {
    unset ($addressArray['IS_MAIN']);
    unset ($addressArray['FLOOR']);
    unset ($addressArray['INTERCOM']);
    foreach ($addressArray as $addressKey => $addressPart) {
        if (empty ($addressArray[$addressKey])){
            unset ($addressArray[$addressKey]);
        } else {
            switch ($addressKey) {
                case "CITY": $addressArray['CITY'] = "г. " . $addressArray['CITY'];
                    break;
                case "STREET": $addressArray['STREET'] = "ул. " . $addressArray['STREET'];
                    break;
                case "HOME": $addressArray['HOME'] = "д. " . $addressArray['HOME'];
                    break;
                case "BUILD": $addressArray['BUILD'] = "корп. " . $addressArray['BUILD'];
                    break;
                case "FLAT": $addressArray['FLAT'] = "кв. " . $addressArray['FLAT'];
                    break;
                default;
            }
        }
    }

    echo implode(', ', $addressArray);
}

/**
 * @param array $fullnameArray array of name components
 */
function showFullname ($fullnameArray){

    echo implode(' ', $fullnameArray);
}


// Get main address
$arResult['ADDRESSES'][0]['STATE'] = $arResult['arUser']['PERSONAL_STATE'];
$arResult['ADDRESSES'][0]['CITY'] = $arResult['arUser']['PERSONAL_CITY'];
$arResult['ADDRESSES'][0]['STREET'] = $arResult['arUser']['PERSONAL_STREET'];
$arResult['ADDRESSES'][0]['HOME'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_HOME']['VALUE'];
$arResult['ADDRESSES'][0]['BUILD'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_HOUSING']['VALUE'];
$arResult['ADDRESSES'][0]['FLAT'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_FLAT']['VALUE'];
$arResult['ADDRESSES'][0]['FLOOR'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_FLOOR']['VALUE'];
$arResult['ADDRESSES'][0]['INTERCOM'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_INTERCOM']['VALUE'];
$arResult['ADDRESSES'][0]['IS_MAIN'] = $arResult['USER_PROPERTIES']['DATA']['UF_PERSONAL_IS_MAIN']['VALUE'];

// Get main phone
$arResult['PHONES'][0] = $arResult['arUser']['WORK_PHONE'];

// Get main name
$arResult['FULLNAMES'][0]['LAST_NAME'] = $arResult['arUser']['LAST_NAME'];
$arResult['FULLNAMES'][0]['NAME'] = $arResult['arUser']['NAME'];
$arResult['FULLNAMES'][0]['SECOND_NAME'] = $arResult['arUser']['SECOND_NAME'];
