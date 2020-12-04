<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;

/**
 * Format services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
// use \fecshop\services\helper\Format;
class Format extends Service
{
    /**
     * @param $number | Float
     * @param $bits | Int
     * @return $number | Float
     * 返回格式化形式的float小数，譬如2 会变成2.00
     */
    public function numberFormat($number, $bits = 2)
    {
        return number_format($number, $bits, '.', '');
    }

    /**
     * @param $day | Int 多少天之前
     * 返回最近xx天的日期数组
     */
    public function getPreDayDateArr($day)
    {
        $arr = [];
        for ($i=$day; $i>=0; $i--) {
            $str = date("Y-m-d", strtotime("-$i day"));
            $arr[$str] = 0;
        }
        
        return $arr;
    }
    
}
