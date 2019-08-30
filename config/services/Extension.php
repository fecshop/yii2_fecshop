<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'extension' => [
        'class' => 'fecshop\services\Extension',
        'childService' => [
            'administer' => [
                'class' => 'fecshop\services\extension\Administer',
            ],
            'remoteService' => [
                'class' => 'fecshop\services\extension\RemoteService',
            ],
            'generate' => [
                'class' => 'fecshop\services\extension\Generate',
            ],
        ],
    ],
];