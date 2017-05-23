<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'order' => [
        'class' => 'fecshop\services\Order',
        // 子服务
        'childService' => [
            'item' => [
                'class' => 'fecshop\services\order\Item',
            ],
        ],
        /*
        //'increment_id' => '',
        'requiredAddressAttr' => [ # 必填的订单字段。
            'first_name',
            'last_name',
            'email',
            'telephone',
            'street1',
            'country',
            'city',
            'state',
            'zip'
        ],
        #处理多少分钟后，支付状态为pending的订单，归还库存。
        'minuteBeforeThatReturnPendingStock' 	=>  60,
        # 脚本一次性处理多少个pending订单。
        'orderCountThatReturnPendingStock' 		=>  30,
        # 子服务
        'childService' => [
            'item' => [
                'class' => 'fecshop\services\order\Item',
            ],
        ],
        */
    ],
];
