<?php
namespace fecshop\app\appfront\modules\Site\controllers;
use Yii;
use fec\helpers\CModule;
use fecshop\app\appfront\modules\AppfrontController;
class HelperController extends AppfrontController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }



}












