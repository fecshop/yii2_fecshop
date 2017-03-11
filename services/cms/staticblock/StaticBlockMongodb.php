<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cms\staticblock;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\cms\StaticBlock;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlockMongodb implements StaticBlockInterface
{
	public $numPerPage = 20;
	
	public function getPrimaryKey(){
		return '_id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			return StaticBlock::findOne($primaryKey);
		}else{
			return new StaticBlock;
		}
	}
	
	public function getByIdentify($identify){
		return StaticBlock::find()->asArray()->where([
			'identify' => $identify
		])->one();
	}
	
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
			'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	public function coll($filter=''){
		$query = StaticBlock::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
	}
	
	/**
	 * @property $one|Array
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one){
		$currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		if(!($this->validateIdentify($one))){
			Yii::$service->helper->errors->add('StaticBlock: identify存在，您必须定义一个唯一的identify ');
			return;
		}
		if($primaryVal){
			$model = StaticBlock::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('StaticBlock '.$this->getPrimaryKey().' is not exist');
				return;
			}			
		}else{
			$model = new StaticBlock;
			$model->created_at = time();
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
			$primaryVal = new \MongoDB\BSON\ObjectId();
			$model->{$this->getPrimaryKey()} = $primaryVal;
		}
		
		$model->updated_at = time();
		unset($one['_id']);
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		return true;
	}
	
	protected function validateIdentify($one){
		$identify = $one['identify'];
		$id = $this->getPrimaryKey();
		$primaryVal = isset($one[$id]) ? $one[$id] : '';
		$where = ['identify' => $identify];
		$query = StaticBlock::find()->asArray();
		$query->where(['identify' => $identify]);
		if($primaryVal){
			$query->andWhere([$id => ['$ne'=> new \MongoDB\BSON\ObjectId($primaryVal)]]);
		}
		$one = $query->one();
		
		if(!empty($one)){
			return false;
		}
		return true;
	}
	
	/**
	 * remove Static Block
	 */ 
	public function remove($ids){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
			foreach($ids as $id){
				$model = StaticBlock::findOne($id);
				$model->delete();
			}	
		}else{
			$id = $ids;
			$model = StaticBlock::findOne($id);
			$model->delete();
		}
		return true;
	}
}


