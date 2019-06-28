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
    'params' => [
        'appName' => 'appapi',
        // 速度控制[120,60] 代表  60秒内最大访问120次，
        'rateLimit'             => [
            'enable'=> false,   # 是否开启？默认不开启速度控制。
            //'limit' => [120, 60],
        ],
        /**
         * 该配置用来设置：允许那些账户在appapi入口进行登录获取token
         * 1.apiUserAllow数组的值为空：代表默认是所有的后台用户
         * 2.apiUserAllow数组中设置了用户名（数组可以设置多个），那么，只有包含在这个数组中的用户，才可以用于appapi用户登录获取access-token。其他的账户获取token就会失败
         * 3.默认该数组为空，允许所有的appadmin的后台用户进行登录获取access-token
         */
        'apiUserAllow' => [
            
        ],
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'rules' => [
                '' => 'v1/home/index',
            ],
            /*
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
                //
                //[
                //    'class' => 'yii\rest\UrlRule',
                //    'controller' => 'v1/product',
                //    # 默认开启复数，需要在url后面加一个s，譬如v1/article，默认访问为v1/articles
                //    # 如果为false，则url后面不需要加s，譬如v1/article，默认访问为v1/article	，
                //    //'pluralize' => false,
                //],
                ////
                'GET v1/products'                    => 'v1/product/customindex',
                'POST v1/products'                    => 'v1/product/customcreate',
                'GET v1/products/<product_id>'        => 'v1/product/customview',
                'PATCH v1/products/<product_id>'    => 'v1/product/customupdate',
                'PUT v1/products/<product_id>'        => 'v1/product/customupdate',
                'DELETE v1/products/<product_id>'    => 'v1/product/customdelete',

            ],
            */
        ],
        'request' => [
            'class' => 'yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        
        'user' => [
            // 【默认】不开启速度限制的 User Model
            'identityClass' => 'fecshop\models\mysqldb\AdminUser',
            // 开启速度限制的 User Model
            //'identityClass' => 'fecshop\models\mysqldb\adminUser\AdminUserAccessToken',
            
            //'enableAutoLogin' => true,
            // 关闭session
            'enableSession'     => false,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],

];
// product 生产环境，errorHandler使用 AppserverErrorHandler
if (YII_ENV_PROD) {
    $config['components']['errorHandler']['class'] = 'fecshop\components\AppserverErrorHandler';
}

return $config;