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
        //'categoryAggregateMaxCount' => 6000,
        /**
          * 分类页面的产品，如果一个spu下面由多个sku同时在这个分类，
          * 那么，是否只显示一个sku（score最高），而不是全部sku
          * true： 代表只显示一个sku
          * false: 代表产品全部显示
          */
        //'productSpuShowOnlyOneSku' => false,
        // 'customAttrGroup' => [], 详细参看@common/config/fecshop_local_services/Product.php 里面的配置
        // 子服务
        'childService' => [
            'image' => [
                'class'             => 'fecshop\services\product\Image',
                // 产品图片存放路径。
                //'imageFloder'       => 'media/catalog/product', 
                // MB  # 图片最大尺寸
                //'maxUploadMSize'=> 5, 
                /**
                 * // https://github.com/liip-forks/Imagine/blob/b3705657f1e4513c6351d3aabc4f9efb7f415803/lib/Imagine/Imagick/Image.php#L703
                 * png图片resize压缩的质量数
                 * 范围为  0-9，数越大，质量越高，图片文件的容量越大, 数越低，图片越模糊，容量越小
                 */
                //'pngCompressionLevel' => 8,
                /**
                  * https://github.com/liip-forks/Imagine/blob/b3705657f1e4513c6351d3aabc4f9efb7f415803/lib/Imagine/Imagick/Image.php#L676   
                  * https://secure.php.net/manual/zh/imagick.setimagecompressionquality.php
                  * 'jpeg', 'jpg', 'pjpeg' 格式图片进行压缩的质量数
                  * 范围：1-100，数越大，质量越高，图片文件的容量越大, 数越低，图片越模糊，容量越小
                  */
               // 'jpegQuality' => 80,
                //    'allowImgType' 	=> [ # 允许的图片类型
                //    'image/jpeg',
                //    'image/gif',
                //    'image/png',
                //    'image/jpg',
                //    'image/pjpeg',
                //], 
                //'waterImg'        => 'product_water.jpg',  # 水印图片
            ],
            'price' => [
                'class' => 'fecshop\services\product\Price',
                //'ifSpecialPriceGtPriceFinalPriceEqPrice' => true, // 设置为true后，如果产品的special_price > price， 则 special_price无效，价格为price
            ],
            'review' => [
                'class' => 'fecshop\services\product\Review',
                //'filterByLang'    => false,    // 是否通过语言进行评论过滤？
                // true：代表用户购物过的产品才能评论，false：代表用户没有购买的产品也可以评论
                //'reviewOnlyOrderedProduct' => true,
                // 订单创建后，多久内可以进行评论，超过这个期限将不能评论产品（单位为月）, 当 reviewOnlyOrderedProduct 设置为true时有效。
                //'reviewMonth' => 6,
            ],
            'attr' => [
                'class' => 'fecshop\services\product\Attr',
            ],
            'attrGroup' => [
                'class' => 'fecshop\services\product\AttrGroup',
            ],
            'favorite' => [
                'class' => 'fecshop\services\product\Favorite',
            ],
            'info' => [
                'class' => 'fecshop\services\product\Info',
            ],
            'productapi' => [
                'class' => 'fecshop\services\product\ProductApi',
            ],
            'stock' => [
                'class' => 'fecshop\services\product\Stock',
                //'zeroInventory' => 0, // 是否零库存，1代表开启零库存。
            ],
            'brand' => [
                'class' => 'fecshop\services\product\Brand',
            ],
            'brandcategory' => [
                'class' => 'fecshop\services\product\Brandcategory',
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
