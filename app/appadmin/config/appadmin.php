<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
// 本文件在app/web/index.php 处引入。
// fecshop的核心模块
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename) {
    $modules = array_merge($modules, require($filename));
}
$params = require __DIR__ .'/params.php';
return [
    'modules'=>$modules,
    /* only config in front web */
    'bootstrap' => ['store'],
    'params'    => $params,
    'components' => [
        'store' => [
            'appName' => 'appadmin',
        ],
        'user' => [
            'identityClass' => 'fecshop\models\mysqldb\AdminUser',
            'enableAutoLogin' => true,
        ],
        'i18n' => [
            'translations' => [
                'appadmin' => [
                    //'class' => 'yii\i18n\PhpMessageSource',
                    'class' => 'fecshop\yii\i18n\PhpMessageSource',
                    'basePaths' => [
                        '@fecshop/app/appadmin/languages',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'fecadmin/error',
        ],

        'urlManager' => [
            'rules' => [
                '' => 'fecadmin/index/index',
            ],
        ],
    ],
    
];
