<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\editaccount;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	public function getLastData(){
		$identity = Yii::$app->user->identity;
		return [
			'firstname' 	=> $identity['firstname'],
			'email' 		=> $identity['email'],
			'lastname' 		=> $identity['lastname'],
			'actionUrl'		=> Yii::$service->url->getUrl('customer/editaccount'),
		];
	}
	/**
	 * @property $editForm|Array
	 * 保存修改后的用户信息。
	 */
	public function saveAccount($editForm){
		$identity = Yii::$app->user->identity;
		$firstname 			= $editForm['firstname'] ? $editForm['firstname'] : '';
		$lastname 			= $editForm['lastname'] ? $editForm['lastname'] : '';
		$current_password 	= $editForm['current_password'] ? $editForm['current_password'] : '';
		$password 			= $editForm['password'] ? $editForm['password'] : '';
		$confirmation 		= $editForm['confirmation'] ? $editForm['confirmation'] : '';
		if(!$firstname || !$lastname){
			Yii::$service->page->message->addError('first name and last name can not empty');
			return;
		}
		if(!$current_password){
			Yii::$service->page->message->addError('current password can not empty');
			return;
		}
		if(!$password || !$confirmation){
			Yii::$service->page->message->addError('password and confirmation password can not empty');
			return;
		}
		if($password != $confirmation){
			Yii::$service->page->message->addError('password and confirmation password  must be equal');
			return;
		}
		
		if(!$identity->validatePassword($password)){
			Yii::$service->page->message->addError('Current password is not right,If you forget your password, you can retrieve your password by forgetting your password in login page');
			return;
		}
		$identity->firstname =  $firstname;
		$identity->lastname  =  $lastname;
		$identity->password =  $password;
		$identity->save();
		if($identity->)
	}
	
	
	
	
	
	
	
	
	
}