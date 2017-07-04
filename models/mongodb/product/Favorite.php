<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mongodb\product;

use yii\mongodb\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Favorite extends ActiveRecord
{
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        return 'favorite';
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        $origin = [
            '_id',
            'product_id',    // 产品id 字符串
            'user_id',        // 用户id int类型
            'created_at',    // 创建时间 int
            'updated_at',    // 更新时间 int
            'store',            // Store 当前store
        ];

        return $origin;
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
            ['user_id'        => -1],
            ['product_id'        => -1],

        ];

        $options = ['background' => true];
        foreach ($indexs as $columns) {
            self::getCollection()->createIndex($columns, $options);
        }
    }
}
