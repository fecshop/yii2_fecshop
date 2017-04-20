<?php

namespace fecshop\app\appapi\modules\V1\controllers;
use Yii;
use yii\web\Response;
use fecshop\app\appapi\modules\AppapiController;

class ProductcategoryController extends AppapiController
{
	
	public $modelClass;
	
	public function init(){
		echo 1;exit;
		//echo 
		# 得到当前service相应的model 
		$this->modelClass = Yii::$service->category->getModelName();
		parent::init();
	}
	
	
	
	
	
	
	
	
}