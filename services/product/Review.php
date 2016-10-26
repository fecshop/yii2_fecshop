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

use fecshop\models\mongodb\product\Review as ReviewModel;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review extends Service
{
	public $filterByStore;
	public $filterByLang;
	public $newReviewAudit;
	/**
	 * @property $arr | Array
	 * 初始化review model的属性，因为每一个产品的可能添加的评论字段不同。
	 */
	protected function actionInitReviewAttr($arr){
		if(!empty($arr) && is_array($arr)){
			$ReviewModel 	= new ReviewModel;
			$attr_arr 		= $ReviewModel->attributes(true);
			$arr_keys 		= array_keys($arr);
			$attrs 			= array_diff($arr_keys,$attr_arr);
			ReviewModel::addCustomAttrs($attrs);
		}
	}
	/**
	 * @property $spu | String.
	 * 通过spu找到评论总数。
	 */
	protected function actionGetCountBySpu($spu){
		$where = [
			'product_spu' => $spu
		];
		if($this->filterByStore && ($currentStore = Yii::$service->store->currentStore)){
			$where['store'] = $currentStore;
		}
		if($this->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)){
			$where['lang_code'] = $currentLangCode;
		}
		$count = ReviewModel::find()->asArray()->where($where)->count();
		return  $count ?  $count : 0;
	}
	/**
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['review_date' => SORT_DESC],
	 * 		where'			=> [
	 * 			['spu' => 'uk10001'],
	 * 		],
	 * 		'asArray' => true,
	 * ]
	 * 通过spu找到评论listing
	 */
	protected function actionGetListBySpu($filter){
		
		if($this->filterByStore && ($currentStore = Yii::$service->store->currentStore)){
			$filter['where'][] = ['store' => $currentStore ];
		}
		if($this->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)){
			$filter['where'][] = ['lang_code' => $currentLangCode ];
		}
		$query = ReviewModel::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
		
	}
	
	/**
	 * 得到review noactive status
	 */
	protected function actionNoActiveStatus(){
		return ReviewModel::NOACTIVE_STATUS;
	}
	
	/**
	 * 得到review active status
	 */
	protected function actionActiveStatus(){
		return ReviewModel::ACTIVE_STATUS;
	}
	
	/**
	 * @property $review_data | Array 
	 * 
	 * 增加评论
	 */
	protected function actionAddReview($review_data){
		//$this->initReviewAttr($review_data);
		$model = new ReviewModel;
		if(isset($review_data['_id'])){
			unset($review_data['_id']);
		}
		# 默认状态。
		if($this->newReviewAudit){
			$review_data['status'] = ReviewModel::NOACTIVE_STATUS;
		}else{
			$review_data['status'] = ReviewModel::ACTIVE_STATUS;
		}
		$review_data['store'] = Yii::$service->store->currentStore;
		$review_data['lang_code'] = Yii::$service->store->currentLangCode;
		$review_data['review_date'] = time();
		$saveStatus = Yii::$service->helper->ar->save($model,$review_data);
		
		return true;
	}
	
	/**
	 * @property $review_data | Array 
	 * 保存评论
	 */
	protected function actionUpdateReview($review_data){
		//$this->initReviewAttr($review_data);
		$model = ReviewModel::findOne(['_id'=> $review_data['_id']]);
		unset($review_data['_id']);
		$saveStatus = Yii::$service->helper->ar->save($model,$review_data);
		return true;
	}
	
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 * 查看review 的列表
	 */
	protected function actionList($filter){
		$query = ReviewModel::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
	}
	/**
	 * @property $_id | String
	 * 后台编辑 通过评论id找到评论
	 * 注意：因为每个产品的评论可能加入了新的字段，因此不能使用ActiveRecord的方式取出来，
	 * 使用下面的方式可以把字段都取出来。
	 */
	protected function actionGetByReviewId($_id){
		
		return ReviewModel::getCollection()->findOne(['_id' => $_id]);
		
	}
	
	
	
	
	
	
	
}