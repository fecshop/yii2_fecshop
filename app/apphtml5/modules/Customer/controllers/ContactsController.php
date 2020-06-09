<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ContactsController extends AppfrontController
{
    public $enableCsrfValidation = true;

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $editForm = \Yii::$service->helper->htmlEncode($editForm);
            $this->getBlock()->saveContactsInfo($editForm);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
}
