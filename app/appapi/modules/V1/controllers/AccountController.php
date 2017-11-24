<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class AccountController extends AppapiController
{
   
    
    public function actionLogin(){
        $username       = Yii::$app->request->post('username');
        $password       = Yii::$app->request->post('password');
        $apiUserAllow   = Yii::$app->params['apiUserAllow'];
        // 如果 数组 $apiUserAllow 不为空，那么仅仅允许这个配置数组里面的用户登录
        if (!empty($apiUserAllow) && is_array($apiUserAllow)) {
            if (!in_array($username,$apiUserAllow)) {
                return [
                    'access-token' => '',
                    'status'       => 'error',
                    'code'         => 401,
                ];
            }
        }
        
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
