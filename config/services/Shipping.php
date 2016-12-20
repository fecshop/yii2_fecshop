<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'shipping' => [
		'class' => 'fecshop\services\Shipping',
		'shippingConfig'=>[
			'free_shipping'=>[  # 免运费
				'label'=>'Free shipping( 7-20 work days)',
				'name' => 'HKBRAM',
				'cost' => 0,
			],
			'fast_shipping'=>[
				'label'=>'Fast Shipping( 5-10 work days)',
				'name' => 'HKDHL',
				'cost' => 'csv'
				
			],
			
		],
	]
];