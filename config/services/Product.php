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
		//'terry' => 'xxxx',
		# 子服务
		'childService' => [
			'image' => [
				'class' => 'fecshop\services\product\Image',
				'imageFloder' => 'media/catalog/product',
			],
			'price' => [
				'class' => 'fecshop\services\product\Price',
			],
			'review' => [
				'class' => 'fecshop\services\product\Review',
			],
			'info' => [
				'class' => 'fecshop\services\product\Info',
			],
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
		],
	],
];