<?php
//pr($arResult, true);

$fieldsOrder = explode("|", $arParams['FIELDS_ORDER']);

if (is_array($fieldsOrder)){

    $orderResult = array();
    foreach ($fieldsOrder as $field) {
        if(in_array($field, $arResult['SHOW_FIELDS'])){
            $orderResult[] = $field;
        }
    }

    $arResult['SHOW_FIELDS'] = $orderResult;
    $arResult['SHOW_FIELDS'][] = 'SECOND_NAME';
}