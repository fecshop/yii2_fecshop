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
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\db\product\ViewLog as DbViewLog;
use fecshop\models\mongodb\product\ViewLog as MongodbViewLog;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ViewLog extends Service
{
	public $type;
	public $_defaultType = 'session';
	public $_defaultViewTable = 'log_product_view';
	public $_sessionKey = 'services_product_viewlog_history';
	public $_maxProductCount = 10;
	public $_currentType;
	public $_currentTable;
	
	public $_currentModel;
	
	
	# 初始化
	public function init(){
		$configType = $this->type;
		if(!$configType){
			$configType = $this->_defaultType;
		}
		$configTypeArr = explode(',',$configType);
		$storage = $configTypeArr[0];
		
		if($storage == 'session'){
			
		}else if(($storage == 'mysql') || ($storage == 'mongodb')){
			if(isset($configTypeArr[1]) && $configTypeArr[1]){
				$this->_currentTable = $configTypeArr[1];
			}else{
				$this->_currentTable = $this->_defaultViewTable;
			}
			if($storage == 'mysql'){
				DbViewLog::setCurrentTableName($this->_currentTable);
			}else if($storage == 'mongodb'){
				MongodbViewLog::setCurrentCollectionName($this->_currentTable);
			}
		}else{
			
			throw new InvalidConfigException('services:product->viewlog,config type is not right, you can 
			only config type in [session,mysql,mongodb]
			,example:(1)session,(2)mysql.'.$this->_defaultViewTable.',(3)mongodb.'.$this->_defaultViewTable.'
			if you config mysql or mongodb ,do not config table , default ('.$this->_defaultViewTable.') will use. 
			current file:'.get_called_class());
		}
		$this->_currentType = $storage;
	}
	
	
	/**
	 *	得到产品浏览的历史记录
	 */
	public function getHistory()
	{
		return 'category best sell product';
	}
	
	/**
	 *	保存产品的历史记录
	 */
	public function setHistory($productOb){
		
		$logArr = [
			'date_time' => CDate::getCurrentDateTime(),
			'product_id'=> $productOb['id'],
			'sku'		=> $productOb['sku'],
			'image'		=> $productOb['image'],
			'name'		=> is_array($productOb['name']) ? serialize($productOb['name']) : $productOb['name'],
		];
		if($this->_currentType == 'session'){
			if(!($session_history = CSession::get($this->_sessionKey))){
				$session_history = [];
			}else if(($count = count($session_history)) >= $this->_maxProductCount)){
				$unsetMaxKey = $count - $this->_maxProductCount ;
				for($i=0;$i<=$unsetMaxKey;$i++){
					array_shift($session_history);
				}
			}
			$session_history[] = $logArr;
			CSession::set($this->_sessionKey,$session_history);
		}else if(($this->_currentType == 'mysql'){
			$this->_currentModel
			$this->_currentTable
			$fn_set_exec = $this->_currentModel.'::setCurrentTableName';
			\call_user_func_array($fn_set_exec,[$this->_currentTable]);
			
		}else if(($this->_currentType == 'mongodb'){
			$this->_currentTable
		}
	}
	
	
	
 
}