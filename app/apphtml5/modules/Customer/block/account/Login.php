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
class Login
{
    public function getLastData($param = '')
    {
        //$loginParam = \Yii::$app->getModule('customer')->params['login'];
        $appName = Yii::$service->helper->getAppName();
        $loginPageCaptcha = Yii::$app->store->get($appName.'_account', 'loginPageCaptcha');
        $loginPageCaptcha = ($loginPageCaptcha == Yii::$app->store->enable)  ? true : false;
        
        $email = isset($param['email']) ? $param['email'] : '';

        return [
            'loginPageCaptcha' => $loginPageCaptcha,
            'email' => $email,
            'googleLoginUrl' => Yii::$service->customer->google->getLoginUrl('customer/google/loginv'),
            'facebookLoginUrl' => Yii::$service->customer->facebook->getLoginUrl('customer/facebook/loginv'),
        ];
    }

    public function login($param)
    {
        $captcha = $param['captcha'];
        //$loginParam = \Yii::$app->getModule('customer')->params['login'];
        //$loginPageCaptcha = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $loginPageCaptcha = Yii::$app->store->get($appName.'_account', 'loginPageCaptcha');
        $loginPageCaptcha = ($loginPageCaptcha == Yii::$app->store->enable)  ? true : false;
        if ($loginPageCaptcha && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $loginPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
            $email = $param['email'];
            $identity = Yii::$service->customer->getAvailableUserIdentityByEmail($email);
            if (!$identity['email']) {
                Yii::$service->page->message->addError(['this email is not exit']);
                return;
            }
            if ($identity['status'] == $identity::STATUS_REGISTER_DISABLE) {
                $correctMessage = Yii::$service->page->translate->__("Your account is not activated. You need to open the activation link in your email to activate. If you have not received the email, you can resend the email by {url_click_here_before}clicking here{url_click_here_end} {end_text}", ['url_click_here_before' => '<span  class="email_register_resend" >',  'url_click_here_end' => '</span>', 'end_text'=> '<span class="resend_text"></span>' ]);
                Yii::$service->page->message->addError($correctMessage);  
                return true;
            }
        }
        if (is_array($param) && !empty($param)) {
            if (Yii::$service->customer->login($param)) {
                // 发送邮件
                if ($param['email']) {
                    $this->sendLoginEmail($param);
                }
            }
        }
        Yii::$service->page->message->addByHelperErrors();
    }

    /**
     * 发送登录邮件.
     */
    public function sendLoginEmail($param)
    {
        if ($param) {
            Yii::$service->email->customer->sendLoginEmail($param);
        }
    }
}
