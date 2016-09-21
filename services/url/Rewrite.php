<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\url;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
use fecshop\services\url\rewrite\RewriteMysqldb;
use fecshop\services\url\rewrite\RewriteMongodb;
/**
 * Url Rewrite services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Rewrite extends Service
{
	public $storage = 'mongodb';
	protected $_urlRewrite;
	
	
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_urlRewrite = new RewriteMongodb;
		}else if($this->storage == 'mysqldb'){
			$this->_urlRewrite = new RewriteMysqldb;
		}
	}
	
	protected function actionGetOriginUrl($urlKey){
		return $this->_urlRewrite->getOriginUrl($urlKey);
	}
	
	/**
	 * get artile's primary key.
	 */
	protected function actionGetPrimaryKey(){
		return $this->_urlRewrite->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		return $this->_urlRewrite->getByPrimaryKey($primaryKey);
	}
	
	//public function getById($id){
	//	return $this->_article->getById($id);
	//}
	
	/**
	 * @property $filter|Array
	 * get artile collection by $filter
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
	protected function actionColl($filter=''){
		return $this->_urlRewrite->coll($filter);
	}
	
	/**
	 * @property $one|Array , save one data .
	 * @property $originUrlKey|String , article origin url key.
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	protected function actionSave($one){
		return $this->_urlRewrite->save($one);
	}
	
	protected function actionRemove($ids){
		return $this->_urlRewrite->remove($ids);
	}
	
	protected function actionRemoveByUpdatedAt($time){
		return $this->_urlRewrite->removeByUpdatedAt($time);
	}
	
	
	protected function actionFind(){
		return $this->_urlRewrite->find();
	}
	
	protected function actionFindOne($where){
		return $this->_urlRewrite->findOne($where);
	}
	
	protected function actionNewModel(){
		return $this->_urlRewrite->newModel();
	}
	
}