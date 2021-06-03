<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;

class HomeController extends AppapiController
{


    public function actionIndex(){
        return [
                'code'    => 200,
                'message' => 'Welcome To Fecmall AppApi',
                'data'    => [],
            ];

    }
}
