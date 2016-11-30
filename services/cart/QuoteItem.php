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
					//var_dump($one['product_id']);
					$product_id = $one['product_id'];
					$product_one = Yii::$service->product->getByPrimaryKey($product_id);
					if($product_one['_id']){
						$qty = $one['qty'];
						$custom_option_sku = $one['custom_option_sku'];
						$product_price = Yii::$service->product->price->getCartPriceByProductId($product_id,$qty,$custom_option_sku);
						
						$product_price = isset($product_price['value']) ? $product_price['value'] : 0;
						$product_row_price = $product_price * $qty;
						$product_total += $product_row_price;
						$productSpuOptions = $this->getProductSpuOptions($product_one);
						$products[] = [
							'item_id' => $one['item_id'],
							'product_id' 		=> $product_id ,
							'qty' 				=> $qty ,
							'custom_option_sku' => $custom_option_sku ,
							'product_price' 	=> $product_price ,
							'product_row_price' => $product_row_price ,
							'product_name'		=> $product_one['name'],
							'product_url'		=> $product_one['url_key'],
							'product_image'		=> $product_one['image'],
							'custom_option'		=> $product_one['custom_option'],
							'spu_options' 			=> $productSpuOptions,  
						];
					}
				}
				//var_dump($product_total);  
				return [
					'products' 		=> $products,
					'product_total' => $product_total,
				];
			}
		}
	}
	/**
	 * 得到产品的spu对应的属性以及值。
	 */
	protected function getProductSpuOptions($productOb){
		$custom_option_info_arr = [];
		if(isset($productOb['attr_group']) && !empty($productOb['attr_group'])){
			$productAttrGroup = $productOb['attr_group'];
			$attrInfo = Yii::$service->product->getGroupAttrInfo($productAttrGroup);
			if(is_array($attrInfo) && !empty($attrInfo)){
				$attrs = array_keys($attrInfo);
				\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
			}
			$productOb = Yii::$service->product->getByPrimaryKey($productOb['_id']->{'$id'});
			$spuArr = Yii::$service->product->getSpuAttr($productAttrGroup);
			if(is_array($spuArr) && !empty($spuArr)){
				foreach($spuArr as $spu_attr){
					if(isset($productOb[$spu_attr]) && !empty($productOb[$spu_attr])){
						$custom_option_info_arr[$spu_attr] = $productOb[$spu_attr];
					}
				}
			}
		}
		return $custom_option_info_arr ;
	}
	
	public function addOneItem($item_id){
		$cart_id = Yii::$service->cart->quote->getCartId();
		if($cart_id){
			$one = MyCartItem::find()->where([
				'cart_id' => $cart_id,
				'item_id' => $item_id,
			])->one();
			if($one['item_id']){
				$one['qty'] = $one['qty'] + 1;
				$one->save();
				return true;
			}
		}
		return false;
	}
	
	
	public function lessOneItem($item_id){
		$cart_id = Yii::$service->cart->quote->getCartId();
		if($cart_id){
			
			$one = MyCartItem::find()->where([
				'cart_id' => $cart_id,
				'item_id' => $item_id,
			])->one();
			if($one['item_id']){
				
				if($one['qty'] > 1){
					$one['qty'] = $one['qty'] - 1;
					$one->save();
					
					return true;
				}
			}
		}
		return false;
	}
	
	public function removeItem($item_id){
		$cart_id = Yii::$service->cart->quote->getCartId();
		if($cart_id){
			$one = MyCartItem::find()->where([
				'cart_id' => $cart_id,
				'item_id' => $item_id,
			])->one();
			if($one['item_id']){
				$one->delete();
				return true;
			}
		}
		return false;
	}
	
	
}