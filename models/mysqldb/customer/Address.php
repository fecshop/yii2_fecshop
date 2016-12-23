<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mysqldb\customer;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */

class Address extends ActiveRecord
{
    const STATUS_DELETED = 10;
    const STATUS_ACTIVE  = 1;
	
    public static function tableName()
    {
        return 'customer_address';
    }
	
	
}
