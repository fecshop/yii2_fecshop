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
    
    public function validateParam($email,$password,$firstname,$lastname,$captcha){
        $minNameLength = Yii::$service->customer->getRegisterNameMinLength();
        $maxNameLength = Yii::$service->customer->getRegisterNameMaxLength();
        $minPassLength = Yii::$service->customer->getRegisterPassMinLength();
        $maxPassLength = Yii::$service->customer->getRegisterPassMaxLength();
            
        $registerParam = \Yii::$app->getModule('customer')->params['register'];
        $registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if ($registerPageCaptcha && !$captcha) {
            
            return [
                'code'         => 401,
                'content'       => 'Captcha can not empty',
            ];
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            
            return [
                'code'         => 401,
                'content'       => 'Captcha is not right',
            ];
        } elseif (!$email) {
            return [
                'code'         => 401,
                'content'       => 'email can not empty',
            ];
        } elseif (!$password) {
            return [
                'code'         => 401,
                'content'       => 'password can not empty',
            ];
        } elseif (strlen($password) < $minPassLength || strlen($password) > $maxPassLength) {
            return [
                'code'         => 401,
                'content'       => 'password must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        } elseif (strlen($firstname) < $minNameLength || strlen($firstname) > $maxNameLength) {
            return [
                'code'         => 401,
                'content'       => 'firstname must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        } elseif (strlen($lastname) < $minNameLength || strlen($lastname) > $maxNameLength) {
            return [
                'code'         => 401,
                'content'       => 'lastname must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        }
        return false;
        
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
        $is_subscribed = $is_subscribed ? 1 : 2;
        if($errorInfo = $this->validateParam($email,$password,$firstname,$lastname,$captcha)){
            return $errorInfo;
        }
        
        $param['email']         = $email;
        $param['password']      = $password;
        $param['firstname']     = $firstname;
        $param['lastname']      = $lastname;
        $param['is_subscribed'] = $is_subscribed;
        
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->register($param);
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
                ];
            }else{
                return [
                    'code' => 402,
                    'content' => implode(',',$this->_errors),
                ];
            }
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