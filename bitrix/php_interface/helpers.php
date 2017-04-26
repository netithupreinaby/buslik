<?php
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
class CommonMethods{

    static function declination($value=1, $status= array('','а','ов'))
    {

        $values =array(2,0,1,1,1,2);
        return $status[($value%100>4 && $value%100<20)? 2 : $values[($value%10<5)?$value%10:5]];

    }

    /**
     * Получает данные eлемента инфоблока по его внешнему id
     * @param string $objectId  id  элемента ифноблока Каталога
     * @param string $iblockId  id инфоблока, если передан, то получаем все свойства элемента
     */

    static function getObjectByIdIblock($objectId,$iblockId=null){

        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM",'DETAIL_PAGE_URL','PROPERTY_*');
        $arFilter = Array('ID'=>$objectId, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
        if($iblockId){

            $arFilter['IBLOCK_ID'] = $iblockId;

        }
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
        $ob = $res->GetNext();
        return $ob;

    }

    /**
     * Получает данные eлемента инфоблока по его внешнему id
     * @param string $externalId  внешний id  элемента ифноблока Каталога
     * @param string $iblockId  id инфоблока, если передан, то получаем все свойства элемента
     */

    static function getObjectByXmlId($externalId,$iblockId=null){

        $arSelect = Array("ID", "NAME","PREVIEW_PICTURE", "DATE_ACTIVE_FROM",'DETAIL_PAGE_URL','PROPERTY_*');
        $arFilter = Array('EXTERNAL_ID'=>$externalId, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
        if($iblockId){

            $arFilter['IBLOCK_ID'] = $iblockId;

        }
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
        $ob = $res->GetNext();
        return $ob;

    }

    /**
     * Обрезка строки до заданной длины
     * @param string $str  входная строка
     * @param string $length  длина конечной строки
     */

    static function cutString($str,$length){

        $str = strip_tags($str);
        $str = substr($str, 0, $length);
        $str = rtrim($str, "!,.-");


        return substr($str, 0, strrpos($str, ' '));


    }

    static  function getPicturesFromFolder($folder,$types,$sort)
    {
        if(!$types) {

            $types = array(
                '.jpg',
                '.png',
                '.gif',
                '.JPG',
                '.PNG',
                '.GIF'
            );

        }

        if (!$folder) return;

        $adsf = scandir($_SERVER['DOCUMENT_ROOT'].'/'. $folder);

        if ($files = scandir($_SERVER['DOCUMENT_ROOT'].'/'. $folder)) {
            if ($sort) {
                switch ($sort) {
                    case 'natsort':
                        natsort($files);
                        break;
                    case 'rsort':
                        rsort($files);
                        break;
                }
            }
        foreach ($files as $file) {
            if(is_file($_SERVER['DOCUMENT_ROOT'].$folder.$file)) {

                $ext[] =  $folder . $file;

            }
        }

        return $ext;
    }

    }


    static function getReferenceElement($xmlId){



        CModule::IncludeModule('iblock');
        CModule::IncludeModule('highloadblock');

        $hlblock = HL\HighloadBlockTable::getById(5)->fetch(); // id highload блока
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();

        $res = $entityClass::getList(array(
            'select' => array('*'),
            //'order' => array('ID' => 'ASC'),
            'filter' => array('UF_XML_ID' => $xmlId)
         ));

         $row = $res->fetch();

         return $row;



    }


}

class CatalogHelpers
{

    /**
     * Получает цену каталога согласно выбранному региону
     * @param string $elementId id элемента ифноблока Каталог
     * @return $price array|bool Return current price
     */
    static function getCurrentPrice($elementId)
    {

        if (CCatalogSKU::IsExistOffers($elementId)) {

            $price = CCatalogProduct::GetOptimalPrice($elementId, 1);
            return $price;


        } else {

            $db_res = CPrice::GetList(array(), array("PRODUCT_ID" => $elementId, "CATALOG_GROUP_ID" => $_SESSION['catalogGroupId']));
            $price = $db_res->Fetch();
            $price['PRICE'];
            return $price;
        }


    }


    /**
     * Calculate final price of product
     * @param integer $item_id Item ID
     * @param string $sale_currency Currency code
     * @return float
     */
    static function getFinalPriceInCurrency($item_id, $sale_currency = 'BYN')
    {

        global $USER;

        $currency_code = 'BYN';

        // Do item have offers?
        if (CCatalogSku::IsExistOffers($item_id)) {

            // Пытаемся найти цену среди торговых предложений
            $res = CIBlockElement::GetByID($item_id);

            if ($ar_res = $res->GetNext()) {

                if (isset($ar_res['IBLOCK_ID']) && $ar_res['IBLOCK_ID']) {

                    // Find all offers
                    $offers = CIBlockPriceTools::GetOffersArray(array(
                        'IBLOCK_ID' => $ar_res['IBLOCK_ID'],
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'CHECK_PERMISSIONS' => 'Y'
                    ), array($item_id), null, null, null, null, null, null, array('CURRENCY_ID' => $sale_currency), $USER->getId(), null);

                    foreach ($offers as $offer) {

                        $price = CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray(), 'N');
                        if (isset($price['PRICE'])) {

                            $final_price = $price['PRICE']['PRICE'];
                            $currency_code = $price['PRICE']['CURRENCY'];

                            // Find discounts and calculate price with discounts
                            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N");
                            if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                                $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
                            }

                            // Stop cycle, use found value
                            break;
                        }

                    }
                }
            }

        } else {

            // Simple product, not trade offers
            $price = CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

            // Got price?
            if (!$price || !isset($price['PRICE'])) {
                return false;
            }

            // Change currency code if found
            if (isset($price['CURRENCY'])) {
                $currency_code = $price['CURRENCY'];
            }
            if (isset($price['PRICE']['CURRENCY'])) {
                $currency_code = $price['PRICE']['CURRENCY'];
            }

            // Get final price
            $final_price = $price['PRICE']['PRICE'];

            // Find discounts and calculate price with discounts
            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
            if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
            }

        }

