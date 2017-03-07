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
	public $rootCategoryId = '0';
	
	/**
	 * @property $parentId|Int
	 * 得到分类的目录信息
	 * 
	 */
	protected function actionGetCategoryMenuArr($parentId=''){
		$arr = [];
		if(!$parentId)
			$parentId = $this->rootCategoryId;
		$data = Category::find()->asArray()->select([
			'_id','parent_id','name','url_key','menu_custom'
		])->where([
			'parent_id' => $parentId
		])->all();
		
		if(is_array($data) && !empty($data)){
			foreach($data as $category){
				$categoryOne = [
					'_id'		=> (string)$category['_id'],
					'name' 		=> Yii::$service->store->getStoreAttrVal($category['name'],'name'),
					'menu_custom'=> Yii::$service->store->getStoreAttrVal($category['menu_custom'],'menu_custom'),
					'url' 		=> Yii::$service->url->getUrl($category['url_key']),
				];
				$childMenu = $this->getCategoryMenuArr((string)$category['_id']);
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
