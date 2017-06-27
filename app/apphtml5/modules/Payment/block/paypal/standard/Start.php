<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\block\paypal\standard;

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
        $nvpStr_ = Yii::$service->payment->paypal->getStandardTokenNvpStr();
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
            Yii::$service->url->redirect($redirectUrl);
            return;
        } elseif (strtolower($SetExpressCheckoutReturn['ACK']) == 'failure') {
            echo $SetExpressCheckoutReturn['L_LONGMESSAGE0'];
        } else {
            var_dump($SetExpressCheckoutReturn);
        }
    }
}
