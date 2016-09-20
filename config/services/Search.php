<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'search' => [
		'class' => 'fecshop\services\Search',
		'filterAttr' => [
			'color','size', # 在搜索页面侧栏的搜索过滤属性字段
		],
		'childService' => [
			'mongoSearch' => [
				'class' 		=> 'fecshop\services\search\MongoSearch',
				'searchIndexConfig'  => [
					'name' => 10,  
					'description' => 5,  
				], 
				'searchLang'  => [
					'en' => 'english',
					'fr' => 'french',
					'de' => 'german',
					'es' => 'spanish',
					'ru' => 'russian',
					'pt' => 'portuguese',
				],
			],
			'xunSearch'  => [
				'class' 		=> 'fecshop\services\search\XunSearch',
				'searchIndexConfig'  => [
					'name' => 10,  
					'description' => 5,  
				], 
				'searchLang'    => ['zh'],
			],
		],
	]
];