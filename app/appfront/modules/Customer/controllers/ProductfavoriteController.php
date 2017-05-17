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
class ProductfavoriteController extends AppfrontController
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
		$type = Yii::$app->request->get('type');
		$favorite_id = Yii::$app->request->get('favorite_id');
		if($type && $favorite_id){
			$this->getBlock()->remove($favorite_id);
		}
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
}
















