<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\payment;

//use fecshop\models\mysqldb\IpnMessage;
use fecshop\services\Service;
use Yii;

/**
 * Payment Paypal services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Alipay extends Service
{
    public $gatewayUrl;
    // 商家 appid
    public $appId;
    // 商家uid
    public $sellerId;
    // 应用私钥
    public $rsaPrivateKey;
    // 支付宝公钥
    public $alipayrsaPublicKey;
    public $format;
    public $charset;
    public $signType;
    
    public $devide;
    public $apiVersion = '1.0'; //'1.0';
    
    //protected $_returnUrl;
    //protected $_notifyUrl;
    protected $_AopClient;
    protected $_alipayRequest;
    protected $_productCode;
    protected $_order;
    //交易创建，等待买家付款
    const WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';
    //未付款交易超时关闭，或支付完成后全额退款
    const TRADE_CLOSED = 'TRADE_CLOSED';
    //交易支付成功
    const TRADE_SUCCESS = 'TRADE_SUCCESS';
    //交易结束，不可退款
    const TRADE_FINISHED = 'TRADE_FINISHED';
    
    protected $_ipnMessageModelName = '\fecshop\models\mysqldb\IpnMessage';
    protected $_ipnMessageModel;
    
    /**
     * 引入 支付宝支付的SDK文件。
     */
    public function init()
    {
        parent::init();
        $AopSdkFile = Yii::getAlias('@fecshop/lib/alipay/AopSdk.php');
        require($AopSdkFile);
        list($this->_ipnMessageModelName,$this->_ipnMessageModel) = \Yii::mapGet($this->_ipnMessageModelName);  
    }
    /**
     * 初始化 $this->_AopClient
     */
    protected function initParam()
    {
        if(!$this->_AopClient){
            $this->_AopClient = new \AopClient;
            $this->_AopClient->gatewayUrl        = $this->gatewayUrl;
            $this->_AopClient->appId             = $this->appId;
            $this->_AopClient->rsaPrivateKey     = $this->rsaPrivateKey;
            $this->_AopClient->apiVersion        = $this->apiVersion; //'1.0';
            $this->_AopClient->format            = $this->format;
            $this->_AopClient->charset           = $this->charset;
            $this->_AopClient->signType          = $this->signType;
            $this->_AopClient->alipayrsaPublicKey= $this->alipayrsaPublicKey;
        }
    }
    /**
     * @property $out_trade_no | String ，[支付宝传递过来的]fecshop站内订单号
     * @property $total_amount | String ，[支付宝传递过来的]fecshop站内订单金额（CNY）
     * @property $seller_id    | String ，[支付宝传递过来的]商家UID
     * @property $auth_app_id  | String ，[支付宝传递过来的]商家appId
     * 验证订单数据是否正确，需要满足下面的条件：
     * 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号
     * 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）
     * 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
     * 4、验证app_id是否为该商户本身。
     * 上述1、2、3、4有任何一个验证不通过，则表明本次通知是异常通知，
     * 务必忽略。在上述验证通过后商户必须根据支付宝不同类型的业务通知，
     * 正确的进行不同的业务处理，并且过滤重复的通知结果数据。
     * 在支付宝的业务通知中，只有交易通知状态为TRADE_SUCCESS或TRADE_FINISHED时，
     * 支付宝才会认定为买家付款成功。
     */
    protected function validateReviewOrder($out_trade_no,$total_amount,$seller_id,$auth_app_id)
    {
        if(!$this->_order){
            $this->_order = Yii::$service->order->getByIncrementId($out_trade_no);
            Yii::$service->payment->setPaymentMethod($this->_order['payment_method']);
        }
        if(!$this->_order){
            Yii::$service->helper->errors->add('order increment id:'.$out_trade_no.' is not exist.');
            
            return false;
        }
        $base_grand_total = $this->_order['base_grand_total'];
        $order_total_amount = Yii::$service->page->currency->getCurrencyPrice($base_grand_total,'CNY');
        if($order_total_amount != $total_amount){
            Yii::$service->helper->errors->add('order increment id:'.$out_trade_no.' , total_amount('.$total_amount.') is not equal to order_total_amount('.$order_total_amount.')');
            
            return false;
        }  
        if(!$this->sellerId){
            Yii::$service->helper->errors->add('you must config sellerId in alipay payment config file');
            
            return false;
        }
        if($seller_id != $this->sellerId){
            Yii::$service->helper->errors->add('request sellerId('.$seller_id.') is not equle to config sellerId('.$this->sellerId.')');
            
            return false;
        }
        if($auth_app_id != $this->appId){
            Yii::$service->helper->errors->add('request auth_app_id('.$auth_app_id.') is not equle to config appId('.$this->appId.')');
            
            return false;
        }
        
        return true;
    }
    /**
     * 支付宝 支付成功后，返回网站，调用该函数进行支付宝订单支付状态查询
     * 如果支付成功，则修改订单状态为支付成功状态。
     */
    protected function actionReview()
    {
        $this->initParam();
        $trade_no       = Yii::$app->request->get('trade_no');
        $out_trade_no   = Yii::$app->request->get('out_trade_no');
        $total_amount   = Yii::$app->request->get('total_amount');
        $seller_id      = Yii::$app->request->get('seller_id');
        $auth_app_id    = Yii::$app->request->get('auth_app_id');
        //验证订单的合法性
        if (!$this->validateReviewOrder($out_trade_no,$total_amount,$seller_id,$auth_app_id)){
            
            return false;
        }
        $this->_AopClient->postCharset = $this->charset;
        $this->_alipayRequest = new \AlipayTradeQueryRequest();
        $bizContent = json_encode([
            'out_trade_no' => $out_trade_no,
            'trade_no'     => $trade_no,
        ]);
        //echo $bizContent;
        $this->_alipayRequest->setBizContent($bizContent);
        $result = $this->_AopClient->execute($this->_alipayRequest);
        $responseNode = str_replace(".", "_", $this->_alipayRequest->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode)&&$resultCode == 10000) {
            $this->paymentSuccess($out_trade_no,$trade_no);
            // 清空购物车
            Yii::$service->cart->clearCartProductAndCoupon();
            
            return true;
        } else {
            Yii::$service->helper->errors->add('alipay payment fail,resultCode:'.$resultCode);
            
            return false;
        }
        
    }
    /**
     * 支付宝的消息接收IPN，执行的函数，接收的消息用来更改订单状态。
     * 您开启log后，可以在@app/runtime/fecshop_logs
     *      文件夹下执行：tail -f fecshop_debug.log ， 来查看log输出。
     */
    public function receiveIpn()
    {
        Yii::info('alipay service receiveIpn():begin init param', 'fecshop_debug');
        $this->initParam();
        Yii::info('alipay service receiveIpn():begin rsaCheck', 'fecshop_debug');
        // 验签 
        $checkV2Status = $this->_AopClient->rsaCheckV1($_POST, '' ,$this->signType);
        Yii::info('alipay service receiveIpn():rsacheck end', 'fecshop_debug');
        if ($checkV2Status) {
            Yii::info('alipay service receiveIpn():rsacheck success', 'fecshop_debug');
            $trade_no       = Yii::$app->request->post('trade_no');
            $out_trade_no   = Yii::$app->request->post('out_trade_no');
            $total_amount   = Yii::$app->request->post('total_amount');
            $seller_id      = Yii::$app->request->post('seller_id');
            $auth_app_id    = Yii::$app->request->post('app_id');
            $trade_status   = Yii::$app->request->post('trade_status');
            Yii::info('alipay service receiveIpn(): [ trade_no: ]'.$trade_no, 'fecshop_debug');
            Yii::info('alipay service receiveIpn(): [ out_trade_no: ]'.$out_trade_no, 'fecshop_debug');
            Yii::info('alipay service receiveIpn(): [ total_amount: ]'.$total_amount, 'fecshop_debug');
            Yii::info('alipay service receiveIpn(): [ seller_id: ]'.$seller_id, 'fecshop_debug');
            Yii::info('alipay service receiveIpn(): [ auth_app_id: ]'.$auth_app_id, 'fecshop_debug');
            Yii::info('alipay service receiveIpn(): [ trade_status: ]'.$trade_status, 'fecshop_debug');
            
            //验证订单的合法性
            if (!$this->validateReviewOrder($out_trade_no,$total_amount,$seller_id,$auth_app_id)) {
                Yii::info('alipay service receiveIpn(): validate order fail', 'fecshop_debug');
                
                return false;
            }
            Yii::info('alipay service receiveIpn():validate order success', 'fecshop_debug');
            if (self::TRADE_SUCCESS == $trade_status){
                Yii::info('alipay service receiveIpn():alipay trade success ', 'fecshop_debug');
                if ($this->paymentSuccess($out_trade_no,$trade_no)){
                    Yii::info('alipay service receiveIpn():update order status success', 'fecshop_debug');
                    
                    return true;
                }
            }
        }else{
            
            return false;
        }
        
    }
    /**
     * @property $increment_id | String 订单号
     * @property $sendEmail | boolean 是否发送邮件
     * 订单支付成功后，需要更改订单支付状态等一系列的处理。
     */
    protected function paymentSuccess($increment_id,$trade_no,$sendEmail = true)
    {
        if (!$this->_order) {
            $this->_order = Yii::$service->order->getByIncrementId($increment_id);
            Yii::$service->payment->setPaymentMethod($this->_order['payment_method']);
        }
        // 如果订单状态已经是processing，那么，不需要更改订单状态了。
        if ($this->_order['order_status'] == Yii::$service->order->payment_status_processing){
            
            return true;
        }
        $order = $this->_order;        
        if (isset($order['increment_id']) && $order['increment_id']) {
            // 如果支付成功，则更改订单状态为支付成功
            $order->order_status = Yii::$service->order->payment_status_processing;
            $order->txn_id = $trade_no; // 支付宝的交易号
            // 更新订单信息
            $order->save();
            // 得到当前的订单信息
            $orderInfo = Yii::$service->order->getOrderInfoByIncrementId($order['increment_id']);
            // 发送新订单邮件
            Yii::$service->email->order->sendCreateEmail($orderInfo);
        
            return true;
        }
    }

    
    /**
     * 根据订单，将内容提交给支付宝。跳转到支付宝支付页面。
     * 在下单页面点击place order按钮，跳转到支付宝的时候，执行该函数。
     */
    public function start(){
        // 初始化参数
        $this->initParam();
        // 根据wap 还是pc ，进行参数初始化
        if($this->devide == 'wap'){
            $this->_alipayRequest   = new \AlipayTradeWapPayRequest ();
            $this->_productCode     = 'QUICK_WAP_WAY';
        }else if($this->devide == 'pc'){
            $this->_productCode     = 'FAST_INSTANT_TRADE_PAY';
            $this->_alipayRequest   = new \AlipayTradePagePayRequest ();
        }else{
            Yii::$service->helper->errors->add('you must config param [devide] in payment alipay service');
            return;
        }
        
        // 根据订单得到json格式的支付宝支付参数。
        $bizContent = $this->getStartBizContentAndSetPaymentMethod();
        if(!$bizContent){
            Yii::$service->helper->errors->add('generate alipay bizContent error');
        }
        // 设置支付成功返回的url 和 支付消息接收url
        // 在调用这个函数之前一定要先设置 Yii::$service->payment->setPaymentMethod($payment_method);
        $returnUrl = Yii::$service->payment->getStandardReturnUrl();
        $notifyUrl = Yii::$service->payment->getStandardIpnUrl();    
        /*
        echo $returnUrl;
        echo '#';
        echo $notifyUrl;
        echo '#';
        echo $bizContent;
        exit;
        */
        $this->_alipayRequest->setReturnUrl($returnUrl);
        $this->_alipayRequest->setNotifyUrl($notifyUrl);
        $this->_alipayRequest->setBizContent($bizContent);

        return $this->_AopClient->pageExecute($this->_alipayRequest); 
    }
    /**
     * 通过订单信息，得到支付宝支付传递的参数数据
     * 也就是一个json格式的数组。
     */
    protected function getStartBizContentAndSetPaymentMethod(){
        $currentOrderInfo = Yii::$service->order->getCurrentOrderInfo();
        if(isset($currentOrderInfo['products']) && is_array($currentOrderInfo['products'])){
            $subject_arr = [];
            foreach($currentOrderInfo['products'] as $product){
                $subject_arr[] = $product['name'];
            }
            if(!empty($subject_arr)){
                $subject = implode(',',$subject_arr);
                $increment_id = $currentOrderInfo['increment_id'];
                $base_grand_total = $currentOrderInfo['base_grand_total'];
                $total_amount = Yii::$service->page->currency->getCurrencyPrice($base_grand_total,'CNY');
                Yii::$service->payment->setPaymentMethod($currentOrderInfo['payment_method']);
                return json_encode([
                    // param 参看：https://docs.open.alipay.com/common/105901
                    'out_trade_no' => $increment_id,
                    'product_code' => $this->_productCode,
                    'total_amount' => $total_amount,
                    'subject'      => $subject,
                    //'body'         => '',
                ]);
            }
        }
    }
    
    
    
    
}