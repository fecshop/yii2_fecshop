<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
/**
 * 本文件在@appfront/web/index.php 处，会被引入。
 * 该配置文件会加载./modules/*.php，并合并成一个数组，返回。
 */
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename) {
    $modules = array_merge($modules, require($filename));
}
$params = require __DIR__ .'/params.php';
// 此处也可以重写fecshop的组件。供调用。
$config = [
    'modules'=>$modules,
    // 参数配置部分
    'params'    => $params,

    // Yii组件配置 ，关于yii2组件，可以参看：http://www.yiichina.com/doc/guide/2.0/structure-application-components
    'components' => [
        // 404页面对应的url key
        'errorHandler' => [
            'errorAction' => 'database/error/index',
        ],
        // 首页对应的url key
        'urlManager' => [
            'rules' => [
                '' => 'database/config/index',
            ],
        ],
        'assetManager' => [
            'forceCopy' => true,
        ],
    ],
    

];
return $config;