<?php
/**
 * FecShop file.
 * 
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'sitemap' => [
		'class' => 'fecshop\services\Sitemap',
		/*
		'sitemapConfig' => [
			#对于下面的设置，您可能感觉很啰嗦，域名作为store的key，在store service中已经设置，
			#为什么需要在这里重新搞一套呢？  这样做是为了更加的灵活
			#
			# appfront入口
			'appfront' => [
				# store的key(域名)，
				'fecshop.appfront.fancyecommerce.com' => [
					'https'			=> false,  # false代表使用http，true代表使用https			
					'sitemapDir' 	=> '@appfront/web/sitemap.xml', # sitemap存放的地址
					'showScriptName'=> true,	# 是否显示index.php ，譬如http://www.fecshop.com/index.php/xxxxxx,当nginx没有设置重写，这里需要设置为true,这样url中会存在index.php，否则会404
												# 这个设置对seo来说，设置为false最合适，也就是隐藏 url中index.php ，这种设置需要开启nginx的url重写
				],
				# store的key(域名)
				'fecshop.appfront.fancyecommerce.com/fr' => [
					'https'			=> false,  # false代表使用http，true代表使用https			
					'sitemapDir' 	=> '@appfront/web/fr/sitemap.xml', # sitemap存放的地址
					'showScriptName'=> true,
				],
				
				'fecshop.appfront.es.fancyecommerce.com' => [
					'https'			=> false,  # false代表使用http，true代表使用https			
					'sitemapDir' 	=> '@appfront/web/sitemap_es.xml',
					'showScriptName'=> true,
				],
				'fecshop.appfront.fancyecommerce.com/cn' => [
					'https'			=> false,  # false代表使用http，true代表使用https			
					'sitemapDir' 	=> '@appfront/web/cn/sitemap.xml',
					'showScriptName'=> true,
				],
			]
		],
		*/
	],
];