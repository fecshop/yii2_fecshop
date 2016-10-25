<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\reviewproduct;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Add {
	
	
	public function getLastData(){
		$_id = Yii::$app->request->get('_id');
		if(!$_id){
			Yii::$service->page->message->addError('product _id  is empty');
			return [];
		}
		$product = Yii::$service->product->getByPrimaryKey($_id);
		if(!$product['spu']){
			Yii::$service->page->message->addError('product _id:'.$_id.'  is not exist in product collection');
			return [];
		}
		
		$price_info 	= $this->getProductPriceInfo($product);
		$spu 			= $product['spu'];
		$image 			= $product['image'];
		$main_img 		= isset($image['main']['image']) ? $image['main']['image'] : '';
		$url_key 		= $product['url_key'];
		$name 			= Yii::$service->store->getStoreAttrVal($product['name'],'name');
		return [
			'name' 			=> $name,
			'spu' 			=> $spu,
			'price_info' 	=> $price_info,
			'main_img' 		=> $main_img,
			'url'		=> Yii::$service->url->getUrl($url_key),
		];
	}
	
	protected function getProductPriceInfo($product){
		$price			= $product['price'];
		$special_price	= $product['special_price'];
		$special_from	= $product['special_from'];
		$special_to		= $product['special_to'];
		return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price,$special_price,$special_from,$special_to);
		
	}
	
	
	protected function getSpuData(){
		$spu	= $this->_product['spu'];
		$filter = [
	  		'select' 	=> ['size',],  	
	   		'where'			=> [
	 			['spu' => $spu],
	  		],
			'asArray' => true,
		];
		$coll = Yii::$service->product->coll($filter);
		if(is_array($coll['coll']) && !empty($coll['coll'])){
			foreach($coll['coll'] as $one){
				$spu = $one['spu'];
				
			}
		}
		
	}
	
	
	
	
	
	
	
	
}
