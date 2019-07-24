<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers;

use fecshop\app\appserver\modules\Payment\PaymentController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wxpayh5Controller extends PaymentController
{
    public $enableCsrfValidation = false;
    protected $_increment_id;
    protected $_order_model;
    
    /**
     * 支付开始页面.
     */
    public function actionStart()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $checkOrder = $this->checkOrder();
        if($checkOrder !== true){
            return $checkOrder;
        }
        
        $return_Url = Yii::$app->request->post('return_url');
        //Yii::$service->page->theme->layoutFile = 'wxpay_jsapi.php';
        $objectxml = Yii::$service->payment->wxpayH5->getScanCodeStart();
        //var_dump($objectxml);
        //$returnUrl =  Yii::$service->payment->getStandardReturnUrl(); 
        $return_Url = urlencode($return_Url);
        $redirectUrl = $objectxml['mweb_url'] . '&redirect_url=' . $return_Url;
        $data = [
            'redirectUrl' => $redirectUrl,
        ];
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    public function actionReview()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $checkOrder = $this->checkOrder();
        if($checkOrder !== true){
            return $checkOrder;
        }
        
        $out_trade_no = $this->_increment_id;
        $reviewStatus = Yii::$service->payment->wxpay->scanCodeCheckTradeIsSuccess($out_trade_no);
        if($reviewStatus){
            $data = [
                'redirectUrl' => $redirectUrl,
            ];
            $code = Yii::$service->helper->appserver->status_success;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }else{
            $errors = Yii::$service->helper->errors->get(',');
            $data = [
                'errors' => $errors,
            ];
            $code = Yii::$service->helper->appserver->order_wxpay_payment_fail;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
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
