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
use Yii;
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
        if (Yii::$service->store->isAppServerMobile()) {
            $searchText = Yii::$app->request->get('q');
            $searchText = \Yii::$service->helper->htmlEncode($searchText);
            $urlPath = 'search/'.$searchText;
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
}
