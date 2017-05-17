<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Customer\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\apphtml5\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductfavoriteController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';
	
	public function actionIndex(){
		if(Yii::$app->user->isGuest){
			return Yii::$service->url->redirectByUrlKey('customer/account/login');
		}
		$type = Yii::$app->request->get('type');
		$favorite_id = Yii::$app->request->get('favorite_id');
		if($type && $favorite_id){
			$this->getBlock()->remove($favorite_id);
		}
		$data = $this->getBlock()->getLastData();
		if(is_array($data) && !empty($data)){
			return $this->render($this->action->id,$data);
		}else{
			return ;
		}
	}
	
	
	
}
















