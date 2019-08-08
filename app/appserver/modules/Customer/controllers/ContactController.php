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
        //$contactsCaptcha = false;
        //$contacts = Yii::$app->getModule('customer')->params['contacts'];
        $appName = Yii::$service->helper->getAppName();
        $contactsCaptcha = Yii::$app->store->get($appName.'_account', 'contactsCaptcha');
        $contactsCaptcha = ($contactsCaptcha == Yii::$app->store->enable)  ? true : false;
        
        if (isset($contacts['email']['address'])) {
            $contactsEmail = $contacts['email']['address'];
        }
        if (!$contactsEmail) {
            $contactsEmail = Yii::$service->email->contactsEmailAddress();
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'customer_name'     => $customer_name,
            'customer_email'    => $customer_email,
            'contactsCaptchaActive'   => $contactsCaptcha,
            'contactsEmail'     => $contactsEmail,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
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
        $errorArr = [];
        if(!$customer_name){
            $errorArr[] = 'customer name can not empty';
        }
        if(!$email){
            $errorArr[] = 'email can not empty';
        }
        if (!$telephone) {
            $errorArr[] = 'telephone can not empty';
        }
        if (!$comment) {
            $errorArr[] = 'comment can not empty';
        }
        if (!empty($errorArr)) {
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = implode(',',$errorArr);
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
        }   
        //$contacts = Yii::$app->getModule('customer')->params['contacts'];
        //$contactsCaptcha = $contacts['contactsCaptcha'] ? true : false;
        $appName = Yii::$service->helper->getAppName();
        $contactsCaptcha = Yii::$app->store->get($appName.'_account', 'contactsCaptcha');
        $contactsCaptcha = ($contactsCaptcha == Yii::$app->store->enable)  ? true : false;
        
        if($contactsCaptcha){
            if(!Yii::$service->helper->captcha->validateCaptcha($captcha)){
                $code = Yii::$service->helper->appserver->status_invalid_captcha;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
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
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'content' => 'contact us success',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }else{
            
            $code = Yii::$service->helper->appserver->account_contact_us_send_email_fail;
            $data = [
                'content' => 'contact us success',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
    }
    
   
}