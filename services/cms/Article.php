<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cms;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
use fecshop\services\cms\article\ArticleMysqldb;
use fecshop\services\cms\article\ArticleMongodb;
/**
 * Cms Article services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Article extends Service
{
	public $storage = 'mongodb';
	protected $_article;
	
	
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_article = new ArticleMongodb;
		}else if($this->storage == 'mysqldb'){
			$this->_article = new ArticleMysqldb;
		}
	}
	/**
	 * Get Url by article's url key.
	 */
	public function getUrlByPath($urlPath){
		//return Yii::$service->url->getHttpBaseUrl().'/'.$urlKey;
		return Yii::$service->url->getUrlByPath($urlPath);
	}
	/**
	 * get artile's primary key.
	 */
	public function getPrimaryKey(){
		return $this->_article->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	public function getByPrimaryKey($primaryKey){
		return $this->_article->getByPrimaryKey($primaryKey);
	}
	
	
	
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
		return $this->_article->coll($filter);
	}
	
	/**
	 * @property $one|Array , save one data .
	 * @property $originUrlKey|String , article origin url key.
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one,$originUrlKey){
		return $this->_article->save($one,$originUrlKey);
	}
	
	public function remove($ids){
		return $this->_article->remove($ids);
	}
	
}