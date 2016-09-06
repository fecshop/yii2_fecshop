<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\category\CategoryMysqldb;
use fecshop\services\category\CategoryMongodb;
/**
 * Category Service is the component that you can get category info from it.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends Service
{
	
	public $storage = 'mongodb';
	protected $_category;
	
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_category = new CategoryMongodb;
		//}else if($this->storage == 'mysqldb'){
			//$this->_category = new CategoryMysqldb;
		}
	}
	/**
	 * Get Url by article's url key.
	 */
	//public function getUrlByPath($urlPath){
		//return Yii::$service->url->getHttpBaseUrl().'/'.$urlKey;
		//return Yii::$service->url->getUrlByPath($urlPath);
	//}
	/**
	 * get artile's primary key.
	 */
	protected function actionGetPrimaryKey(){
		return $this->_category->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		return $this->_category->getByPrimaryKey($primaryKey);
	}
	
	
	
	/**
	 * @property $filter|Array
	 * get artile collection by $filter
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		'where'			=> [
	 * 			'price' => [
	 * 				'?gt' => 1,
	 * 				'?lt' => 10,
	 * 			],
	 * 			'sku' => 'uk10001',
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	protected function actionColl($filter=''){
		return $this->_category->coll($filter);
	}
	/**
	 *  用于后台分类树编辑。
	 */
	protected function actionGetTreeArr($rootCategoryId=0){
		return $this->_category->getTreeArr($rootCategoryId);
	}
	
	/**
	 * @property $one|Array , save one data .
	 * @property $originUrlKey|String , article origin url key.
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	protected function actionSave($one,$originUrlKey){
		return $this->_category->save($one,$originUrlKey);
	}
	
	protected function actionRemove($ids){
		return $this->_category->remove($ids);
	}
	
	protected function actionGetAllParentInfo($parent_id){
		return $this->_category->getAllParentInfo($parent_id);
	}
	
	
	protected function actionGetFilterCategory($category_id,$parent_id){
		return $this->_category->getFilterCategory($category_id,$parent_id);
	}
	
	
	
	
	
}