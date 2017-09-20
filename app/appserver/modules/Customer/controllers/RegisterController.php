<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RegisterController extends AppserverController
{
    public $enableCsrfValidation = false ;
    public $_errors;
    
     public function register($param)
    {
        $captcha = $param['captcha'];
        $registerParam = \Yii::$app->getModule('customer')->params['register'];
        $registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if ($registerPageCaptcha && !$captcha) {
            $this->_errors[] = ['Captcha can not empty'];

            return false;
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            $this->_errors[] = ['Captcha is not right'];
            
            return false;
        }
        Yii::$service->customer->register($param);
        $errors = Yii::$service->helper->errors->get(',');
        if (!$errors) {
            // 发送注册邮件
            $this->sendRegisterEmail($param);

            return true;
        }else{
            $this->_errors[] = $errors;
            
            return false;
        }
    }

    /**
     * 发送登录邮件.
     */
    public function sendRegisterEmail($param)
    {
        if ($param) {
            //Email::sendRegisterEmail($param);
            Yii::$service->email->customer->sendRegisterEmail($param);
        }
    }
    
    
    
    public function actionAccount(){
        
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            return [
                'code'         => 400,
                'content'       => 'account is login',
            ];
        }
        $email      = Yii::$app->request->post('email');
        $password   = Yii::$app->request->post('password');
        $firstname  = Yii::$app->request->post('firstname');
        $lastname   = Yii::$app->request->post('lastname');
        $captcha    = Yii::$app->request->post('captcha');
        $is_subscribed = Yii::$app->request->post('is_subscribed');
        
        $param['email']         = $email;
        $param['password']      = $password;
        $param['firstname']     = $firstname;
        $param['lastname']      = $lastname;
        $param['is_subscribed'] = $is_subscribed;
        $param['captcha']       = $captcha;
        
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->register($param);
            //echo $registerStatus;exit;
            if ($registerStatus) {
                $params_register = Yii::$app->getModule('customer')->params['register'];
                $redirect = '/customer/account/login';
                // 注册成功后，是否自动登录
                if (isset($params_register['successAutoLogin']) && $params_register['successAutoLogin']) {
                    $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
                    if($accessToken){
                        $redirect = '/customer/account/index';
                    }
                }
                return [
                    'code' => 200,
                    'content' => 'register success',
                    'redirect' => $redirect,
                ]
            }else{
                return [
                    'code' => 402,
                    'content' => implode(',',$this->_errors);
                ]
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            return [
                'code'         => 400,
                'content'       => 'account is login',
                
            ];
        }
        $email       = Yii::$app->request->post('email');
        $password    = Yii::$app->request->post('password');
        $loginParam = \Yii::$app->getModule('customer')->params['login'];
        $loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        if($loginCaptchaActive){
            $captcha    = Yii::$app->request->post('captcha');
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                return [
                    'code'         => 401,
                    'content'       => 'captcha ['.$captcha.'] is not right',
                ];
            }
        }
        $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
        if($accessToken){
            return [
                'code'         => 200,
                'content'      => 'login success',
            ];
        }else{
            return [
                'code'          => 402,
                'content'       => 'email or password is not right',
                
            ];
        }
        
    }
    
    /**
     * 登录页面
     *
     */
    public function actionIndex(){
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            return [
                'code'          => 400,
                'content'       => 'account is login',
                
            ];
        }
        $registerParam = \Yii::$app->getModule('customer')->params['register'];
        $registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;

        return [
            'code' => 200,
            'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
            'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
            'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
            'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            'registerCaptchaActive' => $registerPageCaptcha,
        ];
        
        
    }
    
}