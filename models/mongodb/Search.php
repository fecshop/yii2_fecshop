<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mongodb;

use yii\base\InvalidValueException;
use yii\mongodb\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Search extends ActiveRecord
{
    /**
     * 语言，在使用model之前必须设置语言，否则报错。
     */
    public static $_lang;
    public static $_filterColumns;
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        if (self::$_lang) {
            return 'full_search_product_'.self::$_lang;
        } else {
            //throw new InvalidValueException('search class $_lang is empty, you must set search model class variable $_lang before  use it');
            return 'full_search_product_no_lang';
        }
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        $origin = [
            '_id',
            'name',
            'spu',
            'sku',
            'score',
            'status',
            'is_in_stock',
            'url_key',
            'price',
            'cost_price',
            'special_price',
            'special_from',
            'special_to',
            'final_price',   // 算出来的最终价格。这个通过脚本赋值。
            'image',
            'short_description',
            'description',
            'created_at',
            'sync_updated_at',  // 同步产品表信息到搜索表的时间戳。
        ];
        if (is_array(self::$_filterColumns) && !empty(self::$_filterColumns)) {
            $origin = array_merge($origin, self::$_filterColumns);
            $origin = array_unique($origin);
        }

        return $origin;
    }
}
