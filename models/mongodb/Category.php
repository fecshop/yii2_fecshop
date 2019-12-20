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
class Category extends ActiveRecord
{
    
    
    const MENU_SHOW      = 1;
    const MENU_NOT_SHOW  = 2;
    const STATUS_ENABLE  = 1;
    const STATUS_DISABLE = 2;

    
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        return 'category';
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        return [
            '_id',
            'parent_id',
            'name',
            'status',
            'menu_show',
            'url_key',
            'level',
            'thumbnail_image',
            'image',
            'filter_product_attr_selected',
            'filter_product_attr_unselected',
            'description',
            'menu_custom',
            'title',
            'meta_description',
            'meta_keywords',
            //'include_in_menu',
            //'is_feature',
            //'available_sort_by',
            //'default_sort_by',
            //'theme',
            //'active_from',
            //'active_to',
            'created_at',
            'updated_at',
            'created_user_id',
            //other
            /*
                category filter
                category product


            */
            'origin_mysql_parent_id',  // 用户mysql数据同步到mongodb，这里保存的是mysql数据库中的parent_id
            'origin_mysql_id',  //  用户mysql数据同步到mongodb，这里保存的是mysql数据库中的id
            'sort_order',
       ];
    }
}
