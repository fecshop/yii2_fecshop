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
use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review {
	
	public  $product_id;
	public  $spu;
	public  $filterBySpu = true;
	public  $filterOrderBy = 'review_date';
	
	public function __construct(){
		# 初始化当前appfront的设置，覆盖service的初始设置。
		ReviewHelper::initReviewConfig();
	}
	
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
				'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
			];
		}
		
		
	}
	
	public function getReviewsBySpu($spu){
		$review = Yii::$app->getModule('catalog')->params['review'];
		$productPageReviewCount = isset($review['productPageReviewCount']) ? $review['productPageReviewCount'] : 10;
		$currentIp = \fec\helpers\CFunc::get_real_ip();
		$filter = [
	  		'numPerPage' 	=> $productPageReviewCount,  	
	  		'pageNum'		=> 1,
	  		'orderBy'	=> [ $this->filterOrderBy => SORT_DESC ],
	 		'where'			=> [
				[
					'$or' => [
						[
							'status' => Yii::$service->product->review->activeStatus(),
							'product_spu' => $spu
						],
						[
							'status' => Yii::$service->product->review->noActiveStatus(),
							'product_spu' => $spu,
							'ip' => $currentIp
						]
					]
				],
			],
		];
		
		# 调出来 review 信息。
		return Yii::$service->product->review->getListBySpu($filter);
		
	}
	
	
}
