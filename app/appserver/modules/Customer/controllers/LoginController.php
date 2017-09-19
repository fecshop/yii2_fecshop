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
        $loginParam = \Yii::$app->getModule('customer')->params['login'];
        $loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        return [
            'code'              => 200,
            'loginCaptchaActive'  => $loginCaptchaActive,
            'googleLoginUrl'    => Yii::$service->customer->google->getLoginUrl('customer/google/loginv'),
            'facebookLoginUrl'  => Yii::$service->customer->facebook->getLoginUrl('customer/facebook/loginv'),
        ];
        
        
    }
    
}