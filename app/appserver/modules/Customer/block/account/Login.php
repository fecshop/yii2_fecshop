<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\block\account;

use fecshop\app\appfront\helper\mailer\Email;
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
        if (is_array($param) && !empty($param)) {
            if (Yii::$service->customer->login($param)) {
                // 如果需要发送登陆邮件，则执行发送
                if ($param['email']) {
                    $this->sendLoginEmail($param);
                }
            }
        }
        Yii::$service->page->message->addByHelperErrors();
    }

    /**
     * 发送用户登陆邮件
     */
    public function sendLoginEmail($param)
    {
        if ($param) {
            Yii::$service->email->customer->sendLoginEmail($param);
        }
    }
}
