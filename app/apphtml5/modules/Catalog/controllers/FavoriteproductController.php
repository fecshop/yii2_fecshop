<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Catalog\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FavoriteproductController extends AppfrontController
{
    // 增加收藏
    public function actionAdd()
    {
        $data = $this->getBlock()->getLastData();

        return $data;
        //return $this->render($this->action->id,$data);
    }

    public function actionLists()
    {
        $data = $this->getBlock()->getLastData($editForm);

        return $this->render($this->action->id, $data);
    }
}
