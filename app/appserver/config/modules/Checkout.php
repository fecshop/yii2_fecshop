<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'checkout' => [
        'class' => '\fecshop\app\appserver\modules\Checkout\Module',
        'params'=> [
            // 'guestOrder' => true, // 是否支持游客下单 **废弃，改为后台配置
        ],
    ]
];