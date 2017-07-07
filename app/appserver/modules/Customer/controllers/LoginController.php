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
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class LoginController extends AppserverController
{
    public $enableCsrfValidation = false ;
    
    public function actionIndex(){
        $email       = Yii::$app->request->post('email');
        $password    = Yii::$app->request->post('password');
        $accessToken = Yii::$service->customer->loginAndGetAccessToken($email,$password);
        if($accessToken){
            echo json_encode([
                'access-token' => $accessToken,
                'status'       => 'success',
                'code'         => 200,
            ]);
            return;
        }else{
            echo json_encode([
                'access-token' => '',
                'status'       => 'error',
                'code'         => 401,
            ]);
            return;
        }
        
    }
    
    
}