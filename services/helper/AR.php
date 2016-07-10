<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\helper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\ChildService;
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AR extends ChildService
{
	public $numPerPage=20;
	public $pageNum=1;
	
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
	public function getCollByFilter($query,$filter){
		$asArray 	= isset($filter['asArray']) ? $filter['asArray'] : false;
		$numPerPage = isset($filter['numPerPage']) ? $filter['numPerPage'] : $this->numPerPage;
		$pageNum 	= isset($filter['pageNum']) ? $filter['pageNum'] : $this->pageNum;
		$orderBy 	= isset($filter['orderBy']) ? $filter['orderBy'] : '';
		$where 		= isset($filter['where']) ? $filter['where'] : '';
		
		if($asArray)
			$query->asArray();
		if($where)
			$query->where($where);
		$offset = ($pageNum -1 ) * $numPerPage;
		$query->limit($numPerPage)->offset($offset);
		if($orderBy)
			$query->orderBy($orderBy);
		return $query->all();
	}
	
	
	public function save($model,$one){
		
		$attributes = $model->attributes();
		foreach($attributes as $attr){
			if(isset($one[$attr])){
				$model[$attr] = $one[$attr];
			}
		}
		return $model->save();
	}
	
	
}