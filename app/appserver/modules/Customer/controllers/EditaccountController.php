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
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EditaccountController extends AppserverTokenController
{
    public $enableCsrfValidation = false ;
    /**
     * 登录用户的部分
     */
    public function actionIndex(){
        $identity = Yii::$app->user->identity;
        if(isset($identity['email'])){
            return [
                'code'      => 200,
                'email'     => $identity['email'],
                'firstname' => $identity['firstname'],
                'lastname'  => $identity['lastname'],
                'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
                'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
                'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
                'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
               
            ];
        }
        
    }
    
    
    
    
    public function actionUpdate(){
        $firstname = Yii::$app->request->post('firstname');
        $lastname  = Yii::$app->request->post('lastname');
        $current_password       = Yii::$app->request->post('current_password');
        $new_password           = Yii::$app->request->post('new_password');
        $confirm_new_password   = Yii::$app->request->post('confirm_new_password');
        $identity = Yii::$app->user->identity;
        if($errorInfo = $this->validateParams($identity ,$firstname,$lastname,$current_password,$new_password,$confirm_new_password)){
            return $errorInfo;
        }
        $identity->firstname = $firstname;
        $identity->lastname = $lastname;
        if($current_password){
            $identity->setPassword($new_password);
        }
        if ($identity->validate()) {
            $identity->save();
            return [
                'code'         => 200,
                'content'      => 'update account info success',
            ];
        }else{
            $errors = Yii::$service->helper->errors->getModelErrorsStrFormat($identity->errors);
            if($errors){
                return [
                    'code'     => 401,
                    'content'  => $errors,
                ];
            }
        }
        
    }
    
    public function validateParams($identity ,$firstname,$lastname,$current_password,$new_password,$confirm_new_password){
        $minNameLength = Yii::$service->customer->getRegisterNameMinLength();
        $maxNameLength = Yii::$service->customer->getRegisterNameMaxLength();
        $minPassLength = Yii::$service->customer->getRegisterPassMinLength();
        $maxPassLength = Yii::$service->customer->getRegisterPassMaxLength();
         
        if(!$identity){
            return [
                'code'         => 401,
                'content'       => 'current user is not exist',
            ];
        }
        if ($current_password && !$new_password) {
            return [
                'code'         => 401,
                'content'       => 'new password can not empty',
            ];
        } elseif ($current_password && ($new_password != $confirm_new_password)) {
            return [
                'code'         => 401,
                'content'       => 'Password and confirmation password must be consistent',
            ];
        } elseif ($current_password && (strlen($new_password) < $minPassLength || strlen($new_password) > $maxPassLength)) {
            return [
                'code'         => 401,
                'content'       => 'password must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        } elseif (strlen($firstname) < $minNameLength || strlen($firstname) > $maxNameLength) {
            return [
                'code'         => 401,
                'content'       => 'firstname must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        } elseif (strlen($lastname) < $minNameLength || strlen($lastname) > $maxNameLength) {
            return [
                'code'         => 401,
                'content'       => 'lastname must >= '.$minPassLength.' and <= '.$maxPassLength,
            ];
        }
        
        if($current_password && !$identity->validatePassword($current_password)){
            return [
                'code'         => 401,
                'content'       => 'current password is not correct',
            ];
        }
        return false;
    }
    
}