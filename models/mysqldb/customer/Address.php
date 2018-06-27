<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\customer;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Address extends ActiveRecord
{
    const STATUS_DELETED = 10;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%customer_address}}';
    }
    
    public function rules()
    {
        $rules = [
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'length' => [4, 90]],
            
            ['first_name', 'filter', 'filter' => 'trim'],
            ['first_name', 'string', 'length' => [1, 50]],
            
            ['last_name', 'filter', 'filter' => 'trim'],
            ['last_name', 'string', 'length' => [1, 50]],
            
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'string', 'length' => [0, 50]],
            
            ['street1', 'filter', 'filter' => 'trim'],
            ['street1', 'string', 'length' => [1, 500]],
            
            ['street2', 'filter', 'filter' => 'trim'],
            ['street2', 'string', 'length' => [1, 500]],
            
            ['city', 'filter', 'filter' => 'trim'],
            ['city', 'string', 'length' => [1, 50]],
            
            ['state', 'filter', 'filter' => 'trim'],
            ['state', 'string', 'length' => [1, 50]],
            
            ['zip', 'filter', 'filter' => 'trim'],
            ['zip', 'string', 'length' => [1, 50]],
            
            ['country', 'filter', 'filter' => 'trim'],
            ['country', 'string', 'length' => [1, 50]],
            
        ];

        return $rules;
    }
    
    
}
