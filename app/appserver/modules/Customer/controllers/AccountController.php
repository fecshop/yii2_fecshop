<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends AppserverTokenController
{
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $leftMenu = $this->getLeftMenu();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'menuList' => $leftMenu,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    
    public function getLeftMenu()
    {
        $leftMenu = \Yii::$app->getModule('customer')->params['leftMenu'];
        if (is_array($leftMenu) && !empty($leftMenu)) {
            $arr = [];
            foreach ($leftMenu as $name => $url) {
                $name = Yii::$service->page->translate->__($name);
                $arr[$name] = $url;
            }
            return $arr;
        }else{
            return [];
        }
        
    }
    
    
    /**
     * 登出账户.
     */
    public function actionLogout()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        Yii::$service->customer->logoutByAccessToken();
        //Yii::$service->cart->clearCart();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
       
        
    }
    
    
    public function actionForgotpassword()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        //$forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        //$forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $forgotCaptcha = Yii::$app->store->get($appName.'_account', 'forgotPasswordCaptcha');
        $forgotCaptcha = ($forgotCaptcha == Yii::$app->store->enable)  ? true : false;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'forgotCaptchaActive' => $forgotCaptcha,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    
}