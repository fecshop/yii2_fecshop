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
class Paypal extends Service
{
    // paypal支付状态
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
    
    protected $expressPayerID;
    protected $expressToken;
    
    protected $_ipnMessageModelName = '\fecshop\models\mysqldb\IpnMessage';
    protected $_ipnMessageModel;
    
    public function __construct(){
        list($this->_ipnMessageModelName,$this->_ipnMessageModel) = \Yii::mapGet($this->_ipnMessageModelName);  
    }
    /**
     * @property $domain | string
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
        if ($this->verifySecurity($post)) {
            // 验证数据是否已经发送
            if ($this->isNotDuplicate()) {
                // 验证数据是否被篡改。
                if ($this->isNotDistort()) {
                    $this->updateStandardOrderPayment();
                } else {
                    // 如果数据和订单数据不一致，而且，支付状态为成功，则此订单
                    // 标记为可疑的。
                    $suspected_fraud = Yii::$service->order->payment_status_suspected_fraud;
                    $this->updateStandardOrderPayment($suspected_fraud);
                }
            }
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
        Yii::$service->payment->setPaymentMethod('paypal_standard');
        $verifyUrl = $this->getVerifyUrl();
        $verifyReturn = $this->curlGet($verifyUrl);
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
        $verifyUrl      = Yii::$service->payment->getStandardPaymentUrl();
        $verifyUrl      = $verifyUrl.'?'.$urlParamStr;

        return $verifyUrl;
    }

    /**
     * @property $url | string, 请求的url
     * @property $i | 请求的次数，因为curl可能存在失败的可能，当
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
                    if ((float) $currentCurrencyGrandTotal == (float) $mc_gross) {
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
     * @property $orderstatus | String 退款状态
     * 更新订单状态。这是express 支付方式使用的。
     */
    protected function updateStandardOrderPayment($orderstatus = '')
    {
        $order_cancel_status = Yii::$service->order->payment_status_canceled;
        // 如果订单状态被取消，那么不能进行支付。
        if ($this->_order->order_status == $order_cancel_status) {
            Yii::$service->helper->error->add('The order status has been canceled and you can not pay for item ,you can create a new order to pay');

            return;
        }
        if ($this->_postData['txn_type']) {
            $this->_order->txn_type = $this->_postData['txn_type'];
        }
        if ($this->_postData['txn_id']) {
            $this->_order->txn_id = $this->_postData['txn_id'];
        }
        if ($this->_postData['payer_id']) {
            $this->_order->payer_id = $this->_postData['payer_id'];
        }
        if ($this->_postData['ipn_track_id']) {
            $this->_order->ipn_track_id = $this->_postData['ipn_track_id'];
        }
        if ($this->_postData['receiver_id']) {
            $this->_order->receiver_id = $this->_postData['receiver_id'];
        }
        if ($this->_postData['verify_sign']) {
            $this->_order->verify_sign = $this->_postData['verify_sign'];
        }
        if ($this->_postData['charset']) {
            $this->_order->charset = $this->_postData['charset'];
        }
        if ($this->_postData['mc_fee']) {
            $this->_order->payment_fee = $this->_postData['mc_fee'];
            $currency = $this->_postData['mc_currency'];
            $this->_order->base_payment_fee = Yii::$service->page->currency->getBaseCurrencyPrice($this->_postData['mc_fee'], $currency);
        }
        if ($this->_postData['payment_type']) {
            $this->_order->payment_type = $this->_postData['payment_type'];
        }
        if ($this->_postData['payment_date']) {
            $this->_order->paypal_order_datetime = date('Y-m-d H:i:s', $this->_postData['payment_date']);
        }
        if ($this->_postData['protection_eligibility']) {
            $this->_order->protection_eligibility = $this->_postData['protection_eligibility'];
        }
        $this->_order->updated_at = time();
        // 在service中不要出现事务代码，如果添加事务，请在调用层使用。
        //$innerTransaction = Yii::$app->db->beginTransaction();
        //try {
            if ($orderstatus) {
                // 指定了订单状态
                $this->_order->order_status = $orderstatus;
                $this->_order->save();
                $payment_status = strtolower($this->_postData['payment_status']);
                //Yii::$app->mylog->log('save_'.$orderstatus);
            } else {
                $payment_status = strtolower($this->_postData['payment_status']);
                if ($payment_status == $this->payment_status_completed) {
                    $this->_order->order_status = Yii::$service->order->payment_status_processing;
                    // 更新订单信息
                    $this->_order->save();
                    // 得到当前的订单信息
                    $orderInfo = Yii::$service->order->getOrderInfoByIncrementId($this->_order['increment_id']);
                    // 发送新订单邮件
                    Yii::$service->email->order->sendCreateEmail($orderInfo);
                } elseif ($payment_status == $this->payment_status_failed) {
                    # 因为涉及到扣库存，因此，先不更改订单状态。
                    //$this->_order->order_status = Yii::$service->order->payment_status_canceled;
                    //$this->_order->save();
                } elseif ($payment_status == $this->payment_status_refunded) {
                    # 因为涉及到扣库存，因此，先不更改订单状态。
                    //$this->_order->order_status = Yii::$service->order->payment_status_canceled;
                    //$this->_order->save();
                } else {
                    
                }
            }
            //$innerTransaction->commit();
            return true;
        //} catch (Exception $e) {
        //	$innerTransaction->rollBack();
        //}
        //return false;
    }

