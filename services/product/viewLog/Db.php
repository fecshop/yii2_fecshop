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
use fecshop\models\db\product\ViewLog as DbViewLog;
use fecshop\models\mongodb\product\ViewLog as MongodbViewLog;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Db extends Service
{
	
	public $table;
	public $_defaultTable = 'log_product_view';
	public $_maxProductCount = 10;
	
	# init function
	public function init(){
		if(!$this->table)
			$this->table = $this->_defaultTable; 
		DbViewLog::setCurrentTableName($this->table);
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
		$coll = DbViewLog::find()->where([
				'user_id' => $user_id,
			])
			->asArray()
			->orderBy(['date_time' => SORT_DESC])
			->limit($count)
			->all();
		return $coll;
		
	}
	
	/**
	 *	save product visit log
	 */
	public function setHistory($productOb){
		$DbViewLog = new DbViewLog;
		if(isset($productOb['user_id']) && $productOb['user_id']){
			$DbViewLog->user_id = $productOb['user_id'];
		}else if($currentUser = CUser::getCurrentUserId()){
			$DbViewLog->user_id = $currentUser;
		}else{
			// if not give user_id, can not save history
			return;
		}	
		$DbViewLog->date_time = CDate::getCurrentDateTime();
		$DbViewLog->product_id = $productOb['id'];
		$DbViewLog->sku = $productOb['sku'];
		$DbViewLog->image = $productOb['image'];
		$DbViewLog->name = $productOb['name'];
		$DbViewLog->save();
	}
	
	
	
 
}