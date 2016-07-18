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
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ArticleMysqldb implements ArticleInterface
{
	public $numPerPage = 20;
	
	
	public function getPrimaryKey(){
		return 'id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			return Article::findOne($primaryKey);
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
		
		$query = Article::find();
		$query = Yii::$app->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
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
				Yii::$app->helper->errors->add('article '.$this->getPrimaryKey().' is not exist');
				return;
			}	
		}else{
			$model = new Article;
			$model->created_at = $currentDateTime;
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
		}
		$model->updated_at = $currentDateTime;
		$saveStatus = Yii::$app->helper->ar->save($model,$one);
		if(!$primaryVal){
			$primaryVal = Yii::$app->db->getLastInsertID();
		}
		
		$originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
		$originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
		$urlKey = Yii::$app->url->saveRewriteUrlKeyByStr($one['title'],$originUrl,$originUrlKey);
		$model->url_key = $urlKey;
		$model->save();
		
		return true;
	}
	
	public function remove($ids){
		if(!$ids){
			Yii::$app->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
			$innerTransaction = Yii::$app->db->beginTransaction();
			try {
				foreach($ids as $id){
					$model = Article::findOne($id);
					if($model->id){
						$url_key =  $model['url_key'];
						Yii::$app->url->removeRewriteUrlKey($url_key);
						$model->delete();
					}else{
						
						//throw new InvalidValueException("ID:$id is not exist.");
						Yii::$app->helper->errors->add("Article Remove Errors:ID $id is not exist.");
						$innerTransaction->rollBack();
						return false;
					}
				}
				$innerTransaction->commit();
			} catch (Exception $e) {
				Yii::$app->helper->errors->add("Article Remove Errors: transaction rollback");
				$innerTransaction->rollBack();
				return false;
			}
		}else{
			$id = $ids;
			$model = Article::findOne($id);
			if($model->id){
				$innerTransaction = Yii::$app->db->beginTransaction();
				try {
					$url_key =  $model['url_key'];
					Yii::$app->url->removeRewriteUrlKey($url_key);
					$model->delete();
					$innerTransaction->commit();
				} catch (Exception $e) {
					Yii::$app->helper->errors->add("Article Remove Errors: transaction rollback");
					$innerTransaction->rollBack();
				}
			}else{
				Yii::$app->helper->errors->add("Article Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
		
	}
	
}