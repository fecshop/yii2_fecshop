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
class LoginController extends AppserverController
{
    public $enableCsrfValidation = false ;
    /**
     * 登录用户的部分
     */
    public function actionAccount(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $email       = Yii::$app->request->post('email');
        $password    = Yii::$app->request->post('password');
        $loginParam  = \Yii::$app->getModule('customer')->params['login'];
        $loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        if($loginCaptchaActive){
            $captcha    = Yii::$app->request->post('captcha');
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                $code = Yii::$service->helper->appserver->status_invalid_captcha;
                $data = [];
                $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
                
                return $reponseData;
            }
        }
        $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
        if($accessToken){
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }else{
            
            $code = Yii::$service->helper->appserver->account_login_invalid_email_or_password;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        
    }
    
    /**
     * 登录页面
     *
     */
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [ ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $loginParam = \Yii::$app->getModule('customer')->params['login'];
        $loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ 
            'loginCaptchaActive'=> $loginCaptchaActive,
            'googleLoginUrl'    => Yii::$service->customer->google->getLoginUrl('customer/google/loginv'),
            'facebookLoginUrl'  => Yii::$service->customer->facebook->getLoginUrl('customer/facebook/loginv'),
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
}