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
class Newsletter extends ActiveRecord
{
    const ENABLE_STATUS = 1;
    const DISABLE_STATUS = 10;

    public static function tableName()
    {
        return '{{%newsletter}}';
    }
    
    
}
