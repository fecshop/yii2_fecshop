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
        //echo Yii::$service->session->getUUID();exit;
        $identity = Yii::$app->user->identity;
        var_dump($identity['email']);
        exit;
        //$accessToken = Yii::$app->request->post('access_token'); 
    
    }
    
   
    
}