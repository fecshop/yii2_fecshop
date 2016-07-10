<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'category' => [
		'class' => 'fecshop\services\Category',
		//'terry' => 'xxxx',
		
		# ×Ó·þÎñ
		'childService' => [
			'product' => [
				'class' => 'fecshop\services\category\Product',
			],
			'menu' => [
				'class' => 'fecshop\services\category\Menu',
				//'rootCategoryId' => 0,
			],
		],
	],
];