<?php

namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	protected $_payment_mothod;
	public function getLastData(){
		$currency_info = Yii::$service->page->currency->getCurrencyInfo();
		return [
			'payments' => $this->getPayment(),
			'shippings' => $this->getShippings(),
			'current_payment_mothod' => $this->_payment_mothod,
			'cart_info'  => $this->getCartInfo(),
			'currency_info' => $currency_info,
		];
	}
	
	
	
	public function getCartInfo(){
		$cart_info = Yii::$service->cart->getCartInfo();
		
		if(isset($cart_info['products']) && is_array($cart_info['products'])){
			foreach($cart_info['products'] as $k=>$product_one){
				# 设置名字，得到当前store的语言名字。
				$cart_info['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'],'name');
				# 设置图片
				if(isset($product_one['product_image']['main']['image'])){
					$cart_info['products'][$k]['image'] = $product_one['product_image']['main']['image'];
				}
				# 产品的url
				$cart_info['products'][$k]['url'] = Yii::$service->url->getUrl($product_one['product_url']);
				
				$custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
				$custom_option_sku = $product_one['custom_option_sku'];
				# 将在产品页面选择的颜色尺码等属性显示出来。
				//$custom_option_info_arr = $this->getProductOptions($product_one,$custom_option_sku);
				//$cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
				# 设置相应的custom option 对应的图片
				$custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
				if($custom_option_image){
					$cart_info['products'][$k]['image'] = $custom_option_image;
				}
			}
		}
		
		return $cart_info;
	}
	
	
	public function getShippings(){
		$country = Yii::$service->helper->country->getDefaultCountry();
		$cartInfo = Yii::$service->cart->quoteItem->getCartProductInfo();
		
		$product_weight = $cartInfo['product_weight'];
		# 传递当前的货运方式，这个需要从cart中选取，
		# 如果cart中没有shipping_method，那么该值为空
		$current_shipping_method = '';
		$region='*';
		$shippingArr = $this->getShippingArr($product_weight,$current_shipping_method,$country,$region);
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
	public  function getShippingArr($weight,$current_shipping_method,$country,$region='*'){
		//$shippingName = '';
		//$now_shipping = $shipping_method;
		//if($now_shipping){
		//	$shippingName = $now_shipping;
		//}
		//if(!$shippingName){
		//	$shippingName = Yii::$service->shipping->getDefaultShipping();
		//}
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
				if($current_shipping_method == $method){
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