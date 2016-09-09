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
			['>','price',11],
			['<','price',22],
		],
	 ]
	 */
	
	protected function actionColl($filter){
		$category_id = isset($filter['category_id']) ? $filter['category_id'] : '';
		if(!$category_id){
			Yii::$service->helper->errors->add('category id is empty');
			return ;
		}else{
			unset($filter['category_id']);
			$filter['where'][] = ['category' => $category_id];
		}
		if(!isset($filter['pageNum']) || !$filter['pageNum']){
			$filter['pageNum'] = 1;
		}
		if(!isset($filter['numPerPage']) || !$filter['numPerPage']){
			$filter['numPerPage'] = $this->numPerPage ;
		}
		if(isset($filter['orderBy']) && !empty($filter['orderBy'])){
			if(!is_array($filter['orderBy'])){
				Yii::$service->helper->errors->add('orderBy must be array');
				return;
			}
		}
		return Yii::$service->product->coll($filter);
	}
	
	protected function actionGetFrontList($filter){
		$filter['group'] 	= '$spu';
		$coll 				= Yii::$service->product->getFrontCategoryProducts($filter);
		$collection 		= $coll['coll'];
		$count 				= $coll['count'];
		
		$arr = $this->convertToCategoryInfo($collection);
		return [
			'coll' => $arr,
			'count'=> $count,
		];
	}
	
	protected function actionConvertToCategoryInfo($collection){
		$arr = [];
		$defaultImg = Yii::$service->product->image->defautImg();
		if(is_array($collection) && !empty($collection)){
			foreach($collection as $one){
				
				$name 		= Yii::$service->store->getStoreAttrVal($one['name'],'name');
				$image 		= $one['image'];
				$url_key 	= $one['url_key'];
				if(isset($image['main']['image']) && !empty($image['main']['image'])){
					$image = $image['main']['image'];
				}else{
					$image = $defaultImg;
				}
				list($price,$special_price) = $this->getPrices($one['price'],$one['special_price'],$one['special_from'],$one['special_to']);
				$arr[] = [
					'name' 			=> $name,
					'sku' 			=> $one['sku'],
					'image' 		=> $image,
					'price' 		=> $price,
					'special_price' => $special_price,
					'url'			=> Yii::$service->url->getUrl($url_key),
				];
			}
		}
		return $arr;
	}
	
	
	protected function getPrices($price,$special_price,$special_from,$special_to){
		if($special_price){
			$now = time();
			if(
				($now >= $special_from) && (!$special_to || ($now <= $special_to))
			){
				return [$price,$special_price];
			}
		}
		return [$price,0];
	}
	
	
	
	
	
	
}