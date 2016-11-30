<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */
namespace fecshop\app\appfront\modules\checkout\block\cart;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	
	
	
	public function getLastData(){
		
		$this->initHead();
		$currency_info = Yii::$service->page->currency->getCurrencyInfo();
		return [
			'cart_info' => $this->getCartInfo(),
			'currency_info' => $currency_info,
		];
	}
	/** @return data example
	 *	[
	 *				'grand_total' 	=> $grand_total,
	 *				'shipping_cost' => $shippingCost,
	 *				'coupon_cost' 	=> $couponCost,
	 *				'product_total' => $product_total,
	 *				'products' 		=> $products,
	 *	]
	 *			上面的products数组的个数如下：	
	 *			$products[] = [
	 *					'product_id' 		=> $product_id ,
	 *					'qty' 				=> $qty ,
	 *					'custom_option_sku' => $custom_option_sku ,
	 *					'product_price' 	=> $product_price ,
	 *					'product_row_price' => $product_row_price ,
	 *					'product_name'		=> $one['name'],
	 *					'product_url'		=> $one['url_key'],
	 *					'product_image'		=> $one['image'],
	 *				];
	 */
	public function getCartInfo(){
		$cart_info = Yii::$service->cart->getCartInfo();
		//var_dump($cart_info);
		//exit;
		if(isset($cart_info['products']) && is_array($cart_info['products'])){
			foreach($cart_info['products'] as $k=>$product_one){
				$cart_info['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'],'name');
				if(isset($product_one['product_image']['main']['image'])){
					$cart_info['products'][$k]['image'] = $product_one['product_image']['main']['image'];
				}
				$cart_info['products'][$k]['url'] = Yii::$service->url->getUrl($product_one['product_url']);
				$custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
				$custom_option_sku = $product_one['custom_option_sku'];
				if(isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])){
					$custom_option_info = $custom_option[$custom_option_sku];
					$custom_option_info_arr = [];
					foreach($custom_option_info as $attr=>$val){
						if(!in_array($attr,['qty','sku','price','image'])){ 
							$attr = str_replace('_',' ',$attr);
							$attr = ucfirst($attr);
							$custom_option_info_arr[$attr] = $val;
						}
					}
					$cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
					$custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
					if($custom_option_image){
						$cart_info['products'][$k]['image'] = $custom_option_image;
					}
				}
			}
		}
		
		return $cart_info;
	}
	
	public function initHead(){
		
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => 'checkout cart',
		]);
		
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => 'checkout cart page',
		]);
		$this->_title = 'checkout cart page';
		Yii::$app->view->title = $this->_title;
	}
	
}
