        // Convert to sale currency if needed
        if ($currency_code != $sale_currency) {
            $final_price = CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
            $currency_code = $sale_currency;
        }

        return $final_price;

    }


    /**
     * Calculate final price of product
     * @param integer $item_id Item ID
     * @param string $sale_currency Currency code
     * @param string $id_sku  Get a discount for SKU
     * @param array $type_price  array(1); number store 
     * @return float
     */
    static function getFinalPriceInCurrencyReal($item_id, $id_sku=false, $type_price=false, $sale_currency = 'BYN')
    {

        global $USER;

        $currency_code = 'BYN';

        $finalPrice = array();

        // Do item have offers?
        if (CCatalogSku::IsExistOffers($item_id)) {

            // Пытаемся найти цену среди торговых предложений
            $res = CIBlockElement::GetByID($item_id);

            if ($ar_res = $res->GetNext()) {

                if (isset($ar_res['IBLOCK_ID']) && $ar_res['IBLOCK_ID']) {

                    $arFilter = array(
                        'IBLOCK_ID' => $ar_res['IBLOCK_ID'],
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'CHECK_PERMISSIONS' => 'Y'
                    );

                    if(!empty($type_price) && !empty($id_sku)){

                        //$arFilter['ID'] = $id_sku;

                    }

                    $arPrices = null;


                    if($type_price){
                        $getTypePrice = true;
                        if(is_array($type_price)){
                            $arPrices = $type_price;
                        }else{
                            $arPrices = $type_price;
                        }
                    }


                    // Find all offers
                    $offers = CIBlockPriceTools::GetOffersArray($arFilter, array($item_id), null, array('XML_ID','PRICES','MIN_PRICE'), null, null, $arPrices, null, array('CURRENCY_ID' => $sale_currency), $USER->getId(), null);


                    $minPrice = 100000000;
                    $minSkuPrice = array();
                    foreach ($offers as $offer) {

                        if($id_sku){
                            if($id_sku==$offer['ID']){

                                if($getTypePrice){
                                    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($offer['ID'], $USER->GetUserGroupArray(), "N", $type_price);
                                    //debugbreaK();
                                    if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                                        $pricenew =  CatalogGetPriceTableEx($offer['ID'], 0, $type_price, 'Y', array());
                                        $pricenew =array_shift($pricenew['MATRIX'][$type_price]);
                                        $finalPrice['real_price']	= $pricenew['DISCOUNT_PRICE'];
                                        $finalPrice['old_price']	= $pricenew['PRICE'];
                                        $finalPrice['IDPRICE']		= $pricenew['ID'];
                                        $maxDiscountsByRate = CatalogHelpers::maxDiscountsByRate($arDiscounts);
                                        $finalPrice['type_sale']['VALUE_TYPE']	= $maxDiscountsByRate['VALUE_TYPE'];
                                        $finalPrice['type_sale']['VALUE_CALC']  = ceil(($finalPrice['old_price'] -  $finalPrice['real_price'])/ $finalPrice['old_price']*100);
                                        $finalPrice['type_sale']['VALUE']		= $maxDiscountsByRate['VALUE'];
                                        $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');
                                        break;
                                    }else{
                                        $pricenew =  CatalogGetPriceTableEx($offer['ID'], 0, $type_price, 'Y', array());
                                        $finalPrice['real_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];$finalPrice['old_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];
                                        $finalPrice['IDPRICE'] = $pricenew['MATRIX'][$type_price][0]['ID'];
                                        $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');
                                        break;
                                    }
                                }

                                $price = CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray(), 'N');
                                $minPrice = $price['DISCOUNT_PRICE'];
                                $minSkuPrice = $price;
                                //debugbreak();

                                break;
                            }
                        }else{

                            $price = CCatalogProduct::GetOptimalPrice($offer['ID'], 1, $USER->GetUserGroupArray(), 'N');


                            if (isset($price['PRICE'])) {

                                if(!empty($price['DISCOUNT_PRICE']) && $minPrice>$price['DISCOUNT_PRICE']) {

                                    $minPrice = $price['DISCOUNT_PRICE'];
                                    $minSkuPrice = $price;

                                }
                            }

                        }

                    }


                    if(!empty($minSkuPrice['DISCOUNT_PRICE'])){

                        $finalPrice['real_price'] = $minSkuPrice['DISCOUNT_PRICE'];
                        $finalPrice['old_price'] = $minSkuPrice['PRICE']['PRICE'];
                        $finalPrice['type_sale']['VALUE_TYPE'] = $minSkuPrice['DISCOUNT']['VALUE_TYPE'];
                        $finalPrice['type_sale']['VALUE'] = $minSkuPrice['DISCOUNT']['VALUE'];

                        if($price['DISCOUNT']['VALUE_TYPE']=='S' || $minSkuPrice['DISCOUNT']['VALUE_TYPE']=='F'){

                            $finalPrice['type_sale']['percent'] =  ceil(($finalPrice['old_price'] -  $finalPrice['real_price'])/ $finalPrice['old_price']*100);

                        }elseif($price['DISCOUNT']['VALUE_TYPE']=='P'){

                            $finalPrice['type_sale']['percent'] = $minSkuPrice['DISCOUNT']['VALUE'];

                        }else{

                            $finalPrice['type_sale']['percent'] = $minSkuPrice['DISCOUNT']['VALUE'];

                        }

                        $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');


                    }



                }
            }

        } else {


            if($type_price) {

                //real price by type price
                $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", $type_price);
                //debugbreaK();
                if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                    $pricenew =  CatalogGetPriceTableEx($item_id, 0, $type_price, 'Y', array());
                    $pricenew =array_shift($pricenew['MATRIX'][$type_price]);
                    $finalPrice['real_price']	= $pricenew['DISCOUNT_PRICE'];
                    $finalPrice['old_price']	= $pricenew['PRICE'];
                    $finalPrice['IDPRICE']		= $pricenew['ID'];
                    $maxDiscountsByRate = CatalogHelpers::maxDiscountsByRate($arDiscounts);
                    $finalPrice['type_sale']['VALUE_TYPE']	= $maxDiscountsByRate['VALUE_TYPE'];
                    $finalPrice['type_sale']['VALUE_CALC']  = ceil(($finalPrice['old_price'] -  $finalPrice['real_price'])/ $finalPrice['old_price']*100);
                    $finalPrice['type_sale']['VALUE']		= $maxDiscountsByRate['VALUE'];
                    $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');
                }else{
                    $pricenew =  CatalogGetPriceTableEx($item_id, 0, $type_price, 'Y', array());
                    $finalPrice['real_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];$finalPrice['old_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];
                    $finalPrice['IDPRICE'] = $pricenew['MATRIX'][$type_price][0]['ID'];
                    $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');
                }


            }else{
            // Simple product, not trade offers
                $price = CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

                // Got price?

                if (!$price || !isset($price['PRICE'])) {
                    return array('real_price'=>'');
                }


                // Get final price
                if(!empty($price['DISCOUNT_PRICE'])) {

                    $finalPrice['real_price'] = $price['DISCOUNT_PRICE'];
                    $finalPrice['old_price'] = $price['PRICE']['PRICE'];
                    $finalPrice['type_sale']['VALUE_TYPE'] = $price['DISCOUNT']['VALUE_TYPE'];
                    $finalPrice['type_sale']['VALUE'] = $price['DISCOUNT']['VALUE'];

                    if ($price['DISCOUNT']['VALUE_TYPE'] == 'S' || $price['DISCOUNT']['VALUE_TYPE'] == 'F') {

                        $finalPrice['type_sale']['percent'] = ceil(($finalPrice['old_price'] - $finalPrice['real_price']) / $finalPrice['old_price'] * 100);


                    } elseif ($price['DISCOUNT']['VALUE_TYPE'] == 'P') {

                        $finalPrice['type_sale']['percent'] = $price['DISCOUNT']['VALUE'];

                    } else {

                        $finalPrice['type_sale']['percent'] = $price['DISCOUNT']['VALUE'];

                    }

                    $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price'] - $finalPrice['real_price'], 2, '.', '');


                }


            }




        }



        return $finalPrice;

    }


    static function maxDiscountsByRate($arDiscounts)
    {
        $RateMax = NULL;
        $DiscountMax = array();
        foreach($arDiscounts as $Discount)
        {

            if ($Discount['PRIORITY'] >= $DiscountRateMax)
            {
                $RateMax = $Discount['PRIORITY'];
                $DiscountMax = $Discount;
            }

        }

        return $DiscountMax;
    }

    function getOffersRealPriceById($offer_id,$type_price){
        global $USER;

        $finalPrice = array();
        $arDiscounts = CCatalogDiscount::GetDiscountByProduct($offer_id, $USER->GetUserGroupArray(), "N", $type_price);
        //debugbreaK();
        if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
            $pricenew =  CatalogGetPriceTableEx($offer_id, 0, $type_price, 'Y', array());
            $pricenew =array_shift($pricenew['MATRIX'][$type_price]);
            $finalPrice['real_price']	= $pricenew['DISCOUNT_PRICE'];
            $finalPrice['old_price']	= $pricenew['PRICE'];
            $finalPrice['IDPRICE']		= $pricenew['ID'];
            $maxDiscountsByRate = CatalogHelpers::maxDiscountsByRate($arDiscounts);
            $finalPrice['type_sale']['VALUE_TYPE']	= $maxDiscountsByRate['VALUE_TYPE'];
            $finalPrice['type_sale']['VALUE_CALC']  = ceil(($finalPrice['old_price'] -  $finalPrice['real_price'])/ $finalPrice['old_price']*100);
            $finalPrice['type_sale']['VALUE']		= ceil($maxDiscountsByRate['VALUE']);
            $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');

        }else{
            $pricenew =  CatalogGetPriceTableEx($offer_id, 0, $type_price, 'Y', array());
            $finalPrice['real_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];$finalPrice['old_price'] = $pricenew['MATRIX'][$type_price][0]['PRICE'];
            $finalPrice['IDPRICE'] = $pricenew['MATRIX'][$type_price][0]['ID'];
            $finalPrice['discount_in_rubles'] = number_format($finalPrice['old_price']-$finalPrice['real_price'], 2, '.', '');
        }

        return $finalPrice;
    }


    static function setColorsArray(){

        if(!isset($_SESSION['colors'])){
        $file = $_SERVER['DOCUMENT_ROOT'].'/colors.csv';
        $colors = array();
        
        $arrayTest = array_map('str_getcsv', file($file));

        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataArray = explode(';',$data[0]);    
                 $colors[$dataArray[3]] = $dataArray[1];

            }
            fclose($handle);
        }

            
            $_SESSION['colors'] = $colors;
            
        }

    }


    static function getStoreValue($productId, $storeId = 2)
    {

        $rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $productId, 'STORE_ID' => $storeId), false, false, array('AMOUNT'));

        if ($arStore = $rsStore->Fetch()) {

            return $arStore['AMOUNT'];

        }

    }

    static function getStoresQuantity($product_id,$stores){

        $arSelect = array();
        $arStores = array();
        foreach($stores as $store) {

            $rsStore = CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' => $product_id, 'STORE_ID' => $store), false, false, array('AMOUNT'));
            if ($arStore = $rsStore->Fetch()) {

                $arStores[$store] =  $arStore['AMOUNT'];

            }

        }

        return $arStores;


    }

    static function getCurrentStores($cityID)
    {

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter = Array("IBLOCK_ID" => '59', "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", 'ID' => $cityID);

        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
        $ob = $res->GetNextElement();

        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $stores = array();


        foreach($arProps['CONNECTED_STORES']['~VALUE'] as $storeId) {

            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
            $arFilter = Array("IBLOCK_ID" => '60', "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", 'ID' => $storeId);

            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
            $ob = $res->GetNextElement();

            $arFieldsStore = $ob->GetFields();
            $arPropsStore = $ob->GetProperties();

            $stores[$arPropsStore['PRICE_TYPE']['~VALUE']] = $arPropsStore['STORE_ID']['~VALUE'];

        }

        return $stores;

    }


}

