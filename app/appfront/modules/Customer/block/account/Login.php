<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\account;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
use fecshop\app\appfront\helper\mailer\Email;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Login {
	
	
	public function getLastData(){
		$loginParam = \Yii::$app->getModule('customer')->params['login'];
		$loginPageCaptcha = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
		return [
			'loginPageCaptcha' => $loginPageCaptcha,
		];
	}
	
	public function login($param){
		$captcha = $param['captcha'];
		$loginParam = \Yii::$app->getModule('customer')->params['login'];
		$loginPageCaptcha = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
		if($loginPageCaptcha && !$captcha){
			Yii::$service->page->message->addError(['Captcha can not empty']);
			return;
		}else if($captcha && $loginPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)){
			Yii::$service->page->message->addError(['Captcha is not right']);
			return;
		}
		if(is_array($param) && !empty($param)){
			Yii::$service->customer->login($param);
			# 发送邮件
			if($param['email']){
				$this->sendLoginEmail($param['email']);
			}
		}
		if(!Yii::$app->user->isGuest){
			//Yii::$service->url->redirectByUrlKey('customer/account');
			Yii::$service->customer->loginSuccessRedirect('customer/account');
		}
		$errors = Yii::$service->helper->errors->get(true);
		if($errors){
			if(is_array($errors) && !empty($errors)){
				foreach($errors as $error){
					if(is_array($error) && !empty($error)){
						foreach($error as $er){
							
							Yii::$service->page->message->addError($er);
						}
					}
				}
			} 
		}
	}
	/**
	 * 发送登录邮件
	 */
	public function sendLoginEmail($emailAddress){
		if($emailAddress){
			Email::sendLoginEmail($emailAddress);
		}
	}
}