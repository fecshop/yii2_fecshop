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
            $redirectUrl = Yii::$service->payment->paypal->getSetStandardCheckoutUrl($token);
            return [
                'code' => 200,
                'content' => $redirectUrl,
            ];
        } elseif (strtolower($SetExpressCheckoutReturn['ACK']) == 'failure') {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn['L_LONGMESSAGE0'],
            ];
        } else {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn,
            ];
        }
    }
}
