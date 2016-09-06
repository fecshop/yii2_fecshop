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
	/**
	 * 得到分类的侧栏过滤信息
	 */
	 /*
	 [
		['name' => 'xxx','url_key'=>'xxx'],
		[
			'name' => 'xxx',
			'url_key'=>'xxx',
			'child' => [
				['name' => 'xxx','url_key'=>'xxx'],
				['name' => 'xxx','url_key'=>'xxx'],
				[
					'name' => 'xxx',
					'url_key'=>'xxx',
					'current' => true,
					'child' => [
						['name' => 'xxx','url_key'=>'xxx'],
						['name' => 'xxx','url_key'=>'xxx'],
						['name' => 'xxx','url_key'=>'xxx'],
					]
				],
			]
		],
		['name' => 'xxx','url_key'=>'xxx'],
		['name' => 'xxx','url_key'=>'xxx'],
	 ]
	 
	
	
	 
	 */
	public function getParentCategory($parent_id){
		if($parent_id === '0'){
			return [];
		}
		$category = Category::find()->asArray()->where(['_id' => new \mongoId($parent_id)])->one();
		if(isset($category['_id']) && !empty($category['_id']) ){
			$currentUrlKey 		= $category['url_key'];
			$currentName 		= $category['name'];
			$currentId			= $category['_id']->{'$id'};
			
			$currentCategory[] = [
				'_id' 		=> $currentId,
				'name' 		=> $currentName,
				'url_key'	=> $currentUrlKey,
				'parent_id'	=> $category['parent_id'],
			];
			$parentCategory = $this->getParentCategory($category['parent_id']);
			
			return array_merge($parentCategory,$currentCategory);
			
		}else{
			return [];
		}
	}
	
	public function getFilterCategory($category_id,$parent_id){
		
		# 1.如果level为一级，那么title部分为当前的分类，子分类位一级分类下的二级分类
		
		# 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，列出来子分类
		# 3.如果level为三级，那么将所有的二级分类列出。
		# 当前二级分类的所有子分类列出，当前三级分类如果有子分类，则列出
		//echo $category_id.'##';
		//echo $parent_id;
		$returnData = [];
		$primaryKey 		= $this->getPrimaryKey();
		
		$currentCategory 	= Category::findOne($category_id);
		
		$currentUrlKey 		= $currentCategory['url_key'];
		$currentName 		= $currentCategory['name'];
		$currentId			= $currentCategory['_id']->{'$id'};
		
		//var_dump($currentCategory);exit;
		
		
		$returnData['current'] = [
			'_id' 		=> $currentId,
			'name' 		=> $currentName,
			'url_key'	=> $currentUrlKey,
			'parent_id'	=> $currentCategory['parent_id'],
		];
		//echo $currentCategory['parent_id'];
		//exit;
		if($currentCategory['parent_id']){
			$allParent = $this->getParentCategory($currentCategory['parent_id']);
			$allParent[] = $returnData['current'];
			$data = $this->getAllParentCate($allParent);
		}else{
			// 点击的是一级分类的时候
			$data = $this->getOneLevelCateChild($returnData['current']);
		}
		//$data = $this->getChildCate($currentId);
		//var_dump($data);exit;
		return $data;
		
	}
	
	public function getOneLevelCateChild($category){
		//'_id' 		=> $currentId,
		//'name' 		=> $currentName,
		//'url_key'	=> $currentUrlKey,
		$data[0] = $category;
		$_id = $category['_id'];
		$name = $category['name'];
		$url_key = $category['url_key'];
		$cate = Category::find()->asArray()->where([
			'parent_id' => $_id,
		])->all();
		if(is_array($cate) && !empty($cate)){
			foreach($cate as $one){
				$c_id = $one['_id']->{'$id'};
				$data[0]['child'][$c_id] = [
					'name' 		=> $one['name'],
					'url_key'	=> $one['url_key'],
					'parent_id'	=> $one['parent_id'],
				];
			}
		}
		return $data;
	}
	
	
	public function getAllParentCate($allParent){
		//var_dump($allParent);exit;
		$d = $allParent;
		$data = [];
		if(is_array($allParent) && !empty($allParent)){
			foreach($allParent as $k => $category){
				unset($d[$k]);
				$category_id = $category['_id'];
				$parent_id  = $category['parent_id'];
				if($parent_id){
					$cate = Category::find()->asArray()->where([
						'parent_id' => $parent_id,
					])->all();
					//var_dump($cate);
					//echo '$$$$$$$$$$';
					if(is_array($cate) && !empty($cate)){
						//echo '**********';
						foreach($cate as $one){
							$c_id = $one['_id']->{'$id'};
							$data[$c_id] = [
								'name' 		=> $one['name'],
								'url_key'	=> $one['url_key'],
								'parent_id'	=> $one['parent_id'],
							];
							//echo $category_id;
							//echo '&&&'.$c_id;
							if(($c_id == $category_id) && !empty($d)){
								$data[$c_id]['child'] = $this->getAllParentCate($d);
							}
							if(($c_id == $category_id) && empty($d)){
								$child_cate = $this->getChildCate($c_id);
								$data[$c_id]['current'] = true;
								if(!empty($child_cate)){
									$data[$c_id]['child'] = $child_cate;
								}
							}
						}
					}
					break;
				}
			}
		}
		return $data;
	}
	
	public function getChildCate($category_id){
		//echo $category_id;
		$data = Category::find()->asArray()->where([
						'parent_id' => $category_id,
					])->all();
		$arr = [];
		if(is_array($data) && !empty($data)){
			foreach($data as $one){
				$currentUrlKey 		= $one['url_key'];
				$currentName 		= $one['name'];
				$currentId			= $one['_id']->{'$id'};
				
				$arr[$currentId] = [
					//'_id' 		=> $currentId,
					'name' 		=> $currentName,
					'url_key'	=> $currentUrlKey,
					'parent_id'	=> $one['parent_id'],
				];
			}
		}
		return $arr;
	}
	
	
}


