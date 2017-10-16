<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class PaymentController extends AppserverController
{
    protected $_increment_id;
    protected $_order_model;

    public function checkOrder()
    {
        //$homeUrl = Yii::$service->url->homeUrl();
        $this->_increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$this->_increment_id) {
            return [
                'code' => 401,
                'content' => 'order is not exist',
            ];
        }

        $this->_order_model = Yii::$service->order->GetByIncrementId($this->_increment_id);
        if (!isset($this->_order_model['increment_id'])) {
            return [
                'code' => 401,
                'content' => 'order is not exist',
            ];;
        }
        return true;
    }
}
