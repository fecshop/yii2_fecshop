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
		
		//echo Yii::$service->cmstest->article->storage;exit;
		//$one = Yii::$service->cms->article->getById(1);
		//var_dump($one);
		//$this->attributes
		//$one = ['title' => '444'];
		//echo Yii::$service->cms->article->save($one);
		//exit;
		
		//$r = Yii::$service->cms->article->remove([4,555]);
		//if(!$r)
		//	var_dump(Yii::$service->helper->errors->get());
		//exit;
		//$coll = Yii::$service->cms->article->coll();
		//var_dump($coll);
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		$this->getBlock()->getLastData();
		return $this->render($this->action->id,[]);
	}
	
	public function actionChangecurrency(){
		$currency = \fec\helpers\CRequest::param('currency');
		Yii::$service->page->currency->setCurrentCurrency($currency);
	}
}
















