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
	/**
	 * 返回主键。
	 */
	public function getPrimaryKey(){
		return '_id';
	}
	/**
	 * 通过主键，得到Category对象。
	 */
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
	 *			['>','price','1'],
	 *			['<','price','10'],
	 * 			['sku' => 'uk10001'],
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
	 *  得到总数
	 */
	public function collCount($filter=''){
		$query = Category::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return $query->count();
	}
	
	/**
	 * @property $one|Array , save one data . 分类数组
	 * @property $originUrlKey|String , 分类的在修改之前的url key.（在数据库中保存的url_key字段，如果没有则为空）
	 * 保存分类，同时生成分类的伪静态url（自定义url），如果按照name生成的url或者自定义的urlkey存在，系统则会增加几个随机数字字符串，来增加唯一性。                
	 */
	public function save($one,$originUrlKey='catalog/category/index'){
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
			$primaryVal = new \MongoDB\BSON\ObjectId();
			$model->{$this->getPrimaryKey()} = $primaryVal;
			$parent_id = $one['parent_id'];
		}
		# 增加分类的级别字段level，从1级级别开始依次类推。
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
	 * @property $id | String  主键值
	 * 通过主键值找到分类，并且删除分类在url rewrite表中的记录
	 * 查看这个分类是否存在子分类，如果存在子分类，则删除所有的子分类，以及子分类在url rewrite表中对应的数据。
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
				$idVal = (string)$one['_id'];
				if($this->hasChildCategory($idVal)){
					$this->removeChildCate($idVal);
				}
				$url_key =  $one['url_key'];
				Yii::$service->url->removeRewriteUrlKey($url_key);
				$one->delete();
			}
		}
	}
	/**
	 *  得到分类的树数组。
	 *  数组中只有  id  name(default language), child(子分类) 等数据。
	 *  目前此函数仅仅用于后台对分类的编辑使用。 appadmin 
	 */
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
				$idVal = (string)$cate[$idKey];
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
	/**
	 * @property $parent_id|String 
	 * 通过当前分类的parent_id字段（当前分类的上级分类id），得到所有的上级信息数组。
	 * 里面包含的信息为：name，url_key。
	 * 譬如一个分类为三级分类，将他的parent_id传递给这个函数，那么，他返回的数组信息为[一级分类的信息（name，url_key），二级分类的信息（name，url_key）].
	 * 目前这个功能用于前端分类页面的面包屑导航。
	 */
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
	
	protected function getParentCategory($parent_id){
		if($parent_id === '0'){
			return [];
		}
		$category = Category::find()->asArray()->where(['_id' => new \MongoDB\BSON\ObjectId($parent_id)])->one();
		if(isset($category['_id']) && !empty($category['_id']) ){
			$currentUrlKey 		= $category['url_key'];
			$currentName 		= $category['name'];
			$currentId			= (string)$category['_id'];
			
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
	/**
	 * @property $category_id|String  当前的分类_id 
	 * @property $parent_id|String  当前的分类上级id parent_id 
	 * 这个功能是点击分类后，在产品分类页面侧栏的子分类菜单导航，详细的逻辑如下：
	 * 1.如果level为一级，那么title部分为当前的分类，子分类为一级分类下的二级分类
	 * 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，会列出来当前二级分类对应的子分类
	 * 3.如果level为三级，那么将所有的二级分类列出。当前三级分类的所有姊妹分类（同一个父类）列出，当前三级分类如果有子分类，则列出
	 * 4.依次递归。
	 * 具体的显示效果，请查看appfront 对应的分类页面。
	 */
	public function getFilterCategory($category_id,$parent_id){
		$returnData = [];
		$primaryKey 		= $this->getPrimaryKey();
		$currentCategory 	= Category::findOne($category_id);
		$currentUrlKey 		= $currentCategory['url_key'];
		$currentName 		= $currentCategory['name'];
		$currentId			= (string)$currentCategory['_id'];
		$returnData['current'] = [
			'_id' 		=> $currentId,
			'name' 		=> $currentName,
			'url_key'	=> $currentUrlKey,
			'parent_id'	=> $currentCategory['parent_id'],
		];
		if($currentCategory['parent_id']){
			$allParent = $this->getParentCategory($currentCategory['parent_id']);
			$allParent[] = $returnData['current'];
			$data = $this->getAllParentCate($allParent);
		}else{
			$data = $this->getOneLevelCateChild($returnData['current']);
		}
		return $data;
	}
	
	protected function getOneLevelCateChild($category){
		//'_id' 		=> $currentId,
		//'name' 		=> $currentName,
		//'url_key'	=> $currentUrlKey,
		//$category['current'] = true;
		//$data[0] = $category;
		$_id = $category['_id'];
		$name = $category['name'];
		$url_key = $category['url_key'];
		$cate = Category::find()->asArray()->where([
			'parent_id' => $_id,
		])->all();
		if(is_array($cate) && !empty($cate)){
			foreach($cate as $one){
				$c_id = (string)$one['_id'];
				$data[$c_id] = [
					'name' 		=> $one['name'],
					'url_key'	=> $one['url_key'],
					'parent_id'	=> $one['parent_id'],
				];
			}
		}
		return $data;
	}
	
	
	protected function getAllParentCate($allParent){
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
							$c_id = (string)$one['_id'];
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
	
	protected function getChildCate($category_id){
		//echo $category_id;
		$data = Category::find()->asArray()->where([
						'parent_id' => $category_id,
					])->all();
		$arr = [];
		if(is_array($data) && !empty($data)){
			foreach($data as $one){
				$currentUrlKey 		= $one['url_key'];
				$currentName 		= $one['name'];
				$currentId			= (string)$one['_id'];
				
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


