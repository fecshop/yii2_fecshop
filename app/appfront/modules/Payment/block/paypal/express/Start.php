<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Payment\block\paypal\express;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start {
	
	public function startExpress(){
		# 首先验证购物车中是否存在产品
		//$this->validateCart();
		//if($LANDINGPAGE == 'Login' ){
		//	$clickButton = 'paypal button';
		//}else{
		//	$clickButton = 'Credit Card button';
		//}
		
		$methodName_ = "SetExpressCheckout";
		$nvpStr_ = Yii::$service->payment->paypal->getNvpStr($LANDINGPAGE);
		//echo $nvpStr_;exit;
		$SetExpressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
		if(strtolower($SetExpressCheckoutReturn['ACK']) == 'success'){
			$token = $SetExpressCheckoutReturn['TOKEN'];
			$redirectUrl = Yii::$service->payment->paypal->getSetExpressCheckoutUrl($token);
			Yii::$service->url->redirect($redirectUrl);
			exit;
		}
	
		
	}
	
	
	
	
	# 首先验证购物车中是否存在产品
	//public function validateCart(){
		
		
	//}
	
	
	
	
	
	
	
	
	
	
	
	
	
}