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
        $increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$increment_id) {
            return [
                'code' => 401,
                'content' => 'current order is not exist',
            ];
        }
        $order = Yii::$service->order->getInfoByIncrementId($increment_id);
        // 清空购物车。这里针对的是未登录用户进行购物车清空。
        //if (Yii::$app->user->isGuest) {
            Yii::$service->cart->clearCartProductAndCoupon();
        //}
        // 清空session中存储的当前订单编号。
        Yii::$service->order->removeSessionIncrementId();

        return [
            'code' => 200,
            'increment_id' => $increment_id,
            'order'            => $order,
        ];
    }
}
