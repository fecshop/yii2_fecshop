<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\adminUser;

use fecshop\models\mysqldb\AdminUser;
use Yii;
use yii\base\Model;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUserLogin extends Model
{
    public $username;
    public $password;
    //public $captcha;
    private $_adminUser;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
           
            $adminUser = $this->getAdminUser();
            if (!$adminUser) {
                $this->addError($attribute, 'username is not exist');
            } elseif (!$adminUser->validatePassword($this->password)) {
                $this->addError($attribute, 'user password is not correct');
            }
        }
    }

    public function getAdminUser()
    {
        if ($this->_adminUser === null) {
            $this->_adminUser = AdminUser::findByUsername($this->username);
        }

        return $this->_adminUser;
    }

    /**
     * @param $duration | Int
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
            return \Yii::$app->user->login($this->getAdminUser(), $duration);
        } else {
            return false;
        }
    }
}
