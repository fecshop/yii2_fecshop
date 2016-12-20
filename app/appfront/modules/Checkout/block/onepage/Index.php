<?php

namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	
	protected $_payment_mothod;
	
	
	
	public function getLastData(){
		
		
		return [
			'payments' => $this->getPayment(),
			'shippings' => $this->getShippings(),
			'current_payment_mothod' => $this->_payment_mothod,
		];
	}
	
	public function getShippings(){
		$country = Yii::$service->helper->country->getDefaultCountry();
		$cartInfo = Yii::$service->cart->quoteItem->getCartProductInfo();
		
		$product_weight = $cartInfo['product_weight'];
		$shipping_method = Yii::$service->shipping->getDefaultShipping();
		$region='*';
		$shippingArr = $this->getShippingArr($product_weight,$shipping_method,$country,$region);
		return $shippingArr ;
	}
	
	
	public function getPayment(){
		$paymentArr = Yii::$service->payment->getStandardPaymentArr();
		if(!$this->_payment_mothod){
			foreach($paymentArr as $k => $v){
				$this->_payment_mothod = $k;
				break;
			}
		}
		return $paymentArr;
	}
	
	
	# 在下单页面，得到订单的运费html
	/**
	 * @property $weight | Float , 总量
	 * @property $shipping_method | String  $shipping_method key
	 * @property $country | String  国家
	 * @return String ， 通过上面的三个参数，得到各个运费方式对应的运费。
	 * 			最后生成html
	 */
	public  function getShippingArr($weight,$shipping_method,$country,$region='*'){
		$shippingName = '';
		$now_shipping = $shipping_method;
		if($now_shipping){
			$shippingName = $now_shipping;
		}
		if(!$shippingName){
			$shippingName = Yii::$service->shipping->getDefaultShipping();
		}
		$allshipping = Yii::$service->shipping->getShippingMethod();
		$sr = '';
		$shipping_i = 1;
		$arr = [];
		if(is_array($allshipping)){
			foreach($allshipping as $method=>$shipping){
				$label = $shipping['label'];
				$name = $shipping['name'];
				# 得到运费的金额
				$cost = Yii::$service->shipping->getShippingCostWithSymbols($method,$weight,$country,$region);
				//var_dump($cost);
				$currentCurrencyCost = $cost['currentCost'];
				$symbol = Yii::$service->page->currency->getCurrentSymbol();
				if($shippingName == $method){
					$check = ' checked="checked" ';
				}else{
					$check = '';
				}
				$arr[] = [
					'method'=> $method,
					'label' => $label,
					'name'  => $name,
					'cost'  => $symbol.$currentCurrencyCost,
					'check' => $check,
					'shipping_i' => $shipping_i,
				];
				/*
				$sr .= '<div class="shippingmethods">
								<dd class="flatrate">'.$label.'</dd>
								<dt>
									<input data-role="none" '.$check.' type="radio" id="s_method_flatrate_flatrate'.$shipping_i.'" value="'.$method.'" class="validate-one-required-by-name" name="shipping_method">
									<label for="s_method_flatrate_flatrate'.$shipping_i.'">'.$name.'
										<strong>                 
											<span class="price">'.$symbol.$currentCurrencyCost.'</span>
										</strong>
									</label>
								</dt>
							</div>';
				*/
				$shipping_i++;
			}
		}
		return $arr;
	}
}