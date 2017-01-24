<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cms\article;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mysqldb\cms\Article;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ArticleMysqldb implements ArticleInterface
{
	public $numPerPage = 20;
	/**
	 *  language attribute.
	 */
	protected $_lang_attr = [
			'title',
			'meta_description',
			'content',
			'meta_keywords',
		];
	
	public function getPrimaryKey(){
		return 'id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			$one = Article::findOne($primaryKey);
			foreach($this->_lang_attr as $attrName){
				if(isset($one[$attrName])){
					$one[$attrName] = unserialize($one[$attrName]);
				}
			}
			return $one;
		}else{
			return new Article;
		}
		
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
		
		$query = Article::find();
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
	public function save($one,$originUrlKey){
		$currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		if($primaryVal){
			$model = Article::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('article '.$this->getPrimaryKey().' is not exist');
				return;
			}	
		}else{
			$model = new Article;
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
		
		$originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
		$originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
		$defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['title'],'title');
		$urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle,$originUrl,$originUrlKey);
		$model->url_key = $urlKey;
		$model->save();
		
		return true;
	}
	
	public function remove($ids){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
			$innerTransaction = Yii::$app->db->beginTransaction();
			try {
				foreach($ids as $id){
					$model = Article::findOne($id);
					if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
					$url_key =  $model['url_key'];
						Yii::$service->url->removeRewriteUrlKey($url_key);
						$model->delete();
					}else{
						
						//throw new InvalidValueException("ID:$id is not exist.");
						Yii::$service->helper->errors->add("Article Remove Errors:ID $id is not exist.");
						$innerTransaction->rollBack();
						return false;
					}
				}
				$innerTransaction->commit();
			} catch (Exception $e) {
				Yii::$service->helper->errors->add("Article Remove Errors: transaction rollback");
				$innerTransaction->rollBack();
				return false;
			}
		}else{
			$id = $ids;
			$model = Article::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				$innerTransaction = Yii::$app->db->beginTransaction();
				try {
					$url_key =  $model['url_key'];
					Yii::$service->url->removeRewriteUrlKey($url_key);
					$model->delete();
					$innerTransaction->commit();
				} catch (Exception $e) {
					Yii::$service->helper->errors->add("Article Remove Errors: transaction rollback");
					$innerTransaction->rollBack();
				}
			}else{
				Yii::$service->helper->errors->add("Article Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
		
	}
	
}