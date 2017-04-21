<?php

namespace fecshop\app\appapi\modules\V1\controllers;
use Yii;
use yii\web\Response;
use fecshop\app\appapi\modules\AppapiController;
use yii\data\Pagination;
class ProductController extends AppapiController
{
	
	public $modelClass = 'PRODUCT';
	
	public $numPerPage = 20;
	
	
	/**
	 * 得到产品的数据list
	 */
	# http://fecshop.appapi.fancyecommerce.com/v1/products?page=2&access-token=1Gk6ZNn-QaBaKFI4uE2bSw0w3N7ej72q
	# http://fecshop.appapi.fancyecommerce.com/v1/products?page=2
	public function actionCustomindex(){
		$page = Yii::$app->request->get('page');
		$page = $page ? $page : 1;
		$filter = [
	  		'numPerPage' 	=> $this->numPerPage,  	
	  		'pageNum'		=> $page,
			'asArray' => true,
		];
		$data 	= Yii::$service->product->coll($filter);
		$coll 	= $data['coll'];
		$count 	= $data['count'];
		
		$pageCount = ceil($count/$this->numPerPage);
		$serializer = new \yii\rest\Serializer;
		Yii::$app->response->getHeaders()
            ->set($serializer->totalCountHeader, $count)
            ->set($serializer->pageCountHeader, $pageCount)
            ->set($serializer->currentPageHeader, $page)
            ->set($serializer->perPageHeader, $this->numPerPage)
            ;
		return $coll;
		
		#{"_id":{"$oid":"57bab0d5f656f2940a3bf56e"}," 需要处理
	}
	/**
	 * 得到单个产品
	 */
	public function actionCustomview($product_id){
		$product = Yii::$service->product->getByPrimaryKey($product_id);
		return $product;
		//product_id
	}
	/**
	 * 创建产品
	 */
	public function actionCustomcreate(){
		return 'custom_create';
	}
	/**
	 * 更新产品
	 */
	public function actionCustomupdate($product_id){
		return 'custom_update:'.$product_id;
	}
	/**
	 * 删除产品
	 */
	public function actionCustomdelete($product_id){
		//$product = Yii::$service->product->getByPrimaryKey($product_id);
		//$product->delete();
	}
	
	
	
	
}