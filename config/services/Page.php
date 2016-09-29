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
				 ** if you set value in config , it can not active ,it will be set value in store bootstrap. 
				* it will be set in store service bootstrop
				'localThemeDir' 	=> '@appfront/theme/terry/theme01',
				# it will be set in store service bootstrop
				'thirdThemeDir'		=> [],
				# init in @fecshop/app/appName/modules/AppfrontController.php
				# it will be set value in appfront  controller  init, it can not effect if you set value to it.
				#'fecshopThemeDir'	=> '',
				*/
			],
			'widget' => [
				'class' 		=> 'fecshop\services\page\Widget',
				/* config example:
					'widgetConfig' => [
					
						'head' => [
							#'class' => 'fecshop\app\appfront\modules\Cms\block\widgets\Head',
							# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
							'view'  => 'widgets/head.php',
						],
						'header' => [
							'class' => 'fecshop\app\appfront\modules\Cms\block\widgets\Headers',
							# 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
							'view'  => 'widgets/header.php',
							'cache' => [
								'enable'	=> false,
								//'timeout' 	=> 4500,
							],
						],
					],
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