<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use Yii;
use fec\helpers\CModule;
use fecshop\app\appfront\modules\AppfrontController;
class HomeController extends AppfrontController
{
    public function init(){
		//echo 1222;exit;
		parent::init();
	}
	# 网站信息管理
    public function actionIndex()
    {
		//$dd = new \fecshop\services\cms\Article;
		//echo $dd->gtest22(['22'=>333]);
		//echo 44;
		//exit;
		//echo Yii::$service->cms->article->gtest();
		//exit;
		//echo 22;
		//$dd = new \fecshop\services\Service;
		//echo $dd->ee();
		//exit;
		//return 111;
		//exit;
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
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
	
	public function actionChangecurrency(){
		$currency = \fec\helpers\CRequest::param('currency');
		Yii::$service->page->currency->setCurrentCurrency($currency);
	}
}
















