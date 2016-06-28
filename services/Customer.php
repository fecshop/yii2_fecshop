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
	/**
	 * @property $data|Array
	 * like :['email'=>'xxx@xxx.com','password'=>'xxxx']
	 */
	public function login($data){
		
		
	}
	/**
	 * @property $data|Array
	 * register customer account
	 * ['email','firstname','lastname','password'
	 *	,'sex','age',
	 * ]
	 */
	public function register($data){
		
		
	}
	
	/**
	 * @property $customerId|Int
	 * Get customer info by customerId, if customer id is empty, current customer id will be set, 
	 * if current customer id is empty , false will be return .
	 */
	public function viewInfo($customerId = ''){
		
		
	}
	
	/**
	 * @property $password|String
	 * @property $customerId|Int
	 * change  customer password.
	 * if $customer id is empty, it will be equals current customer id.
	 */ 
	public function changePassword($password,$customerId=''){
		
		
	}
	
	/**
	 * @property $customerId|Array
	 * ['firstname','lastname','password','customerId']
	 */
	public function changeNameAndPassword($data){
		
	}
	
	/**
	 * get current customer identify.
	 */
	public function getCurrentAccount(){
		return Yii::$app->user->identity->username;
		
	}
	
	
}