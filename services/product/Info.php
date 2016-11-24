<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use fecshop\services\Service;
use fec\helpers\CDate;
use fec\helpers\CUser;
use fecshop\models\mongodb\Product;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends Service
{
	/**
	 * @property $custome_option | Array
	 * $custome_option = [
		"my_color" 	=> "red",
		"my_size" 	=> "M",
		"my_size2" 	=> "M2",
		"my_size3" 	=> "L3"
	 ]
	 * 通过custom的各个值，生成custom option sku
	 */
	public function getCustomOptionSkuByValue($custome_option){
		$str = '';
		$arr = [];
		if(is_array($custome_option) && !empty($custome_option)){
			foreach($custome_option as $k=>$v){
				$arr[] = str_replace(' ','*',$v);
			}
		}
		return implode('-',$arr);
	}
	/**
	 * @property $custom_option | Array 前台传递的custom option 一维数组。
	 * @property $product_custom_option | Array  数据库中存储的产品custom_option的值
	 * 验证前台传递的custom option 是否正确。
	 */
	public function validateProductCustomOption($custom_option,$product_custom_option){
		if(empty($product_custom_option) && empty($custom_option)){
			return true; # 都为空，说明不需要验证。
		}
		if($custom_option){
			$co_sku = $this->getCustomOptionSkuByValue($custom_option)；
			//$product_custom_option = $product['custom_option'];
			if(!is_array($product_custom_option)){
				Yii::$service->helper->errors->add('this product custom option is error');
				return;
			}
			foreach($product_custom_option as $p_sku => $option){
				if($p_sku == $co_sku){
					return true;
				}
			}
		}
		Yii::$service->helper->errors->add('this product custom option can not find in this product');
		return false;		
	}
	
	/**
	 * @property $product | Object  产品对象
	 * @property $sale_qty| 想要购买的个数
	 * 验证当前产品，是否是可以出售的。
	 */
	public static function productIsCanSale($product,$sale_qty){
		$is_in_stock = $product['is_in_stock'];
		$qty 		= $product['qty'];
		if($is_in_stock == 1){
			if($qty >= $sale_qty){
				return true;
			}
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
 
}