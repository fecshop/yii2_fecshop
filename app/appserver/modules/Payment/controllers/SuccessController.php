<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SuccessController extends AppserverController
{
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $increment_id = Yii::$app->request->post('increment_id');
        if (!$increment_id) {
            $code = Yii::$service->helper->appserver->order_not_find_increment_id_from_dbsession;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $order = Yii::$service->order->getInfoByIncrementId($increment_id);
        // 清空购物车。这里针对的是未登录用户进行购物车清空。
        //if (Yii::$app->user->isGuest) {
            Yii::$service->cart->clearCartProductAndCoupon();
        //}
        // 清空session中存储的当前订单编号。
        //Yii::$service->order->removeSessionIncrementId();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ 
            'increment_id'  => $increment_id,
            'order'         => $order,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
}
