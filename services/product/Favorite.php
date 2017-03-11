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

use fecshop\models\mongodb\product\Favorite as FavoriteModel;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Favorite extends Service
{
	
	protected function actionGetPrimaryKey(){
		return '_id';
	}
	
	protected function actionGetByPrimaryKey($val){
		$one = FavoriteModel::findOne($val);
		if($one[$this->getPrimaryKey()]){
			return $one;
		}else{
			return new FavoriteModel;
		}
	}
	
	protected function actionGetByProductIdAndUserId($product_id,$user_id=''){
		
		if(!$user_id){
			$identity = Yii::$app->user->identity;
			$user_id  = $identity['id'];
		}
		if($user_id){
			$one = FavoriteModel::findOne([
				'product_id' => $product_id,
				'user_id'	 => $user_id,
			]);
			if($one[$this->getPrimaryKey()]){
				return $one;
			}
		}
	}
	
	protected function actionAdd($product_id,$user_id){
		$user_id = (int)$user_id;
		$productPrimaryKey =  Yii::$service->product->getPrimaryKey();
		$product = Yii::$service->product->getByPrimaryKey($product_id);
		# 检查产品是否存在，如果不存在，输出报错信息。
		if(!isset($product[$productPrimaryKey])){
			Yii::$service->helper->errors->add('product is not exist!');
			return ;
		}
		//echo $product_id;exit;
		$favoritePrimaryKey = Yii::$service->product->favorite->getPrimaryKey();
		$one = FavoriteModel::findOne([
			'product_id' => $product_id,
			'user_id'	 => $user_id,
		]);
		if(isset($one[$favoritePrimaryKey])){
			$one->updated_at = time();
			$one->store = Yii::$service->store->currentStore;
			$one->save();
			return true;
		}
		$one = new FavoriteModel;
		$one->product_id = $product_id;
		$one->user_id = $user_id;
		$one->created_at = time();
		$one->updated_at = time();
		$one->store = Yii::$service->store->currentStore;
		$one->save();
		# 更新该用户总的收藏产品个数到用户表
		$this->updateUserFavoriteCount($user_id);
		$this->updateProductFavoriteCount($product_id);
		return true;
	}
	/**
	 * @property $product_id | String
	 * 更新该产品被收藏的总个数。
	 */
	protected function updateProductFavoriteCount($product_id){
		if($product_id){
			$count = FavoriteModel::find()->where(['product_id'=>$product_id])->count();
			$product = Yii::$service->product->getByPrimaryKey($product_id);
			if($product['_id']){
				$product->favorite_count = $count;
				$product->save();
			}
		}
		
	}
	/**
	 * @property $user_id | Int
	 * 更新该用户总的收藏产品个数到用户表
	 */
	protected function updateUserFavoriteCount($user_id = ''){
		$identity = Yii::$app->user->identity;
		if(!$user_id){
			$user_id  = $identity['id'];
		}
		if($user_id){
			$count = FavoriteModel::find()->where(['user_id'=>$user_id])->count();
			$identity->favorite_product_count = $count;
			$identity->save();
		}
	}
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	protected function actionList($filter){
		$query = FavoriteModel::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
		
	}
	
	protected function actionColl($filter){
		return $this->list($filter);
	}
	/**
	 * @property $favorite_id|String 
	 * 通过id删除favorite
	 */
	protected function actionCurrentUserRemove($favorite_id){
		$identity = Yii::$app->user->identity;
		$user_id  = $identity['id'];
		
		$one = FavoriteModel::findOne([
			'_id' 		=> new \MongoDB\BSON\ObjectId($favorite_id),
			'user_id'	=> $user_id,
		]);
		if($one['_id']){
			$one->delete();
			$this->updateUserFavoriteCount($user_id);
			$this->updateProductFavoriteCount($product_id);
			return true;
		}
		
		return;
	}
	
	
	
}