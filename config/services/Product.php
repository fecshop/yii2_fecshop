<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'product' => [
        'class' => 'fecshop\services\Product',
        /**
         * 分类页面的最大的产品总数
         * aggregate 的分页，是把全部产品查出来，然后php进去切分，类似于Es。
         * 因此对总数进行了限制。
         */
        'categoryAggregateMaxCount' => 6000,
        // 'customAttrGroup' => [], 详细参看@common/config/fecshop_local_services/Product.php 里面的配置
        // 子服务
        'childService' => [
            'image' => [
                'class'             => 'fecshop\services\product\Image',
                'imageFloder'       => 'media/catalog/product', # 产品图片存放路径。
                //'allowImgType' 	=> [ # 允许的图片类型
                //    'image/jpeg',
                //    'image/gif',
                //    'image/png',
                //    'image/jpg',
                //    'image/pjpeg',
                //], 
                'maxUploadMSize'=> 5, //MB  # 图片最大尺寸
                //'waterImg'        => 'product_water.jpg',  # 水印图片
            ],
            'price' => [
                'class' => 'fecshop\services\product\Price',
                'ifSpecialPriceGtPriceFinalPriceEqPrice' => true, // 设置为true后，如果产品的special_price > price， 则 special_price无效，价格为price
            ],
            'review' => [
                'class' => 'fecshop\services\product\Review',
                'filterByLang'    => false,    // 是否通过语言进行评论过滤？
            ],
            'favorite' => [
                'class' => 'fecshop\services\product\Favorite',
            ],
            'info' => [
                'class' => 'fecshop\services\product\Info',

            ],
            'stock' => [
                'class' => 'fecshop\services\product\Stock',
                'zeroInventory' => 0, // 是否零库存，1代表开启零库存。
            ],
            /* #暂时没用

            'coll' => [
                'class' => 'fecshop\services\product\Coll',
                //'numPerPage' => 50,	# default
                //'pageNum' => 1,		# default
                //'orderBy' => ['_id' => SORT_DESC ],  # default
                //'allowMaxPageNum' => 200, # default
            ],
            'bestSell' => [
                'class' => 'fecshop\services\product\BestSell',
            ],
            'viewLog' => [
                'class' => 'fecshop\services\product\ViewLog',
                'childService' => [
                    'session' => [
                        'class' => 'fecshop\services\product\viewLog\Session',
                    ],
                    'db'	=>[
                        'class' => 'fecshop\services\product\viewLog\Db',
                        //'table' => '',  # custom table, you must create this mysql table before you use it.
                    ],
                    'mongodb'	=>[
                        'class' => 'fecshop\services\product\viewLog\Mongodb',
                        'collection' => '',
                    ],
                ],

            ],
            */
        ],
    ],
];
