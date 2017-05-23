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
return [
    'modules'=>$modules,
    'params' => [
        'appName' => 'appapi',
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl'        => true,
            'enableStrictParsing'    => true,
            'showScriptName'        => false,
            'rules' => [
                //################
                //# Article Api ##
                //################
                // http://fecshop.appapi.fancyecommerce.com/v1/articles
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/article',
                    // 默认开启复数，需要在url后面加一个s，譬如v1/article，默认访问为v1/articles
                    // 如果为false，则url后面不需要加s，譬如v1/article，默认访问为v1/article	，
                    //'pluralize' => false,
                ],
                // 这个设置是和复数没有任何关系的
                'GET v1/articles/test' => 'v1/article/test',

                //#################
                //# Customer Api ##
                //#################
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/customer',
                    // 默认开启复数，需要在url后面加一个s，譬如v1/article，默认访问为v1/articles
                    // 如果为false，则url后面不需要加s，譬如v1/article，默认访问为v1/article	，
                    //'pluralize' => false,
                ],

                //#################
                //# Category Api ##
                //#################
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/pcate',
                    //'pluralize' => true,
                ],

                //#################
                //#Product Api ##
                //#################
                /*
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/product',
                    # 默认开启复数，需要在url后面加一个s，譬如v1/article，默认访问为v1/articles
                    # 如果为false，则url后面不需要加s，譬如v1/article，默认访问为v1/article	，
                    //'pluralize' => false,
                ],
                */
                'GET v1/products'                    => 'v1/product/customindex',
                'POST v1/products'                    => 'v1/product/customcreate',
                'GET v1/products/<product_id>'        => 'v1/product/customview',
                'PATCH v1/products/<product_id>'    => 'v1/product/customupdate',
                'PUT v1/products/<product_id>'        => 'v1/product/customupdate',
                'DELETE v1/products/<product_id>'    => 'v1/product/customdelete',

            ],
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'user' => [
            'identityClass' => 'fecadmin\models\AdminUser',
            //'enableAutoLogin' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],

];
