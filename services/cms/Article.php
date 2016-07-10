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
use fecshop\services\ChildService;
use fecshop\services\cms\article\ArticleMysqldb;
use fecshop\services\cms\article\ArticleMongodb;
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Article extends ChildService
{
	
	protected $_article;
	
	public function init(){
		if(Yii::$app->cms->storage == 'mongodb'){
			$this->_article = new ArticleMongodb;
		}else{
			$this->_article = new ArticleMysqldb;
		}
	}
	
	
	public function getById($id){
		return $this->_article->getById($id);
	}
	
	public function coll($filter=''){
		return $this->_article->coll($filter);
	}
	
	/**
	 * @property $date|Array
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one){
		return $this->_article->save($one);
	}
	
	public function remove($ids){
		return $this->_article->remove($ids);
	}
	
}