<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\controllers;

use fecshop\app\apphtml5\modules\Payment\PaymentController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CheckmoneyController extends PaymentController
{
    public $enableCsrfValidation = true;

    /**
     * 支付开始页面.
     */
    public function actionStart()
    {
        $payment_method = isset($this->_order_model['payment_method']) ? $this->_order_model['payment_method'] : '';
        if ($payment_method) {
            $complateUrl = Yii::$service->payment->getStandardSuccessRedirectUrl($payment_method);
            if ($complateUrl) {
                // 登录用户，在支付前清空购物车。
                //if(!Yii::$app->user->isGuest){
                //	Yii::$service->cart->clearCartProductAndCoupon();
                //}
                Yii::$service->url->redirect($complateUrl);
                exit;
            }
        }

        $homeUrl = Yii::$service->url->homeUrl();
        Yii::$service->url->redirect($homeUrl);
    }

    /**
     * 成功支付页面.
     */
    public function actionSuccess()
    {
        $data = [
            'increment_id' => $this->_increment_id,
        ];
        // 清理购物车中的产品。(游客用户的购物车在成功页面清空)
        if (Yii::$app->user->isGuest) {
            Yii::$service->cart->clearCartProductAndCoupon();
        }
        // 清理session中的当前的increment_id
        Yii::$service->order->removeSessionIncrementId();

        return $this->render('../../payment/checkmoney/success', $data);
    }

    /**
     * IPN消息推送地址
     * IPN过来后，不清除session中的 increment_id ，也不清除购物车
     * 仅仅是更改订单支付状态。
     */
    public function actionIpn()
    {
    }
}
