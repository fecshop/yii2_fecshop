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
use fecshop\models\mongodb\Product;
use fecshop\models\mongodb\CategoryProduct;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
	
	public $pageNum = 1;
	public $numPerPage = 50;
	
	public $allowedNumPerPage ;
	/**
	 * [
		'categoryId' 	=> 1,
		'pageNum'		=> 2,
		'numPerPage'	=> 50,
		'orderBy'		=> 'name',
		'where'			=> [
			'price' => [
				'?gte'  => 11,
				'?lt'	=> 22
			],
		],
	 ]
	 */
	
	public function getFilterProduct($filter){
		$where 		= isset($filter['where']) ? $filter['where'] : '';
		$categoryId = isset($filter['categoryId']) ? $filter['categoryId'] : '';
		if($categoryId){
			$productIds = $this->getProductIdsByCategoryId($categoryId);
			$where['_id'] = ['?in' => $productIds];
		}
		$pageNum = isset($filter['pageNum']) ? $filter['pageNum'] : $this->pageNum;
		$numPerPage = isset($filter['numPerPage']) ? $filter['numPerPage'] : $this->numPerPage;
		$orderBy = isset($filter['orderBy']) ? $filter['orderBy'] : '';
		$offset = ($pageNum - 1)*$numPerPage;
		
		$query = Product::find()->asArray()->offset($offset)->limit($numPerPage);
		if($orderBy)
			$query->orderBy($orderBy);
		return $query->all();
	}
	
	/**
	 * @property $categoryId|Int
	 * @return $productIds|Array
	 */
	protected function getProductIdsByCategoryId($categoryId){
		$data = CategoryProduct::find()->asArray()->where([
			'category_id' => $categoryId
		])->all();
		$productIds = [];
		if(!empty($data)){
			foreach($data as $one){
				$productIds[] = $one['product_id'];
			}
		}
		return $productIds;
	}
	
	
	
	
	
}