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
                'enable' => false,
                // print log info to db.
                'dbprint'        => false,
                // print log info to front html
                'htmlprint'    => false,
                // print log
                'htmlprintbyparam'  => [
                    // like :http://fecshop.appfront.fancyecommerce.com/cn/?servicelog=xxxxxxxx
                    'enable'        => false,
                    'paramKey'        => 'servicelog',
                    'paramVal'            => 'xxxxxxxx',
                ],
            ],
        ],
    ],
];