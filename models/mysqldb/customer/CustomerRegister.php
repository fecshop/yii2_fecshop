<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mysqldb\customer;
use fecshop\models\mysqldb\Customer;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomerRegister extends Customer {
	private $_admin_user;
	
	private $_rules;
	
	public function setCustomerRules($rules){
		$this->_rules = $rules;
	}
	
	public function rules()
    {
       
		
		$parent_rules  = parent::rules();
		$current_rules = [
		
			['email', 'filter', 'filter' => 'trim'],
			['email','email'],
			['email','validateEmail'],
			
			['password', 'filter', 'filter' => 'trim'],
			
			['firstname', 'filter', 'filter' => 'trim'],
			['lastname' , 'filter', 'filter' => 'trim'],
			['is_subscribed', 'validateIsSubscribed'],
        //    ['email', 'required'],
        //    ['email', 'email'],
        //    ['email', 'string', 'max' => 255],
		//	['email', 'validateEmail'],
		//	['code', 'string', 'min' => 5, 'max' => 5],
			
		//	['role', 'required'],
			
		//	['person', 'required'],
			
        //    ['email', 'unique', 'targetClass' => '\fecadmin\models\AdminUser', 'message' => 'This email address has already been taken.'],

		//	['password', 'required'],
         //   ['password', 'string', 'min' => 6],
			
		];
		
		
		$rules =  array_merge($parent_rules,$current_rules) ;
		if(is_array($this->_rules)){
			$rules =  array_merge($rules,$this->_rules) ;
		}
		return $rules;
    }
	
	public function validateIsSubscribed($attribute, $params){
		if($this->is_subscribed != 1){
			$this->is_subscribed = 2;
		}
	}
	
	
	
	public function validateEmail($attribute, $params){
		if($this->id){
			$one = Customer::find()
				->where(" id != :id AND email = :email ",[':id'=>$this->id,':email'=>$this->email])
				->one();
			if($one['id']){
				$this->addError($attribute,"this email is exist!");
			}
			
		}else{
			$one = Customer::find()
				->where('email = :email',[':email' => $this->email])
				->one();
			if($one['id']){
				$this->addError($attribute,"this email is exist!");
			}
		}
	}
	
	
	
	public function setPassword($password)
    {
		if($this->password){
			$this->password_hash = \Yii::$app->security->generatePasswordHash($password);
			$this->password = '';
		}
    }
	
	# 重写保存方法
	public function save($runValidation = true, $attributeNames = NULL){
		
		
		# 如果auth_key为空，则重置
		if(!$this->auth_key){
			$this->generateAuthKey();
		}
		# 如果access_token为空，则重置
		if(!$this->access_token){
			$this->generateAccessToken();
		}
		# 设置password
		$this->setPassword($this->password);
		parent::save($runValidation,$attributeNames);
	}
}




