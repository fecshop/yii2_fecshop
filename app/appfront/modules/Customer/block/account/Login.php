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
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Login {
	
	public function getLastData(){
		return [
		
		];
	}
	
	public function login($param){
		if(is_array($param) && !empty($param)){
			Yii::$service->customer->login($param);
		}
		if(!Yii::$app->user->isGuest){
			Yii::$service->url->redirectByUrlKey('customer/account');
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
}