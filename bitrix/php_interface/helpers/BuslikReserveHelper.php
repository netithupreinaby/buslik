<?php

class BuslikReserveHelper
{

    const RESERVE_TYPE_CARD   = 0;
    const RESERVE_TYPE_BASKET = 1;
    const RESERVE_TYPE_ORDER  = 2;

    const RESERVE_TIME_CARD   = 30;
    const RESERVE_TIME_BASKET = 30;
    const RESERVE_TIME_ORDER  = 10;

    public static function setReserve($productData, $reserveType = 0, $active = true)
    {
        global $DB, $USER;
        $userID = $USER->GetID();
        $userFID = CSaleBasket::GetBasketUserID();

        $startTime = mktime();

        switch ($reserveType) {
            case self::RESERVE_TYPE_CARD: $reserveTime = self::RESERVE_TIME_CARD;
                break;
            case self::RESERVE_TYPE_BASKET: $reserveTime = self::RESERVE_TIME_BASKET;
                break;
            case self::RESERVE_TYPE_ORDER: $reserveTime = self::RESERVE_TIME_ORDER;
                break;
            default: $reserveTime = self::RESERVE_TIME_ORDER;
        }

        $productId = array();
        $productCount = array();
        $products = array();

        foreach ($productData as $index => $product) {
            $productId[] = $product['ID'];
            $productCount[] = $product['QUANTITY'];
            $products[] = array(
                'id' => $product['ID'],
                'count' => $product['QUANTITY'],
            );
        }

        if (is_array($productId) && count($productId) > 1){
            $productWhere = " AND `product_id` IN (" . implode(',',$productId) . ")";
        } else {
            $productWhere = " AND `product_id`=" . intval($productId[0]);
        }

        $selectSql = "SELECT * FROM `a_user_reserve` WHERE `user_id`=" . $userID . " AND `fuser_id`=" . $userFID . $productWhere;

        $result = $DB->query($selectSql);

        if ($result->SelectedRowsCount() === 0){

            if (count($productId) == 1){
                reset($productId);
                $values = array(
                    'user_id' => $userID,
                    'fuser_id' => $userFID,
                    'product_id' => current($productId),
                    'product_count' => current($productCount),
                    'reserve_type' => $reserveType,
                    'start_time' => $startTime,
                    'is_active' => $active,
                );
            } else {
                foreach ($products as $index => $product) {
                    $values = array(
                        'user_id' => $userID,
                        'fuser_id' => $userFID,
                        'product_id' => $product['ID'],
                        'product_count' => $product['QUANTITY'],
                        'reserve_type' => $reserveType,
                        'start_time' => $startTime,
                        'is_active' => $active,
                    );

                    $DB->Insert('a_user_reserve', $values, '', false, '', true);
                }
            }

        } else {

            $reservedGoods = array();
            while ($row = $result->Fetch()){
                $reservedGoods[$row['id']] = $row;
            }
        }
    }
}