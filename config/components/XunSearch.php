<?php
/**
 * FecShop file.
 * doc:https://packagist.org/packages/hightman/xunsearch.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'xunsearch' => [
        'class' => 'hightman\xunsearch\Connection', // 此行必须
        'iniDirectory' => '@fecshop/config/xunsearch',    // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
        'charset' => 'utf-8',   // 指定项目使用的默认编码，默认即时 utf-8，可不指定
    ],
];
