<?php

/*
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
class Paypal extends Service
{
    /**
     * paypal支付状态 详细参看：https://developer.paypal.com/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
     * 打开url后，浏览器查找：PAYMENTINFO_n_PAYMENTSTATUS  ， 即可找到下面各个状态对应的含义
     */
    public $payment_status_none         = 'none';

    public $payment_status_completed    = 'completed';

    public $payment_status_denied       = 'denied';

    public $payment_status_expired      = 'expired';

    public $payment_status_failed       = 'failed';

    public $payment_status_in_progress  = 'in_progress';

    public $payment_status_pending      = 'pending';

    public $payment_status_refunded     = 'refunded';

    public $payment_status_refunded_part= 'partially_refunded';

    public $payment_status_reversed     = 'reversed';

    public $payment_status_unreversed   = 'canceled_reversal';

    public $payment_status_processed    = 'processed';

    public $payment_status_voided       = 'voided';

    public $seller_email ;

    // 是否使用证书的方式（https）
    public $use_local_certs = false;

    // 在payment中 express paypal 的配置值
    public $express_payment_method;

    public $standard_payment_method;

    public $version = '109.0';

    public $crt_file;

    protected $_postData;

    protected $_order;

    const EXPRESS_TOKEN     = 'paypal_express_token';

    const EXPRESS_PAYER_ID  = 'paypal_express_payer_id';
    
    protected $payerID;

    protected $token;

    // 允许更改的订单状态，不存在这里面的订单状态不允许修改
    protected $_allowChangOrderStatus;
    
    protected $_ipnMessageModelName = '\fecshop\models\mysqldb\IpnMessage';

    protected $_ipnMessageModel;
    
    protected $_account;
    protected $_password;
    protected $_signature;
    protected $_env;
    
    public function init()
    {
        parent::init();
        $this->_account = Yii::$app->store->get('payment_paypal', 'paypal_account');
        $this->_password = Yii::$app->store->get('payment_paypal', 'paypal_password');
        $this->_signature = Yii::$app->store->get('payment_paypal', 'paypal_signature');
        $this->_env = Yii::$app->store->get('payment_paypal', 'paypal_env');
        
        list($this->_ipnMessageModelName, $this->_ipnMessageModel) = \Yii::mapGet($this->_ipnMessageModelName);
        $this->_allowChangOrderStatus = [
            Yii::$service->order->payment_status_pending,
            Yii::$service->order->payment_status_processing,
        ];
    }

    /**
     * @param $domain | string
     * @return 得到证书crt文件的绝对路径
     */
    public function getCrtFile($domain)
    {
        if (isset($this->crt_file[$domain]) && !empty($this->crt_file[$domain])) {
            return Yii::getAlias($this->crt_file[$domain]);
        }
    }

    /**
     * 在paypal 标准支付中，paypal会向网站发送IPN消息，告知fecshop订单支付状态，
     * 进而fecshop更改订单状态。
     * fecshop一方面验证消息是否由paypal发出，另一方面要验证订单是否和后台的一致。
     */
    public function receiveIpn($post)
    {
        \Yii::info('receiveIpn', 'fecshop_debug');
        if ($this->verifySecurity($post)) {
            \Yii::info('verifySecurity', 'fecshop_debug');
            // 验证数据是否已经发送
            //if ($this->isNotDuplicate()) {
            // 验证数据是否被篡改。
            if ($this->isNotDistort()) {
                \Yii::info('updateOrderStatusByIpn', 'fecshop_debug');
                $this->updateOrderStatusByIpn();
            } else {
                // 如果数据和订单数据不一致，而且，支付状态为成功，则此订单
                // 标记为可疑的。
                $suspected_fraud = Yii::$service->order->payment_status_suspected_fraud;
                $this->updateOrderStatusByIpn($suspected_fraud);
            }
            // }
        }
    }

    /**
     * 该函数是为了验证IPN是否是由paypal发出，
     * 当paypal发送IPN消息给fecshop，fecshop不知道是否是伪造的支付消息，
     * 因此，fecshop将接收到的参数传递给paypal，询问paypal是否是paypal
     * 发送的IPN消息，如果是，则返回VERIFIED。
     */
    protected function verifySecurity($post)
    {
        $this->_postData = $post;
        $verifyUrl = $this->getVerifyUrl();
        \Yii::info('verifyUrl:'.$verifyUrl, 'fecshop_debug');
        $verifyReturn = $this->curlGet($verifyUrl);
        \Yii::info('verifyReturn:'.$verifyReturn, 'fecshop_debug');
        if ($verifyReturn == 'VERIFIED') {
            return true;
        }
    }

    /**
     * paypal发送的IPN，需要进行验证是否IPN是由paypal发出
     * 因此需要请求paypal确认，此函数返回请求paypal的url。
     */
    protected function getVerifyUrl()
    {
        $urlParamStr = '';
        if ($this->_postData) {
            foreach ($this->_postData as $k => $v) {
                $urlParamStr .= '&'.$k.'='.urlencode($v);
            }
        }
        $urlParamStr   .= '&cmd=_notify-validate';
        $urlParamStr    = substr($urlParamStr, 1);
        $current_payment_method = Yii::$service->payment->getPaymentMethod();
        //if ($current_payment_method == $this->standard_payment_method) {
        //    $verifyUrl = Yii::$service->payment->getStandardWebscrUrl($this->standard_payment_method);
        //} else {
        //    $verifyUrl = Yii::$service->payment->getExpressWebscrUrl($this->express_payment_method);
        //}
        if ($this->_env == Yii::$service->payment->env_sanbox) {
            $verifyUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $verifyUrl = 'https://www.paypal.com/cgi-bin/webscr';
        }
        $verifyUrl      = $verifyUrl.'?'.$urlParamStr;

        return $verifyUrl;
    }
    
    /**
     * @param $url | string, 请求的url
     * @param $i | 请求的次数，因为curl可能存在失败的可能，当
     * 失败后，就会通过递归的方式进行多次请求，这里设置的最大请求5次。
     * @return 返回请求url的return信息。
     */
    protected function curlGet($url, $i = 0)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        if ($this->use_local_certs) {
            $crtFile = $this->getCrtFile('www.paypal.com');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $crtFile);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        $httpResponse = curl_exec($ch);
        if (!$httpResponse) {
            $i++;
            if ($i <= 5) {
                return $this->curlGet($url, $i);
            } else {
                return $httpResponse;
            }
        }

        return $httpResponse;
    }

    /**
     * paypal 可能发送多次IPN消息
     * 判断是否重复，如果不重复，把当前的插入。
     */
    /*
    protected function isNotDuplicate()
    {
        $ipn = $this->_ipnMessageModel->find()
            ->asArray()
            ->where([
            'txn_id'=>$this->_postData['txn_id'],
            'payment_status'=>$this->_postData['payment_status'],
            ])
            ->one();
        if (is_array($ipn) && !empty($ipn)) {
            return false;
        } else {
            $IpnMessage = new $this->_ipnMessageModelName();
            $IpnMessage->txn_id = $this->_postData['txn_id'];
            $IpnMessage->payment_status = $this->_postData['payment_status'];
            $IpnMessage->updated_at = time();
            $IpnMessage->save();

            return true;
        }
    }
    */

    /**
     * 验证订单数据是否被篡改。
     * 通过订单号找到订单，查看是否存在
     * 验证邮件地址，订单金额是否准确。
     */
    protected function isNotDistort()
    {
        //Yii::$app->mylog->log("begin isNotDistort..");
        $increment_id = $this->_postData['invoice'];
        $mc_gross = $this->_postData['mc_gross'];
        $mc_currency = $this->_postData['mc_currency'];

        if ($increment_id && $mc_gross && $mc_currency) {
            $this->_order = Yii::$service->order->getByIncrementId($increment_id);
            if ($this->_order) {
                $order_currency_code = $this->_order['order_currency_code'];
                if ($order_currency_code == $mc_currency) {
                    // 核对订单总额
                    $currentCurrencyGrandTotal = $this->_order['grand_total'];
                    // if (round($currentCurrencyGrandTotal, 2) == round($mc_gross, 2)) {
                    // 因为float精度问题，使用高精度函数进行比较，精度到2位小数
                    if(bccomp($currentCurrencyGrandTotal, $mc_gross, 2) == 0){
                        return true;
                    } else {
                    }
                } else {
                }
            }
        }

        return false;
    }

    /**
     * @param $orderstatus | String 订单状态
     * 根据接收的ipn消息，更改订单状态。
     */
    protected function updateOrderStatusByIpn($orderstatus = '')
    {
        $order_cancel_status = Yii::$service->order->payment_status_canceled;
        // 如果订单状态被取消，那么不能进行支付。
        if ($this->_order->order_status == $order_cancel_status) {
            Yii::$service->helper->error->add('The order status has been canceled and you can not pay for item ,you can create a new order to pay');

            return;
        }
        $updateArr = [];
        if ($this->_postData['txn_type']) {
            $updateArr['txn_type'] = $this->_postData['txn_type'];
        }
        if ($this->_postData['txn_id']) {
            $updateArr['txn_id'] = $this->_postData['txn_id'];
        }
        if ($this->_postData['payer_id']) {
            $updateArr['payer_id'] = $this->_postData['payer_id'];
        }
        if ($this->_postData['ipn_track_id']) {
            $updateArr['ipn_track_id'] = $this->_postData['ipn_track_id'];
        }
        if ($this->_postData['receiver_id']) {
            $updateArr['receiver_id'] = $this->_postData['receiver_id'];
        }
        if ($this->_postData['verify_sign']) {
            $updateArr['verify_sign'] = $this->_postData['verify_sign'];
        }
        if ($this->_postData['charset']) {
            $updateArr['charset'] = $this->_postData['charset'];
        }
        if ($this->_postData['mc_fee']) {
            $updateArr['payment_fee'] = $this->_postData['mc_fee'];
            $currency = $this->_postData['mc_currency'];
            $updateArr['base_payment_fee'] = Yii::$service->page->currency->getBaseCurrencyPrice($this->_postData['mc_fee'], $currency);
        }
        if ($this->_postData['payment_type']) {
            $updateArr['payment_type'] = $this->_postData['payment_type'];
        }
        if ($this->_postData['payment_date']) {
            $updateArr['paypal_order_datetime'] = date('Y-m-d H:i:s', $this->_postData['payment_date']);
        }
        if ($this->_postData['protection_eligibility']) {
            $updateArr['protection_eligibility'] = $this->_postData['protection_eligibility'];
        }
        $updateArr['updated_at'] = time();
        //$this->_order->updated_at = time();
        // 在service中不要出现事务代码，如果添加事务，请在调用层使用。
        //$innerTransaction = Yii::$app->db->beginTransaction();
        //try {
        // 可以更改的订单状态
            
        if ($orderstatus) {
            $updateArr['order_status'] = $orderstatus;
            $this->_order->updateAll(
                    $updateArr,
                    [
                        'and',
                        ['order_id' => $this->_order['order_id']],
                        ['in','order_status',$this->_allowChangOrderStatus]
                    ]
                );
        // 指定了订单状态
                // $this->_order->order_status = $orderstatus;
                // $this->_order->save();
                // $payment_status = strtolower($this->_postData['payment_status']);
                // Yii::$app->mylog->log('save_'.$orderstatus);
        } else {
            $payment_status = strtolower($this->_postData['payment_status']);
            if ($payment_status == $this->payment_status_completed) {
                // paypal支付完成，将订单状态改成：收款已确认。
                // 只有存在于 $this->_allowChangOrderStatus 数组的状态，才允许更改，按照目前的设置，取消了的订单是不允许更改的
                $orderstatus = Yii::$service->order->payment_status_confirmed;
                $updateArr['order_status'] = $orderstatus;
                $updateColumn = $this->_order->updateAll(
                        $updateArr,
                        [
                            'and',
                            ['order_id' => $this->_order['order_id']],
                            ['in','order_status',$this->_allowChangOrderStatus]
                        ]
                    );
                //$this->_order->order_status = Yii::$service->order->payment_status_processing;
                // 更新订单信息
                //$this->_order->save();
                // 因为IPN消息可能不止发送一次，但是这里只允许一次，
                // 如果重复发送，$updateColumn 的更新返回值将为0
                if (!empty($updateColumn)) {
                    Yii::$service->order->orderPaymentCompleteEvent($this->_order['increment_id']);
                    // 上面的函数已经执行下面的代码，因此注释掉。
                        // $orderInfo = Yii::$service->order->getOrderInfoByIncrementId($this->_order['increment_id']);
                        // 发送新订单邮件
                        // Yii::$service->email->order->sendCreateEmail($orderInfo);
                }
            } elseif ($payment_status == $this->payment_status_pending) {
                // pending 代表信用卡预付方式，需要等待paypal从信用卡中把钱扣除，因此订单状态是processing
                $orderstatus = Yii::$service->order->payment_status_processing;
                $updateArr['order_status'] = $orderstatus;
                $updateColumn = $this->_order->updateAll(
                        $updateArr,
                        [
                            'and',
                            ['order_id' => $this->_order['order_id']],
                            ['order_status' => Yii::$service->order->payment_status_pending]
                        ]
                    );
            } elseif ($payment_status == $this->payment_status_failed) {
                // 暂不处理
            } elseif ($payment_status == $this->payment_status_refunded) {
                // 暂不处理
            } else {
                // 暂不处理
            }
        }
        //$innerTransaction->commit();
        return true;
        //} catch (\Exception $e) {
        //	$innerTransaction->rollBack();
        //}
        //return false;
    }

    // express 部分

    /**
     * @param $token | String , 通过 下面的 PPHttpPost5 方法返回的paypal express的token
     * @return String，通过token得到跳转的 paypal url，通过这个url跳转到paypal登录页面，进行支付的开始
     */
    public function getExpressCheckoutUrl($token)
    {
        if ($token) {
            //$webscrUrl = Yii::$service->payment->getExpressWebscrUrl($this->express_payment_method);
            if ($this->_env == Yii::$service->payment->env_sanbox) {
                $webscrUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $webscrUrl = 'https://www.paypal.com/cgi-bin/webscr';
            }
            return $webscrUrl.'?cmd=_express-checkout&token='.urlencode($token);
        }
    }
    
    /**
     * @param $token | String , 通过 下面的 PPHttpPost5 方法返回的paypal standard的token
     * @return String，通过token得到跳转的 paypal url，通过这个url跳转到paypal登录页面，进行支付的开始
     */
    public function getStandardCheckoutUrl($token)
    {
        if ($token) {
            // $webscrUrl = Yii::$service->payment->getStandardWebscrUrl($this->standard_payment_method);
            if ($this->_env == Yii::$service->payment->env_sanbox) {
                $webscrUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $webscrUrl = 'https://www.paypal.com/cgi-bin/webscr';
            }
            return $webscrUrl.'?useraction=commit&cmd=_express-checkout&token='.urlencode($token);
        }
    }

    /**
     * @param $methodName_ | String，请求的方法，譬如： $methodName_ = "SetExpressCheckout";
     * @param $nvpStr_ | String ，请求传递的购物车中的产品和总额部分的数据，组合成字符串的格式。
     * @param $i | Int ， 限制递归次数的变量，当api获取失败的时候，可以通过递归的方式多次尝试，直至超过最大失败次数，才会返回失败
     * 此方法为获取token。返回的数据为数组，里面含有 ACK  TOKEN 等值。
     * 也就是和paypal进行初次的api账号密码验证，成功后返回token等信息。
     */
    public function PPHttpPost5($methodName_, $nvpStr_, $i = 1)
    {
        $current_payment_method = Yii::$service->payment->getPaymentMethod();
        $API_NvpUrl     = Yii::$service->payment->getStandardNvpUrl($this->standard_payment_method);
        $API_Signature  = $this->_signature;
        $API_UserName   = $this->_account;
        $API_Password   = $this->_password;
        if ($this->_env == Yii::$service->payment->env_sanbox) {
            $API_NvpUrl = 'https://api-3t.sandbox.paypal.com/nvp';
        } else {
            $API_NvpUrl = 'https://api-3t.paypal.com/nvp';
        }
        
        
        if ($current_payment_method == $this->standard_payment_method) {
            $ipn_url        = Yii::$service->payment->getStandardIpnUrl($this->standard_payment_method);
        } else {
            $ipn_url        = Yii::$service->payment->getExpressIpnUrl($this->express_payment_method);
        }
        // Set the API operation, version, and API signature in the request.
        $nvpreq  =  "METHOD=$methodName_&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
        $nvpreq .=  "&PAYMENTREQUEST_0_NOTIFYURL=".urlencode($ipn_url);
        //echo $nvpreq;
        //\Yii::info($nvpreq, 'fecshop_debug');
       
        //exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_URL, $API_NvpUrl);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        if ($this->use_local_certs) {
            $crtFile = $this->getCrtFile('api-3t.paypal.com');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $crtFile);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        // Get response from the server.
        $httpResponse = curl_exec($ch);
        //echo "<br><br>%%%%%".$httpResponse."%%%%%<br><br>";
        if (!$httpResponse) {
            $i++;
            if ($i > 5) {
                //获取三次失败后，则推出。
                exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
            } else {
                $httpResponse = $this->PPHttpPost5($methodName_, $nvpStr_, $i);
            }
        } else {
            //第一次获取数据失败，则再次获取。并返回、
            if ($i > 0) {
                //return $httpResponse;
            }
        }
        //paypal返回的是一系列的字符串，譬如：L_TIMESTAMP0=2014-11-08T01:51:13Z&L_TIMESTAMP1=2014-11-08T01:40:41Z&L_TIMESTAMP2=2014-11-08T01:40:40Z&
        //下面要做的是先把字符串通过&字符打碎成数组
        //
        //echo "***************<br>";
        //echo urldecode($httpResponse);
        //echo "<br>***************<br>";
        $httpResponseAr = explode('&', urldecode($httpResponse));
        $httpParsedResponseAr = [];
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode('=', $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_NvpUrl.");
        }

        return $httpParsedResponseAr;
    }

    /**
     * @param $nvp_array | Array, 各个配置参数
     * 将数组里面的key和value，组合成url的字符串，生成nvp url
     */
    public function getRequestUrlStrByArray($nvp_array)
    {
        $str = '';
        if (is_array($nvp_array) && !empty($nvp_array)) {
            foreach ($nvp_array as $k=>$v) {
                $str .= '&'.urlencode($k).'='.urlencode($v);
            }
        }
        //echo $str;exit;
        return $str;
    }

    /**
     * 【paypal支付部分】api发送付款请求的参数部分
     *  通过该函数，将参数组合成字符串，为下一步api发送给paypal进行付款做准备
     */
    public function getCheckoutPaymentNvpStr($token)
    {
        $nvp_array = [];
        $nvp_array['PAYERID'] = $this->getPayerID();
        $nvp_array['TOKEN']   = $this->getToken();
        $nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $nvp_array['VERSION'] = $this->version;
        // https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
        // 检查地址
        $nvp_array['ADDROVERRIDE'] = 0;
        //ADDROVERRIDE
        // 得到购物车的信息，通过购物车信息填写。
        $orderInfo      = Yii::$service->order->getInfoByPaymentToken($token);
        //$cartInfo     = Yii::$service->cart->getCartInfo(true);
        $currency       = Yii::$service->page->currency->getCurrentCurrency();
        $grand_total    = Yii::$service->helper->format->number_format($orderInfo['grand_total']);
        $subtotal       = Yii::$service->helper->format->number_format($orderInfo['subtotal']);
        $shipping_total = Yii::$service->helper->format->number_format($orderInfo['shipping_total']);
        $discount_amount= Yii::$service->helper->format->number_format($orderInfo['subtotal_with_discount']);
        $subtotal       = $subtotal - $discount_amount;

        $nvp_array['PAYMENTREQUEST_0_SHIPTOSTREET']         = $orderInfo['customer_address_street1'].' '.$orderInfo['customer_address_street2'];
        $nvp_array['PAYMENTREQUEST_0_SHIPTOCITY']           = $orderInfo['customer_address_city'];
        $nvp_array['PAYMENTREQUEST_0_SHIPTOSTATE']          = $orderInfo['customer_address_state_name'];
        $nvp_array['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']    = $orderInfo['customer_address_country'];
        $nvp_array['PAYMENTREQUEST_0_SHIPTOZIP']            = $orderInfo['customer_address_zip'];
        $nvp_array['PAYMENTREQUEST_0_SHIPTONAME']           = $orderInfo['customer_firstname'].' '.$orderInfo['customer_lastname'];
        $nvp_array['PAYMENTREQUEST_0_INVNUM']               = $orderInfo['increment_id'];

        $nvp_array['PAYMENTREQUEST_0_CURRENCYCODE']         = $currency;
        $nvp_array['PAYMENTREQUEST_0_AMT']                  = $grand_total;
        $nvp_array['PAYMENTREQUEST_0_ITEMAMT']              = $subtotal;
        $nvp_array['PAYMENTREQUEST_0_SHIPPINGAMT']          = $shipping_total;
        if ($this->seller_email) {
            $nvp_array['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']  = $this->seller_email;
        }
        $i = 0;

        foreach ($orderInfo['products'] as $item) {
            $nvp_array['L_PAYMENTREQUEST_0_QTY'.$i]     = $item['qty'];
            $nvp_array['L_PAYMENTREQUEST_0_NUMBER'.$i]  = $item['sku'];
            $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i]     = Yii::$service->helper->format->number_format($item['price']);
            $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i]    = $item['name'];
            $nvp_array['L_PAYMENTREQUEST_0_CURRENCYCODE'.$i] = $currency;
            $i++;
        }
        $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = 'Discount';
        $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i]  = '-'.$discount_amount;
        //var_dump($nvp_array);
        $nvpStr = $this->getRequestUrlStrByArray($nvp_array);
        //var_dump($nvpStr);
        return $nvpStr;
    }

    /**
     * 【paypal快捷支付部分】将参数组合成字符串。
     *  通过api token，从paypal获取用户在paypal保存的货运地址。
     */
    public function getExpressAddressNvpStr()
    {
        $nvp_array = [];
        $nvp_array['VERSION'] = Yii::$service->payment->paypal->version;
        $nvp_array['token'] = Yii::$service->payment->paypal->getToken();

        return $this->getRequestUrlStrByArray($nvp_array);
    }

    /**
     * @param $landingPage | String ，访问api的类型，譬如login
     * 【paypal快捷支付部分】通过购物车中的数据，组合成访问paypal express api的url
     * 这里返回的的字符串，是快捷支付部分获取token和payerId的参数。
     * 将返回的参数，传递给Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_)
     * 最终得到paypal express的token和payerId
     */
    public function getExpressTokenNvpStr($landingPage = 'Login', $return_url='', $cancel_url='')
    {
        $nvp_array = [];
        $nvp_array['LANDINGPAGE'] = $landingPage;
        
        
        if ($return_url) {
            $nvp_array['RETURNURL'] = $return_url;
        } else {
            $nvp_array['RETURNURL'] = Yii::$service->payment->getExpressReturnUrl($this->express_payment_method);
        }
        if ($cancel_url) {
            $nvp_array['CANCELURL'] = $cancel_url;
        } else {
            $nvp_array['CANCELURL'] = Yii::$service->payment->getExpressCancelUrl($this->express_payment_method);
        }
        $nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $nvp_array['VERSION'] = $this->version;
        // 得到购物车的信息，通过购物车信息填写。
        $cartInfo = Yii::$service->cart->getCartInfo(true);
        $currency = Yii::$service->page->currency->getCurrentCurrency();

        $grand_total = $cartInfo['grand_total'];
        $subtotal = $cartInfo['product_total'];
        $shipping_total = $cartInfo['shipping_cost'];
        $discount_amount = $cartInfo['coupon_cost'];
        $subtotal = $subtotal - $discount_amount;

        $nvp_array['PAYMENTREQUEST_0_CURRENCYCODE'] = $currency;
        $nvp_array['PAYMENTREQUEST_0_AMT'] = $grand_total;
        $nvp_array['PAYMENTREQUEST_0_ITEMAMT'] = $subtotal;
        $nvp_array['PAYMENTREQUEST_0_SHIPPINGAMT'] = $shipping_total;
        if ($this->seller_email) {
            $nvp_array['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']  = $this->seller_email;
        }
        $i = 0;

        foreach ($cartInfo['products'] as $item) {
            $nvp_array['L_PAYMENTREQUEST_0_QTY'.$i] = $item['qty'];
            $nvp_array['L_PAYMENTREQUEST_0_NUMBER'.$i] = $item['sku'];
            $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = $item['product_price'];
            $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = Yii::$service->store->getStoreAttrVal($item['name'], 'name');
            $nvp_array['L_PAYMENTREQUEST_0_CURRENCYCODE'.$i] = $currency;
            $i++;
        }
        $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = 'Discount';
        $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = '-'.$discount_amount;

        return $this->getRequestUrlStrByArray($nvp_array);
    }
    
    /**
     * @param $landingPage | String ，访问api的类型，譬如login
     * 【paypal标准支付部分】通过订单中的数据，组合成访问paypal api的url
     * 这里返回的的字符串，是标准支付部分获取token和payerId的参数。
     *  通过 $checkoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
     *  获取token和payerId的参数。（$nvpStr_ 就是本函数的返回值）
     */
    public function getStandardTokenNvpStr($landingPage = 'Login', $return_url='', $cancel_url='')
    {
        $nvp_array = [];
        $nvp_array['LANDINGPAGE'] = $landingPage;
        if ($return_url) {
            $nvp_array['RETURNURL'] = $return_url;
        } else {
            $nvp_array['RETURNURL'] = Yii::$service->payment->getStandardReturnUrl('paypal_standard');
        }
        if ($cancel_url) {
            $nvp_array['CANCELURL'] = $cancel_url;
        } else {
            $nvp_array['CANCELURL'] = Yii::$service->payment->getStandardCancelUrl('paypal_standard');
        }
        $nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $nvp_array['VERSION'] = $this->version;
        // 得到购物车的信息，通过购物车信息填写。
        $orderInfo      = Yii::$service->order->getCurrentOrderInfo();
        //var_dump($orderInfo);
        $currency       = $orderInfo['order_currency_code'];

        $grand_total    = Yii::$service->helper->format->number_format($orderInfo['grand_total']);
        $subtotal       = Yii::$service->helper->format->number_format($orderInfo['subtotal']);
        $shipping_total = Yii::$service->helper->format->number_format($orderInfo['shipping_total']);
        $discount_amount= $orderInfo['subtotal_with_discount'] ? $orderInfo['subtotal_with_discount'] : 0;
        $subtotal = $subtotal - $discount_amount;

        $nvp_array['PAYMENTREQUEST_0_CURRENCYCODE'] = $currency;
        $nvp_array['PAYMENTREQUEST_0_AMT']          = $grand_total;
        $nvp_array['PAYMENTREQUEST_0_ITEMAMT']      = $subtotal;
        $nvp_array['PAYMENTREQUEST_0_SHIPPINGAMT']  = $shipping_total;
        if ($this->seller_email) {
            $nvp_array['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']  = $this->seller_email;
        }
        $i = 0;

        foreach ($orderInfo['products'] as $item) {
            $nvp_array['L_PAYMENTREQUEST_0_QTY'.$i] = $item['qty'];
            $nvp_array['L_PAYMENTREQUEST_0_NUMBER'.$i] = $item['sku'];
            $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = Yii::$service->helper->format->number_format($item['price']);
            $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = $item['name'];
            ;
            $nvp_array['L_PAYMENTREQUEST_0_CURRENCYCODE'.$i] = $currency;
            $i++;
        }
        $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = 'Discount';
        $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = '-'.$discount_amount;
        
        //var_dump($nvp_array);
        //exit;
        return $this->getRequestUrlStrByArray($nvp_array);
    }

    /**
     * 从get参数里得到paypal支付的token
     */
    public function getToken()
    {
        if (!$this->token) {
            $token = Yii::$app->request->get('token');
            if (!$token) {
                $token = Yii::$app->request->post('token');
            }
            $token = \Yii::$service->helper->htmlEncode($token);
            if ($token) {
                $this->token = $token;
            }
        }
        return $this->token;
    }

    /**
     * 从get参数里得到paypal支付的PayerID
     */
    public function getPayerID()
    {
        if (!$this->payerID) {
            $payerID = Yii::$app->request->get('PayerID');
            if (!$payerID) {
                $payerID = Yii::$app->request->post('PayerID');
            }
            $payerID = \Yii::$service->helper->htmlEncode($payerID);
            if ($payerID) {
                $this->payerID = $payerID;
            }
        }
        return $this->payerID;
    }

    /**
     * @param $doCheckoutReturn | Array ，
     * paypal付款状态提交后，更新订单的支付部分的信息。
     */
    public function updateOrderPayment($doCheckoutReturn, $token)
    {
        if ($doCheckoutReturn) {
            $order = Yii::$service->order->getByPaymentToken($token);
            $order_cancel_status = Yii::$service->order->payment_status_canceled;
            // 如果订单状态被取消，那么不能进行支付。
            if ($order['order_status'] == $order_cancel_status) {
                Yii::$service->helper->errors->add('The order status has been canceled and you can not pay for item ,you can create a new order to pay');

                return false;
            }
            $updateArr = [];
            if ($order['increment_id']) {
                //echo 'bbb';
                $updateArr['txn_id'] = $doCheckoutReturn['PAYMENTINFO_0_TRANSACTIONID'];
                $updateArr['txn_type'] = $doCheckoutReturn['PAYMENTINFO_0_TRANSACTIONTYPE'];
                $PAYMENTINFO_0_AMT = $doCheckoutReturn['PAYMENTINFO_0_AMT'];
                $updateArr['payment_fee'] = $doCheckoutReturn['PAYMENTINFO_0_FEEAMT'];

                $currency = $doCheckoutReturn['PAYMENTINFO_0_CURRENCYCODE'];
                $updateArr['base_payment_fee'] = Yii::$service->page->currency->getBaseCurrencyPrice($updateArr['payment_fee'], $currency);
                $updateArr['payer_id'] = $this->getPayerID();

                $updateArr['correlation_id'] = $doCheckoutReturn['CORRELATIONID'];
                $updateArr['protection_eligibility'] = $doCheckoutReturn['PAYMENTINFO_0_PROTECTIONELIGIBILITY'];
                $updateArr['protection_eligibility_type'] = $doCheckoutReturn['PAYMENTINFO_0_PROTECTIONELIGIBILITYTYPE'];
                $updateArr['secure_merchant_account_id'] = $doCheckoutReturn['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID'];
                $updateArr['build'] = $doCheckoutReturn['BUILD'];
                $updateArr['payment_type'] = $doCheckoutReturn['PAYMENTINFO_0_PAYMENTTYPE'];
                $updateArr['paypal_order_datetime'] = date('Y-m-d H:i:s', $doCheckoutReturn['PAYMENTINFO_0_ORDERTIME']);
                $PAYMENTINFO_0_PAYMENTSTATUS = $doCheckoutReturn['PAYMENTINFO_0_PAYMENTSTATUS'];
                $updateArr['updated_at'] = time();
                if (
                    strtolower($PAYMENTINFO_0_PAYMENTSTATUS) == $this->payment_status_completed
                    ||
                    strtolower($PAYMENTINFO_0_PAYMENTSTATUS) == $this->payment_status_processed
                ) {
                    $order_status = Yii::$service->order->payment_status_confirmed;
                    if ($currency == $order['order_currency_code'] && $PAYMENTINFO_0_AMT == $order['grand_total']) {
                        $updateArr['order_status'] = $order_status;
                        $updateColumn = $order->updateAll(
                            $updateArr,
                            [
                                'and',
                                ['order_id' => $order['order_id']],
                                ['in','order_status',$this->_allowChangOrderStatus]
                            ]
                        );
                        // 因为IPN消息可能不止发送一次，但是这里只允许一次，
                        // 如果重复发送，$updateColumn 的更新返回值将为0
                        if (!empty($updateColumn)) {
                            // 执行订单支付成功后的事情。
                            Yii::$service->order->orderPaymentCompleteEvent($order['increment_id']);
                            // 上面的函数已经执行下面的代码，因此注释掉。
                            // $orderInfo = Yii::$service->order->getOrderInfoByIncrementId($order['increment_id']);
                            // Yii::$service->email->order->sendCreateEmail($orderInfo);
                        }
                        return true;
                    } else {
                        // 金额不一致，判定为欺诈
                        Yii::$service->helper->errors->add('The amount of payment is inconsistent with the amount of the order');
                        $order_status = Yii::$service->order->payment_status_suspected_fraud;
                        $updateArr['order_status'] = $order_status;
                        $updateColumn = $order->updateAll(
                            $updateArr,
                            [
                                'and',
                                ['order_id' => $order['order_id']],
                                ['in','order_status',$this->_allowChangOrderStatus]
                            ]
                        );
                    }
                } elseif (strtolower($PAYMENTINFO_0_PAYMENTSTATUS) == $this->payment_status_pending) {
                    // 这种情况代表paypal 信用卡预售，需要等待一段时间才知道是否收到钱
                    $order_status = Yii::$service->order->payment_status_processing;
                    if ($currency == $order['order_currency_code'] && $PAYMENTINFO_0_AMT == $order['grand_total']) {
                        $updateArr['order_status'] = $order_status;
                        $updateColumn = $order->updateAll(
                            $updateArr,
                            [
                                'and',
                                ['order_id' => $order['order_id']],
                                ['order_status' => Yii::$service->order->payment_status_pending]
                            ]
                        );
                        // 这种情况并没有接收到paypal的钱，只是一种支付等待状态，
                        // 因此，对于这种支付状态，视为正常订单，但是没有支付成功，需要延迟等待，如果支付成功，paypal会继续发送IPN消息。
                        return true;
                    } else {
                        // 金额不一致，判定为欺诈
                        Yii::$service->helper->errors->add('The amount of payment is inconsistent with the amount of the order');
                        $order_status = Yii::$service->order->payment_status_suspected_fraud;
                        $updateArr['order_status'] = $order_status;
                        $updateColumn = $order->updateAll(
                            $updateArr,
                            [
                                'and',
                                ['order_id' => $order['order_id']],
                                ['in','order_status',$this->_allowChangOrderStatus]
                            ]
                        );
                    }
                } else {
                    Yii::$service->helper->errors->add('paypal payment is not complete , current payment status is {payment_status}', ['payment_status' => $PAYMENTINFO_0_PAYMENTSTATUS]);
                }
            } else {
                Yii::$service->helper->errors->add('current order is not exist');
            }
        } else {
            Yii::$service->helper->errors->add('CheckoutReturn is empty');
        }

        return false;
    }
}
