<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product\viewLog;
use Yii;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fec\helpers\CDate;
use fec\helpers\CUser;
use fecshop\models\mongodb\product\ViewLog as MongodbViewLog;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Mongodb extends Service
{
	
	public $collection;
	public $_defaultCollection = 'log_product_view';
	public $_maxProductCount = 10;
	
	# init
	public function init(){
		if(!$this->collection)
			$this->collection = $this->_defaultCollection; 
		MongodbViewLog::setCurrentCollectionName($this->collection);
	}
	
	
	/**
	 *	get product history log
	 */
	public function getHistory($user_id='',$count = '')
	{
		if(!$count)
			$count = $this->_maxProductCount;
		if(!$user_id)
			$user_id = CUser::getCurrentUserId();
		if(!$user_id)
			return ;
		$coll = MongodbViewLog::find()->where([
				'user_id' => $user_id,
			])
			->asArray()
			->orderBy(['date_time' => SORT_DESC])
			->limit($count)
			->all();
		return $coll;
		
	}
	
	/**
	 *	save product history log
	 */
	public function setHistory($productOb){
		
		$arr = [
			'date_time' 	=> CDate::getCurrentDateTime(),
			'product_id' 	=> $productOb['id'],
			'sku' 			=> $productOb['sku'],
			'image' 		=> $productOb['image'],
			'name' 			=> $productOb['name'],
		];
		
		if(isset($productOb['user_id']) && $productOb['user_id']){
			$arr['user_id'] = $productOb['user_id'];
		}else if($currentUser = CUser::getCurrentUserId()){
			$arr['user_id'] = $currentUser;
		}else{
			// if not give user_id, can not save history
			return;
		}
		
		$MongodbViewLog = MongodbViewLog::getCollection();
		$MongodbViewLog->save($arr);
		
	}
	
	
	
 
}