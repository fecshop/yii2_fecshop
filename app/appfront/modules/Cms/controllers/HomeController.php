<?php
namespace fecshop\app\appfront\modules\Cms\controllers;
use Yii;
use fecshop\app\appfront\modules\AppfrontController;
class HomeController extends AppfrontController
{
   
	# 网站信息管理
    public function actionIndex()
    {
		//echo 111;exit;
		echo Yii::$app->page->widget->render('love');
		Yii::$app->page->theme->layoutFile = 'home.php';
		return $this->render($this->action->id,[]);
	}
}
















