<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\error;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit //extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $primaryKey = Yii::$service->helper->errorHandler->getPrimaryKey();
        $primaryVal = Yii::$app->request->get($primaryKey);
        $errorHander = Yii::$service->helper->errorHandler->getByPrimaryKey($primaryVal);
        return $errorHander->attributes;
        
    }
    

}
