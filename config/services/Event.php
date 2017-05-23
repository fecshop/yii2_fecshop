<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'event' => [
        'class' => 'fecshop\services\Event',
        // 事件配置表，这是Fecshop默认存在的事件。您可以在配置中添加您的事件函数。
        'eventList' => [
            // 加入购物车before
            'event_add_to_cart_before'        => [],
            // 加入购物车after
            'event_add_to_cart_after'        => [],
            // 生成订单before
            'event_generate_order_before'    => [],
            // 生成订单after
            'event_generate_order_after'    => [],

        ],

    ],
];
