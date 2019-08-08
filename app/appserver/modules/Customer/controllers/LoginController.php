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
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $email       = Yii::$app->request->post('email');
        $password    = Yii::$app->request->post('password');
        //$loginParam  = \Yii::$app->getModule('customer')->params['login'];
        //$loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $loginPageCaptcha = Yii::$app->store->get($appName.'_account', 'loginPageCaptcha');
        
        $loginCaptchaActive = ($loginPageCaptcha == Yii::$app->store->enable)  ? true : false;
        if($loginCaptchaActive){
            $captcha    = Yii::$app->request->post('captcha');
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                $code = Yii::$service->helper->appserver->status_invalid_captcha;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
            $identity = Yii::$service->customer->getAvailableUserIdentityByEmail($email);
            if (!$identity['email']) {
                $code = Yii::$service->helper->appserver->account_login_invalid_email_or_password;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
            // 账户未激活
            if ($identity['status'] == $identity::STATUS_REGISTER_DISABLE) {
                
                $code = Yii::$service->helper->appserver->account_register_disable;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            
            }
        }
        $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
        if($accessToken){
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }else{
            
            $code = Yii::$service->helper->appserver->account_login_invalid_email_or_password;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
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
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $appName = Yii::$service->helper->getAppName();
        $loginPageCaptcha = Yii::$app->store->get($appName.'_account', 'loginPageCaptcha');
        
        $loginCaptchaActive = ($loginPageCaptcha == Yii::$app->store->enable)  ? true : false;
        
        //$loginParam = \Yii::$app->getModule('customer')->params['login'];
        //$loginCaptchaActive = isset($loginParam['loginPageCaptcha']) ? $loginParam['loginPageCaptcha'] : false;
        $googleRedirectUrl   = Yii::$app->request->get('googleRedirectUrl');
        $facebookRedirectUrl = Yii::$app->request->get('facebookRedirectUrl');
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ 
            'loginCaptchaActive'=> $loginCaptchaActive,
            'googleLoginUrl'    => Yii::$service->customer->google->getLoginUrl($googleRedirectUrl,true),
            'facebookLoginUrl'  => Yii::$service->customer->facebook->getLoginUrl($facebookRedirectUrl,true),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    /**
     * 登录页面
     *
     */
    public function actionWxindex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            // 用户已经登录
            $code = Yii::$service->helper->appserver->account_is_logined;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [  ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    // 登陆用户
    public function loginByIdentity($identity, $wx_session_key)
    {
        // 进行用户登陆
        return Yii::$service->customer->loginByIdentityAndGetAccessToken($identity, $wx_session_key);
        
    }
    // 绑定账户
    public function actionBindaccount()
    {
        $wxCode = Yii::$app->request->post('code');
        //echo $wxCode;
        // 通过code 和 微信的一些验证信息，得到微信的信息uid
        $wxUserInfo = Yii::$service->helper->wx->getUserInfoByCode($wxCode);
        // 如果通过code获取微信信息（api获取）失败
        if (!$wxUserInfo) {
            // code  获取openid失败
            $code = Yii::$service->helper->appserver->account_wx_get_user_info_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // 得到 openid  和  session_key
        $wx_openid = $wxUserInfo['openid'];
        $wx_session_key = $wxUserInfo['session_key'];
        
        if (!$wx_openid || !$wx_session_key) {
            $code = Yii::$service->helper->appserver->no_account_openid_and_session_key;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        if (Yii::$service->customer->getByWxOpenid($wx_openid)) {
            // 已经存在绑定的用户，绑定失败
            $code = Yii::$service->helper->appserver->account_has_account_openid;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $isBindNew = $wxCode = Yii::$app->request->post('isBindNew');
        $email = $wxCode = Yii::$app->request->post('email');
        $password = $wxCode = Yii::$app->request->post('password');
        if ($isBindNew == 1) {  // 进行注册
            // 查看该email是否存在
            if (Yii::$service->customer->isRegistered($email)) {
                $code = Yii::$service->helper->appserver->account_register_email_exit;
                $data = [
                    'errors' => Yii::$service->helper->errors->get(),
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
            $param = [
                'email' => $email,
                'password' => $password,
            ];
            
            if (!Yii::$service->customer->register($param)) {
                
                $code = Yii::$service->helper->appserver->account_register_fail;
                $data = [
                    'errors' => Yii::$service->helper->errors->get(),
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        // 账户登陆，如果登陆成功，则绑定wx_session_key 和 wx_openid
        $access_token = Yii::$service->customer->loginAndGetAccessToken($email, $password);
        if ($access_token) {
            $identity = Yii::$app->user->identity;
            $identity->wx_session_key = $wx_session_key;
            $identity->wx_openid = $wx_openid;
            $identity->save();
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } else {
            $code = Yii::$service->helper->appserver->account_login_and_get_access_token_fail;
            $data = [
                'errors' => Yii::$service->helper->errors->get(),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
    }
    
    /**
      * 通过code，通过api获取session_key 和 openid
      * 1.通过openid，查询数据库是否存在相应的用户
      * 2.如果不存在则需要微信小程序进行用户绑定，或者新用户注册
      * 3.如果存在用户，则进行登陆
      */
    public function actionWxcode()
    {
        $wxCode = Yii::$app->request->post('code');
        //echo $wxCode;
        // 通过code 和 微信的一些验证信息，得到微信的信息uid
        $wxUserInfo = Yii::$service->helper->wx->getUserInfoByCode($wxCode);
        // 如果通过code获取微信信息（api获取）失败
        if (!$wxUserInfo) {
            // code  获取openid失败
            $code = Yii::$service->helper->appserver->account_wx_get_user_info_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $session_key = $wxUserInfo['session_key'];
        $openid = $wxUserInfo['openid'];
        // 通过 $openid  得到 user
        $customer = Yii::$service->customer->getByWxOpenid($openid);
        // 如果$openid 没有 对应的customer，则需要先绑定或者创建相应的账户
        if (!$customer) {
            $code = Yii::$service->helper->appserver->account_wx_get_customer_by_openid_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        // 如果登陆失败
        if (!$this->loginByIdentity($customer, $session_key)) {
            $code = Yii::$service->helper->appserver->account_wx_user_login_fail;
            $data = [ ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // 登陆成功 更新信息
        $identity = Yii::$app->user->identity;
        $identity->wx_session_key = $session_key;
        // $identity->wx_openid = $openid;
        $identity->save();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [  ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
        
        
        // 然后再数据库中查询，是否有相关数据，如果存在，则进行账户登陆，返回access-token
        
        
        
        // 如果没有数据，要求用户绑定，将uid保存到session中，前端页面，用户可以绑定已有的账户，或者新的邮箱账户
        
        
        // 用户提交邮箱和密码，如果是已有账户，那么进行验证，成功后，绑定微信uid，如果不存在，则返回错误
        
        // 如果是新的邮箱和密码，则邮箱验证成功后，直接绑定。
        
        
        // 绑定成功后，进行账户登陆，返回access-token
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}