<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\payment;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mysqldb\IpnMessage;
use fecshop\services\Service;
/**
 * Payment Paypal services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Paypal extends Service
{
	# paypal支付状态
	public $payment_status_none         	= 'none';
    public $payment_status_completed    	= 'completed';
    public $payment_status_denied       	= 'denied';
    public $payment_status_expired      	= 'expired';
    public $payment_status_failed      		= 'failed';
    public $payment_status_in_progress  	= 'in_progress';
    public $payment_status_pending  		= 'pending';
    public $payment_status_refunded     	= 'refunded';
    public $payment_status_refunded_part 	= 'partially_refunded';
    public $payment_status_reversed   		= 'reversed';
    public $payment_status_unreversed   	= 'canceled_reversal';
    public $payment_status_processed  		= 'processed';
    public $payment_status_voided     		= 'voided';
	
	public $use_local_certs = true;
	# 在payment中 express paypal 的配置值
	public $express_payment_method;
	public $version = '109.0'；
	public $crt_file;
	
	protected $_postData;
	protected $_order;
	
	const EXPRESS_TOKEN 	= 'paypal_express_token';
	const EXPRESS_PAYER_ID	= 'paypal_express_payer_id';
	
	public function getCrtFile($domain){
		if(isset($this->crt_file[$domain]) && !empty($this->crt_file[$domain])){
			return Yii::getAlias($this->crt_file[$domain]);
		}
	}
	
	public function receiveIpn(){
		if($this->verifySecurity()){
			# 验证数据是否已经发送
			if($this->isNotDuplicate()){
				# 验证数据是否被篡改。
				if($this->isNotDistort()){
					$this->updateOrderAndCoupon();
				}else{
					# 如果数据和订单数据不一致，而且，支付状态为成功，则此订单
					# 标记为可疑的。
					$suspected_fraud = Yii::$service->order->payment_status_suspected_fraud;
					$this->updateOrderAndCoupon($suspected_fraud);
				}
			}
		}
	}
	
	protected function verifySecurity(){
		$this->_postData = Yii::$app->request->post();
		Yii::$service->payment->setPaymentMethod('paypal_standard');
		$verifyUrl = $this->getVerifyUrl();
		$verifyReturn = $this->curlGet($verifyUrl);
		if($verifyReturn == 'VERIFIED'){
			return true;
		}
	}
	
	protected function getVerifyUrl(){
		$urlParamStr = '';
		if($this->_postData){
			foreach ($this->_postData as $k => $v) {
				$urlParamStr .= '&'.$k.'='.urlencode($v);
			}
		}
		$urlParamStr .= "&cmd=_notify-validate";
		$urlParamStr = substr($urlParamStr, 1);
		$verifyUrl = Yii::$service->payment->getStandardPaymentUrl();
		$verifyUrl = $verifyUrl."?".$urlParamStr;
		return $verifyUrl;
	}
	
	protected function curlGet($url,$i=0){
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->use_local_certs) {
			$crtFile = $this->getCrtFile('www.sandbox.paypal.com');
            curl_setopt($ch, CURLOPT_CAINFO, $crtFile);
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $httpResponse = curl_exec($ch);
		if(!$httpResponse){
			$i++;
			if($i<=5){
				return $this->curlGet($url,$i);
			}else{
				return $httpResponse;
			}
		}
		return $httpResponse;
	}
	
	
	# 判断是否重复，如果不重复，把当前的插入。
	protected function isNotDuplicate(){
		$ipn = IpnMessage::find()
				->asArray()
				->where([
				'txn_id'=>$this->_postData['txn_id'],
				'payment_status'=>$this->_postData['payment_status'],
				])
				->one();
		if(is_array($ipn) && !empty($ipn)){
			return false;
		}else{
			$IpnMessage = new IpnMessage;
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
	protected function isNotDistort(){
		//Yii::$app->mylog->log("begin isNotDistort..");
		$increment_id 		= $this->_postData['invoice'];
		$mc_gross 			= $this->_postData['mc_gross'];
		$mc_currency 		= $this->_postData['mc_currency'];
		
		if($increment_id && $mc_gross && $mc_currency){
			$this->_order = Yii::$service->order->getByIncrementId($increment_id);;
			if($this->_order){
				$order_currency_code = $this->_order['order_currency_code'];
				if($order_currency_code == $mc_currency){
					# 核对订单总额
					$currentCurrencyGrandTotal = $this->_order['grand_total'];
					if((float)$currentCurrencyGrandTotal == (float)$mc_gross ){
						return true;
					}else{
						
					}
				}else{
				
				}
			}
		}
		return false;
	}
	/**
	 * @property $orderstatus | String 退款状态
	 * 更新订单状态。
	 */
	public function updateOrderAndCoupon($orderstatus = ''){
		if($this->_postData['txn_type']){
			$this->_order->txn_type = $this->_postData['txn_type'];	
		}	
		if($this->_postData['txn_id']){
			$this->_order->txn_id = $this->_postData['txn_id'];
		}
		if($this->_postData['payer_id']){
			$this->_order->payer_id = $this->_postData['payer_id'];
		}
		if($this->_postData['ipn_track_id']){
			$this->_order->ipn_track_id = $this->_postData['ipn_track_id'];
		}
		if($this->_postData['receiver_id']){
			$this->_order->receiver_id = $this->_postData['receiver_id'];
		}
		if($this->_postData['verify_sign']){
			$this->_order->verify_sign = $this->_postData['verify_sign'];
		}
		if($this->_postData['charset']){
			$this->_order->charset = $this->_postData['charset'];
		}
		if($this->_postData['mc_fee']){
			$this->_order->payment_fee = $this->_postData['mc_fee'];
			$currency = $this->_postData['mc_currency'];
			$this->_order->base_payment_fee = Yii::$service->page->currency->getBaseCurrencyPrice($this->_postData['mc_fee'],$currency);
		}
		if($this->_postData['payment_type']){
			$this->_order->payment_type = $this->_postData['payment_type'];
		}
		if($this->_postData['payment_date']){
			$this->_order->paypal_order_datetime = date("Y-m-d H:i:s",$this->_postData['payment_date']);
		}
		if($this->_postData['protection_eligibility']){
			$this->_order->protection_eligibility = $this->_postData['protection_eligibility'];
		}
		$this->_order->updated_at = time();
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			if($orderstatus){
				# 指定了订单状态
				$this->_order->order_status = $orderstatus;
				$this->_order->save();
				$payment_status = strtolower($this->_postData['payment_status']);
				//Yii::$app->mylog->log('save_'.$orderstatus);
			}else{
				$payment_status = strtolower($this->_postData['payment_status']);
				if($payment_status == $this->payment_status_completed) {
					$this->_order->order_status = Yii::$service->order->payment_status_processing;
					# 更新订单信息
					$this->_order->save();
					# 更新库存
					//$orderitem = Salesorderitem::find()->asArray()->where(['order_id'=>$this->_order->order_id])->all();
					//Order::updateProductStockQty($orderitem);
					# 更新coupon使用量
					//$customer_id = $this->_order['customer_id'];
					//$coupon_code = $this->_order['coupon_code'];
					//if($customer_id && $coupon_code){
					//	Coupon::CouponTakeEffect($customer_id,$coupon_code);
					//}
					#LOG
					//Yii::$app->mylog->log('save_'.Order::ORDER_PROCESSING);
			}else if($payment_status == $this->payment_status_failed){
					$this->_order->order_status = Yii::$service->order->payment_status_canceled;
					$this->_order->save();
				}else if($payment_status == $this->payment_status_refunded){		
					$this->_order->order_status = Yii::$service->order->payment_status_canceled;
					$this->_order->save();
				}else{
					
				}
			}	
			$innerTransaction->commit();
			return true;
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return false;
	}
	
	
	# express 部分
	
	/**
	 * @property $token | String , 通过 下面的 PPHttpPost5 方法返回的paypal express的token
	 * @return String，通过token得到跳转的 paypal url，
	 * 得到上面的url后，进行跳转到paypal，然后确认，然后返回到fecshop，paypal会传递货运地址等信息
	 * 到fecshop，这样用户不需要手动填写货运地址等信息。因此，这种方式为快捷支付。
	 */
	public function getSetExpressCheckoutUrl($token){
		if($token){
			$ApiUrl 	= Yii::$service->payment->getExpressApiUrl($this->express_payment_method);
			return $ApiUrl."?cmd=_express-checkout&token=".urlencode($token);
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
		
		$API_NvpUrl 	= Yii::$service->payment->getExpressNvpUrl($this->express_payment_method);
		$API_Signature 	= Yii::$service->payment->getExpressSignature($this->express_payment_method);
		$API_UserName 	= Yii::$service->payment->getExpressAccount($this->express_payment_method);
		$API_Password 	= Yii::$service->payment->getExpressPassword($this->express_payment_method);
		# Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_URL, $API_NvpUrl);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if ($this->use_local_certs) {
			$crtFile = $this->getCrtFile('api-3t.sandbox.paypal.com');
            curl_setopt($ch, CURLOPT_CAINFO, $crtFile);
        }
		# Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		# Get response from the server.
		$httpResponse = curl_exec($ch);
		if(!$httpResponse) {
			$i++;
			if($i>5){
				//获取三次失败后，则推出。
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}else{
				$httpResponse = $this->PPHttpPost5($methodName_, $nvpStr_,$i);
			}
		}else{
			//第一次获取数据失败，则再次获取。并返回、
			if($i>0){
				//return $httpResponse;
			}
		}
		//paypal返回的是一系列的字符串，譬如：L_TIMESTAMP0=2014-11-08T01:51:13Z&L_TIMESTAMP1=2014-11-08T01:40:41Z&L_TIMESTAMP2=2014-11-08T01:40:40Z&
		//下面要做的是先把字符串通过&字符打碎成数组
		//
		//echo "***************<br>";
		//echo urldecode($httpResponse);
		//echo "<br>***************<br>";
		$httpResponseAr = explode("&", urldecode($httpResponse));
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_NvpUrl.");
		}
		return $httpParsedResponseAr;
	}
	
	
	/**
	 * @property $nvp_array | Array, 各个配置参数
	 * 将数组里面的key和value，组合成url的字符串，生成nvp url
	 */
	public function getRequestUrlStrByArray($nvp_array){
		$str = '';
		if(is_array($nvp_array) && !empty($nvp_array)){
			foreach($nvp_array as $k=>$v){
				$str .= '&'.urlencode($k).'='.urlencode($v);
			}
		}
		//echo $str;exit;
		return $str;
	}
	
	/**
	 * @property $landingPage | String ，访问api的类型，譬如login
	 * 通过购物车中的数据，组合成访问paypal express api的url
	 */
	public function getNvpStr($landingPage){
		$nvp_array = [];
		$nvp_array['LANDINGPAGE'] 	= $landingPage;
		$nvp_array['RETURNURL'] 	= Yii::$service->payment->getExpressReturnUrl($this->express_payment_method);
		$nvp_array['CANCELURL'] 	= Yii::$service->payment->getExpressCancelUrl($this->express_payment_method);
		$nvp_array['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
		$nvp_array['VERSION'] = $this->version;
		# 得到购物车的信息，通过购物车信息填写。
		$cartInfo = Yii::$service->cart->getCartInfo();
		$currency = Yii::$service->page->currency->getCurrentCurrency();
		
		$grand_total 		= $cartInfo['grand_total'];
		$subtotal			= $cartInfo['product_total'];
		$shipping_total		= $cartInfo['shipping_cost'];
		$discount_amount	= $cartInfo['coupon_cost'];
		$subtotal = $subtotal - $discount_amount;
		
		$nvp_array['PAYMENTREQUEST_0_CURRENCYCODE'] = $currency;
		$nvp_array['PAYMENTREQUEST_0_AMT'] = $grand_total;
		$nvp_array['PAYMENTREQUEST_0_ITEMAMT'] = $subtotal;
		$nvp_array['PAYMENTREQUEST_0_SHIPPINGAMT'] = $shipping_total;
		$i = 0;
		
		foreach($cartInfo['products'] as $item){
			$nvp_array['L_PAYMENTREQUEST_0_QTY'.$i] 		= $item['qty'];
			$nvp_array['L_PAYMENTREQUEST_0_NUMBER'.$i] 		= $item['sku'];
			$nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] 		= $item['product_price'];
			$nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] 		= Yii::$service->store->getStoreAttrVal($item['name'],'name');
			$nvp_array['L_PAYMENTREQUEST_0_CURRENCYCODE'.$i]= $currency;
			$i++;
		}
		$nvp_array['L_PAYMENTREQUEST_0_NAME'.$i] = 'Discount';
		$nvp_array['L_PAYMENTREQUEST_0_AMT'.$i] = "-".$discount_amount;
		return $this->getRequestUrlStrByArray($nvp_array);
	}
	/**
	 * 从request get 中取出来token，然后保存到session中
	 */
	public function setExpressToken(){
		$token = Yii::$app->request->get('token');
		if($token){
			Yii::$app->session->set(self::EXPRESS_TOKEN,$token);
			return true;
		}
		return false;
	}
	/**
	 * 从request get 中取出来PayerID，然后保存到session中
	 */
	public function setExpressPayerID(){
		$PayerID = Yii::$app->request->get('PayerID');
		if($PayerID){
			Yii::$app->session->set(self::EXPRESS_PAYER_ID,$PayerID);
			return true;
		}
		return false;
	}
	/**
	 * 从session 中取出来token
	 */
	public function getExpressToken(){
		return Yii::$app->session->get(self::EXPRESS_TOKEN);
	}
	/**
	 * 从session 中取出来PayerID
	 */
	public function getExpressPayerID(){
		return Yii::$app->session->get(self::EXPRESS_PAYER_ID);
	}
	
	
}