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
class ContactController extends AppserverController
{
    public $enableCsrfValidation = false ;
    
    /**
     * 登录用户的部分
     */
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        $customer_name = '';
        $customer_email= '';
        
        if($identity){
            $customer_name = $identity['firstname'].' '.$identity['lastname'];
            $customer_email= $identity['email'];
        }
        
        $contactsEmail = '';
        $contactsCaptcha = false;
        $contacts = Yii::$app->getModule('customer')->params['contacts'];

        if (isset($contacts['contactsCaptcha'])) {
            $contactsCaptcha = $contacts['contactsCaptcha'] ? true : false;
        }
        if (isset($contacts['email']['address'])) {
            $contactsEmail = $contacts['email']['address'];
        }
        if (!$contactsEmail) {
            $contactsEmail = Yii::$service->email->contactsEmailAddress();
        }
        
        return [
            'code'              => 200,
            'customer_name'     => $customer_name,
            'customer_email'    => $customer_email,
            'contactsCaptchaActive'   => $contactsCaptcha,
            'contactsEmail'     => $contactsEmail,
        ];
        
    }
    
    public function actionSubmit(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $customer_name  = Yii::$app->request->post('customer_name');
        $email          = Yii::$app->request->post('email');
        $telephone      = Yii::$app->request->post('telephone');
        $comment        = Yii::$app->request->post('comment');
        $captcha        = Yii::$app->request->post('captcha');
        if(!$customer_name){
            return [
                'code' => 401,
                'content' => 'customer name can not empty',
            ];
        }
        if(!$email){
            return [
                'code' => 401,
                'content' => 'email can not empty',
            ];
        }
        if(!$telephone){
            return [
                'code' => 401,
                'content' => 'telephone can not empty',
            ];
        }
        if(!$comment){
            return [
                'code' => 401,
                'content' => 'comment can not empty',
            ];
        }
        $contacts = Yii::$app->getModule('customer')->params['contacts'];
        $contactsCaptcha = $contacts['contactsCaptcha'] ? true : false;
        if($contactsCaptcha){
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                return [
                    'code'         => 401,
                    'content'       => 'captcha ['.$captcha.'] is not right',
                ];
            }
            
        }
        // 开始保存
        
        $paramData = [
            'name'          => $customer_name,
            'telephone'     => $telephone,
            'comment'       => $comment,
            'email'         => $email,
        ];
        if (Yii::$service->email->customer->sendContactsEmail($paramData)) {
            return [
                'code' => 200,
                'content' => 'contact us success'
            ];
        }else{
            return [
                'code' => 401,
                'content' => 'contact us fail'
            ];
        }
        
    }
    
   
}