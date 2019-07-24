<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers\alipay;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StandardController extends AppserverController
{
    
    public $enableCsrfValidation = false;
    /**
     * 在网站下单页面，选择支付宝支付方式后，
     * 跳转到支付宝支付页面前准备的部分。
     */
    public function actionStart()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        //$AopSdkFile = Yii::getAlias('@fecshop/lib/alipay/AopSdk.php');
        //require($AopSdkFile);
        //echo '支付宝支付跳转中...';
        //Yii::$service->payment->alipay->devide = 'wap';
        $return_url = Yii::$app->request->post('return_url');
        $increment_id = Yii::$app->request->post('increment_id');
        Yii::$service->order->setCurrentOrderIncrementId($increment_id);
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'redirectUrl'  => Yii::$service->payment->alipay->start($return_url,'GET'),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    /**
     * 从支付宝支付成功后，跳转返回 fec-shop 的部分
     */
    public function actionReview()
    {
        $reviewStatus = Yii::$service->payment->alipay->review();
        if($reviewStatus){
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }else{
            
            $code = Yii::$service->helper->appserver->order_alipay_payment_fail;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
    }
    /**
     * IPN，支付宝消息接收部分
     */
    public function actionIpn()
    {
        \Yii::info('alipay ipn begin', 'fecshop_debug');
       
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            \Yii::info('', 'fecshop_debug');
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            $ipnStatus = Yii::$service->payment->alipay->receiveIpn();
            if($ipnStatus){
                echo 'success';
                return;
            }
        }
    }
    
    /*
    public function actionCancel()
    {
        $innerTransaction = Yii::$app->db->beginTransaction();
		try {
            if(Yii::$service->order->cancel()){
                $innerTransaction->commit();
            }else{
                $innerTransaction->rollBack();
            }
		} catch (\Exception $e) {
			$innerTransaction->rollBack();
		}
        return Yii::$service->url->redirectByUrlKey('checkout/onepage');
    }
    */
    
}
