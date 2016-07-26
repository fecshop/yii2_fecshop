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
 * Breadcrumbs services
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
	
	public function getOriginUrl($urlKey){
		return $this->_urlRewrite->getOriginUrl($urlKey);
	}
	
	/**
	 * get artile's primary key.
	 */
	public function getPrimaryKey(){
		return $this->_urlRewrite->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	public function getByPrimaryKey($primaryKey){
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
	 * 		'where'			=> [
	 * 			'price' => [
	 * 				'?gt' => 1,
	 * 				'?lt' => 10,
	 * 			],
	 * 			'sku' => 'uk10001',
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	public function coll($filter=''){
		return $this->_urlRewrite->coll($filter);
	}
	
	/**
	 * @property $one|Array , save one data .
	 * @property $originUrlKey|String , article origin url key.
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one){
		return $this->_urlRewrite->save($one);
	}
	
	public function remove($ids){
		return $this->_urlRewrite->remove($ids);
	}
	
	
	public function find(){
		return $this->_urlRewrite->find();
	}
	
	public function findOne($where){
		return $this->_urlRewrite->findOne($where);
	}
	
	public function newModel(){
		return $this->_urlRewrite->newModel();
	}
	
}