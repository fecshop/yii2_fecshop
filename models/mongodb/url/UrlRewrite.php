<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mongodb\url;

use yii\mongodb\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class UrlRewrite extends ActiveRecord
{
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        return 'url_rewrite';
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        return [
        '_id',
        'type',
        'custom_url_key',
        'origin_url',
        'status',
        'updated_at',
        'created_at',
        ];
    }
    /**
     * 给model对应的表创建索引的方法
     * 在indexs数组中填写索引，如果有多个索引，可以填写多行
     * 在migrate的时候会运行创建索引，譬如：
     * @fecshop/migrations/mongodb/m170228_072455_fecshop_tables
     */
    public static function create_index()
    {
        $indexs = [
            ['custom_url_key'        => -1],

        ];

        $options = ['background' => true];
        foreach ($indexs as $columns) {
            self::getCollection()->createIndex($columns, $options);
        }
    }
}
