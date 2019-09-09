<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
namespace fecshop\app\appinstall\modules\Database\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ErrorController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\web\ErrorAction',
            ],
            //'captcha' => [
            //    'class' => 'yii\captcha\CaptchaAction',
            //    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            //],
        ];
    }

}
