<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\product;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Attr extends ActiveRecord
{
    const STATUS_ENABLE  = 1;
    const STATUS_DISABLE = 2;
    
    public static function tableName()
    {
        return '{{%product_attribute}}';
    }

}
