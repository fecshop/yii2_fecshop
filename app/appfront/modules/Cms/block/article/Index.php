<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */
namespace fecshop\app\appfront\modules\cms\block\article;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	
	protected $_artile;
	
	public function getLastData(){
		
		echo Yii::$app->page->translate->__('fecshop,{username}', ['username' => 'terry']);
		$this->initHead();
		# change current layout File.
		//Yii::$app->page->theme->layoutFile = 'home.php';
		return [
			'title' => $this->_artile['title'],
			'content' => $this->_artile['content'],
			'created_at' => $this->_artile['created_at'],
		];
	}
	
	public function initHead(){
		$primaryKey = Yii::$app->cms->article->getPrimaryKey();
		$primaryVal = CRequest::param($primaryKey);
		$article = Yii::$app->cms->article->getByPrimaryKey($primaryVal);
		$this->_artile = $article ;
		
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => $article['meta_keywords'],
		]);
		
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => $article['meta_description'],
		]);
		Yii::$app->view->title = $article['title'];
		
	}
	
}
















