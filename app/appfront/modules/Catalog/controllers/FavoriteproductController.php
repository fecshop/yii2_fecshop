<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\controllers;

use fecshop\app\appfront\modules\AppfrontController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FavoriteproductController extends AppfrontController
{
    public $enableCsrfValidation = false;
    // 增加收藏
    public function actionAdd()
    {
        return $this->getBlock()->getLastData();
        //return $this->render($this->action->id,$data);
    }
    // 收藏列表
    //public function actionLists()
    //{
    //    $data = $this->getBlock()->getLastData($editForm);
    //
    //    return $this->render($this->action->id, $data);
    //}
}
