<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers\paypal;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExpressController extends AppserverController
{
    public $enableCsrfValidation = false;

    public function actionStart()
    {
        return $this->getBlock()->startExpress();
    }

    // 2.Review  从paypal确认后返回
    public function actionReview()
    {
        return $this->getBlock()->getLastData();
    }
    // 3. 提交订单
    public function actionSubmitorder(){
        return $this->getBlock('placeorder')->getLastData();
    }
    /**
     * IPN已经关掉
     */
    public function actionIpn()
    {
        \Yii::info('paypal ipn begin', 'fecshop_debug');
       
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            //Yii::$service->payment->paypal->receiveIpn($post);
        }
    }
}
