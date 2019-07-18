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
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Article extends ActiveRecord
{
    const STATUS_DELETED = 10;
    const STATUS_ACTIVE = 1;
    
    public static function tableName()
    {
        return '{{%article}}';
    }
    
    public function beforeSave($insert)
    {
        foreach ($this->attributes() as $attr) {
            if (is_array($this->{$attr})) {
                throw new InvalidValueException('article model save fail,  attribute ['.$attr. '] is array, you must serialize it before save ');
            }
        }
        return parent::beforeSave($insert);
    }
}