class importSaleXmlBuslickBuslick{

    function importFileSaleXml($href = '/upload/salexmlfull.xml', $action=false){
        $href = '/upload/salexml.xml';
        $href = $_SERVER["DOCUMENT_ROOT"].$href;
        $arraySale = array();
        $arraySale = xmlSaleReader::xmlFile2Array($href);
        if(count($arraySale)>0){
            $add = importSaleXmlBuslick::addImportSaleBitrix($arraySale,$action);
        }
    }


    function addImportSaleBitrix($Sales,$action=false){

        $debugEcho = false;
        $debugAdd = false;
        $debugAddChunk = false;


        if(!$action){

            $updateRuleSale = true;
            $updateIblockSale = true;
            $addXmlidSalebyProduct= true;

        }elseif($action=='updateRuleSale'){

            $updateRuleSale = true;

        }elseif($action=='updateIblockSale'){

            $updateIblockSale = true;

        }elseif($action=='addXmlidSalebyProduct'){

            $addXmlidSalebyProduct = true;

        }









        $chunk_size = 100;
        $max_count_rule = 100000;
        $addIds = array();
        //$addIds = array(8698,8701,8705);

        if($updateIblockSale){
            importSaleXmlBuslick::getSaleIblockByCode($Sales);
        }

        if($addXmlidSalebyProduct){
        importSaleXmlBuslick::addSaleXmlIdInProduct($Sales);
        }


        if($updateRuleSale){
            $typePrice = importSaleXmlBuslick::getAllTypePrice($price);
            $i = 0;
            $j = 0;
            if (CModule::IncludeModule("catalog")) {

                $t_s = microtime(true);
                $arr = array();

                foreach ($Sales as $k => $Sale) {
                    unset($Sale['@attributes']);
                    //unset($Sale['CATALOG_GROUP_IDS']);
                    $Sale['CATALOG_GROUP_IDS'] = importSaleXmlBuslick::getIdShopTypePriceByXml($Sale['CATALOG_GROUP_IDS'],$typePrice);

                    $Sale['NAME'] = $Sale['NAME']."  import xml";
                    if (count($Sale['CONDITIONS']['CHILDREN']) > $chunk_size && count($Sale['CONDITIONS']['CHILDREN'])<$max_count_rule) {

                        if($debugAddChunk){
                            $CHILDREN = array_chunk($Sale['CONDITIONS']['CHILDREN'], $chunk_size, true);
                            $o = 1;
                            $count = count($CHILDREN);
                            foreach ($CHILDREN as $child) {
                                $Insert = $Sale;
                                $Insert["NAME"] = $Insert["NAME"] . " chunk " . $o . " of " . $count;
                                $Insert['CONDITIONS']['CHILDREN'] = $child;

                                $ID = CCatalogDiscount::Add($Insert);
                                $res = $ID > 0;
                                if ($res){
                                    $addIds[]= $res;
                                }else{
                                    if($debugEcho) {
                                        echo('</br>');
                                        echo('error k ' . $k . '-' . $o . '-' . $Sale['ACTIVE_FROM'] . '-to - ' . $Sale['ACTIVE_TO'] . ' count =' . count($Sale['CONDITIONS']['CHILDREN']) . '  name =  ' . $Sale['name'] . '  time add' . $time);
                                        echo('</br>');
                                    }
                                }
                                $o++;

                                $arr[] = ' k ' . $k . '-- chunk count =' . count($Insert['CONDITIONS']['CHILDREN']) . '  name =  ' . $Insert['name'] . '  time add' . $time;
                            }
                        }

                    } else {
                        if ($debugAdd) {
                            $time_start = microtime(true);

                            //debugbreak();

                            $ID = CCatalogDiscount::Add($Sale);
                            $res = $ID > 0;
                            if ($res){
                                $addIds[] = $ID;
                            }else{
                                if($debugEcho) {
                                    echo('</br>');
                                    echo('error k ' . $k . '--' . $Sale['ACTIVE_FROM'] . '-to - ' . $Sale['ACTIVE_TO'] . ' count =' . count($Sale['CONDITIONS']['CHILDREN']) . '  name =  ' . $Sale['name'] . '  time add' . $time);
                                    echo('</br>');
                                }
                                $j++;
                            }


                            $time_end = microtime(true);
                            $time = $time_end - $time_start;

                            if($debugEcho) {
                                echo(' k ' . $k . '-- count =' . count($Sale['CONDITIONS']['CHILDREN']) . '  name =  ' . $Sale['name'] . '  time add' . $time);
                                $arr[] = ' k ' . $k . '-- count =' . count($Sale['CONDITIONS']['CHILDREN']) . '  name =  ' . $Sale['name'] . '  time add' . $time;
                                echo('</br>');
                                echo('</br>');
                                echo('</br>');
                            }
                        }
                        $i++;
                    }

                }

                if($debugEcho) {
                $t_e = microtime(true);
                $t = $t_e - $t_s;
                echo('all time add' . $t);
                echo('</br>');
                echo('</br>');
                echo('</br>');

                    printr($arr);
                    echo('</br>');
                    echo(count($arFields) . '--- insert--' . $i . '---error' . $j);
                    echo('</br>');
                }
            }

        importSaleXmlBuslick::deleteSaleNotInImport($addIds);

        }
    }


