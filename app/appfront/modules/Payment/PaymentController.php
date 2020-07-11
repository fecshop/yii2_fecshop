<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class PaymentController extends AppfrontController
{
    protected $_increment_id;
    protected $_order_model;

    public function init()
    {
        parent::init();
        $homeUrl = Yii::$service->url->homeUrl();
        $this->_increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$this->_increment_id) {
            Yii::$service->url->redirect($homeUrl);
            exit;
        }

        $this->_order_model = Yii::$service->order->GetByIncrementId($this->_increment_id);
        if (!isset($this->_order_model['increment_id'])) {
            Yii::$service->url->redirect($homeUrl);
            exit;
        }

    }
}
