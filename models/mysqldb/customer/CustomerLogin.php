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
use Yii;
use yii\base\Model;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomerLogin extends Model
{
    public $email;
    public $password;
    //public $captcha;
    private $_customer;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer) {
                $this->addError($attribute, 'email is not exist');
            } elseif (!$customer->validatePassword($this->password)) {
                $this->addError($attribute, 'user password is not correct');
            }
        }
    }

    public function getCustomer()
    {
        if ($this->_customer === null) {
            $this->_customer = Customer::findByEmail($this->email);
        }

        return $this->_customer;
    }

    /**
     * @property $duration | Int
     * 对于参数$duration：
     * 1. 当不开启cookie时，$duration的设置是无效的，yii2只会从user组件Yii::$app->user->authTimeout
     *    中读取过期时间
     * 2. 当开启cookie，$duration是有效的，会设置cookie的过期时间。
     *	  如果不传递时间，默认使用 Yii::$service->session->timeout的值。
     * 总之，为了方便处理cookie和session的超时时间，统一使用
     * session的超时时间，这样做的好处为，可以让account 和 cart session的超时时间保持一致
     */
    public function login($duration = 0)
    {
        if (!$duration) {
            if (Yii::$service->session->timeout) {
                $duration = Yii::$service->session->timeout;
            }
        }
        if ($this->validate()) {
            //return \Yii::$app->user->login($this->getAdminUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            return \Yii::$app->user->login($this->getCustomer(), $duration);
        } else {
            return false;
        }
    }
}
