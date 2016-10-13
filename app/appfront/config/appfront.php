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
$params = require(__DIR__ .'/params.php');
# 此处也可以重写fecshop的组件。供调用。
return [
	'modules'=>$modules,
	/* only config in front web */
	'bootstrap' => ['store'],
	'params'	=> $params,
	
	# Yii组件配置
	'components' => [
		# language config.
		'i18n' => [
			'translations' => [
				'appfront' => [
					//'class' => 'yii\i18n\PhpMessageSource',
					'class' => 'fecshop\yii\i18n\PhpMessageSource',
					'basePaths' => [
						'@fecshop/app/appfront/languages',
					],
				],
			],
		],
		
		'user' => [
			'identityClass' => 'fecshop\models\mysqldb\Customer',
			# 是否cookie 登录。
			'enableAutoLogin' => true,
		],
		
		'errorHandler' => [
			'errorAction' => 'site/helper/error',
		],
		
		'urlManager' => [
			'rules' => [
				'' => 'cms/home/index',
			],
		],
		
		'request' => [
			'class' => 'fecshop\yii\web\Request',
			/*
			'enableCookieValidation' => true,
			'enableCsrfValidation' => true,
			'cookieValidationKey' => 'O1d232trde1x-M97_7QvwPo-5QGdkLMp#@#@',
			'noCsrfRoutes' => [
				'catalog/product/addreview',
				'favorite/product/remark',
				'paypal/ipn/index',
				'paypal/ipn',
			],
			*/
		],
	],

];
