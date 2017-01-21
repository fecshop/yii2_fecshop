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
use fecshop\models\mysqldb\Cart\Item as MyCartItem;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends Service
{
	
	public  function validateProduct($item,$product){
		$qty 				= $item['qty'];
		$product_id 		= $item['product_id'];
		# 验证提交产品数据
		# 验证产品是否存在
		if(!$product['sku']){
			Yii::$service->helper->errors->add('this product is not exist');
			return false;
		}
		# 验证库存 是否库存满足？
		$canSale = Yii::$service->product->info->productIsCanSale($product,$qty);
		if(!$canSale){
			return false;
		}
		# 验证产品是否
		if($product['status'] != 1){
			Yii::$service->helper->errors->add('product is not active');
			return false;
		}
		return true;
	}
	
	public function getCustomOptionSku($item,$product){
		$qty 				= $item['qty'];
		$custom_option_sku 	= $item['custom_option_sku'];
		$product_id 		= $item['product_id'];
		
		$co_sku = '';
		if($custom_option_sku){
			$product_custom_option = $product['custom_option'];
			$co_sku = Yii::$service->product->info->getProductCOSku($custom_option_sku,$product_custom_option);
			
			if($co_sku){
				return $co_sku;
			}
		}
		
		
	}
	
	
	public function productIsCanSale($product,$qty){
		if($product['is_in_stock'] != 1){
			Yii::$service->helper->errors->add('this product is stock out');
			return false;
		}
		if($qty > $product['qty']){
			Yii::$service->helper->errors->add('product qty add to cart is gt  product stock count');
			return false;
		}
		
		
	}
	
	
	
	
}