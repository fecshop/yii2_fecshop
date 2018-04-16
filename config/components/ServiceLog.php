<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'serviceLog' => [
        'class' => 'fecshop\components\ServiceLog',
        'log_config' => [
            // service log config
            'services' => [
                // if enable is false , all services will be close
                // 总开关，开启或关闭Service Log，如果关闭了总开关，那么下面的开关开启都无效
                'enable' => false,
                // print log info to db.
                // 将Service Log写入db的开关，开启后将会写入到 mongodb数据库的表 `fecshop_service_log ` , 前提：需要总开关开启
                'dbprint'        => false,
                // print log info to front html
                // 直接在页面显示Service Log 的开关。前提：需要总开关开启
                'htmlprint'    => false,
                // print log
                // 直接在页面显示Service Log 的开关。前提：需要总开关开启
                // 
                'htmlprintbyparam'  => [
                    // like :http://fecshop.appfront.fancyecommerce.com?servicelog=xxxxxxxx
                    // 当这里开启后，如果访问http://fecshop.appfront.fancyecommerce.com 是看不到service log信息的，
                    // 只有访问 http://fecshop.appfront.fancyecommerce.com?servicelog=xxxxxxxx ， 才能看到
                    'enable'        => false,
                    'paramKey'        => 'servicelog',
                    'paramVal'            => 'xxxxxxxx',
                ],
            ],
        ],
    ],
];