<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
# 本文件在app/web/index.php 处引入。
# fecshop - apphtml5 的核心模块
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
				'apphtml5' => [
					//'class' => 'yii\i18n\PhpMessageSource',
					'class' => 'fecshop\yii\i18n\PhpMessageSource',
					'basePaths' => [
						'@fecshop/app/apphtml5/languages',
					],
				],
			],
		],
		
		'user' => [
			'class' 			=> 'fecshop\yii\web\User',
			'identityClass' 	=> 'fecshop\models\mysqldb\Customer',
			# 是否cookie 登录。
			/**
			 * @var boolean whether to enable cookie-based login. Defaults to false.
			 * Note that this property will be ignored if [[enableSession]] is false.
			 * 设置为true的好处为，当浏览器关掉在打开，可以自动登录。
			 */
			'enableAutoLogin' 	=> true,
			
			/** 
			 * authTimeout => 56666, 
			 * 这里请不要设置authTimeout，为了让customer账户session
			 * 和cart的session保持一致，设置超时时间请统一在session组件
			 * 中设置超时时间。
			 */
			//'authTimeout' 		=> 56666,
		],
		# 404页面对应的链接。
		'errorHandler' => [
			'errorAction' => 'site/helper/error',
		],
		# 首页对应的url
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
