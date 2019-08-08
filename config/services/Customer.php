<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'customer' => [
        'class' => 'fecshop\services\Customer',
        /*
        'customer_register' => [
            'min_name_length' => 1,  // 注册账号的firstname, lastname的最小长度
            'max_name_length' => 30, // 注册账号的firstname, lastname的最大长度
            'min_pass_length' => 6,  // 注册账号的密码的最小长度
            'max_pass_length' => 30, // 注册账号的密码的最大长度
        ],
        */
        // 子服务
        'childService' => [
            'newsletter' => [
                'class'        => 'fecshop\services\customer\Newsletter',
            ],

            'address' => [
                'class'        => 'fecshop\services\customer\Address',
            ],
            'affiliate' => [
                'class'        => 'fecshop\services\customer\Affiliate',
            ],
            'coupon' => [
                'class'        => 'fecshop\services\customer\Coupon',
            ],
            'dropship' => [
                'class'        => 'fecshop\services\customer\Dropship',
            ],
            'favorite' => [
                'class'        => 'fecshop\services\customer\Favorite',
            ],
            'message' => [
                'class'        => 'fecshop\services\customer\Message',
            ],
            'order' => [
                'class'        => 'fecshop\services\customer\Order',
            ],
            'point' => [
                'class'        => 'fecshop\services\customer\Point',
            ],
            'review' => [
                'class'        => 'fecshop\services\customer\Review',
            ],
            'wholesale' => [
                'class'        => 'fecshop\services\customer\Wholesale',
            ],

            'facebook' => [
                'class'        => 'fecshop\services\customer\Facebook',

            ],
            'google' => [
                'class'        => 'fecshop\services\customer\Google',

            ],
        ],
    ],
];
