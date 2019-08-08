<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    /**
     * checkout 模块的配置，您可以在@appfront/config/fecshop_local_modules/Checkout.php 
     * 中进行配置，二开，或者重写该模块（在上面路径中如果文件不存在，自行新建配置文件。）
     */ 
    'checkout' => [
        'class' => '\fecshop\app\appfront\modules\Checkout\Module',
        /**
         * 模块内部的params配置。
         */
        'params'=> [
            // 'guestOrder' => true, // 是否支持游客下单 **废弃，改为后台配置
            // 'checkout_cart_breadcrumbs' => false,
            // 'checkout_onepage_breadcrumbs' => false,
        ],
    ],
];
