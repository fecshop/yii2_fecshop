<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'category' => [
        'class' => 'fecshop\services\Category',
        'customCategoryFilterAttr' => [
            /*
            'brand_id' => [
                'label' => 'Brand',
                'items' =>  [
                    1 => '华为',
                    3 => '小米',
                    4 => '大华',
                ],
            ],
            */
        ],
        // 子服务
        'childService' => [
            'product' => [
                'class' => 'fecshop\services\category\Product',
            ],
            'menu' => [
                'class' => 'fecshop\services\category\Menu',
                //'rootCategoryId' => 0,
            ],
            'image' => [
                'class'        => 'fecshop\services\category\Image',
                'imageFloder'    => 'media/catalog/category',
                //'allowImgType' 	=> ['image/jpeg','image/gif','image/png'],
                'maxUploadMSize'=> 5, //MB

            ],
        ],
    ],
];
