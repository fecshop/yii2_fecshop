<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
// 本文件在app/web/index.php 处引入。
// fecshop - appfront 的核心模块
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename) {
    $modules = array_merge($modules, require($filename));
}
// 此处也可以重写fecshop的组件。供调用。
$config = [
    'modules'=>$modules,
    /* only config in front web */
    'bootstrap' => ['store'],
    'params'    => [
        /* appfront base theme dir   */
        //'appfrontBaseTheme' 	=> '@fecshop/app/appfront/theme/base/front',
        //'appfrontBaseLayoutName'=> 'main.php',
        'appName' => 'appserver',
        // 速度控制[120,60] 代表  60秒内最大访问120次，
        //'rateLimit'             => [
        //    'enable'=> false,   # 是否开启？默认不开启速度控制。
        //    'limit' => [120, 60],
        //]
    ],
    // language config.
    'components' => [
        'i18n' => [
            'translations' => [
                'appserver' => [
                    //'class' => 'yii\i18n\PhpMessageSource',
                    'class' => 'fecshop\yii\i18n\PhpMessageSource',
                    'basePaths' => [
                        '@fecshop/app/appserver/languages',
                    ],
                    'sourceLanguage' => 'en_US', // 如果 en_US 也想翻译，那么可以改成en_XX。
                ],
            ],
        ],

        'user' => [
            // 'class'            => 'fecshop\yii\web\User',
            // 【默认】不开启速度限制的 User Model
            'identityClass'     => 'fecshop\models\mysqldb\Customer',
            // 开启速度限制的 User Model
            // 'identityClass'     => 'fecshop\models\mysqldb\customer\CustomerAccessToken',
            // 关闭session
            'enableSession'     => false,
        ],
        'urlManager' => [
            'rules' => [
                '' => 'cms/home/index',
            ],
        ],

        'request' => [
            'class' => 'yii\web\Request',
            /*
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'O1d232trde1x-M97_7QvwPo-5QGdkLMp#@#@',
            'noCsrfRoutes' => [
                'catalog/product/addreview',
                'favorite/product/remark',
                'paypal/ipn/index',
                'paypal/ipn',
            ],
            */
        ],
        // 404页面对应的url key
        'errorHandler' => [
            'exceptionView' => '@fecshop/yii/views/errorHandler/exception.php',
        ],
    ],

];
// product 生产环境，errorHandler使用 AppserverErrorHandler
if (YII_ENV_PROD) {
    $config['components']['errorHandler']['class'] = 'fecshop\components\AppserverErrorHandler';
}

return $config;