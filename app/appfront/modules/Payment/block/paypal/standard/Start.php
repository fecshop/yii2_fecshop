<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Payment\block\paypal\standard;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start {
	
	public function getLastData(){
		$increment_id 		= Yii::$service->order->getSessionIncrementId();
		$loaderImg 			= Yii::$service->image->getImgUrl('images/loader.gif','appfront');
		$paypalLogoImg 		= Yii::$service->image->getImgUrl('custom/logo.png','appfront');		
		
		/*
		$store_name 		= Store::getCurrentStoreLabel();
		
		$paypal = Config::param("payment_method");
		$submitAction = $paypal['paypal']['payments_standard']['redirect_url'];
		$paypal_account = $paypal['paypal']['payments_standard']['user'];
		
		$order_increment_id = $this->_order['increment_id'];
		$order_currency = $this->_order['order_currency_code'];
		
		$paymentaction = "sale";
		$cmd = '_cart';
		$upload = 1;
		
		$return_url = Url::getUrl('paypal/standard/success');  
		$cancel_url = Url::getUrl('paypal/standard/cancel'); 
		$notify_url = Url::getUrl('paypal/ipn'); 
		
		$tax = 0.00;
		$tax_cart = 0.00;
		
		$amount = $this->_order['grand_total'];
		$shipping = $this->_order['shipping_total'];
		$discount_amount = $this->_order['subtotal_with_discount'];
		$discount_amount_cart = $discount_amount;
		*/
		
		return [
			'loaderImg' 	=> $loaderImg,
			'paypalLogoImg'	=> $paypalLogoImg,
		];
		
	}
	
}