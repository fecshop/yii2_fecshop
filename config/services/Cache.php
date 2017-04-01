<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'cache' => [
		'class' => 'fecshop\services\Cache',
		/**
		 * cache 总开关，设置false后，无论cacheConfig中的enable
		 * 是否为true，都会全部关闭掉cache。
		 */
		'enable'=> true, 	
		/**
		 * 各个页面cache的配置
		 */
		'cacheConfig'	=> [
			# 分类页面
			'category'  => [
				'enable' 		=> true, 	# 是否开启分类页面的缓存
				'timeout'		=> 3600, 	# 如果开启，缓存的过期时间，这里设置为秒
				'disableUrlParam' => 'abcdef', # 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便查看无缓存的页面是什么样子。
				# url出现的这些参数的值，将参与cache唯一key的生成。
				'cacheUrlParam'	=> [
					# 分页，排序，等参数 
					'p','dir','sort','numPerPage',
					# 侧栏属性过滤等参数
					'price','size','color',
					'style','dresses-length','pattern-type',
					'collar','xinghao','cpu'
				],
			],
			# 产品页面
			'product'  => [
				'enable' 		=> true, 	# 是否开启分类页面的缓存
				'timeout'		=> 3600, 	# 如果开启，缓存的过期时间，这里设置为秒
				'disableUrlParam' => 'abcdef', # 如果开启缓存，在url加入什么参数后，系统不读取缓存，这个选项是为了方便查看无缓存的页面是什么样子。
			],
		],
	],
];