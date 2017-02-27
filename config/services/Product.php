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
		/*
		'customAttrGroup' => [
			'dress_group' => [
				'dresses-length' 	=> [
					'dbtype' 	=> 'Int',
					'label'=>'裙长',
					'name'=>'dresses-length',
					'display'=>[
						'type' => 'inputString',
						'lang' => true,
					],
					'require' => 1,
				],
				'style-status'		=> [
					'dbtype' 	=> 'Int',
					'label'=>'分类状态',
					'name'=>'status',
					'display'=>[
						'type' => 'select',
						'data' => [
							1 	=> '激活',
							2 	=> '关闭',
						]
					],
					'require' => 1,
					'default' => 1,
				],
			],
			
			'computer_group' => [
				'memory_capacity' 	=> [
					'dbtype' 	=> 'String',
					'label'=>'Memory Capacity',
					'name'=>'memory_capacity',
					'display'=>[
						'type' => 'inputString',
						'lang' => true,
					],
					'require' => 1,
				],
				'cpu'		=> [
					'dbtype' 	=> 'Int',
					'label'=>'CPU型号',
					'name'=>'cpu',
					'display'=>[
						'type' => 'select',
						'data' => [
							1 	=> 'i3',
							2 	=> 'i5',
							3 	=> 'i7',
						]
					],
					'require' => 1,
					'default' => 1,
				],
			],
			
			
		],
		*/
		# 子服务
		'childService' => [
			'image' => [
				'class' 		=> 'fecshop\services\product\Image',
				'imageFloder' 	=> 'media/catalog/product',
				//'allowImgType' 	=> ['image/jpeg','image/gif','image/png'],
				'maxUploadMSize'=> 5, #MB
			
			],
			'price' => [
				'class' => 'fecshop\services\product\Price',
				'ifSpecialPriceGtPriceFinalPriceEqPrice' => true, # 设置为true后，如果产品的special_price > price， 则 special_price无效，价格为price 
			],
			'review' => [
				'class' => 'fecshop\services\product\Review',
				'filterByLang'	=> false,	# 是否通过语言进行评论过滤？
			],
			'favorite' => [
				'class' => 'fecshop\services\product\Favorite',
			],
			'info' => [
				'class' => 'fecshop\services\product\Info',
				
			],
			'stock' => [
				'class' => 'fecshop\services\product\Stock',
				
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