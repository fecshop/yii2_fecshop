<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */
namespace fecshop\app\appfront\modules\cms\block\home;
use Yii;
use fec\helpers\CModule;

class Index {
	
	
	public function getLastData(){
		$this->initHead();
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		return [
			'bestSellerProducts' => $this->getBestSellerProduct(),
			'bestFeaturedProducts'	 => $this->getFeaturedProduct(),
		];
		
	}
	public function getFeaturedProduct(){
		$featured_skus = Yii::$app->controller->module->params['homeFeaturedSku'];
		return $this->getProductBySkus($featured_skus);
	}
	
	public function getBestSellerProduct(){
		$best_skus = Yii::$app->controller->module->params['homeBestSellerSku'];
		return $this->getProductBySkus($best_skus);
	}
	
	public function getProductBySkus($skus){
		
		if(is_array($skus) && !empty($skus)){
			$filter['select'] = [
				'sku','spu','name','image',
				'price','special_price',
				'special_from','special_to',
				'url_key','score',
			];
			$filter['where'] = ['in','sku',$skus];
			$products = Yii::$service->product->getProducts($filter);
			//var_dump($products);
			$products = Yii::$service->category->product->convertToCategoryInfo($products);
			return $products;
		}
		
	}
	
	
	public function initHead(){
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => CModule::param('homeMetaKeywords')
		]);
		
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => CModule::param('homeMetaDescription')
		]);
		Yii::$app->view->title = CModule::param('homeTitle');
		
	}
	
}
















