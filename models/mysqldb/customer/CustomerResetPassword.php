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
class CustomerResetPassword extends Customer
{
    public $username;
    public $old_password;
    public $new_password;
    public $password_repeat;
    private $_admin_user;

    public function rules()
    {
        return [
            [['old_password', 'new_password', 'password_repeat'], 'required'],
        //	['username', 'validateLogin'],
            ['new_password', 'validateNewPassword'],
            ['old_password', 'validateOldPassword'],
        ];
    }

    public function getAdminUser()
    {
        if ($this->_admin_user === null) {
            $this->_admin_user = Yii::$app->user->identity;
        }

        return $this->_admin_user;
    }

    public function updatePassword()
    {
        $AdminUser = $this->getAdminUser();
        $AdminUser->setPassword($this->new_password);
        $AdminUser->save();
    }

    public function validateNewPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->new_password != $this->password_repeat) {
                $this->addError($attribute, 'Password and PasswordRepeat is Inconsistent!');

                return;
            }
        }
    }

    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $username = $this->getAdminUser()->username;
            $AdminUser = AdminUser::findByUsername($username);
            if ($AdminUser->validatePassword($this->old_password)) {
            } else {
                $this->addError($attribute, 'old password is not right!');
            }
        }
    }
}
