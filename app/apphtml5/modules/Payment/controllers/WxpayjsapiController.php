<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class WxpayjsapiController extends AppfrontController
{
    public $enableCsrfValidation = false;
    protected $_increment_id;
    protected $_order_model;
    
    
    public function initFunc()
    {
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
    /**
     * 支付开始页面.
     */
    public function actionStart()
    {
        $this->initFunc();
        Yii::$service->page->theme->layoutFile = 'wxpay_jsapi.php';
        $data = Yii::$service->payment->wxpayJsApi->getScanCodeStart();
        $data['success_url'] = Yii::$service->payment->getStandardSuccessRedirectUrl();
        return $this->render($this->action->id, $data);
        
    }
    
    /**
     * IPN消息推送地址
     * IPN过来后，不清除session中的 increment_id ，也不清除购物车
     * 仅仅是更改订单支付状态。
     */
    public function actionIpn()
    {
        Yii::$service->payment->wxpay->ipn();
    }

    /** 废弃
     *  成功支付页面.
     */
    public function actionSuccess()
    {
        $this->initFunc();
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

    
}
