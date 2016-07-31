<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
# 本文件在app/web/index.php 处引入。
# fecshop - appfront 的核心模块
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
# 此处也可以重写fecshop的组件。供调用。
return [
	'modules'=>$modules,
	'params'	=> [
	
	],
	'components' => [
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' 		=> true,
			'enableStrictParsing' 	=> true,
			'showScriptName' 		=> false,
			'rules' => [
				'GET v1/article/index/test' => 'v1/article/index/test',
				[
					# http://fecshop.appapi.fancyecommerce.com/v1/article/index
					'class' => 'yii\rest\UrlRule', 
					'controller' => 'v1/article/index',  
					'pluralize' => false,  
                ],  
			],
		],
		'request' => [  
            'class' => '\yii\web\Request',  
            'enableCookieValidation' => false,  
            'parsers' => [  
                'application/json' => 'yii\web\JsonParser',  
            ],  
        ],
		
		'user' => [
			'identityClass' => 'fecadmin\models\AdminUser',
			'enableAutoLogin' => true,
		],
		
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		
	],
	 
	
];
