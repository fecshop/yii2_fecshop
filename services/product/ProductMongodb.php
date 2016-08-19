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
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\Product;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductMongodb implements ProductInterface
{
	public $numPerPage = 20;
	
	public function getPrimaryKey(){
		return '_id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			return Product::findOne($primaryKey);
		}else{
			return new Product;
		}
	}
	/*
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
	public function coll($filter=''){
		$query = Product::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
	}
	
	/**
	 * @property $one|Array
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one,$originUrlKey){
		//var_dump($one);exit;
		if(!$this->initSave($one)){
			return;
		}
		$currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		
		if($primaryVal){
			
			$model = Product::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('Product '.$this->getPrimaryKey().' is not exist');
				return;
			}	
			#验证sku 是否重复
			$product_one = Product::find()->asArray()->where([
				'<>',$this->getPrimaryKey(),(new \MongoId($primaryVal))
			])->andWhere([
				'sku' => $one['sku'],
			])->one();
			if($product_one['sku']){
				Yii::$service->helper->errors->add('Product Sku 已经存在，请使用其他的sku');
				return;
			}
		}else{
			$model = new Product;
			$model->created_at = time();
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
			$primaryVal = new \MongoId;
			$model->{$this->getPrimaryKey()} = $primaryVal;
			#验证sku 是否重复
			$product_one = Product::find()->asArray()->where([
				'sku' => $one['sku'],
			])->one();
			if($product_one['sku']){
				Yii::$service->helper->errors->add('Product Sku 已经存在，请使用其他的sku');
				return;
			}
		}
		$model->updated_at = time();
		unset($one['_id']);
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		$originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
		$originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
		$defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'],'name');
		$urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle,$originUrl,$originUrlKey);
		$model->url_key = $urlKey;
		$model->save();
		return true;
	}
	
	protected function initSave($one){
		if(!isset($one['sku']) || empty($one['sku'])){
			Yii::$service->helper->errors->add(' sku 必须存在 ');
			return false;
		}
		if(!isset($one['spu']) || empty($one['spu'])){
			Yii::$service->helper->errors->add(' spu 必须存在 ');
			return false;
		}
		$defaultLangName = \Yii::$service->fecshoplang->getDefaultLangAttrName('name'); 
		if(!isset($one['name'][$defaultLangName]) || empty($one['name'][$defaultLangName])){
			Yii::$service->helper->errors->add(' name '.$defaultLangName.' 不能为空 ');
			return false;
		}
		$defaultLangDes = \Yii::$service->fecshoplang->getDefaultLangAttrName('description');
		if(!isset($one['description'][$defaultLangDes]) || empty($one['description'][$defaultLangDes])){
			Yii::$service->helper->errors->add(' description '.$defaultLangDes.'不能为空 ');
			return false;
		}
		return true;
	}
	
	/**
	 * remove Product
	 */ 
	public function remove($ids){
		if(empty($ids)){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids)){
			foreach($ids as $id){
				$model = Product::findOne($id);
				if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
					$url_key =  $model['url_key'];
					Yii::$service->url->removeRewriteUrlKey($url_key);
					$model->delete();
					//$this->removeChildCate($id);
				}else{
					Yii::$service->helper->errors->add("Product Remove Errors:ID:$id is not exist.");
					return false;
				}
			}
		}else{
			$id = $ids;
			$model = Product::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				$url_key =  $model['url_key'];
				Yii::$service->url->removeRewriteUrlKey($url_key);
				$model->delete();
				//$this->removeChildCate($id);
			}else{
				Yii::$service->helper->errors->add("Product Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
	}
	
	
	
}


