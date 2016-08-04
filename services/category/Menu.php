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
use fec\helpers\CDir;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\mongodb\Category;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
	public $rootCategoryId = 0;
	
	/**
	 * @property $parentId|Int
	 * get category menu as array. array key is: _id ,name ,urlPath,childMenu
	 * 
	 */
	protected function actionGetCategoryMenuArr($parentId=''){
		$arr = [];
		if(!$parentId)
			$parentId = $this->rootCategoryId;
		$data = Category::find()->asArray()->select([
			'_id','parent_id','name','url_path'
		])->where([
			'parent_id' => $parentId
		])->all();
		if(is_array($data) && !empty($data)){
			foreach($data as $category){
				$categoryOne = [
					'_id'		=> $category['_id'];
					'name' 		=> Yii::$service->store->getLangVal($category['name'],'name'),
					'urlPath' 	=> $category['url_path'],
				]
				$childMenu = $this->getCategoryMenuArr($category['parent_id']);
				if($childMenu){
					$categoryOne['childMenu'] = $childMenu;
				}
				$arr[] = $categoryOne;
			}
			return $arr;
		}
		return '';
	}
	
	/**
	 * @property $categoryId|Array 
	 * check if cateogry has child .
	 */
	protected function hasChild($categoryId){
		$one = Category::find()->asArray()->where([
				'parent_id' => $categoryId
			])->one();
		if($one['_id'])
			return true;
		return false;
	}
}