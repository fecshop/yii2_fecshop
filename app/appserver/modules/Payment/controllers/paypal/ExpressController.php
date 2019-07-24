<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers\paypal;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExpressController extends AppserverTokenController
{
    public $enableCsrfValidation = false;

    public function actionStart()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $payment_method = Yii::$service->payment->paypal->express_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        return $this->getBlock()->startPayment();
    }

    // 2.Review  从paypal确认后返回
    public function actionReview()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $payment_method = Yii::$service->payment->paypal->express_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        return $this->getBlock()->getLastData();
    }
    // 3. 提交订单
    public function actionSubmitorder(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $payment_method = Yii::$service->payment->paypal->express_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        return $this->getBlock('placeorder')->getLastData();
    }
    /**
     * IPN已经关掉
     */
    public function actionIpn()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        \Yii::info('paypal ipn begin express', 'fecshop_debug');
        $payment_method = Yii::$service->payment->paypal->express_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            Yii::$service->payment->paypal->receiveIpn($post);
        }
    }
}
