<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\redis;

use yii\redis\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SessionStorage extends ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }
    
    public function attributes()
    {
        return [
            'id', 'session_uuid',
            'session_key', 'session_value',
            'session_timeout','session_updated_at'
        ];
    }
    /**
     * relations can not be defined via a table as there are not tables in redis. You can only define relations via other records.
     */
}
