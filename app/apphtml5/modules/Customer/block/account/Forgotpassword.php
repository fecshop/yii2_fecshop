<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\block\account;

use fecshop\app\apphtml5\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Forgotpassword
{
    public function getLastData()
    {
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;

        return [
            'forgotCaptcha' => $forgotCaptcha,
        ];
    }

    public function sendForgotPasswordMailer($editForm)
    {
        $captcha = $editForm['captcha'];
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if ($forgotCaptcha && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $forgotCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        //判断该邮箱是否存在
        if ($identity = $this->getUserIdentity($editForm)) {
            // 生成重置密码的 passwordResetToken

            $passwordResetToken = Yii::$service->customer->generatePasswordResetToken($identity);

            if ($passwordResetToken) {
                $identity['password_reset_token'] = $passwordResetToken;
                $this->sendForgotPasswordEmail($identity);

                return $identity;
            }
        } else {
            Yii::$service->page->message->addError(['email is not exist']);

            return;
        }
    }

    /**
     * 发送忘记密码邮件.
     */
    public function sendForgotPasswordEmail($identity)
    {
        if ($identity) {
            Yii::$service->email->customer->sendForgotPasswordEmail($identity);
        }
    }

    public function getUserIdentity($editForm)
    {
        $email = $editForm['email'];
        if ($email) {
            $identity = Yii::$service->customer->getUserIdentityByEmail($email);
            if ($identity) {
                return $identity;
            }
        }

        return false;
    }
}
