<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\cart;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Coupon extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sales_coupon}}';
    }

    public function rules(){
        $rules = [
            ['coupon_code', 'filter', 'filter' => 'trim'],
            ['coupon_code', 'required'],
            ['coupon_code', 'string', 'length' => [2, 100]],

            ['expiration_date', 'filter', 'filter' => 'trim'],

            ['users_per_customer', 'filter', 'filter' => 'trim'],
            ['users_per_customer', 'required'],
            ['users_per_customer', 'integer', 'min' => 1, 'max' => 999999],

            ['type', 'filter', 'filter' => 'trim'],
            ['type', 'required'],
            ['type', 'integer', 'min' => 0, 'max' => 99],

            ['conditions', 'filter', 'filter' => 'trim'],
            ['conditions', 'required'],
            ['conditions', 'integer', 'min' => 1, 'max' => 999999],

            ['discount', 'filter', 'filter' => 'trim'],
            ['discount', 'required'],
            ['discount', 'integer', 'min' => 1, 'max' => 999999],
        ];

        return $rules;
    }
}