    // express 部分

    /**
     * @property $token | String , 通过 下面的 PPHttpPost5 方法返回的paypal express的token
     * @return String，通过token得到跳转的 paypal url，
     *                                             得到上面的url后，进行跳转到paypal，然后确认，然后返回到fecshop，paypal会传递货运地址等信息
     *                                             到fecshop，这样用户不需要手动填写货运地址等信息。因此，这种方式为快捷支付。
     */
    public function getSetExpressCheckoutUrl($token)
    {
        if ($token) {
            $ApiUrl = Yii::$service->payment->getExpressApiUrl($this->express_payment_method);

            return $ApiUrl.'?cmd=_express-checkout&token='.urlencode($token);
        }
    }
    
    
    /**
     * @property $token | String , 通过 下面的 PPHttpPost5 方法返回的paypal express的token
     * @return String，通过token得到跳转的 paypal url，
     *                                             得到上面的url后，进行跳转到paypal，然后确认，然后返回到fecshop，paypal会传递货运地址等信息
     *                                             到fecshop，这样用户不需要手动填写货运地址等信息。因此，这种方式为快捷支付。
     */
    public function getSetStandardCheckoutUrl($token)
    {
        if ($token) {
            $ApiUrl = Yii::$service->payment->getStandardApiUrl($this->standard_payment_method);

            return $ApiUrl.'?useraction=commit&cmd=_express-checkout&token='.urlencode($token);
        }
    }


