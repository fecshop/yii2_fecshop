<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Checkout\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OnepageController extends AppfrontController
{
    public $enableCsrfValidation = false;
	
	public function actionIndex(){
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
		
	}
	
	
	public function actionChangecountry(){
		$this->getBlock('index')->ajaxChangecountry();
		
	}
	
	public function actionAjaxupdateorder(){
		$this->getBlock('index')->ajaxUpdateOrder();
		
	}
	
}
















