<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class HomeController extends AppapiController
{
   
    
    public function actionIndex(){
        return [
                'code'    => 200,
                'message' => 'Welcome To Fec-Shop AppApi',
                'data'    => [],
            ];
        
    }
}
