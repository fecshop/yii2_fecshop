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
class Register {
	
	public function getLastData($param){
		$firstname 		= isset($param['firstname']) ? $param['firstname'] : '';
		$lastname 		= isset($param['lastname']) ? $param['lastname'] : '';
		$email 			= isset($param['email']) ? $param['email'] : '';
		return [
			'firstname'		=> $firstname,
			'lastname'		=> $lastname,
			'email'			=> $email,
			'is_subscribed'	=> $is_subscribed,
			'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
			'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
			'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
			'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
		
		];
	}
	
	public function register($param){
		Yii::$service->customer->register($param);
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
		}else{
			return true;
		}
	}
}



