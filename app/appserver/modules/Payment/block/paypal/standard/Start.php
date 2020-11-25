<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\block\paypal\standard;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start extends \yii\base\BaseObject
{
    public function startPayment($increment_id)
    {
        $methodName_ = 'SetExpressCheckout';
        $return_url = Yii::$app->request->post('return_url');
        $cancel_url = Yii::$app->request->post('cancel_url');
        $nvpStr_ = Yii::$service->payment->paypal->getStandardTokenNvpStr('Login',$return_url,$cancel_url);
        //echo $nvpStr_;exit;
        // 通过接口，得到token信息
        $checkoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($checkoutReturn);
        if (strtolower($checkoutReturn['ACK']) == 'success') {
            $token = $checkoutReturn['TOKEN'];
            //$increment_id = Yii::$service->order->getSessionIncrementId();
            //echo $increment_id ;exit;
            # 将token写入到订单中
            Yii::$service->order->updateTokenByIncrementId($increment_id,$token);
            $redirectUrl = Yii::$service->payment->paypal->getStandardCheckoutUrl($token);
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'redirectUrl' => $redirectUrl,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        
        } else {
            $code = Yii::$service->helper->appserver->order_paypal_standard_get_token_fail;
            $data = [
                'error' => isset($checkoutReturn['L_LONGMESSAGE0']) ? $checkoutReturn['L_LONGMESSAGE0'] : '',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
    }
}
