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
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $leftMenu = $this->getLeftMenu();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'menuList' => $leftMenu,
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
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
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        Yii::$service->customer->logoutByAccessToken();
        Yii::$service->cart->clearCart();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
       
        
    }
    
    
    public function actionForgotpassword()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;

        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'forgotCaptchaActive' => $forgotCaptcha,
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
}