<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiTokenController;
use Yii;

class ArticleController extends AppapiTokenController
{
    
    public function actionTest()
    {
        echo 11;exit;
        //var_dump(get_class(Yii::$service->cms->article->getByPrimaryKey('')));
    }
}
