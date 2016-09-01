<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\category;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\Category;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryMongodb implements CategoryInterface
{
	public $numPerPage = 20;
	
	public function getPrimaryKey(){
		return '_id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			return Category::findOne($primaryKey);
		}else{
			return new Category;
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
		$query = Category::find();
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
		$currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		if($primaryVal){
			$model = Category::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('Category '.$this->getPrimaryKey().' is not exist');
				return;
			}
			$parent_id 		= $model['parent_id'];
		}else{
			$model = new Category;
			$model->created_at = time();
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
			$primaryVal = new \MongoId;
			$model->{$this->getPrimaryKey()} = $primaryVal;
			$parent_id = $one['parent_id'];
		}
		if($parent_id === '0'){
			$model['level'] = 1;
		}else{
			$parent_model 	= Category::findOne($parent_id);
			if($parent_level = $parent_model['level']){
				$model['level'] = $parent_level + 1;
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
	
	
	/**
	 * remove Category
	 */ 
	public function remove($id){
		if(!$id){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		
		$model = Category::findOne($id);
		if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
			$url_key =  $model['url_key'];
			Yii::$service->url->removeRewriteUrlKey($url_key);
			$model->delete();
			$this->removeChildCate($id);
		}else{
			Yii::$service->helper->errors->add("Category Remove Errors:ID:$id is not exist.");
			return false;
		}
		
		return true;
	}
	
	protected function removeChildCate($id){
		$data = Category::find()->where(['parent_id'=>$id])->all();
		if(!empty($data)){
			foreach($data as $one){
				$idVal = $one['_id']->{'$id'};
				if($this->hasChildCategory($idVal)){
					$this->removeChildCate($idVal);
				}
				$url_key =  $one['url_key'];
				Yii::$service->url->removeRewriteUrlKey($url_key);
				$one->delete();
			}
		}
	}
	
	public function getTreeArr($rootCategoryId = '',$lang=''){
		$arr = [];
		if(!$lang){
			$lang = Yii::$service->fecshoplang->defaultLangCode;
		}
		if(!$rootCategoryId){
			$where = ['parent_id' => '0'];
		}else{
			$where = ['parent_id' => $rootCategoryId];
		}
		$categorys =  Category::find()->asArray()->where($where)->all();
		//var_dump($categorys);exit;
		$idKey= $this->getPrimaryKey();
		if(!empty($categorys)){
			foreach($categorys as $cate){
				$idVal = $cate[$idKey]->{'$id'};
				$arr[$idVal] = [
					$idKey 	=> $idVal,
					'name' 	=> Yii::$service->fecshoplang->getLangAttrVal($cate['name'],'name',$lang),
				];
				//echo $arr[$idVal]['name'];
				
				if($this->hasChildCategory($idVal)){
					$arr[$idVal]['child'] = $this->getTreeArr($idVal,$lang);
				}
			}
		}
		return $arr;
	}
	protected function hasChildCategory($idVal){
		$one = Category::find()->asArray()->where(['parent_id'=>$idVal])->one();
		if(!empty($one)){
			return true;
		}
		return false;
	}
	
	
	public function getAllParentInfo($parent_id){
		if($parent_id){
			$parentModel = Category::findOne($parent_id);
			$parent_parent_id = $parentModel['parent_id'];
			$parent_category = [];
			if($parent_parent_id !== '0'){
				$parent_category[] = [
					'name' => $parentModel['name'],
					'url_key'=>$parentModel['url_key'],
				];
				$parent_category = array_merge($this->getAllParentInfo($parent_parent_id),$parent_category);
			}else{
				$parent_category[] = [
					'name' => $parentModel['name'],
					'url_key'=>$parentModel['url_key'],
				];
			}
			return $parent_category;
		}
	}
	
}


