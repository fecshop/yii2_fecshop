<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\controllers;

use fecshop\app\appadmin\modules\Catalog\CatalogController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryController extends CatalogController
{
    public $enableCsrfValidation = true;

    public function actionIndex()
    {
        $idKey = Yii::$service->category->getPrimaryKey();
        $idVal = Yii::$app->request->get($idKey);
        $parent_id = Yii::$app->request->get('parent_id');
        if ($idVal || $parent_id || $parent_id === '0') {
            $data = $this->getBlock()->getLastInfo();
            return $this->render('info', $data);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionSave(){
        $this->getBlock('index')->saveCategory();
    }

    public function actionProduct()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionRemove()
    {
        $this->getBlock('index')->remove();
    }

    public function actionImageupload()
    {
        $this->getBlock('image')->upload();
    }
}
