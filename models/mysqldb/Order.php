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

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order extends ActiveRecord
{
    /**
     * @var 如果该值为true，则会干扰下面的rules()方法，让其返回没有rules限制
     * 可以通过下面的方法setGenerateOrderByPaypalToken() 来设置该值
     * 目前这个部分的设置主要用于paypal express 支付部分，也就是在购物车页面点击paypal
     * 先生成一个空的订单，里面只有order_id 和 token信息，当从paypal express跳转回网站后，
     * 在通过session获取到这个订单，然后更新这个订单。
     */
    protected $generate_order_by_paypal_token = false;

    public static function tableName()
    {
        return '{{%sales_flat_order}}';
    }

    public function setGenerateOrderByPaypalToken($status = true){
        $this->generate_order_by_paypal_token = $status;
    }
    
    public function rules()
    {
        if ($this->generate_order_by_paypal_token) {
            return [];
        }
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
}
