<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mongodb;

use yii\mongodb\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends ActiveRecord
{
    public static $_customProductAttrs;
    protected $_product_attributes;
    
    const STATUS_ENABLE  = 1;
    const STATUS_DISABLE = 2;
    
    const IS_IN_STOCK = 1;
    const OUT_STOCK = 2;
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        return 'product_flat';
    }

    /**
     * get custom product attrs.
     */
    public function addCustomProductAttrs($attrs)
    {
        self::$_customProductAttrs = $attrs;
        // 设置为空后，在执行方法attributes()的时候，就会重新计算 $this->_product_attributes 的值
        $this->_product_attributes = [];
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        if (empty($this->_product_attributes)) {
            $origin = [
                '_id',
                'name',
                'spu',
                'sku',
                
                'score',
                'status',
                'qty',
                'min_sales_qty',
                'is_in_stock',
                'visibility',
                'url_key',
                //'url_path',
                'category',
                'price',
                'cost_price',
                'special_price',
                'special_from',
                'special_to',
                'tier_price',
                'final_price',   // 算出来的最终价格。这个通过脚本赋值。
                'new_product_from',
                'new_product_to',
                'freeshipping',
                'featured',
                'upc',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'image',
                'sell_7_count',
                'sell_30_count',
                'sell_90_count',
                'description',
                'short_description',
                'custom_option',
                'remark',
                
                'long',            // 长
                'width',           // 宽
                'high',            // 高
                'weight',          // 重量
                'volume_weight',   // 体积重
                
                'package_number',  // 打包销售个数，譬如，值为5，则加入购物车的个数都是5的倍数。

                'created_at',
                'updated_at',
                'created_user_id',
                'attr_group',
                'reviw_rate_star_average',       //评论平均评分
                'review_count',                  //评论总数
                'reviw_rate_star_average_lang',  //（语言）评论平均评分
                'review_count_lang',             //（语言）评论总数
                'reviw_rate_star_info',          // 评星详细，各个评星的个数
                'reviw_rate_star_info_lang',     // （语言）评星详细，各个评星的个数
                
                'favorite_count',                // 产品被收藏的次数。
                'relation_sku',            // 相关产品
                'buy_also_buy_sku',        // 买了的还买了什么
                'see_also_see_sku',        // 看了的还看了什么
                'origin_mysql_id',
            ];
            if (is_array(self::$_customProductAttrs) && !empty(self::$_customProductAttrs)) {
                $origin = array_merge($origin, self::$_customProductAttrs);
            }
            $this->_product_attributes = $origin;
        }

        return $this->_product_attributes;
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
            ['spu'        => -1],
            ['sku'        => -1],
            ['category' => -1,'score'           => 1],
            ['category' => -1,'review_count'    => 1],
            ['category' => -1,'favorite_count'  => 1],
            ['category' => -1,'created_at'      => 1],
            ['category' => -1,'final_price'     => 1],
        ];

        $options = ['background' => true];
        foreach ($indexs as $columns) {
            self::getCollection()->createIndex($columns, $options);
        }
    }
}
