<?php
/**
 * FecShop file.
 *
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
			'errors' => [
				'class' => 'fecshop\services\helper\Errors',
			],
			'mobileDetect' => [
				'class' => 'fecshop\services\helper\MobileDetect',
			],
			
			
		],
	],
];