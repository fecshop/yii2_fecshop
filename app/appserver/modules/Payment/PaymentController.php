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
        $this->_increment_id = Yii::$app->request->post('increment_id');
        Yii::$service->order->setCurrentOrderIncrementId($this->_increment_id);
        //$this->_increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$this->_increment_id) {
            
            $code = Yii::$service->helper->appserver->order_not_find_increment_id_from_dbsession;
            $data = [
                'error' => 'can not find order increment id from db session',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }

        $this->_order_model = Yii::$service->order->GetByIncrementId($this->_increment_id);
        if (!isset($this->_order_model['increment_id'])) {
            $code = Yii::$service->helper->appserver->order_not_exist;
            $data = [
                'error' => 'order is not exist',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        return true;
    }
}
