<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Wx\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class AccountController extends AppserverController
{
    //   general/start/first
    public function actionLogin()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            [
                'businessId' => 115781,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 25407,
                'paixu' => 0,
                'picUrl' => "https://cdn.it120.cc/apifactory/2019/06/22/249199f1-6d15-4de2-9e90-94633586056c.jpg",
                'status' => 0,
                'statusStr' => '显示',
                'title' => 'fecshop',
                'type' => 'start',
                'userId' => 16619,
            ],
            
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
}