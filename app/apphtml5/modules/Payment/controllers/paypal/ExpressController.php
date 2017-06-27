<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\controllers\paypal;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExpressController extends AppfrontController
{
    public $enableCsrfValidation = true;

    public function actionStart()
    {
        $data = $this->getBlock()->startExpress();
    }

    // 2.Review  从paypal确认后返回
    public function actionReview()
    {
        $_csrf = Yii::$app->request->post('_csrf');
        if ($_csrf) {
            $status = $this->getBlock('placeorder')->getLastData();
            if ($status) {
                return;
            }
        }
        $data = $this->getBlock()->getLastData();
        if (is_array($data) && !empty($data)) {
            return $this->render($this->action->id, $data);
        } else {
            return $data;
        }
    }
    
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
