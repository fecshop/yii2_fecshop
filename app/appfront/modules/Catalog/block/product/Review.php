<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\product;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review {
	
	public  $product_id;
	public  $spu;
	public  $filterBySpu = true;
	public  $filterOrderBy = 'review_date';
	
	public function getLastData(){
		if(!$this->spu || !$this->product_id){
			return ;
		}
		if($this->filterBySpu){
			$data = $this->getReviewsBySpu($this->spu);
			$count = $data['count'];
			$coll  = $data['coll'];
			return [
				'_id' => $this->product_id,
				'spu' => $this->spu,
				'review_count'	=> $count,
				'coll'			=> $coll ,
			];
		}
		
		
	}
	
	public function getReviewsBySpu($spu){
		$review = Yii::$app->getModule('catalog')->params['review'];
		$productPageReviewCount = isset($review['productPageReviewCount']) ? $review['productPageReviewCount'] : 10;
		
		$filter = [
	  		'numPerPage' 	=> $productPageReviewCount,  	
	  		'pageNum'		=> 1,
	  		'orderBy'	=> [ $this->filterOrderBy => SORT_DESC ],
	 		'where'			=> [
				//['status' => Yii::$service->product->review->activeStatus()],
	  			['product_spu' => $spu],
			],
		];
		return Yii::$service->product->review->list($filter);
		
	}
	
	
}
