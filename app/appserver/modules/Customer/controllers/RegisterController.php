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
    
    public function validateParam($email,$password,$firstname,$lastname,$captcha){
        $minNameLength = Yii::$service->customer->getRegisterNameMinLength();
        $maxNameLength = Yii::$service->customer->getRegisterNameMaxLength();
        $minPassLength = Yii::$service->customer->getRegisterPassMinLength();
        $maxPassLength = Yii::$service->customer->getRegisterPassMaxLength();
            
        //$registerParam = \Yii::$app->getModule('customer')->params['register'];
        //$registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerPageCaptcha');
        $registerPageCaptcha = ($registerPageCaptcha == Yii::$app->store->enable)  ? true : false;
        $errorArr = [];
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if ($registerPageCaptcha && !$captcha) {
            $errorArr[] = 'Captcha can not empty';
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            $errorArr[] = 'Captcha is not right';
        } elseif (!$email) {
            $errorArr[] = 'email can not empty';
        } elseif (!$password) {
            $errorArr[] = 'password can not empty';
        } elseif (strlen($password) < $minPassLength || strlen($password) > $maxPassLength) {
            $errorArr[] = 'password must >= '.$minPassLength.' and <= '.$maxPassLength;
        } elseif (strlen($firstname) < $minNameLength || strlen($firstname) > $maxNameLength) {
            $errorArr[] = 'firstname must >= '.$minPassLength.' and <= '.$maxPassLength;
        } elseif (strlen($lastname) < $minNameLength || strlen($lastname) > $maxNameLength) {
            $errorArr[] = 'lastname must >= '.$minPassLength.' and <= '.$maxPassLength;
        }
        if (!empty($errorArr)) {
            return implode(',',$errorArr);
        } else {
            return true;
        }
    }
    
    
    
    public function actionAccount(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $email      = Yii::$app->request->post('email');
        $password   = Yii::$app->request->post('password');
        $firstname  = Yii::$app->request->post('firstname');
        $lastname   = Yii::$app->request->post('lastname');
        $captcha    = Yii::$app->request->post('captcha');
        $is_subscribed = Yii::$app->request->post('is_subscribed');
        $domain       = Yii::$app->request->post('domain');
        $domain = trim($domain,'/').'/';
        //echo $domain;exit;
        Yii::$service->helper->setAppServiceDomain($domain);
        
        $is_subscribed = $is_subscribed ? 1 : 2;
        $errorInfo = $this->validateParam($email,$password,$firstname,$lastname,$captcha);
        if($errorInfo !== true){
            $code = Yii::$service->helper->appserver->account_register_invalid_data;
            $data = [
                'error' => $errorInfo,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
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
                //$params_register = Yii::$app->getModule('customer')->params['register'];
                $appName = Yii::$service->helper->getAppName();
                $registerSuccessAutoLogin = Yii::$app->store->get($appName.'_account', 'registerSuccessAutoLogin');
                //$registerSuccessRedirectUrlKey = Yii::$app->store->get($appName.'_account', 'registerSuccessRedirectUrlKey');
                
                $redirect = '/customer/account/login';
                
                // 是否需要邮件激活？
                if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
                    $correctMessage = Yii::$service->page->translate->__("Your account registration is successful, we sent an email to your email, you need to login to your email and click the activation link to activate your account. If you have not received the email, you can resend the email by {url_click_here_before}clicking here{url_click_here_end} {end_text}", ['url_click_here_before' => '<span  class="email_register_resend" >',  'url_click_here_end' => '</span>', 'end_text'=> '<span class="resend_text"></span>' ]);
                    Yii::$service->page->message->AddCorrect($correctMessage); 
                    $code = Yii::$service->helper->appserver->account_register_disable;
                    $data = [
                        'content' => 'register success and need enable by email',
                        //'redirect' => $redirect,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    
                    return $responseData;
                } else { // 如果不需要邮件激活？
                    // 注册成功后，是否自动登录
                    if ($registerSuccessAutoLogin == Yii::$app->store->enable) {
                        $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
                        if($accessToken){
                            $redirect = '/customer/account/index';
                        }
                    }
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                        'content' => 'register success',
                        //'redirect' => $redirect,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    
                    return $responseData;
                }
            }else{
                $code = Yii::$service->helper->appserver->account_register_fail;
                $data = [
                    'error' => implode(',',$this->_errors),
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        
    }
    
    /**
     * register页面
     *
     */
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        //$registerParam = \Yii::$app->getModule('customer')->params['register'];
        //$registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $registerPageCaptcha = Yii::$app->store->get($appName.'_account', 'registerSuccessAutoLogin');
        $registerPageCaptcha = ($registerPageCaptcha == Yii::$app->store->enable)  ? true : false;
        //$registerParam = \Yii::$app->getModule('customer')->params['register'];
        //$registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
            'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
            'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
            'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            'registerCaptchaActive' => $registerPageCaptcha,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionTokenenable()
    {
        $registerToken = Yii::$app->request->get('registerToken');
        $status = Yii::$service->customer->registerEnableByTokenAndClearToken($registerToken);
        if (!$status) {
            $code = Yii::$service->helper->appserver->account_register_enable_token_invalid;
            $data = [
                'error' => 'Register Account Enable Token is Expired',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    public function actionResendregisteremail()
    {
        $email = Yii::$app->request->get('email');
        $domain       = Yii::$app->request->get('domain');
        $domain = trim($domain,'/').'/';
        //echo $domain;exit;
        Yii::$service->helper->setAppServiceDomain($domain);
        
        $identity = Yii::$service->customer->getAvailableUserIdentityByEmail($email);
        
        if ($identity['status'] != $identity::STATUS_REGISTER_DISABLE) {
            $code = Yii::$service->helper->appserver->account_register_send_email_fail;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $this->sendRegisterEmail($identity);
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
   
}