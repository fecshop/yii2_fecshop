<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\url\rewrite;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mysqldb\url\UrlRewrite;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RewriteMysqldb implements RewriteInterface
{
	public $numPerPage = 20;
	/**
	 *  language attribute.
	 */
	protected $_lang_attr = [
			
		];
	
	public function getOriginUrl($urlKey){
		$UrlData = UrlRewrite::find()->where([
			'custom_url_key' => $urlKey,
		])->asArray()->one();
		if($UrlData['custom_url_key']){
			return $UrlData['origin_url'];
		}
		return ;
	}
	
	public function getPrimaryKey(){
		return 'id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			$one = UrlRewrite::findOne($primaryKey);
			if(!empty($this->_lang_attr)){
				foreach($this->_lang_attr as $attrName){
					if(isset($one[$attrName])){
						$one[$attrName] = unserialize($one[$attrName]);
					}
				}
			}
			return $one;
		}else{
			return new UrlRewrite;
		}
		
	}
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	public function coll($filter=''){
		
		$query = UrlRewrite::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		$coll  = $query->all();
		if(!empty($coll)){
			foreach($coll as $k => $one){
				if(!empty($this->_lang_attr)){
					foreach($this->_lang_attr as $attr){
						$one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
					}
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
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		if($primaryVal){
			$model = UrlRewrite::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('UrlRewrite '.$this->getPrimaryKey().' is not exist');
				return;
			}	
		}else{
			$model = new UrlRewrite;
		}
		unset($one['_id']);
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		
		return true;
	}
	
	public function remove($ids){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
			$innerTransaction = Yii::$service->db->beginTransaction();
			try {
				foreach($ids as $id){
					$model = UrlRewrite::findOne($id);
					if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
					$url_key =  $model['url_key'];
						$model->delete();
					}else{
						
						//throw new InvalidValueException("ID:$id is not exist.");
						Yii::$service->helper->errors->add("UrlRewrite Remove Errors:ID $id is not exist.");
						$innerTransaction->rollBack();
						return false;
					}
				}
				$innerTransaction->commit();
			} catch (Exception $e) {
				Yii::$service->helper->errors->add("UrlRewrite Remove Errors: transaction rollback");
				$innerTransaction->rollBack();
				return false;
			}
		}else{
			$id = $ids;
			$model = UrlRewrite::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				$innerTransaction = Yii::$service->db->beginTransaction();
				try {
					$url_key =  $model['url_key'];
					$model->delete();
					$innerTransaction->commit();
				} catch (Exception $e) {
					Yii::$service->helper->errors->add("UrlRewrite Remove Errors: transaction rollback");
					$innerTransaction->rollBack();
				}
			}else{
				Yii::$service->helper->errors->add("UrlRewrite Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
		
	}
	
	public function removeByUpdatedAt($time){
		if($time){
			UrlRewrite::deleteAll([
				'<','updated_at',$time
			]);
		}
	}
	
	public function find(){
		return UrlRewrite::find();
	}
	
	public function findOne($where){
		return UrlRewrite::findOne($where);
	}
	
	public function newModel(){
		return new UrlRewrite;
	}
	
}