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
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ForgotController extends AppserverController
{
    
    public function actionPassword()
    {
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity['id']){
            // 用户已经登录
            return [
                'code'         => 400,
                'content'       => 'account is login',
                
            ];
        }
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptchaActive = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;

        return [
            'code' => 200,
            'forgotCaptchaActive' => $forgotCaptchaActive,
        ];
        
    }
    
    public function actionResetpassword(){
        $resetToken = Yii::$app->request->get('resetToken');
        $identity = Yii::$service->customer->findByPasswordResetToken($resetToken);
        //var_dump($identity );exit;
        if ($identity) {
            return [
                'code' => 200,
                'resetPasswordActive' => true,
            ];
        } else {
            return [
                'code' => 401,
                'resetPasswordActive' => false,
            ];
        }
        
        
    }
    
    public function actionSubmitresetpassword(){
        $resetToken = Yii::$app->request->post('resetToken');
        $identity = Yii::$service->customer->findByPasswordResetToken($resetToken);
        //var_dump($identity );exit;
        if (!$identity) {
            return [
                'code' => 401,
                'resetPasswordActive' => false,
            ];
        }
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('newPassword');
        $confirmation = Yii::$app->request->post('confirmPassword');
        
        if (!$email || !$password || !$confirmation) {
            return [
                'code' => 402,
                'content' => 'email or password can not empty',
            ];
        }
        if ($password != $confirmation) {
            return [
                'code' => 402,
                'content' => 'new password and confirmation password must be consistent',
            ];
        }
        
        if ($identity['email'] != $email) {
            return [
                'code' => 402,
                'content' => 'email do not match the resetToken',
            ];
        }
        $status = Yii::$service->customer->changePasswordAndClearToken($password, $identity);
        if ($status) {
            return [
                'code' => 200,
                'content' => 'reset password success',
            ];
        }else{
            return [
                'code' => 403,
                'content' => 'change password submit fail',
            ];
        }
        
    }
    
    public function actionSendcode()
    {
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity['id']){
            // 用户已经登录
            return [
                'code'         => 400,
                'content'       => 'account is login',
                
            ];
        }
        
        $email       = Yii::$app->request->post('email');
        $forgotPasswordParam = \Yii::$app->getModule('customer')->params['forgotPassword'];
        $forgotCaptchaActive = isset($forgotPasswordParam['forgotCaptcha']) ? $forgotPasswordParam['forgotCaptcha'] : false;
        if($forgotCaptchaActive){
            $captcha    = Yii::$app->request->post('captcha');
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                return [
                    'code'         => 401,
                    'content'       => 'captcha ['.$captcha.'] is not right',
                ];
            }
        }
        // 验证邮箱是否存在
        $identity = Yii::$service->customer->getUserIdentityByEmail($email);
        if(!$identity){
            return [
                'code'         => 401,
                'content'      => 'customer email is not exist',
            ];
        }
        // 发送邮件
        $domain       = Yii::$app->request->post('domain');
        $domain = trim($domain,'/').'/';
        //echo $domain;exit;
        Yii::$service->helper->setAppServiceDomain($domain);
        $passwordResetToken = Yii::$service->customer->generatePasswordResetToken($identity);
        $identity['password_reset_token'] = $passwordResetToken;
        $this->sendForgotPasswordEmail($identity);

        return [
            'code'         => 200,
            'content'      => 'send forgot password email success',
        ];
    }
    /**
     * 发送忘记密码邮件.
     */
    protected function sendForgotPasswordEmail($identity)
    {
        if ($identity) {
            Yii::$service->email->customer->sendForgotPasswordEmail($identity);
        }
    }

}