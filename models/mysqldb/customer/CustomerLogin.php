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
class CustomerLogin extends Customer {
	
	public $username;
	public $password;
	public $captcha;
	private $_admin_user;
	public function rules()
    {
        return [
            [['username', 'password'], 'required'],
			['password', 'validatePassword'],
         //   ['captcha', 'captcha','captchaAction'=>'/fecadmin/captcha/index'],
		//	 ['captcha', 'required'],
        ];
    }
	
	public function validatePassword($attribute,$params){
		
		if (!$this->hasErrors()) {
            $AdminUser = $this->getAdminUser();
            if (!$AdminUser) {
                $this->addError('用户名', '用户名不存在');
            }else if(!$AdminUser->validatePassword($this->password)){
				$this->addError('用户名或密码','不正确');
			}
        }
	}
	
	
	public function getAdminUser(){
		if($this->_admin_user === null){
			$this->_admin_user = AdminUser::findByUsername($this->username);
		}
		return $this->_admin_user;
		
	}
	
	public function login()
    {
        if ($this->validate()) {
            //return \Yii::$app->user->login($this->getAdminUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
			return \Yii::$app->user->login($this->getAdminUser(), 3600 * 24);
        } else {
            return false;
        }
    }
}




