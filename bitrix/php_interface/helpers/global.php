<?php

/**
 * @param string $secret  Secret key from ReCaptcha
 * @param string $response  Response from ReCaptcha field $_POST['g-recaptcha-response']
 */
function checkReCaptcha ($secret, $response){

    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify";

    $captchaResponse = file_get_contents($url . "?secret=$secret&response=$response&remoteip=$remoteip");

    $captchaResult = json_decode($captchaResponse, true);

    return $captchaResult;
}

function getUTCtimestamp($time) {

    $monthToEn = array(
        'января' => "january",
        'февраля' => "february",
        'марта' => "march",
        'мая' => "may",
        'апреля' => "april",
        'июня' => "june",
        'июля' => "jule",
        'августа' => "august",
        'сентября' => "september",
        'октября' => "october",
        'ноября' => "november",
        'декабря' => "december",
    );

    $timeParts = explode(' ', $time);

    $timeParts[1] = strtolower($timeParts[1]);
    $timeParts[1] = $monthToEn[$timeParts[1]];

    $time = implode(" ", $timeParts);

    return strtotime($time . "Z");
}

function getUserFavorites(){

    if (!isset($_SESSION['USER_FAVORITES'])){

        global $USER, $DB;
        $userId = $USER->GetId();
        $sql = "SELECT `goods` FROM `a_user_favorite` WHERE `user_id`=" . $userId;
        $result = $DB->query($sql);

        $redultData = $result->Fetch();
        $_SESSION['USER_FAVORITES'] = unserialize($redultData['goods']);

    }
}

function getCountBasket()
{
    CModule::IncludeModule("sale");

    return CSaleBasket::GetList(false, array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"),false,false,array("ID" ))->SelectedRowsCount();
}

/**
 * @param $price float Price for calculate bonuses
 * @return float Bonus quantity
 */
function getBonuses($price){

    global $headerVars;
    $bonusRules = array_filter(explode(PHP_EOL, $headerVars['bonus_rules']));
    if (is_array($bonusRules)){
        foreach ($bonusRules as &$bonusRuleArray) {
            $bonusLineParts = explode("|", $bonusRuleArray);
            $bonusInterval = explode('-', $bonusLineParts[0]);
            $bonusRuleArray = array(
                'FROM' => $bonusInterval[0],
                'TO' => $bonusInterval[1],
                'PERCENT' => $bonusLineParts[1],
            );
        }
    }

    foreach ($bonusRules as $bonusRule) {

        if ($bonusRule['TO'] === null){
            $bonusCost = round($price * $bonusRule['PERCENT']/100);
        } else {
            if ($bonusRule['FROM'] <= $price && $bonusRule['TO'] >= $price){
                $bonusCost = round($price * $bonusRule['PERCENT']/100);
                break;
            }
        }

    }

    return $bonusCost;
}

function getCertificates($iblockId = null)
{

    if ($iblockId === null){
        return null;
    }

    if(CModule::IncludeModule('iblock')){
        $rsSert = CIBlockElement::GetList(array(), array('ACTIVE'=>'Y', 'IBLOCK_ID'=>$iblockId),fakse, false, array('ID', 'NAME'));

        $sert = array();
        while ($obSert = $rsSert->GetNextElement()){
            $sertFields = $obSert->GetFields();
            $sert[] = $sertFields['ID'];
        }
        return $sert;
    }
    return null;
}

function pr($data){
    global $USER;
    if ($USER->IsAdmin()){
        if (!$data){
            echo "<pre>","Empty","</pre>";
        } else {
            echo "<pre>",print_r($data,1),"</pre>";
        }
    }
}