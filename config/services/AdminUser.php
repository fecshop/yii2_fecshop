<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'adminUser' => [
        'class' => 'fecshop\services\AdminUser',
        'childService' => [
            'adminUser' => [
                'class' => 'fecshop\services\adminUser\AdminUser',
            ],
            'userLogin' => [
                'class' => 'fecshop\services\adminUser\UserLogin',
            ],
        ],
    ],
];
