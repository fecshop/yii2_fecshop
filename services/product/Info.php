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
use fecshop\services\ChildService;
use fec\helpers\CDate;
use fec\helpers\CUser;
use fecshop\models\mongodb\Product;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends ChildService
{
	private $_product;
	
	
	/**
	 * Get product info by special  $productId.
	 * product language attributes will be set current language value.
	 * product price will be process to current price.
	 * image will return full image url
	 * this function will be use for front product info page.
	 */
	public function getProduct($productId='',$selectAttr=[])
	{
		//echo 33;exit;
		if(!$this->_product){
			
			if(!$productId){
				$productId = Yii::$app->product->getCurrentProductId();
			}
			if(!$productId){
				throw new InvalidValueException('productId is empty,you must pass a ProductId');
			}
			$product = Product::findOne([
				'_id' => (int)$productId
			]);
			if($product_id){
				$product->
				$this->_product = $product;
			}
		}
		return $this->_product;
		
		
	
	}
	
	/**
	 *  @property $product is object.
	 *	convert product language attribute to current language value.
	 */
	public function getCurrentLangProduct($product){
		$lang_attrs = $this->getLangAttr();
		foreach($lang_attrs as $attr){
			$product->$attr = Yii::$app->store->getLangVal($product->$attr,$attr);
		}
	}
	
	/**
	 *	product language attributes array.
	 */
	public function getLangAttr(){
		return [
			'name',
			'title',
			'meta_keywords',
			'meta_description',
			'short_description',
			'description',
		];
	}
 
}