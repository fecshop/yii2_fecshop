<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\controllers\wxpay;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Alex Chang<1692576541@qq.com>
 * @since 1.0
 */
class StandardController extends AppfrontController
{
    public $enableCsrfValidation = false;
    /**
     * 在下单页面中选择微信支付方式后，
     * 为微信支付做的准备工作。
     */
    public function actionStart()
    {
        Yii::$service->page->theme->layoutFile = 'wxpay_start.php';
        /**
         * 下面的 $startData 返回的数据格式为：
         *[
         *  'scanCodeImgUrl' => $scanCodeImgUrl,
         *   'increment_id'   => $trade_info['increment_id'],
         *   'total_amount'   => $trade_info['total_amount'],
         *   'subject'   => $trade_info['subject'],
         *   'coupon_code'   => $trade_info['coupon_code'],
         *   'product_ids'   => $trade_info['product_ids'],
         *];
         */
        $startData = Yii::$service->payment->wxpay->getScanCodeStart();
        //echo $startData;exit;
        if(!$startData){
            //Yii::$service->url->redirectByUrlKey('/checkout/onepage');
            return;
        }
        if(!Yii::$app->user->isGuest){
            $startData['customer_email'] = Yii::$app->user->identity->email;
        }
        $startData['trace_success_url'] = Yii::$service->url->getUrl('/payment/wxpay/standard/tradesuccess',['out_trade_no' => $startData['increment_id']]);
        $startData['expireTime'] =  Yii::$service->payment->wxpay->expireTime;
        return $this->render($this->action->id, $startData);
    }
    
    
    /** 废弃
     * IPN，微信消息接收部分
     * pc扫码通过js轮询查询支付状态，不需要ipn接受异步支付消息
     */
    public function actionIpn()
    {
        Yii::$service->payment->wxpay->ipn();
    }
    
    /**
     * 生成扫码支付的二维码
     */
    public function actionQrcode()
    {
    	error_reporting(E_ERROR);
    	$phpqrcodeFile = Yii::getAlias('@fecshop/lib/wxpay/example/phpqrcode/phpqrcode.php');
    	require_once($phpqrcodeFile);
    	$url = urldecode($_GET["data"]);
    	\QRcode::png($url);
    }
    
   /**
     * 判断微信支付是否成功，只要一个参数不匹配即判断为不成功
     * @param unknown $out_trade_no
     */
    public function actionTradesuccess()
    {
    	$out_trade_no =  Yii::$app->request->get('out_trade_no');
        if (!$out_trade_no)
    	{
    		return json_encode([
                'code'=> 300,
                'data'=> 'out_trade_no is empty',
    		]);
    	}
        $res = Yii::$service->payment->wxpay->scanCodeCheckTradeIsSuccess($out_trade_no);
        if ($res === true)
    	{
    		return json_encode([
                'code'=> 200,
                'data'=> 'success',
    		]);
    	}
    	else 
    	{
    		return json_encode([
                'code'=> 301,
                'data'=> 'fail',
    		]);
    	}
        
    }
    
}