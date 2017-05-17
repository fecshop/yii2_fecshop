<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EditaccountController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';
	
	public function init(){
		if(Yii::$app->user->isGuest){
			return Yii::$service->url->redirectByUrlKey('customer/account/login');
		}
		parent::init();
	}
	/**
	 * 
	 */
	public function actionIndex(){
		$editForm = Yii::$app->request->post('editForm');
		if(!empty($editForm)){
			$this->getBlock()->saveAccount($editForm);
		}
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
}
















