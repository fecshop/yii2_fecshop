<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\order;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Item extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sales_flat_order_item}}';
    }
    
    public function rules()
    {
        $rules = [
            
            ['store', 'filter', 'filter' => 'trim'],
            ['store', 'string', 'length' => [1, 100]],
            
            ['product_id', 'filter', 'filter' => 'trim'],
            ['product_id', 'required'],
            ['product_id', 'string', 'length' => [1, 100]],
            
            ['sku', 'filter', 'filter' => 'trim'],
            ['sku', 'required'],
            ['sku', 'string', 'length' => [1, 100]],
            
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'length' => [1, 255]],
            
            ['custom_option_sku', 'filter', 'filter' => 'trim'],
            ['custom_option_sku', 'string', 'length' => [1, 50]],
            
            ['image', 'filter', 'filter' => 'trim'],
            ['image', 'string', 'length' => [1, 255]],
            
        ];

        return $rules;
    }
    
    
}
