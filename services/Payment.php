<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * Payment services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Payment extends Service
{
	
	public $paymentConfig;
	protected $_currentPaymentMethod;
	public function setPaymentMethod($payment_method){
		$this->_currentPaymentMethod = $payment_method;
	}
	public function getPaymentMethod(){
		return $this->_currentPaymentMethod;
	}
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回提交订单信息跳转到的第三方支付url，也就是第三方支付的url。
	 */
	public function getStandardStartUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['start_url'])){
				if(!empty($paymentConfig['standard'][$payment_method]['start_url'])){
					return $this->getUrl($paymentConfig['standard'][$payment_method]['start_url']);
				}
			}
		}
	} 
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 第三方支付成功后，返回到网站的url
	 */
	public function getStandardSuccessRedirectUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['success_redirect_url'])){
				if(!empty($paymentConfig['standard'][$payment_method]['success_redirect_url'])){
					return $this->getUrl($paymentConfig['standard'][$payment_method]['success_redirect_url']);
				}
			}
		}
	}
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 第三方网站发送ipn消息，告诉网站支付成功的url。
	 */
	public function getStandardIpnUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['IPN_url'])){
				if(!empty($paymentConfig['standard'][$payment_method]['IPN_url'])){
					return $this->getUrl($paymentConfig['standard'][$payment_method]['IPN_url']);
				}
			}
		}
	}
	
	protected function getUrl($url){
		$homeUrl = Yii::$service->url->homeUrl();
		$url = str_replace('@homeUrl',$homeUrl,$url);
		return trim($url);
	}
	
	/**
	 * @return Array 得到所有支付的数组。
	 */
	public function getStandardPaymentArr(){
		$arr = [];
		if(
			isset($this->paymentConfig['standard']) && 
			is_array($this->paymentConfig['standard'])
		){
			foreach($this->paymentConfig['standard'] as $payment_type => $info){
				$label = $info['label'];
				$imageUrl = '';
				if(is_array($info['image'])){
					list($iUrl,$l) = $info['image'];
					if($iUrl){
						$imageUrl = Yii::$service->image->getImgUrl($iUrl,$l);
					}
				}
				$supplement = $info['supplement'];
				$arr[$payment_type] = [
					'label' => $label,
					'imageUrl' => $imageUrl,
					'supplement' => $supplement,
				];
			}
		}
		return $arr;
	}
	
	/**
	 * @property $shipping_method | String
	 * @return boolean 发货方式
	 */
	protected function actionIfIsCorrectStandard($payment_method){
		$paymentConfig = $this->paymentConfig;
		$standard = isset($paymentConfig['standard']) ? $paymentConfig['standard'] : '';
		if(isset($standard[$payment_method]) && !empty($standard[$payment_method])){
			return true;
		}else{
			return false;
		}
	}
	
}