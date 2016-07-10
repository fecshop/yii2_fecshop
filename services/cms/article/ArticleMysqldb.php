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
	
	public function getById($id){
		return Article::findOne($id);
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
		return Yii::$app->helper->ar->getCollByFilter($query,$filter);
	}
	
	/**
	 * @property $one|Array
	 * save $data to cms model,then,add url rewrite info to system service urlrewrite.                 
	 */
	public function save($one){
		$id = isset($one['id']) ? $one['id'] : '';
		if($id){
			$model = Article::findOne($id);
		}else{
			$model = new Article;
		}
		return Yii::$app->helper->ar->save($model,$one);
	}
	
	public function remove($ids){
		if(is_array($ids) && !empty($ids)){
			foreach($ids as $id){
				$model = Article::findOne($id);
				if($model->id){
					$model->delete();
				}else{
					//throw new InvalidValueException("ID:$id is not exist.");
					Yii::$app->helper->errors->add("article remove ,ID:$id is not exist.");
					return false;
				}
			}
		}else{
			$id = $ids;
			$model = Article::findOne($id);
			if($model->id){
				$model->delete();
			}else{
				Yii::$app->helper->errors->add("article remove ,ID:$id is not exist.");
				return false;
			}
		}
		return true;
		
	}
	
}