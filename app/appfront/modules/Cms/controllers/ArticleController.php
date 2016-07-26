<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
class ArticleController extends AppfrontController
{
    public function init(){
		parent::init();
	}
	# 网站信息管理
    public function actionIndex()
    {
		//$primaryKey = Yii::$service->cms->article->getPrimaryKey();
		//$article = Yii::$service->cms->article->getByPrimaryKey(CRequest::param($primaryKey));
		//var_dump($article);
		//echo 'article';
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	public function actionChangecurrency(){
		$currency = \fec\helpers\CRequest::param('currency');
		Yii::$service->page->currency->setCurrentCurrency($currency);
	}
}
















