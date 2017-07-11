<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\helper;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Format
{
    /**
     * @property $price | Float ,价格
     * @property $bits | Int , 小数点后几位的格式，譬如4.00
     * @return float， 返回格式化后的数据
     * 一般用于模板中，按照显示格式显示产品数据。
     */
    public static function price($price, $bits = 2)
    {
        return number_format($price, $bits);
    }
}
