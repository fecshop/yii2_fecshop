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
	protected $_cart_product_info;
	
	/** 
	 * @property $item | Array, example:
	 * $item = [
	 *		'product_id' 		=> 22222,
	 *		'custom_option_sku' => red-xxl,
	 *		'qty' 				=> 22,
	 * ];
	 * 将某个产品加入到购物车中。在添加到cart_item表后，更新
	 * 购物车中产品的总数。
	 */
	public function addItem($item){
		$cart_id = Yii::$service->cart->quote->getCartId();
		if(!$cart_id){
			Yii::$service->cart->quote->createCart();
			$cart_id = Yii::$service->cart->quote->getCartId();
		}
		# 查看是否存在此产品，如果存在，则相加个数
		$item_one = MyCartItem::find()->where([
			'cart_id' 	=> $cart_id,
			'product_id'=> $item['product_id'],
			'custom_option_sku'	=> $item['custom_option_sku'],
		])->one();
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
	 * @property $item | Array, example:
	 * $item = [
	 *		'product_id' 		=> 22222,
	 *		'custom_option_sku' => red-xxl,
	 *		'qty' 				=> 22,
	 * ];
	 * @return boolean;
	 * 将购物车中的某个产品更改个数，更改后的个数就是上面qty的值。
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
			return true;
		}else{
			Yii::$service->helper->errors->add('This product is not available in the shopping cart');
			return false;
		}
	}
	/**
	 * 通过quoteItem表，计算得到所有产品的总数
	 * 得到购物车中产品的总数，不要使用这个函数，这个函数的作用：
	 * 在购物车中产品有变动后，使用这个函数得到产品总数，更新购物车中
	 * 的产品总数。
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
	 * @return Array ， foramt：
	 *	[
	 *		'products' 		=> $products, 				# 产品详细信息，详情参看代码中的$products。
	 *		'product_total' => $product_total, 			# 产品的当前货币总额
	 *		'base_product_total' => $base_product_total,# 产品的基础货币总额
	 *		'product_weight'=> $product_weight,			# 蟾皮的总重量、
	 *				]
	 * 得到当前购物车的产品信息，具体参看上面的example array。
	 */
	public function getCartProductInfo(){
		$cart_id = Yii::$service->cart->quote->getCartId();
		$products = [];
		$product_total = 0;
		$product_weight = 0;
		if($cart_id){
			if(!isset($this->_cart_product_info[$cart_id])){
				$data = MyCartItem::find()->where([
					'cart_id' => $cart_id
				])->all();
				if(is_array($data) && !empty($data)){
					foreach($data as $one){
						$product_id = $one['product_id'];
						$product_one = Yii::$service->product->getByPrimaryKey($product_id);
						if($product_one['_id']){
							$qty = $one['qty'];
							$custom_option_sku = $one['custom_option_sku'];
							$product_price_arr = Yii::$service->product->price->getCartPriceByProductId($product_id,$qty,$custom_option_sku,2);
							$curr_product_price= isset($product_price_arr['curr_price']) ? $product_price_arr['curr_price'] : 0;
							$base_product_price= isset($product_price_arr['base_price']) ? $product_price_arr['base_price'] : 0;
							$product_price = isset($curr_product_price['value']) ? $curr_product_price['value'] : 0;
							
							$product_row_price = $product_price * $qty;
							$product_total += $product_row_price;
							
							$base_product_row_price = $base_product_price * $qty;
							$base_product_total += $base_product_row_price;
							
							$p_wt = $product_one['weight'] * $qty;
							$product_weight += $p_wt;
							$productSpuOptions = $this->getProductSpuOptions($product_one);
							$products[] = [
								'item_id' => $one['item_id'],
								'product_id' 		=> $product_id ,
								'sku'				=> $product_one['sku'],
								'name'				=> Yii::$service->store->getStoreAttrVal($product_one['name'],'name'),
								'qty' 				=> $qty ,
								'custom_option_sku' => $custom_option_sku ,
								'product_price' 	=> $product_price ,
								'product_row_price' => $product_row_price ,
								
								'base_product_price' 	=> $base_product_price ,
								'base_product_row_price' => $base_product_row_price ,
								
								'product_name'		=> $product_one['name'],
								'product_weight'	=> $product_one['weight'],
								'product_row_weight'=> $p_wt,
								'product_url'		=> $product_one['url_key'],
								'product_image'		=> $product_one['image'],
								'custom_option'		=> $product_one['custom_option'],
								'spu_options' 			=> $productSpuOptions,  
							];
							//var_dump($product_one['image']);exit;
						}
					}
					$this->_cart_product_info[$cart_id] = [
						'products' 		=> $products,
						'product_total' => $product_total,
						'base_product_total' => $base_product_total,
						'product_weight'=> $product_weight,
					];
				}
			}
			return $this->_cart_product_info[$cart_id];
		}
	}
	/**
	 * @property $productOb | Object，类型：\fecshop\models\mongodb\Product
	 * 得到产品的spu对应的属性以及值。
	 * 概念 - spu options：当多个产品是同一个spu，但是不同的sku的时候，他们的产品表里面的
	 * spu attr 的值是不同的，譬如对应鞋子，size 和 color 就是spu attr，对于同一款鞋子，他们
	 * 是同一个spu，对于尺码，颜色不同的鞋子，是不同的sku，他们的spu attr 就是 color 和 size。
	 */
	protected function getProductSpuOptions($productOb){
		$custom_option_info_arr = [];
		if(isset($productOb['attr_group']) && !empty($productOb['attr_group'])){
			$productAttrGroup = $productOb['attr_group'];
			Yii::$service->product->addGroupAttrs($productAttrGroup);
			
			//$attrInfo = Yii::$service->product->getGroupAttrInfo($productAttrGroup);
			//if(is_array($attrInfo) && !empty($attrInfo)){
			//	$attrs = array_keys($attrInfo);
			//	\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
			//}
			$productOb = Yii::$service->product->getByPrimaryKey((string)$productOb['_id']);
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
	/**
	 * @property $item_id | Int ， quoteItem表的id
	 * @return boolean
	 * 将这个item_id对应的产品个数+1.
	 */
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
				# 重新计算购物车的数量
				Yii::$service->cart->quote->computeCartInfo();
				return true;
			}
		}
		return false;
	}
	/**
	 * @property $item_id | Int ， quoteItem表的id
	 * @return boolean
	 * 将这个item_id对应的产品个数-1.
	 */
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
					# 重新计算购物车的数量
					Yii::$service->cart->quote->computeCartInfo();
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * @property $item_id | Int ， quoteItem表的id
	 * @return boolean
	 * 将这个item_id对应的产品删除
	 */
	public function removeItem($item_id){
		$cart_id = Yii::$service->cart->quote->getCartId();
		if($cart_id){
			$one = MyCartItem::find()->where([
				'cart_id' => $cart_id,
				'item_id' => $item_id,
			])->one();
			if($one['item_id']){
				$one->delete();
				# 重新计算购物车的数量
				Yii::$service->cart->quote->computeCartInfo();
				return true;
			}
		}
		return false;
	}
	/**
	 * @property $cart_id | int 购物车id
	 * 删除购物车中的所有产品。
	 * 注意：清空购物车并不是清空所有信息，仅仅是清空用户购物车中的产品。
	 * 另外，购物车的数目更改后，需要更新cart中产品个数的信息。
	 */
	public function removeItemByCartId($cart_id=''){
		if(!$cart_id){
			$cart_id = Yii::$service->cart->quote->getCartId();
		}
		if($cart_id){
			$items = MyCartItem::deleteAll([
				'cart_id' => $cart_id,
				//'item_id' => $item_id,
			]);
			# 重新计算购物车的数量
			Yii::$service->cart->quote->computeCartInfo(0);
		}
		return true;
	}
	/**
	 * @property $new_cart_id | int 更新后的cart_id
	 * @property $cart_id | int 更新前的cart_id
	 * 删除购物车中的所有产品。
	 * 这里仅仅更改cart表的cart_id， 而不会做其他任何事情。
	 */
	public function updateCartId($new_cart_id,$cart_id){
		if($cart_id && $new_cart_id){
			MyCartItem::updateAll(
				['cart_id'=>$new_cart_id],  # $attributes
				'cart_id = '.$cart_id       # $condition
			);
			# 重新计算购物车的数量
			//Yii::$service->cart->quote->computeCartInfo();
			return true;
		}
		return false;
	}
	
	
}
