<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
/**
 * Customer service
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer extends Service
{
	public $customer_register;
	
	/**
	 * 注册用户名字的最小长度
	 */
	protected function actionGetRegisterNameMinLength(){
		if(isset($this->customer_register['min_name_length'])){
			return $this->customer_register['min_name_length'];
		}
	}
	/**
	 * 注册用户名字的最大长度
	 */
	protected function actionGetRegisterNameMaxLength(){
		if(isset($this->customer_register['max_name_length'])){
			return $this->customer_register['max_name_length'];
		}
	}
	/**
	 * 注册用户密码的最小长度
	 */
	protected function actionGetRegisterPassMinLength(){
		if(isset($this->customer_register['min_pass_length'])){
			return $this->customer_register['min_pass_length'];
		}
	}
	/**
	 * 注册用户密码的最大长度
	 */
	protected function actionGetRegisterPassMaxLength(){
		if(isset($this->customer_register['max_pass_length'])){
			return $this->customer_register['max_pass_length'];
		}
	}
	
	/**
	 * @property $data|Array
	 * like :['email'=>'xxx@xxx.com','password'=>'xxxx']
	 */
	protected function actionLogin($data){
		
		
	}
	/**
	 * @property $data|Array
	 * register customer account
	 * ['email','firstname','lastname','password'
	 *	,'sex','age',
	 * ]
	 */
	protected function actionRegister($data){
		
		
	}
	
	/**
	 * @property $customerId|Int
	 * Get customer info by customerId, if customer id is empty, current customer id will be set, 
	 * if current customer id is empty , false will be return .
	 */
	protected function actionViewInfo($customerId = ''){
		
		
	}
	
	/**
	 * @property $password|String
	 * @property $customerId|Int
	 * change  customer password.
	 * if $customer id is empty, it will be equals current customer id.
	 */ 
	protected function actionChangePassword($password,$customerId=''){
		
		
	}
	
	/**
	 * @property $customerId|Array
	 * ['firstname','lastname','password','customerId']
	 */
	protected function actionChangeNameAndPassword($data){
		
	}
	
	/**
	 * get current customer identify.
	 */
	protected function actionGetCurrentAccount(){
		return Yii::$app->user->identity->username;
		
	}
	
	
}