    function deleteSaleNotInImport($listIdInImport){

        $arFilter = array(
            //"!ID" => $listIdInImport,
            "%XML_ID" => '-offline',
        );

        $dbProductDiscounts = CCatalogDiscount::GetList(
            array("SORT" => "ASC"),
            $arFilter,
            false,
            false,
            array(
                "ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO",
                "RENEWAL","XML_ID", "NAME",
            )
        );

        while ($arProductDiscounts = $dbProductDiscounts->Fetch())
        {
            if(!in_array($arProductDiscounts['ID'],$listIdInImport)){
                $del = CCatalogDiscount::Delete($arProductDiscounts['ID']);
            }
        }
    }

    function getAllTypePrice($params){

        $prices = array();

        $dbPriceType = CCatalogGroup::GetList(
            array(),
            array(),
            false,
            false,
            array("ID","NAME","XML_ID")
        );
        while ($arPriceType = $dbPriceType->Fetch())
        {
            $prices[$arPriceType["XML_ID"]] = $arPriceType["ID"];
        }

        return $prices;
    }

    function getIdShopTypePriceByXml($stXmlIds,$typePriceId){
        $shop = array();
        $shopXml = explode(';',$stXmlIds);

        foreach($shopXml as $xmlId){
            if(array_key_exists($xmlId,$typePriceId)){
                $shop[] = $typePriceId[$xmlId];
            }
        }

        if(in_array('534cd330-563f-11e4-a5b8-005056b60002',$shopXml)){
            $shop[] = 1;
        }


        return $shop;
    }


