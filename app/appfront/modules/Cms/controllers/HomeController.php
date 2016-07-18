<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use Yii;
use fec\helpers\CModule;
use fecshop\app\appfront\modules\AppfrontController;
class HomeController extends AppfrontController
{
    public function init(){
		parent::init();
	}
	# 网站信息管理
    public function actionIndex()
    {
		//$one = Yii::$app->cms->article->getById(1);
		//var_dump($one);
		//$this->attributes
		//$one = ['title' => '444'];
		//echo Yii::$app->cms->article->save($one);
		//exit;
		
		//$r = Yii::$app->cms->article->remove([4,555]);
		//if(!$r)
		//	var_dump(Yii::$app->helper->errors->get());
		//exit;
		//$coll = Yii::$app->cms->article->coll();
		//var_dump($coll);
		# change current layout File.
		//Yii::$app->page->theme->layoutFile = 'home.php';
		$this->getBlock()->getLastData();
		return $this->render($this->action->id,[]);
	}
	
	public function actionChangecurrency(){
		$currency = \fec\helpers\CRequest::param('currency');
		Yii::$app->page->currency->setCurrentCurrency($currency);
	}
}
















