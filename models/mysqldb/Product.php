<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb;

use yii\db\ActiveRecord;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends ActiveRecord
{
    public static $_customProductAttrs;
    
    public $category;
    
    const STATUS_ENABLE  = 1;
    const STATUS_DISABLE = 2;
    
    const IS_IN_STOCK = 1;
    const OUT_STOCK = 2;
    
    public static function tableName()
    {
        return '{{%product_flat}}';
    }
    
    public function beforeSave($insert)
    {
        foreach ($this->attributes() as $attr) {
            if (is_array($this->{$attr})) {
                throw new InvalidValueException('product model save fail,  attribute ['.$attr. '] is array, you must serialize it before save ');
            }
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * get custom product attrs.
     */
    public function addCustomProductAttrs($attrs)
    {
        self::$_customProductAttrs = $attrs;
    }
    /*
    public function rules()
    {
        $rules = [
            
            ['customer_email', 'filter', 'filter' => 'trim'],
            ['customer_email', 'email'],
            ['customer_email', 'required'],
            ['customer_email', 'string', 'length' => [4, 90]],
            
            ['customer_firstname', 'filter', 'filter' => 'trim'],
            ['customer_firstname', 'required'],
            ['customer_firstname', 'string', 'length' => [1, 50]],
            
            ['customer_lastname', 'filter', 'filter' => 'trim'],
            //['customer_lastname', 'required'],
            ['customer_lastname', 'string', 'length' => [0, 50]],
            
            ['customer_telephone', 'filter', 'filter' => 'trim'],
            ['customer_telephone', 'required'],
            ['customer_telephone', 'string', 'length' => [1, 50]],
            
            ['customer_address_country', 'filter', 'filter' => 'trim'],
            ['customer_address_country', 'required'],
            ['customer_address_country', 'string', 'length' => [1, 50]],
            
            ['customer_address_state', 'filter', 'filter' => 'trim'],
            ['customer_address_state', 'required'],
            ['customer_address_state', 'string', 'length' => [1, 50]],
            
            ['customer_address_city', 'filter', 'filter' => 'trim'],
            ['customer_address_city', 'required'],
            ['customer_address_city', 'string', 'length' => [1, 50]],
            
            ['customer_address_zip', 'filter', 'filter' => 'trim'],
            ['customer_address_zip', 'required'],
            ['customer_address_zip', 'string', 'length' => [1, 20]],
            
            ['customer_address_street1', 'filter', 'filter' => 'trim'],
            ['customer_address_street1', 'required'],
            ['customer_address_street1', 'string', 'length' => [1, 500]],
            
            ['customer_address_street2', 'filter', 'filter' => 'trim'],
            ['customer_address_street2', 'string', 'length' => [1, 500]],
            
            ['order_remark', 'filter', 'filter' => 'trim'],
            ['order_remark', 'string', 'length' => [1, 1000]],
            
            ['coupon_code', 'filter', 'filter' => 'trim'],
            ['coupon_code','string','length' =>[1, 100]],
        ];

        return $rules;
    }
    */
}