    function getSaleIblockByCode($Sales){
        $idSaleIblock = 20;
        $saleIds = array();
        $saleData = array();

        $saleDataInsert = array();
        $saleDataUpdate = array();

        $idsSaleIblock = array();
        $SaleIblock = array();




        foreach ($Sales as $k => $Sale) {
            if(!in_array($saleIds,$Sale['XML_ID'])){

                $saleIds[] = $Sale['XML_ID'];

                $saleData[$Sale['XML_ID']] = array();
                $saleData[$Sale['XML_ID']]['NAME'] = $Sale['NAME'];
                $saleData[$Sale['XML_ID']]['ACTIVE_FROM'] = $Sale['ACTIVE_FROM'];
                $saleData[$Sale['XML_ID']]['ACTIVE_TO'] = $Sale['ACTIVE_TO'];
                $saleData[$Sale['XML_ID']]['XML_ID'] = $Sale['XML_ID'];
            }
        }




        $arSelect = Array("ID", "NAME","XML_ID","IBLOCK_ID","DATE_ACTIVE_TO","DATE_ACTIVE_FROM","SECTION_ID",);
        $arFilter = Array('XML_ID'=>'РТ000001919-offline', "IBLOCK_ID"=>$idSaleIblock);
        //$arFilter = Array('XML_ID'=>$saleIds, "IBLOCK_ID"=>$idSaleIblock);
        $arFilter = Array("IBLOCK_ID"=>$idSaleIblock);


        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement())
        {

            $element = $ob->GetFields();
            $SaleIblock[$element['ID']] = $element;
            $idsSaleIblock[$element['ID']]  = $element['XML_ID'];

        }



