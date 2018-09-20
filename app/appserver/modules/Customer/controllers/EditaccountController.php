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
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$app->user->identity;
        if(isset($identity['email'])){
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'email'     => $identity['email'],
                'firstname' => $identity['firstname'],
                'lastname'  => $identity['lastname'],
                'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
                'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
                'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
                'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            return $responseData;
        }
    }
    
    public function actionUpdate(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $firstname = Yii::$app->request->post('firstname');
        $lastname  = Yii::$app->request->post('lastname');
        $current_password       = Yii::$app->request->post('current_password');
        $new_password           = Yii::$app->request->post('new_password');
        $confirm_new_password   = Yii::$app->request->post('confirm_new_password');
        $identity = Yii::$app->user->identity;
        $errorInfo = $this->validateParams($identity ,$firstname,$lastname,$current_password,$new_password,$confirm_new_password);
        
        if($errorInfo !== true){
            $code = Yii::$service->helper->appserver->account_edit_invalid_data;
            $data = [
                'error' => $errorInfo,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            return $responseData;
        }
        $identity->firstname = $firstname;
        $identity->lastname = $lastname;
        if($current_password){
            $identity->setPassword($new_password);
        }
        if ($identity->validate()) {
            $identity->save();
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            return $responseData;
        }else{
            $errors = Yii::$service->helper->errors->getModelErrorsStrFormat($identity->errors);
            if($errors){
                $code = Yii::$service->helper->appserver->account_edit_invalid_data;
                $data = [
                    'error' => $errors,
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                return $responseData;
            }
        }
    }
    
    public function validateParams($identity ,$firstname,$lastname,$current_password,$new_password,$confirm_new_password){
        $minNameLength = Yii::$service->customer->getRegisterNameMinLength();
        $maxNameLength = Yii::$service->customer->getRegisterNameMaxLength();
        $minPassLength = Yii::$service->customer->getRegisterPassMinLength();
        $maxPassLength = Yii::$service->customer->getRegisterPassMaxLength();
        $errorArr = [];
        if(!$identity){
            $errorArr[] = 'current user is not exist';
        }
        if ($current_password && !$new_password) {
            $errorArr[] = 'new password can not empty';
        } elseif ($current_password && ($new_password != $confirm_new_password)) {
            $errorArr[] = 'Password and confirmation password must be consistent';
            
        } elseif ($current_password && (strlen($new_password) < $minPassLength || strlen($new_password) > $maxPassLength)) {
            $errorArr[] = 'password must >= '.$minPassLength.' and <= '.$maxPassLength;
           
        } elseif (strlen($firstname) < $minNameLength || strlen($firstname) > $maxNameLength) {
            $errorArr[] = 'firstname must >= '.$minPassLength.' and <= '.$maxPassLength;
            
        } elseif (strlen($lastname) < $minNameLength || strlen($lastname) > $maxNameLength) {
            $errorArr[] = 'lastname must >= '.$minPassLength.' and <= '.$maxPassLength;
        }
        
        if($current_password && !$identity->validatePassword($current_password)){
            $errorArr[] = 'current password is not correct';
        }
        if(!empty($errorArr)){
            return implode(',',$errorArr);
        }else{
            return true;
        }
    }
    
}