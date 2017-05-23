<?php

namespace fecshop\app\appfront\modules\Site\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

class HelperController extends AppfrontController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            //'captcha' => [
            //    'class' => 'yii\captcha\CaptchaAction',
            //    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            //],
        ];
    }

    public function actionCaptcha()
    {
        Yii::$service->helper->captcha->height = 30;
        Yii::$service->helper->captcha->fontsize = 18;
        Yii::$service->helper->captcha->doimg();
        exit;
    }

    //public function actionVcaptcha(){
    //	$code = 'byvh';
    //	echo Yii::$service->helper->captcha->validateCaptcha($code);
    //}
}
