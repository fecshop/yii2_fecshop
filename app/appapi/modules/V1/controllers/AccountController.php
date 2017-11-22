<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class AccountController extends AppapiController
{
   

    public function actionLogin(){
        $username       = Yii::$app->request->post('username');
        $password    = Yii::$app->request->post('password');
        $accessToken = Yii::$service->adminUser->loginAndGetAccessToken($username,$password);
        if($accessToken){
            return [
                'access-token' => $accessToken,
                'status'       => 'success',
                'code'         => 200,
            ];
        }else{
            return [
                'access-token' => '',
                'status'       => 'error',
                'code'         => 401,
            ];
        }
        
    }
}
