<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\cms;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlock extends ActiveRecord
{
    const STATUS_DELETED = 10;
    const STATUS_ACTIVE = 1;
    const STATUS_DISACTIVE = 2;
    
    public static function tableName()
    {
        return '{{%static_block}}';
    }
}
