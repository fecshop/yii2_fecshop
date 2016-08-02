<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'helper' => [
		'class' => 'fecshop\services\Helper',
		# 子服务
		'childService' => [
			'ar' => [
				'class' => 'fecshop\services\helper\AR',
			],
			'log' => [
				'class' => 'fecshop\services\helper\Log',
				'log_config' => [
					# service log config
					'services' => [	
						# if enable is false , all services will be close
						'enable' => false,
						# print log info to db.
						'dbprint' 		=> true,
						# print log info to front html
						'htmlprint'  	=> false,
						# print log
						'htmlprintbyparam'  => [
							# like :http://fecshop.appfront.fancyecommerce.com/cn/?htmlprintparam=xxxxxxxx
							'enable'		=> true,
							'paramKey'		=> 'htmlprintparam',  
							'paramVal'			=> 'xxxxxxxx',
						],
					],
				],
			],
			'errors' => [
				'class' => 'fecshop\services\helper\Errors',
			],
			'mobileDetect' => [
				'class' => 'fecshop\services\helper\MobileDetect',
			],
			
			
		],
	],
];