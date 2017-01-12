<?php
namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	protected $_payment_mothod;
	protected $_address_view_file;
	protected $_address_id;
	protected $_address_list;
	protected $_custom_info;
	protected $_country;
	protected $_state;
	protected $_stateHtml;
	
	public function getLastData(){
		$cartInfo = $this->getCartInfo();
		if(!isset($cartInfo['products']) || !is_array($cartInfo['products']) || empty($cartInfo['products'])){
			Yii::$service->url->redirectByUrlKey('checkout/cart');
		}
		$currency_info = Yii::$service->page->currency->getCurrencyInfo();
		$this->initAddress();
		$this->initCountry();
		
		//$this->initCustomerInfo();
		$this->initState();
		return [
			'payments' 					=> $this->getPayment(),
			'shippings' 				=> $this->getShippings(),
			'current_payment_mothod' 	=> $this->_payment_mothod,
			'cart_info'  				=> $cartInfo,
			'currency_info' 			=> $currency_info,
			'address_view_file' 		=> $this->_address_view_file,
		
			'cart_address_id'			=> $this->_address_id,
			'address_list'				=> $this->_address_list,
			'customer_info'				=> $this->_custom_info,
			'country_select'			=> $this->_countrySelect,
			//'state_select'			=> $this->_stateSelect,
			'state_html'				=> $this->_stateHtml,
		];
	}
	
	/**
	 * 初始化地址信息，首先从当前用户里面取值，然后从cart表中取数据覆盖
	 * 1. 初始化 $this->_address，里面保存的各个地址信息。
	 * 2. 如果是登录用户，而且
	 */
	public function initAddress(){
		$cart = Yii::$service->cart->quote->getCart();
		$address_id = $cart['customer_address_id'];
		
		$address_info = [];	
		if(!Yii::$app->user->isGuest){
			$identity = Yii::$app->user->identity;
			$address_info['email'] 		= $identity['email'];
			$address_info['first_name'] = $identity['firstname'];
			$address_info['last_name'] 	= $identity['lastname'];
		}
		if(isset($cart['customer_email']) && !empty($cart['customer_email'])){
			$address_info['email'] = $cart['customer_email'];
		}
		
		if(isset($cart['customer_firstname']) && !empty($cart['customer_firstname'])){
			$address_info['first_name'] = $cart['customer_firstname'];
		}
		
		if(isset($cart['customer_lastname']) && !empty($cart['customer_lastname'])){
			$address_info['last_name'] = $cart['customer_lastname'];
		}
		
		if(isset($cart['customer_telephone']) && !empty($cart['customer_telephone'])){
			$address_info['telephone'] = $cart['customer_telephone'];
		}
		
		if(isset($cart['customer_address_country']) && !empty($cart['customer_address_country'])){
			$address_info['country'] = $cart['customer_address_country'];
			$this->_country = $address_info['country'];
		}
		
		if(isset($cart['customer_address_state']) && !empty($cart['customer_address_state'])){
			$address_info['state'] = $cart['customer_address_state'];
		}
		
		if(isset($cart['customer_address_city']) && !empty($cart['customer_address_city'])){
			$address_info['city'] = $cart['customer_address_city'];
		}
		
		if(isset($cart['customer_address_zip']) && !empty($cart['customer_address_zip'])){
			$address_info['zip'] = $cart['customer_address_zip'];
		}
		 
		if(isset($cart['customer_address_street1']) && !empty($cart['customer_address_street1'])){
			$address_info['street1'] = $cart['customer_address_street1'];
		}
		
		if(isset($cart['customer_address_street2']) && !empty($cart['customer_address_street2'])){
			$address_info['street2'] = $cart['customer_address_street2'];
		}
		$this->_address = $address_info;
		$this->_address_list = Yii::$service->customer->address->currentAddressList();
		//var_dump($this->_address_list);
		# 如果购物车存在customer_address_id，而且用户地址中中也存在customer_address_id
		if($address_id && isset($this->_address_list[$address_id]) && !empty($this->_address_list[$address_id])){
			$this->_address_id = $address_id;
			$this->_address_view_file = 'checkout/onepage/index/address_select.php';
			$addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
			if($addressModel['country']){
				$this->_country = $addressModel['country'];
				$this->_address['country'] = $this->_country;
			}
			if($addressModel['state']){
				$this->_state = $addressModel['state'];
				$this->_address['state'] = $this->_state;
			}
		}else if(is_array($this->_address_list) && !empty($this->_address_list)){
			# 用户存在地址列表，但是，cart中没有customer_address_id
			# 这种情况下，从列表中取出来一个地址，然后设置成当前的地址。
			foreach($this->_address_list as $adss_id => $info){
				if($info['is_default'] == 1){
					$this->_address_id = $adss_id;
					$this->_address_view_file = 'checkout/onepage/index/address_select.php';
					$addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
					if($addressModel['country']){
						$this->_country = $addressModel['country'];
						$this->_address['country'] = $this->_country;
					}
					if($addressModel['state']){
						$this->_state = $addressModel['state'];
						$this->_address['state'] = $this->_state;
					}
					break;
				}
			}
		}else{
			$this->_address_view_file = 'checkout/onepage/index/address.php';
		}
		if(!$this->_country){
			$this->_country = Yii::$service->helper->country->getDefaultCountry();
		}
		
	}
	
	
	public function initCountry(){
		$this->_countrySelect = Yii::$service->helper->country->getAllCountryOptions('','',$this->_country);
		
	}
	
	public function initState($country = ''){
		$state = isset($this->_address['state']) ? $this->_address['state'] : '';
		if(!$country){
			$country = $this->_country;
		}
		$stateHtml = Yii::$service->helper->country->getStateOptionsByContryCode($country,$state);
		if(!$stateHtml){
			$stateHtml = '<input id="state" name="billing[state]" value="'.$state.'" title="State" class="address_state input-text" style="" type="text">';
		}else{
			$stateHtml = '<select id="address:state" class="address_state validate-select" title="State" name="billing[state]">
							<option value="">Please select region, state or province</option>'
						.$stateHtml.'</select>';
												
		}
		$this->_stateHtml = $stateHtml;
		
	}
	
	
	public function ajaxChangecountry(){
		$country = Yii::$app->request->get('country');
		$state = $this->initState($country);
		echo json_encode([
			'state' => $this->_stateHtml,
		]);
		exit;
	}
	
	/**
	 * @return $cart_info | Array
	 * 本函数为从数据库中得到购物车中的数据，然后结合产品表
	 * 在加入一些产品数据，最终补全所有需要的信息。
	 * 
	 */
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
				$custom_option_info_arr = $this->getProductOptions($product_one,$custom_option_sku);
				$cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
				# 设置相应的custom option 对应的图片
				$custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
				if($custom_option_image){
					$cart_info['products'][$k]['image'] = $custom_option_image;
				}
			}
		}
		return $cart_info;
	}
	
	/**
	 * 将产品页面选择的颜色尺码等显示出来，包括custom option 和spu options部分的数据
	 */
	public function getProductOptions($product_one,$custom_option_sku){
		$custom_option_info_arr = [];
		$custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
		$custom_option_sku = $product_one['custom_option_sku'];
		if(isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])){
			$custom_option_info = $custom_option[$custom_option_sku];
			foreach($custom_option_info as $attr=>$val){
				if(!in_array($attr,['qty','sku','price','image'])){ 
					$attr = str_replace('_',' ',$attr);
					$attr = ucfirst($attr);
					$custom_option_info_arr[$attr] = $val;
				}
			}
		}
		
		$spu_options = $product_one['spu_options'];
		if(is_array($spu_options) && !empty($spu_options)){
			foreach($spu_options as $label => $val){
				$custom_option_info_arr[$label] = $val;
			}
		}
		return $custom_option_info_arr;
	}
	
	
	
	
	public function getShippings($current_shipping_method = ''){
		$country = $this->_country;
		if(!$this->_state){
			$region = '*';
		}else{
			$region = $this->_state;
		}
		$cartInfo = Yii::$service->cart->quoteItem->getCartProductInfo();
		//echo $country ;
		$product_weight = $cartInfo['product_weight'];
		# 传递当前的货运方式，这个需要从cart中选取，
		# 如果cart中没有shipping_method，那么该值为空
		if(!$current_shipping_method){
			$cart = Yii::$service->cart->quote->getCart();
			$current_shipping_method = isset($cart['shipping_method']) ? $cart['shipping_method'] : '';
		}
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
				$currentCurrencyCost = $cost['currCost'];
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
	
	# ajax 更新部分
	public function ajaxUpdateOrder(){
		$country 			= Yii::$app->request->get('country');
		$shipping_method 	= Yii::$app->request->get('shipping_method');
		$address_id 		= Yii::$app->request->get('address_id');
		$state 				= Yii::$app->request->get('state');
		if($address_id){
			$this->_address_id = $address_id;
			$addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
			if($addressModel['country']){
				$country = $addressModel['country'];
				$this->_country = $addressModel['country'];
			}
			if($addressModel['state']){
				$state = $addressModel['state'];
				$this->_state = $addressModel['state'];
			}
		}else if($country){
			$this->_country = $country;
			if(!$state){
				$state = '*';
			}
			$this->_state = $state;
		}
		if($this->_country && $this->_state){
			$shippings = $this->getShippings($shipping_method);
			$payments  = $this->getPayment();
			
			$shippingView = [
				'view'	=> 'checkout/onepage/index/shipping.php'
			];
			$shippingParam = [
				'shippings' => $shippings,
			];
			$shippingHtml = Yii::$service->page->widget->render($shippingView,$shippingParam);
			
			# 先通过item计算出来重量
			$quoteItem = Yii::$service->cart->quoteItem->getCartProductInfo();
			$product_weight = $quoteItem['product_weight'];
			# 得到运费
			$shippingCost 	= Yii::$service->shipping->getShippingCostWithSymbols($shipping_method,$product_weight,$country,$state);
			
			//$shipping_cost  = 0;
			//if(isset($shippingCost['currentCost'])){
			//	$shipping_cost = $shippingCost['currentCost'];
			//}
			# 设置cart的运费部分。
			Yii::$service->cart->quote->setShippingCost($shippingCost);
			# 得到当前货币
			$currency_info = Yii::$service->page->currency->getCurrencyInfo();
			$reviewOrderView = [
				'view'	=> 'checkout/onepage/index/review_order.php'
			];
			$cart_info 		= $this->getCartInfo();
			$reviewOrderParam = [
				'cart_info' => $cart_info,
				'currency_info' => $currency_info,
			];
			$reviewOrderHtml = Yii::$service->page->widget->render($reviewOrderView,$reviewOrderParam); 
			
			echo json_encode([
				'status' 		=> 'success',
				'shippingHtml' 	=> $shippingHtml,
				'reviewOrderHtml' 	=> $reviewOrderHtml,
			]);	
			exit;			
		}
	}
	
	
	
}