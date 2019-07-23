<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'cache' => [
        'class' => 'fecshop\services\Cache',
        /*
         * 各个页面cache的配置
         */
        'cacheConfig'    => [
            // 分类页面
            'category'  => [
                'timeout'        => 3600,    // 则设置缓存的过期时间，这里设置为秒
                'disableUrlParam' => 'fecshop', // 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便在不刷新缓存的情况下，查看无缓存的页面是什么样子。
                // url出现的这些参数的值，将参与cache唯一key的生成。
                'cacheUrlParam'    => [],
            ],
            // 产品页面
            'product'  => [
                'timeout'        => 3600,    //则设置缓存的过期时间，这里设置为秒
                'disableUrlParam' => 'fecshop', // 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便在不刷新缓存的情况下，查看无缓存的页面是什么样子。
            ],

            // 首页页面
            'home'  => [
                'timeout'        => 3600,    // 则设置缓存的过期时间，这里设置为秒
                'disableUrlParam' => 'fecshop', // 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便在不刷新缓存的情况下，查看无缓存的页面是什么样子。
            ],

            // Article（page）页面
            'article'  => [
                'timeout'        => 3600,    // 则设置缓存的过期时间，这里设置为秒
                'disableUrlParam' => 'fecshop', // 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便在不刷新缓存的情况下，查看无缓存的页面是什么样子。
            ],
        ],
    ],
];
