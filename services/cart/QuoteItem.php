<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cart;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\mysqldb\cart\Item as MyCartItem;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class QuoteItem extends Service
{
	
	
	protected $_my_cart_item;	# 购物车cart item 对象
	
	
	/*
	 $item = [
		'product_id' 		=> 22222,
		'custom_option_sku' => red-xxl,
		'qty' 				=> 22,
	 ];
	*/
	
	public function addItem($item){
		$cart_id = Yii::$service->cart->quote->getCartId();
		
		# 查看是否存在此产品，如果存在，则相加个数
		$item_one = MyCartItem::find()->where([
			'cart_id' 	=> $cart_id,
			'product_id'=> $item['product_id'],
			'custom_option_sku'	=> $item['custom_option_sku'],
		])->one();
		
		//echo 3333;exit;
		if($item_one['cart_id']){
			$item_one->qty = $item['qty'] + $item_one['qty'];
			$item_one->save();
			# 重新计算购物车的数量
			Yii::$service->cart->quote->computeCartInfo();
		}else{
			$item_one = new MyCartItem;
			$item_one->store = Yii::$service->store->currentStore;
			$item_one->cart_id			= $cart_id;
			$item_one->created_at 		= time();
			$item_one->updated_at  		= time();
			$item_one->product_id  		= $item['product_id'];
			$item_one->qty				= $item['qty'];
			$item_one->custom_option_sku= $item['custom_option_sku'];
			$item_one->save();
			# 重新计算购物车的数量
			Yii::$service->cart->quote->computeCartInfo();
		}
		
	}
	/**
	 * 将购物车中的某个产品更改个数。
	 */
	public function changeItemQty($item){
		$cart_id = Yii::$service->cart->quote->getCartId();
		# 查看是否存在此产品，如果存在，则更改
		$item_one = MyCartItem::find()->where([
			'cart_id' 	=> $cart_id,
			'product_id'=> $item['product_id'],
			'custom_option_sku'	=> $item['custom_option_sku'],
		])->one();
		if($item_one['cart_id']){
			$item_one->qty = $item['qty'];
			$item_one->save();
			# 重新计算购物车的数量
			Yii::$service->cart->quote->computeCartInfo();
		}
	}
	
	/**
	 * 得到当前用户的购物车产品的所有个数
	 */
	public function getItemQty(){
		$cart_id = Yii::$service->cart->quote->getCartId();
		$item_qty = 0;
		if($cart_id){
			$data = MyCartItem::find()->where([
				'cart_id' => $cart_id
			])->all();
			if(is_array($data) && !empty($data)){
				foreach($data as $one){
					$item_qty += $one['qty'];
				}
			}
		}
		return $item_qty;
	}
	
	/**
	 * 得到当前购物车的产品价格信息。
	 */
	public function getCartProductInfo(){
		$cart_id = Yii::$service->cart->quote->getCartId();
		$products = [];
		$product_total = 0;
		if($cart_id){
			$data = MyCartItem::find()->where([
				'cart_id' => $cart_id
			])->all();
			if(is_array($data) && !empty($data)){
				foreach($data as $one){
					$product_id = $one['product_id'];
					$qty = $one['qty'];
					$custom_option_sku = $one['custom_option_sku'];
					$product_price = Yii::$service->product->price->getCartPriceByProductId($product_id,$qty,$custom_option_sku);
					$product_row_price = $product_price * $qty;
					$product_total += $product_row_total;
					$products[] = [
						'product_id' 		=> $product_id ,
						'qty' 				=> $qty ,
						'custom_option_sku' => $custom_option_sku ,
						'product_price' 	=> $product_price ,
						'product_row_price' => $product_row_price ,
					];
				}
				return [
					'products' 		=> $products,
					'product_total' => $product_total,
				];
			}
		}
	}
	
	
	
}