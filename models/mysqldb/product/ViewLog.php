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
class ViewLog extends ActiveRecord
{
    public static $_tableName;

    public static function tableName()
    {
        return self::$_tableName;
    }

    public static function setCurrentTableName($tableName)
    {
        self::$_tableName = $tableName;
    }
}
