<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalogsearch\controllers;

use fecshop\app\appfront\modules\AppfrontController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class IndexController extends AppfrontController
{
    public function init()
    {
        parent::init();
    }

    //
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
}
