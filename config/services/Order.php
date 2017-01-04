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
		'paymentStatus' => [
			'pending' => 'pending', #未付款订单状态
			'processing' => 'processing' # 已付款订单状态
		],
		
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
			
		],
	],
];
