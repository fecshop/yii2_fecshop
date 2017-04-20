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
	
	//public function init(){
		# 得到当前service相应的model 
		//$this->modelClass = Yii::$service->product->getByPrimaryKey();
	//	parent::init();
	//}
	/**
	 * 得到整体列表
	 */
	public function actionCustomindex(){
		$page = Yii::$app->request->get('page');
		$filter = [
	  		'numPerPage' 	=> $this->numPerPage,  	
	  		'pageNum'		=> $page,
			'asArray' => true,
		];
		$data 	= Yii::$service->product->coll($filter);
		$coll 	= $data['coll'];
		$count 	= $data['count'];
		
		
		$page = $page ? $page : 1;
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
	
	public function actionCustomcreate(){
		return 'custom_create';
	}
	
	public function actionCustomupdate($product_id){
		return 'custom_update:'.$product_id;
	}
	
	public function actionCustomdelete($product_id){
		return 'custom_delete:'.$product_id;
	}
	
	
	
	
}