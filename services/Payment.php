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
	# 不需要释放库存的支付方式。譬如货到付款，在系统中
	# pending订单，如果一段时间未付款，会释放产品库存，但是货到付款类型的订单不会释放，
	# 如果需要释放产品库存，客服在后台取消订单即可释放产品库存。
													
	public $noRelasePaymentMethod;
	protected $_currentPaymentMethod;
	
	
	/**
	 * @property $payment_method | string
	 * 设置当前的支付方式
	 */
	public function setPaymentMethod($payment_method){
		$this->_currentPaymentMethod = $payment_method;
	}
	/**
	 * @return $payment_method | string
	 * 得到当前的支付方式
	 */
	public function getPaymentMethod(){
		return $this->_currentPaymentMethod;
	}
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回提交订单信息跳转到的第三方支付url，也就是第三方支付的url。
	 * #从配置信息中获取
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
	 * #从配置信息中获取
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
	 * #从配置信息中获取
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
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return String 支付取消的url。
	 * #从配置信息中获取
	 */
	public function getStandardCancelUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['cancel_url'])){
				if(!empty($paymentConfig['standard'][$payment_method]['cancel_url'])){
					return $this->getUrl($paymentConfig['standard'][$payment_method]['cancel_url']);
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return String 得到跳转到第三方支付的url。
	 * #从配置信息中获取
	 */
	public function getStandardPaymentUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['payment_url'])){
				if(!empty($paymentConfig['standard'][$payment_method]['payment_url'])){
					return $paymentConfig['standard'][$payment_method]['payment_url'];
				}
			}
		}
	}
	
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return String 用户名
	 * #从配置信息中获取
	 */
	public function getStandardAccount($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['account'])){
				if(!empty($paymentConfig['standard'][$payment_method]['account'])){
					return $paymentConfig['standard'][$payment_method]['account'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return String Password
	 * #从配置信息中获取
	 */
	public function getStandardPassword($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['password'])){
				if(!empty($paymentConfig['standard'][$payment_method]['password'])){
					return $paymentConfig['standard'][$payment_method]['password'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return String Signature
	 * #从配置信息中获取
	 */
	public function getStandardSignature($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['standard'][$payment_method]['signature'])){
				if(!empty($paymentConfig['standard'][$payment_method]['signature'])){
					return $paymentConfig['standard'][$payment_method]['signature'];
				}
			}
		}
	}
	
	/**
	 * @property $url | String url的字符串
	 * @return String 根据传递的字符串格式，得到相应的url
	 */
	protected function getUrl($url){
		$homeUrl = Yii::$service->url->homeUrl();
		$url = str_replace('@homeUrl',$homeUrl,$url);
		return trim($url);
	}
	
	/**
	 * @return Array 得到所有支付的数组，数组含有三个字段。
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
	 * @property $payment_method | String ， 支付方式
	 * @return boolean 判断传递的支付方式，是否在配置中存在，如果存在返回true。
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
	
	####################
	## Express 部分   ##
	####################
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回获取token的url地址。
	 */
	public function getExpressNvpUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['nvp_url'])){
				if(!empty($paymentConfig['express'][$payment_method]['nvp_url'])){
					return $paymentConfig['express'][$payment_method]['nvp_url'];
				}
			}
		}
	}
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的api地址。
	 */
	public function getExpressApiUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['api_url'])){
				if(!empty($paymentConfig['express'][$payment_method]['api_url'])){
					return $paymentConfig['express'][$payment_method]['api_url'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的account。
	 */
	public function getExpressAccount($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['account'])){
				if(!empty($paymentConfig['express'][$payment_method]['account'])){
					return $paymentConfig['express'][$payment_method]['account'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的password。
	 */
	public function getExpressPassword($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['password'])){
				if(!empty($paymentConfig['express'][$payment_method]['password'])){
					return $paymentConfig['express'][$payment_method]['password'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的signature。
	 */
	public function getExpressSignature($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['signature'])){
				if(!empty($paymentConfig['express'][$payment_method]['signature'])){
					return $paymentConfig['express'][$payment_method]['signature'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的label。
	 */
	public function getExpressLabel($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['label'])){
				if(!empty($paymentConfig['express'][$payment_method]['label'])){
					return $paymentConfig['express'][$payment_method]['label'];
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的signature。
	 */
	public function getExpressReturnUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['return_url'])){
				if(!empty($paymentConfig['express'][$payment_method]['return_url'])){
					return $this->getUrl($paymentConfig['express'][$payment_method]['return_url']);
				}
			}
		}
	}
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 返回进行数据交互的express的signature。
	 */
	public function getExpressCancelUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['cancel_url'])){
				if(!empty($paymentConfig['express'][$payment_method]['cancel_url'])){
					return $this->getUrl($paymentConfig['express'][$payment_method]['cancel_url']);
				}
			}
		}
	}
	
	
	/**
	 * @property $payment_method | String 支付方式。
	 * @return 第三方支付成功后，返回到网站的url
	 * #从配置信息中获取
	 */
	public function getExpressSuccessRedirectUrl($payment_method = ''){
		if(!$payment_method){
			$payment_method = $this->getPaymentMethod();
		}
		if($payment_method){
			$paymentConfig = $this->paymentConfig;
			if(isset($paymentConfig['express'][$payment_method]['success_redirect_url'])){
				if(!empty($paymentConfig['express'][$payment_method]['success_redirect_url'])){
					return $this->getUrl($paymentConfig['express'][$payment_method]['success_redirect_url']);
				}
			}
		}
	}
	
}