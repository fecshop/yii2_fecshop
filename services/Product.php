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
use fecshop\services\product\ProductMysqldb;
use fecshop\services\product\ProductMongodb;
/**
 * Product Service is the component that you can get product info from it.
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
	
	public $storage = 'mongodb';
	public $customAttrGroup;
	protected $_product;
	protected $_defaultAttrGroup = 'default';
	
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_product = new ProductMongodb;
		//}else if($this->storage == 'mysqldb'){
			//$this->_category = new CategoryMysqldb;
		}
	}
	# Yii::$service->product->getCustomAttrGroup();
	protected function actionGetCustomAttrGroup(){
		$customAttrGroup = $this->customAttrGroup;
		$arr = array_keys($customAttrGroup);
		$arr[] = $this->_defaultAttrGroup;
		return $arr;
	}
	/**
	 * @property $productAttrGroup|String
	 * return product attrGroup attributes.
	 */
	protected function actionGetGroupAttrInfo($productAttrGroup){
		if($productAttrGroup == $this->_defaultAttrGroup){
			return [];
		}else if(isset($this->customAttrGroup[$productAttrGroup])){
			return isset($this->customAttrGroup[$productAttrGroup]) ? $this->customAttrGroup[$productAttrGroup] : [];
		}
	}
	
	
	protected function actionGetDefaultAttrGroup(){
		return $this->_defaultAttrGroup;
	}
	
	
	protected function actionGetPrimaryKey(){
		return $this->_product->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		return $this->_product->getByPrimaryKey($primaryKey);
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
		return $this->_product->coll($filter);
	}
	
	protected function actionGetTreeArr($rootCategoryId=0){
		return $this->_product->getTreeArr($rootCategoryId);
	}
	
	
	protected function actionGetCategoryProductIds($product_id_arr,$category_id){
		return $this->_product->getCategoryProductIds($product_id_arr,$category_id);
	}
	/**
	 * @property $one|Array , save one data .
	 * @property $originUrlKey|String , article origin url key.
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	protected function actionSave($one,$originUrlKey){
		return $this->_product->save($one,$originUrlKey);
	}
	
	protected function actionRemove($ids){
		return $this->_product->remove($ids);
	}
	
	protected function actionAddAndDeleteProductCategory($category_id,$addCateProductIdArr,$deleteCateProductIdArr){
		return $this->_product->addAndDeleteProductCategory($category_id,$addCateProductIdArr,$deleteCateProductIdArr);
	}
	
}


