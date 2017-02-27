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
		$loaderImg 			= Yii::$service->image->getImgUrl('images/loader.gif','appfront');
		$paypalLogoImg 		= Yii::$service->image->getImgUrl('custom/logo.png','appfront');		
		
		$increment_id 		= Yii::$service->order->getSessionIncrementId();
		if($increment_id){
			$order 				= Yii::$service->order->getInfoByIncrementId($increment_id);
			if($order){
				//var_dump($order);exit;
				Yii::$service->payment->setPaymentMethod($order['payment_method']);
				$success_redirect_url 	= Yii::$service->payment->getStandardSuccessRedirectUrl();
				$cancel_url 			= Yii::$service->payment->getStandardCancelUrl(); 
				$ipn_url 				= Yii::$service->payment->getStandardIpnUrl();
				$store_name 			= Yii::$service->store->currentStore;
				
				$payment_action 	= "sale";
				$cmd 				= '_cart';
				$upload 			= 1;
				$tax 				= 0.00;
				$tax_cart 			= 0.00;
				
				$payment_url 		= Yii::$service->payment->getStandardPaymentUrl();
				$account 			= Yii::$service->payment->getStandardAccount();
				
				return [
					'loader_img' 		=> $loaderImg,
					'paypal_logo_img' 	=> $paypalLogoImg,
					'order' 			=> $order,
					'success_redirect_url' => $success_redirect_url,
					'cancel_url' 		=> $cancel_url,
					'ipn_url' 			=> $ipn_url,
					'store_name' 		=> $store_name,
					'payment_action' 	=> $payment_action,
					'cmd' 				=> $cmd,
					'upload' 			=> $upload,
					'tax' 				=> $tax,
					'tax_cart' 			=> $tax_cart,
					'payment_url'	 	=> $payment_url,
					'account' 			=> $account,
					'product_items_and_shipping'		=> $this->getProductItemsAndShipping($order),
					'address_html'		=> $this->getAddressHtml($order),
				];
			}
		}
	}
	
	public function getAddressHtml($order){
		$stateCode 		= $order['customer_address_state'];
		$countryCode 	= $order['customer_address_country'];
		$country		= Yii::$service->helper->country->getCountryNameByKey($countryCode);
		$state			= Yii::$service->helper->country->getStateByContryCode($countryCode,$stateCode);
		$str = '
			<input id="city" name="city" value="'.$order['customer_address_city'].'" type="hidden"/>
			<input id="country" name="country" value="'.$country.'" type="hidden"/>
			<input id="email" name="email"  value="'.$order['customer_email'].'" type="hidden"/>
			<input id="first_name" name="first_name" value="'.$order['customer_firstname'].'" type="hidden"/>
			<input id="last_name" name="last_name" value="'.$order['customer_lastname'].'" type="hidden"/>
			<input id="zip" name="zip" value="'.$order['customer_address_zip'].'" type="hidden"/>
			<input id="state" name="state" value="'.$state.'" type="hidden"/>
			<input id="address1" name="address1" value="'.$order['customer_address_street1'].'" type="hidden"/>
			<input id="address2" name="address2" value="'.$order['customer_address_street2'].'" type="hidden"/>
			<input id="address_override" name="address_override" value="0" type="hidden"/>
		';
		return $str;
	}
	
	public function getProductItemsAndShipping($order){
		$items = $order['items'];
		$str = '';
		$i = 1;
		foreach($items as $item){
			$sku = isset($item['sku']) ? $item['sku'] : '';
			$name = isset($item['name']) ? $item['name'] : '';
			$qty = isset($item['qty']) ? $item['qty'] : '';
			$price = isset($item['price']) ? str_replace(',','',number_format($item['price'],2)) : number_format($item['price'],0);
			
			$custom_option_info = isset($item['custom_option_info']) ? $item['custom_option_info'] : '';
			if($sku && $qty && $price){
				$str .= '
					<input id="item_number_'.$i.'" name="item_number_'.$i.'" value="'.$sku.'" type="hidden"/>
					<input id="item_name_'.$i.'" name="item_name_'.$i.'" value="'.$name.'" type="hidden"/>
					<input id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$qty.'" type="hidden"/>
					<input id="amount_'.$i.'" name="amount_'.$i.'" value="'.$price.'" type="hidden"/>
					
				';
				if(is_array($custom_option_info) && !empty($custom_option_info)){
					$j = 0;
					foreach($custom_option_info as $co_label=>$co_value){
						$str .= '
						<input id="on'.$j.'_'.$i.'" name="on'.$j.'_'.$i.'" type="hidden" value="'.$co_label.'" />
						<input id="os'.$j.'_'.$i.'" name="os'.$j.'_'.$i.'" type="hidden" value="'.$co_value.'" />
						';
						$j++;
					}
				}
			}
			$i++;
		}
		$shipping_total  = $order['shipping_total'];
		$shipping_total = str_replace(',','',number_format($shipping_total,2));
			
		$shipping_method = $order['shipping_method'];
		$shipping_label  = Yii::$service->shipping->getShippingLabelByMethod($shipping_method);
		$str .= '
			<input id="item_number_'.$i.'" name="item_number_'.$i.'" value="'.$shipping_label.'" type="hidden"/>
			<input id="item_name_'.$i.'" name="item_name_'.$i.'" value="'.$shipping_method.'" type="hidden"/>
			<input id="quantity_'.$i.'" name="quantity_'.$i.'" value="1" type="hidden"/>
			<input id="amount_'.$i.'" name="amount_'.$i.'" value="'.$shipping_total.'" type="hidden"/>
		';	
		return $str;
	}
	
	
	
	
	
}