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
use fecshop\models\mysqldb\cms\StaticBlock;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlockMysqldb implements StaticBlockInterface
{
	public $numPerPage = 20;
	/**
	 *  language attribute.
	 */
	protected $_lang_attr = [
			'title',
			'content',
		];
	
	public function getPrimaryKey(){
		return 'id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			$one = StaticBlock::findOne($primaryKey);
			foreach($this->_lang_attr as $attrName){
				if(isset($one[$attrName])){
					$one[$attrName] = unserialize($one[$attrName]);
				}
			}
			return $one;
		}else{
			return new StaticBlock;
		}
		
	}
	
	public function getByIdentify($identify){
		$one = StaticBlock::find()->asArray()->where([
			'identify' => $identify
		])->one();
		foreach($this->_lang_attr as $attrName){
			if(isset($one[$attrName])){
				$one[$attrName] = unserialize($one[$attrName]);
			}
		}
		return $one;
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
		$coll  = $query->all();
		if(!empty($coll)){
			foreach($coll as $k => $one){
				foreach($this->_lang_attr as $attr){
					$one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
				}
				$coll[$k] = $one;
			}
		}
		//var_dump($one);
		return [
			'coll' => $coll,
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
				Yii::$service->helper->errors->add('static block '.$this->getPrimaryKey().' is not exist');
				return;
			}	
		}else{
			$model = new StaticBlock;
			$model->created_at = time();
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
		}
		$model->updated_at = time();
		foreach($this->_lang_attr as $attrName){
			if(is_array($one[$attrName]) && !empty($one[$attrName])  ){
				$one[$attrName] = serialize($one[$attrName]);
			}
		}
		
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		if(!$primaryVal){
			$primaryVal = Yii::$app->db->getLastInsertID();
		}
		
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
			$query->andWhere(['<>',$id,$primaryVal]);
		}
		$one = $query->one();
		if(!empty($one)){
			return false;
		}
		return true;
	}
	
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
			foreach($ids as $id){
				$model = StaticBlock::findOne($id);
				$model->delete();
			}	
		}
		return true;
		
	}
	
}