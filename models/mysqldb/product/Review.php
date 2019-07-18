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
class Review extends ActiveRecord
{
    // 评论默认状态，也就是用户添加了评论后的状态（前面是客户的评论信息需要审核的前提下，如果客户信息不需要审核的话，则就是ACTIVE_STATUS）
    const NOACTIVE_STATUS = 10;
    // 审核通过的状态
    const ACTIVE_STATUS = 1;
    // 审核拒绝的状态
    const REFUSE_STATUS = 2;
    
    
    public function getActiveStatus(){
        return self::ACTIVE_STATUS;
    }
    
    public function getNoActiveStatus(){
        return self::NOACTIVE_STATUS;
    }
    
    public function getRefuseStatus(){
        return self::REFUSE_STATUS;
    }
    
    public static function tableName()
    {
        return '{{%review}}';
    }

}
