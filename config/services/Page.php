<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'page' => [
		'class' => 'fecshop\services\Page',
		//'terry' => 'xxxx',
		# 子服务
		'childService' => [
			'breadcrumbs' => [
				'class' 		=> 'fecshop\services\page\Breadcrumbs',
				'homeName' 		=> 'Home',  # if homeName => '', Home will not show in breadcrums.
				'ifAddHomeUrl'	=> true,  	# default true, if set false, home will not add url (a).
				//'intervalSymbol'=> ' >> '	# default value:' > '
			],
			'translate' => [
				'class' 		=> 'fecshop\services\page\Translate',
			],
			
			'asset' => [
				'class' =>  'fecshop\services\page\Asset',
				# 在js后面加一个v参数，修改js后，更改v参数，否则，浏览器会使用缓存。
				# /assets/dbdba3fa/js/js.js?v=2
				'jsVersion'		=> 1,
				# /assets/dbdba3fa/css/owl.carousel.css?v=2
				'cssVersion'	=> 1,
				# js和css的域名，如果不设置，则使用网站的域名。
				# 'jsCssDomain'   => '',
				/* js and css config example:
				'jsOptions'	=> [
					# js config 1
					[
						'options' => [
							'position' =>  'POS_END',
						//	'condition'=> 'lt IE 9',
						],
						'js'	=>[
							'js/jquery-3.0.0.min.js',
							'js/js.js',
						],
					],
					# js config 2
					[
						'options' => [
							'condition'=> 'lt IE 9',
						],
						'js'	=>[
							'js/ie9js.js'
						],
					],
				],
				# css config
				'cssOptions'	=> [
					# css config 1.
					[
						'css'	=>[
							'css/style.css',
							'css/ie.css',
						],
					],
					
					# css config 2.
					[
						'options' => [
							'condition'=> 'lt IE 9',
						],
						'css'	=>[
							'css/ltie9.css',
						],
					],
				],
				*/
			],
			
			'theme' => [
				'class' 		=> 'fecshop\services\page\Theme',
				/**  
				 * 
				# 这里是设置本地二开模板路径，如果您在每一个store中
				# 进行了配置，这里将被覆盖。
				'localThemeDir' 	=> '@appfront/theme/terry/theme01',
				# 这里设置的是第三方的模板路径
				'thirdThemeDir'		=> [],
				# 在文件 @fecshop/app/appName/modules/AppfrontController.php 初始化
				# 这里是fecshop的模板路径。
				#'fecshopThemeDir'	=> '',
				*/
			],
			'widget' => [
				'class' 		=> 'fecshop\services\page\Widget',
				/*
				'widgetConfig' => [
					'head' => [
						# 动态数据提供部分
						'class' => 'fecshop\app\appfront\widgets\Head',
						# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
						'view'  => 'widgets/head.php',
						# 缓存
						'cache' => [
							'enable'	=> false, # 是否开启
							'timeout' 	=> 4500,  # 缓存过期时间
						],
					],
					'header' => [
						'class' => 'fecshop\app\appfront\widgets\Headers',
						# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
						'view'  => 'widgets/header.php',
						'cache' => [
							'enable'	=> false,
							'timeout' 	=> 4500,
						],
					],
					'topsearch' => [
					    'view'  => 'widgets/topsearch.php',
					],
					'menu' => [
						'class' => 'fecshop\app\appfront\widgets\Menu',
						# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
						'view'  => 'widgets/menu.php',
						'cache' => [
							'enable'	=> false,
							//'timeout' 	=> 4500,
						],
					],
					'footer' => [
						'class' => 'fecshop\app\appfront\widgets\Footer',
						# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
						'view'  => 'widgets/footer.php',
						'cache' => [
							'enable'	=> false,
							//'timeout' 	=> 4500,
						],
					],
					'scroll' => [
						#'class' => 'fecshop\app\appfront\modules\Cms\block\widgets\Scroll',
						# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
						'view'  => 'widgets/scroll.php',
					],
					'breadcrumbs' => [
						'view'  => 'widgets/breadcrumbs.php',
					],
					'flashmessage' => [
						'view'  => 'widgets/flashmessage.php',
					],
				]
				*/
			],
			'currency' => [
				'class' => 'fecshop\services\page\Currency',
				/* currency config example:
				'baseCurrecy' => 'USD',  # 产品的价格都使用基础货币填写价格值。
				'defaultCurrency' => 'USD', # 如果store不设置货币，就使用这个store默认货币
				'currencys' => [
					'USD' => [
						'rate' 		=> 1,
						'symbol' 	=> '$',
					],
					'RMB' => [
						'rate' 		=> 6.3,
						'symbol' 	=> '￥',
					],
				],
				*/
			],
			
			'newsletter' => [
				'class' 		=> 'fecshop\services\page\Newsletter',
			],
			
			'staticblock' => [
				'class' 		=> 'fecshop\services\page\StaticBlock',
			],
			
			'menu' => [
				'class' => 'fecshop\services\page\Menu',
				
			],
			'message' => [
				'class' => 'fecshop\services\page\Message',
				
			],
			
		],
	],
];