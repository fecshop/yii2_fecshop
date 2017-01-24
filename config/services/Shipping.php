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
		# Shipping的运费，是表格的形式录入，shippingCsvDir是存放运费表格的文件路径。
		'shippingCsvDir' => '@common/config/shipping', 
		'shippingConfig'=>[
			'free_shipping'=>[  # 免运费
				'label'=>'Free shipping( 7-20 work days)',
				'name' => 'HKBRAM',
				'cost' => 0,
			],
			'fast_shipping'=>[
				'label'=>'Fast Shipping( 5-10 work days)',
				'name' => 'HKDHL',
				'cost' => 'csv' # 请将文件名字的命名写入 fast_shipping.csv
				
			],
		],
		# 该值必须在上面的配置 $shippingConfig中存在，如果不存在，则返回为空。
		'defaultShippingMethod' => [
			'enable'	=> true, # 如果值为true，那么用户在cart生成的时候，就会填写上默认的货运方式。
			'shipping' => 'free_shipping',
		],
	]
];