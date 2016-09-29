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
use yii\base\Model;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomerLogin extends Model {
	
	public $email;
	public $password;
	//public $captcha;
	private $_customer;
	public function rules()
    {
        return [
            [['email', 'password'], 'required'],
			['email','email'],
			['password', 'validatePassword'],
        ];
    }
	
	public function validatePassword($attribute,$params){
		
		if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer) {
                $this->addError($attribute,'email is not exist');
            }else if(!$customer->validatePassword($this->password)){
				$this->addError($attribute,'user password is not correct');
			}
        }
	}
	
	
	public function getCustomer(){
		if($this->_customer === null){
			$this->_customer = Customer::findByEmail($this->email);
		}
		return $this->_customer;
		
	}
	
	public function login()
    {
        if ($this->validate()) {
            //return \Yii::$app->user->login($this->getAdminUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
			return \Yii::$app->user->login($this->getCustomer(), 3600 * 24);
        } else {
            return false;
        }
    }
}




