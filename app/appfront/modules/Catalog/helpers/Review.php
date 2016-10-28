<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\helpers;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review{
	
	
	# 初始化当前appfront的设置，覆盖service的初始设置。
	public static function initReviewConfig(){
		# 用当前的配置，覆盖service的公用配置。
		$reviewParam = Yii::$app->getModule("catalog")->params['review'];
		if(isset($reviewParam['filterByStore'])){
			Yii::$service->product->review->filterByStore = $reviewParam['filterByStore'];
		}
		if(isset($reviewParam['filterByLang'])){
			Yii::$service->product->review->filterByLang = $reviewParam['filterByLang'];
		}
		# 新添加的评论是否需要审核
		if(isset($reviewParam['newReviewAudit'])){
			Yii::$service->product->review->newReviewAudit = $reviewParam['newReviewAudit'];
		}
		
	}
	
	
	
	
	
}