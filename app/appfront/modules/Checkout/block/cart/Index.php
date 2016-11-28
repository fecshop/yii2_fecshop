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
		return [
			
		];
	}
	
	public function getCartInfo(){
		
		# 得到单个产品的价格
		//Yii::$service->product->price->getCartPriceByProductId($productId,$qty,$custom_option_sku);
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
















