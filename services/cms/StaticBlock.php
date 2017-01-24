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
use fecshop\services\cms\staticblock\StaticBlockMysqldb;
use fecshop\services\cms\staticblock\StaticBlockMongodb;
/**
 * Cms StaticBlock services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Staticblock extends Service
{
	public $storage = 'mongodb';
	protected $_static_block;
	/**
	 * init static block db.
	 */
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_static_block = new StaticBlockMongodb;
		}else if($this->storage == 'mysqldb'){
			$this->_static_block = new StaticBlockMysqldb;
		}
	}
	/**
	 * get store static block content by identify
	 * example <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('home-big-img','appfront') ?>
	 */
	protected function actionGetStoreContentByIdentify($identify,$app='common'){
		$staticBlock = $this->_static_block->getByIdentify($identify);
		$content = $staticBlock['content'];
		//echo '###';
		//var_dump($content);
		//echo '###';
		$storeContent = Yii::$service->store->getStoreAttrVal($content,'content');
		//echo $storeContent;
		$_params_ = $this->getStaticBlockVariableArr($app);
		ob_start();    
		ob_implicit_flush(false);    
		extract($_params_, EXTR_OVERWRITE);  
		foreach($_params_ as $k => $v){
			$key = '{{'.$k.'}}';
			if(strstr($storeContent,$key)){
				$storeContent = str_replace($key,$v,$storeContent);
			}
		}	
		echo $storeContent;
		return ob_get_clean();    
	}
	/**
	 * staticblock中的变量，可以通过{{homeUlr}},来获取下面的值。 
	 */
	protected function getStaticBlockVariableArr($app){
		return [
			'homeUrl' => Yii::$service->url->homeUrl(),
			'imgBaseUrl' => Yii::$service->image->getBaseImgUrl($app),
		];
	}
	
	/**
	 * get artile's primary key.
	 */
	protected function actionGetPrimaryKey(){
		return $this->_static_block->getPrimaryKey();
	}
	/**
	 * get artile model by primary key.
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		return $this->_static_block->getByPrimaryKey($primaryKey);
	}
	
	
	
	/**
	 * @property $filter|Array
	 * get artile collection by $filter
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
	protected function actionColl($filter=''){
		return $this->_static_block->coll($filter);
	}
	
	/**
	 * @property $one|Array , save one data .
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	protected function actionSave($one){
		return $this->_static_block->save($one);
	}
	
	protected function actionRemove($ids){
		return $this->_static_block->remove($ids);
	}
	
	
}





