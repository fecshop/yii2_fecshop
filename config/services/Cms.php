<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'cms' => [
		'class' => 'fecshop\services\Cms',
		'storage' => 'mysqldb', # mysqldb or mongodb.
		# ×Ó·þÎñ
		'childService' => [
			'article' => [
				'class' 		=> 'fecshop\services\cms\Article',
			],
		],
	],
];