        foreach($saleIds as $xmlId){
            if(array_search($xmlId, $idsSaleIblock)){
                $saleDataUpdate[] = $saleData[$xmlId];
            }else{
                $saleDataInsert[] = $saleData[$xmlId];
            }
        }

        if(count($saleDataInsert)>1){
            $idsInsertSale = array();
            foreach($saleDataInsert as $saleInsert){
                $idsInsertSale[]= importSaleXmlBuslick::addSaleIblock($saleInsert);
            }
        }



    }

    function addSaleIblock($params){
        global $USER;
        $idSaleIblock = 20;
        $el = new CIBlockElement;

        $PROP = array();
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => 20,
            "NAME"           => $params['NAME'],
            "CODE"           => $params['XML_ID'],
            "EXTERNAL_ID"	=> $params['XML_ID'],
            "ACTIVE"         => "N",            // активен
            "DATE_ACTIVE_FROM"	=> $params['ACTIVE_FROM'],
            "DATE_ACTIVE_TO"    => $params['ACTIVE_TO'],
        );

        if($PRODUCT_ID = $el->Add($arLoadProductArray))

            return $PRODUCT_ID;

    }



    function addSaleXmlIdInProduct($Sales){
        $addArray = array();

        foreach($Sales as $sale){
            if(is_set($addArray[$sale['XML_ID']])){
                $addArray[$sale['XML_ID']] =  array_merge($addArray[$sale['XML_ID']],importSaleXmlBuslick::searchProductsSale($sale));
            }else{
                $addArray[$sale['XML_ID']] =  importSaleXmlBuslick::searchProductsSale($sale);
            }
        }


        importSaleXmlBuslick::addPropToInfoBlock('XML_IDS_SALES');


        foreach($addArray as $XML_IDSale => $Sale){

            foreach($Sale as $productXML_ID){

                $productInfo =  importSaleXmlBuslick::getProductIDbyXmlCode($productXML_ID);

                $PROP['XML_IDS_SALES'] = $XML_IDSale;
                $el = new CIBlockElement;
                $resultMy = CIBlockElement::SetPropertyValues($productInfo['ID'], $productInfo['IBLOCK_ID'],  $PROP['XML_IDS_SALES'], 'XML_IDS_SALES');

            }
        }


        return $addArray;
    }

    function searchProductsSale($dataSale){

        $xmlIds = array();

        $xmlIds = importSaleXmlBuslick::searchXmlId($dataSale['CONDITIONS']);

        return $xmlIds;

    }


    function searchXmlId($data){

        $array = array();

        foreach($data as $name => $value){

            if($value['CLASS_ID']=='CondIBXmlID' && $value['DATA']['logic']=='Equal'){
                $array[]= $value['DATA']['value'];
                continue;
            }
            if(is_array($value)){
                $ar = importSaleXmlBuslick::searchXmlId($value);
                $array =array_merge($ar,$array);
            }
        }

        return $array;
    }

    /*
    protected function getPropertyIdByCode( //получение ID свойства по его мнемокоду
        $propCode //мнемо код свойства
    ) {
        $prop = CIBlockProperty::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>58, "CODE"=>$propCode));
        if (!$prop)
            throw new Exception("CIBlockProperty::GetList failed. IBLOCK_ID='$this->infoBlockId'?, CODE='$propCode'. File: ".__FILE__.", line: ".__LINE__);
        if ($propFields = $prop->GetNext())
            return $propFields["ID"] ? intval($propFields["ID"]) : $prop;
        else
            throw new Exception("Get property failed. IBLOCK_ID='$this->infoBlockId', CODE='$propCode'. File: ".__FILE__.", line: ".__LINE__);
    }
*/


    function getProductIDbyXmlCode($XmlCode,$idblock){
        $idblock = $idblock ? $idblock : 58;
        $elements =array();
        if (CModule::IncludeModule('iblock')){
            $arFilter = array('IBLOCK_ID' => IntVal($idblock),'XML_ID'=>$XmlCode,'ACTIVE'=>'Y');
            $rsSect = CIBlockElement::GetList(array('IBLOCK_ID','NAME','SECTION_CODE','SECTION_ID','ID','EXTERNAL_ID','XML_IDS_SALES'),$arFilter, false, Array(), array());
            while($elements = $rsSect->GetNext()){
                return $elements;
            }
        }
    }


    protected function addPropToInfoBlock( //добавляет новое свойство в инфоблок, если его там нет
        $propCode //мнемокод нового свойства
    ) {
        $propsIblock = array();
        $propsIblockCode = array();
        if (!$propsIblock) {
            $properties = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>58));

            while ($prop_fields = $properties->GetNext()) {
                array_push($propsIblock, $prop_fields["CODE"]);
                $propsIblockCode[$prop_fields["CODE"]] = $prop_fields["ID"];

            }
        }

        if (in_array($propCode, $propsIblock))
            return $propsIblockCode[$propCode]; //такое свойство в данном инфоблоке уже есть, добавлять не надо


        unset($propsIblock);
        unset($propsIblockCode);

        //если оказались здесь, то надо добавлять новое свойство к инфоблоку
        // будем добавлять
        $name = $propCode;

        $arFields = array(
            "NAME" => $propCode,
            "ACTIVE" => "Y",
            "SORT" => "500",
            "CODE" => $propCode,
            "MULTIPLE" => "Y",
            "PROPERTY_TYPE" => 'S',
            "IBLOCK_ID" => 58
        );


        $infoBlockProperty = new CIBlockProperty;
        $propID = $infoBlockProperty->Add($arFields);

        return $propID;
    }


}

require_once 'helpers/global.php';
require_once 'helpers/BuslikReserveHelper.php';
require_once 'helpers/BuslikTimeSlotHelper.php';



require_once 'events.php';