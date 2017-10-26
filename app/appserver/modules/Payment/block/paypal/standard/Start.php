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
class Start
{
    public function startExpress()
    {
        $methodName_ = 'SetExpressCheckout';
        $return_url = Yii::$app->request->post('return_url');
        $cancel_url = Yii::$app->request->post('cancel_url');
        $nvpStr_ = Yii::$service->payment->paypal->getStandardTokenNvpStr('Login',$return_url,$cancel_url);
        //echo $nvpStr_;exit;
        // 通过接口，得到token信息
        $SetExpressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($SetExpressCheckoutReturn);
        if (strtolower($SetExpressCheckoutReturn['ACK']) == 'success') {
            $token = $SetExpressCheckoutReturn['TOKEN'];
            $increment_id = Yii::$service->order->getSessionIncrementId();
            # 将token写入到订单中
            Yii::$service->order->updateTokenByIncrementId($increment_id,$token);
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'redirectUrl' => $redirectUrl,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        
        } else {
            $code = Yii::$service->helper->appserver->order_paypal_standard_get_token_fail;
            $data = [
                'error' => isset($SetExpressCheckoutReturn['L_LONGMESSAGE0']) ? $SetExpressCheckoutReturn['L_LONGMESSAGE0'] : '',
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
    }
}
