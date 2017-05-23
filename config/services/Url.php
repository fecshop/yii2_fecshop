<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'url' => [
        'class'        => 'fecshop\services\Url',
        'showScriptName'=> true, // if is show index.php in url.  if set false ,you must config nginx rewrite
        'randomCount'=> 8,  // if url key  is exist in url write table ,  add a random string  behide the url key, this param is define random String length
        // 子服务
        'childService' => [
            'rewrite' => [
                'class' => 'fecshop\services\url\Rewrite',
                'storage' => 'mongodb',
            ],
            'category' => [
                'class' => 'fecshop\services\url\Category',

            ],
        ],
    ],
];
