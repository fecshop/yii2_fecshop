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
use fec\helpers\CDir;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;

use fecshop\models\mongodb\Product;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Coll extends Service
{
	/**
	 *  the number of product info when get products collection in one page.
	 */ 
	public $numPerPage = 50;
	/**
	 *  the number of page  when get products collectionss.
	 */ 
	public $pageNum = 1;
	/**
	 *  the table column and direction (SORT_ASC OR SORT_DESC)  when get products collections.
	 */ 
	public $orderBy = ['_id' => SORT_DESC ];
	/**
	 *  the max PageNum allowed when get product collections
	 */ 
	public $allowMaxPageNum = 200;
	
	/**
	 * @property $filter|Array. 
	 * Get product collection .
	 * $filter is a Array Variable that user for filter product collection.
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
	protected function actionGetProducts($filter){
		$numPerPage 	= isset($filter['numPerPage']) ? $filter['numPerPage'] : $this->numPerPage;
		$pageNum 		= isset($filter['pageNum']) ? $filter['pageNum'] : $this->pageNum;
		$orderBy 		= isset($filter['orderBy']) ? $filter['orderBy'] : $this->orderBy;
		$where			= isset($filter['where']) ? $filter['where'] : '';
		$asArray		= isset($filter['asArray']) ? $filter['asArray'] : true;
		if($pageNum > $this->allowMaxPageNum)
			throw new InvalidValueException("product pageNum is $pageNum, it can not > $this->allowMaxPageNum , you can change param pageNum , or chage config allowMaxPageNum in services product/coll");
		$query 			= Product::find();
		$offset 		= ($pageNum -1 ) * $numPerPage;
		if($asArray)
			$query->asArray();
		if($where)
			$query->where($where);
		$query->limit($numPerPage)->offset($offset)->orderBy($orderBy);
		return $query->all();
	}
	
}