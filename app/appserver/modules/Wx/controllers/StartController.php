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
class StartController extends AppserverController
{
    //   general/start/first
    public function actionFirst()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $code = Yii::$service->helper->appserver->status_success;
        $startConfig = Yii::$app->controller->module->params['startConfig'];
        
        $startImageUrl = Yii::$service->image->getImgUrl($startConfig['picUrl']);
        $data = [
            [
                'businessId' => 115781,
                'picUrl' => $startImageUrl,
                'title' => Yii::$service->page->translate->__($startConfig['title']),  // '',
                'remark' => Yii::$service->page->translate->__($startConfig['remark']),
                
            ],
            
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
}