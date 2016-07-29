<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'Customer' => [
		'class' => 'fecshop\services\Customer',
		# 子服务
		'childService' => [
			'address' => [
				'class' 		=> 'fecshop\services\customer\Address',
			],
			'affiliate' => [
				'class' 		=> 'fecshop\services\customer\Affiliate',
			],
			'coupon' => [
				'class' 		=> 'fecshop\services\customer\Coupon',
			],
			'dropship' => [
				'class' 		=> 'fecshop\services\customer\Dropship',
			],
			'favorite' => [
				'class' 		=> 'fecshop\services\customer\Favorite',
			],
			'message' => [
				'class' 		=> 'fecshop\services\customer\Message',
			],
			'order' => [
				'class' 		=> 'fecshop\services\customer\Order',
			],
			'point' => [
				'class' 		=> 'fecshop\services\customer\Point',
			],
			'review' => [
				'class' 		=> 'fecshop\services\customer\Review',
			],
			'wholesale' => [
				'class' 		=> 'fecshop\services\customer\Wholesale',
			],
		],
	],
];