<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\account;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Register
{
    public function getLastData($param)
    {
        $firstname = isset($param['firstname']) ? $param['firstname'] : '';
        $lastname = isset($param['lastname']) ? $param['lastname'] : '';
        $email = isset($param['email']) ? $param['email'] : '';
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerPageCaptcha');
        $this->breadcrumbs(Yii::$service->page->translate->__('Register'));
        return [
            'firstname'        => $firstname,
            'lastname'         => $lastname,
            'email'            => $email,
            'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
            'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
            'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
            'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            'registerPageCaptcha' => ($registerPageCaptcha == Yii::$app->store->enable ? true : false),
        ];
    }
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['register_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }

    public function register($param)
    {
        $captcha = $param['captcha'];
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerPageCaptcha');
        
        //$registerParam = \Yii::$app->getModule('customer')->params['register'];
        //$registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if (($registerPageCaptcha == Yii::$app->store->enable)  && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        Yii::$service->customer->register($param);
        
        $errors = Yii::$service->page->message->addByHelperErrors();
        if (!$errors) {
            // 发送注册邮件
            $this->sendRegisterEmail($param);

            return true;
        }
    }

   /**
     * 发送登录邮件.
     */
    public function sendRegisterEmail($param)
    {
        if ($param) {
            //Email::sendRegisterEmail($param); 
            if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
                $registerEnableToken = Yii::$service->customer->generateRegisterEnableToken($param['email']);
                if ($registerEnableToken) {
                    $param['register_enable_token'] = $registerEnableToken;
                    
                    Yii::$service->email->customer->sendRegisterEmail($param);
                    return true;
                }
            } else {
                Yii::$service->email->customer->sendRegisterEmail($param);
                return true;
            }
            
        }
    }
}