    /**
     * @property $methodName_ | String，请求的方法，譬如： $methodName_ = "SetExpressCheckout";
     * @property $nvpStr_ | String ，请求传递的购物车中的产品和总额部分的数据，组合成字符串的格式。
     * @property $i | Int ， 限制递归次数的变量，当api获取失败的时候，可以通过递归的方式多次尝试，直至超过最大失败次数，才会返回失败
     * 此方法为获取token。返回的数据为数组，里面含有 ACK  TOKEN 等值。
     * 也就是和paypal进行初次的api账号密码验证，成功后返回token等信息。
     */
    public function PPHttpPost5($methodName_, $nvpStr_, $i = 1)
    {
        $API_NvpUrl = Yii::$service->payment->getExpressNvpUrl($this->express_payment_method);
        $API_Signature  = Yii::$service->payment->getExpressSignature($this->express_payment_method);
        $API_UserName   = Yii::$service->payment->getExpressAccount($this->express_payment_method);
        $API_Password   = Yii::$service->payment->getExpressPassword($this->express_payment_method);
        $ipn_url        = Yii::$service->payment->getExpressIpnUrl($this->express_payment_method);
        
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
     * @property $nvp_array | Array, 各个配置参数
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
     * 【paypal快捷支付部分】api发送付款
     *  通过该函数，将参数组合成字符串，通过api发送给paypal进行付款。
     */
    public function getExpressCheckoutPaymentNvpStr($token)
    {
        $nvp_array = [];

        $nvp_array['PAYERID'] = $this->getExpressPayerID();
        $nvp_array['TOKEN']   = $this->getExpressToken();
        $nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $nvp_array['VERSION'] = $this->version;
        // https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
        // 检查地址
        $nvp_array['ADDROVERRIDE'] = 0;
        //ADDROVERRIDE
        // 得到购物车的信息，通过购物车信息填写。
        $orderInfo      = Yii::$service->order->getInfoByPaymentToken($token);
        //$cartInfo     = Yii::$service->cart->getCartInfo();
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
        $nvp_array['token'] = Yii::$service->payment->paypal->getExpressToken();

        return $this->getRequestUrlStrByArray($nvp_array);
    }

    /**
     * @property $landingPage | String ，访问api的类型，譬如login
     * 【paypal快捷支付部分】通过购物车中的数据，组合成访问paypal express api的url
     * 这里返回的的字符串，是快捷支付部分获取token和payerId的参数。
     * 通过这些参数最终得到paypal express的token和payerId
     */
    public function getExpressTokenNvpStr($landingPage = 'Login')
    {
        $nvp_array = [];
        $nvp_array['LANDINGPAGE'] = $landingPage;
        $nvp_array['RETURNURL'] = Yii::$service->payment->getExpressReturnUrl($this->express_payment_method);
        $nvp_array['CANCELURL'] = Yii::$service->payment->getExpressCancelUrl($this->express_payment_method);
        $nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
        $nvp_array['VERSION'] = $this->version;
        // 得到购物车的信息，通过购物车信息填写。
        $cartInfo = Yii::$service->cart->getCartInfo();
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
     * @property $landingPage | String ，访问api的类型，譬如login
     * 【paypal快捷支付部分】通过购物车中的数据，组合成访问paypal express api的url
     * 这里返回的的字符串，是快捷支付部分获取token和payerId的参数。
     * 通过这些参数最终得到paypal express的token和payerId
     */
    public function getStandardTokenNvpStr($landingPage = 'Login')
    {
        $nvp_array = [];
        $nvp_array['LANDINGPAGE'] = $landingPage;
        $nvp_array['RETURNURL'] = Yii::$service->payment->getStandardReturnUrl('paypal_standard');
        $nvp_array['CANCELURL'] = Yii::$service->payment->getStandardCancelUrl('paypal_standard');
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
        $i = 0;

        foreach ($orderInfo['products'] as $item) {
            $nvp_array['L_PAYMENTREQUEST_0_QTY'.$i] = $item['qty'];
            $nvp_array['L_PAYMENTREQUEST_0_NUMBER'.$i] = $item['sku'];
            $nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = Yii::$service->helper->format->number_format($item['price']);
            $nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = $item['name'];;
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
     * 从get参数里得到express支付的token
     */
    public function getExpressToken()
    {
        if(!$this->expressToken){
            $token = Yii::$app->request->get('token');
            $token = \Yii::$service->helper->htmlEncode($token);
            if ($token) {
                $this->expressToken = $token;
            }
        }
        return $this->expressToken;
    }

    /**
     * 从get参数里得到express支付的PayerID
     */
    public function getExpressPayerID()
    {
        if(!$this->expressPayerID){
            $PayerID = Yii::$app->request->get('PayerID');
            $PayerID = \Yii::$service->helper->htmlEncode($PayerID);
            if ($PayerID) {
                $this->expressPayerID = $PayerID;
            }
        }
        return $this->expressPayerID;
    }

    /**
     * @property $doExpressCheckoutReturn | Array ， 上面的 函数 doExpressCheckoutPayment 返回的数据
     * 快捷支付付款状态提交后，更新订单的支付部分的信息。
     */
    public function updateExpressOrderPayment($DoExpressCheckoutReturn,$token)
    {
        if ($DoExpressCheckoutReturn) {
            //echo 'aaa';
            //$increment_id = Yii::$service->order->getSessionIncrementId();
            //echo "\n $increment_id \n\n";
            //$order = Yii::$service->order->getByIncrementId($increment_id);
            echo '########'.$token.'#####';
            $order = Yii::$service->order->getByPaymentToken($token);
            $order_cancel_status = Yii::$service->order->payment_status_canceled;
            // 如果订单状态被取消，那么不能进行支付。
            if ($order['order_status'] == $order_cancel_status) {
                Yii::$service->helper->errors->add('The order status has been canceled and you can not pay for item ,you can create a new order to pay');

                return false;
            }
            if ($order['increment_id']) {
                //echo 'bbb';
                $order['txn_id'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_TRANSACTIONID'];
                $order['txn_type'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_TRANSACTIONTYPE'];
                $PAYMENTINFO_0_AMT = $DoExpressCheckoutReturn['PAYMENTINFO_0_AMT'];
                $order['payment_fee'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_FEEAMT'];

                $currency = $DoExpressCheckoutReturn['PAYMENTINFO_0_CURRENCYCODE'];
                $order['base_payment_fee'] = Yii::$service->page->currency->getBaseCurrencyPrice($order['payment_fee'], $currency);
                $order['payer_id'] = $this->getExpressPayerID();

                $order['correlation_id'] = $DoExpressCheckoutReturn['CORRELATIONID'];
                $order['protection_eligibility'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_PROTECTIONELIGIBILITY'];
                $order['protection_eligibility_type'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_PROTECTIONELIGIBILITYTYPE'];
                $order['secure_merchant_account_id'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID'];
                $order['build'] = $DoExpressCheckoutReturn['BUILD'];
                $order['payment_type'] = $DoExpressCheckoutReturn['PAYMENTINFO_0_PAYMENTTYPE'];
                $order['paypal_order_datetime'] = date('Y-m-d H:i:s', $DoExpressCheckoutReturn['PAYMENTINFO_0_ORDERTIME']);
                $PAYMENTINFO_0_PAYMENTSTATUS = $DoExpressCheckoutReturn['PAYMENTINFO_0_PAYMENTSTATUS'];
                
                echo $this->payment_status_completed.'##'.$PAYMENTINFO_0_PAYMENTSTATUS."<br>";
                if (strtolower($PAYMENTINFO_0_PAYMENTSTATUS) == $this->payment_status_completed) {
                    //echo 222;
                    // 判断金额是否相符
                    echo $currency."<br/>";
                    echo $order['order_currency_code']."<br/>";
                    echo $PAYMENTINFO_0_AMT."<br/>";
                    echo $order['grand_total']."<br/>";
                    if ($currency == $order['order_currency_code'] && $PAYMENTINFO_0_AMT == $order['grand_total']) {
                        //echo 222;
                        $order->order_status = Yii::$service->order->payment_status_processing;
                        $order->save();
                        // 支付成功，发送新订单邮件
                        $orderInfo = Yii::$service->order->getCurrentOrderInfo();
                        Yii::$service->email->order->sendCreateEmail($orderInfo);

                        return true;
                    } else {
                        Yii::$service->helper->errors->add('The amount of payment is inconsistent with the amount of the order');
                        $order->order_status = Yii::$service->order->payment_status_suspected_fraud;
                        $order->save();
                    }
                } else {
                    Yii::$service->helper->errors->add('paypal express payment is not complete , current payment status is '.$PAYMENTINFO_0_PAYMENTSTATUS);
                }
            } else {
                Yii::$service->helper->errors->add('current order is not exist');
            }
        } else {
            Yii::$service->helper->errors->add('ExpressCheckoutReturn is empty');
        }

        return false;
    }
}
