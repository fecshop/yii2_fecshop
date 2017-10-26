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
            return [
                'code' => 400,
                'content' => 'no login'
            ];
        }
        $leftMenu = $this->getLeftMenu();
        return [
            'code' => 200,
            'menuList' => $leftMenu,
        ];
    
    }
    
    
    
    public function getLeftMenu()
    {
        $leftMenu = \Yii::$app->getModule('customer')->params['leftMenu'];
        if (is_array($leftMenu) && !empty($leftMenu)) {
            return $leftMenu;
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
            return [
                'code' => 400,
                'content' => 'no login'
            ];
        }
        Yii::$service->customer->logoutByAccessToken();
        Yii::$service->cart->clearCart();
        return [
            'code' => 200,
            'content' => 'logout success'
        ];
       
        
    }
    
    
    public function actionForgotpassword()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptcha = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;

        return [
            'code' => 200,
            'forgotCaptchaActive' => $forgotCaptcha,
        ];
        
    }
    
}