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
class AccountController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';
	
	public function init(){
		parent::init();
	}
	public function actionIndex(){
		if(Yii::$app->user->isGuest){
			Yii::$service->url->redirectByUrlKey('customer/account/login');
		}
		$data = $this->getBlock()->getLastData();
		
		return $this->render($this->action->id,$data);
	}
    public function actionLogin()
    {
		$param = Yii::$app->request->post('editForm');
		$this->getBlock()->login($param);
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
	
	public function actionRegister()
    {
		$param = Yii::$app->request->post('editForm');
		
		if(!empty($param)){
			$registerStatus = $this->getBlock()->register($param);
			//echo $registerStatus;exit;
			if($registerStatus){
				$params_register = Yii::$app->getModule('customer')->params['register'];
				# 注册成功后，是否自动登录
				if(isset($params_register['successAutoLogin']) && $params_register['successAutoLogin']  ){
					Yii::$service->customer->login($param);
				}
				# 注册成功后，跳转的页面，如果值为false， 则不跳转。
				if(isset($params_register['loginSuccessRedirectUrlKey']) && $params_register['loginSuccessRedirectUrlKey']  ){
					$redirectUrl = Yii::$service->url->getUrl($params_register['loginSuccessRedirectUrlKey']);
					Yii::$service->url->redirect($redirectUrl);
				}
			}
		}
		$data = $this->getBlock()->getLastData($param);
		return $this->render($this->action->id,$data);
	}
	
	
	public function actionLogout(){
		$rt = Yii::$app->request->get('rt');
		if(!Yii::$app->user->isGuest){
			
				Yii::$app->user->logout();
				
			}
		
		if($rt){
			$redirectUrl = base64_decode($rt);
			//exit;
			Yii::$service->url->redirect($redirectUrl);
		}else{
			Yii::$service->url->redirect(Yii::$service->url->HomeUrl());
		}
	}
	
	public function actionLogininfo(){
		if(!Yii::$app->user->isGuest){
			return json_encode([
				'loginStatus' => true,
			]);
		}
	}
	
}
















