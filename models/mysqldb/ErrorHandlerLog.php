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
// use fecshop\models\mysqldb\IpnMessage;
class ErrorHandlerLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%error_handler_log}}';
    }
}
