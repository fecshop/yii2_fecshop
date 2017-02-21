<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'order' => [
		'class' => 'fecshop\services\Order',
		
		//'increment_id' => '',
		'requiredAddressAttr' => [ # 必填的订单字段。
			'first_name',
			'last_name',
			'email',
			'telephone',
			'street1',
			'country',
			'city',
			'state',
			'zip'
		],
		# 子服务
		'childService' => [
			'item' => [
				'class' => 'fecshop\services\order\Item',
			],
		],
	],
];
