<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */
namespace fecshop\app\appfront\modules\cms\block\home;
use Yii;
use fec\helpers\CModule;

class Index {
	
	
	public function getLastData(){
		$this->initHead();
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		
		
		
	}
	
	
	public function initHead(){
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => CModule::param('homeMetaKeywords')
		]);
		
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => CModule::param('homeMetaDescription')
		]);
		Yii::$app->view->title = CModule::param('homeTitle');
		
	}
	
}
















