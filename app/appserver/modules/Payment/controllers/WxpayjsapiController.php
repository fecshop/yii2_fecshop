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
class WxpayjsapiController extends PaymentController
{
    public $enableCsrfValidation = false;
    /**
     *  通过微信回传的code，进而获取相关的信息
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
        
        $code = Yii::$app->request->post('code');
        // 获取相关的code
        $data = Yii::$service->payment->wxpayJsApi->getScanCodeStart($code);
        
        $code = Yii::$service->helper->appserver->status_success;
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        /**
         * 结果数据格式
         * {
         *     "code":200,
         *     "message":"process success",
         *     "data":{
         *         "jsApiParameters":{
         *             "appId":"wxb508f3849c440445",
         *             "nonceStr":"8krwuurjmalx8ya8abj17z6rrjbnux2u",
         *             "package":"prepay_id=wx19144638959303d9fc4573021193847200",
         *             "signType":"MD5",
         *             "timeStamp":"1560926798",
         *             "paySign":"52C4BE71A104AF772EACA21DDECED45F"
         *         },
         *         "editAddress":{
         *             "addrSign":"cac703ef80de9595064496820ba28b2a9019900b",
         *             "signType":"sha1",
         *             "scope":"jsapi_address",
         *             "appId":"wxb508f3849c440445",
         *             "timeStamp":"1560926798",
         *             "nonceStr":"1234568"
         *         },
         *         "total_amount":"0.12",
         *         "increment_id":"1100003842"
         *     }
         * }
         */
        return $responseData;
    }
    // 返回用于获取code的微信url
    public function actionOpenidurl()
    {
        $url = Yii::$app->request->post('url');
        $openUrl = Yii::$service->payment->wxpayJsApi->getOpenidUrl($url);
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'openUrl' => $openUrl,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
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
    
}
