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
	/* only config in front web */
	'bootstrap' => ['store'],
	'params'	=> [
		/* appfront base theme dir   */
		'appfrontBaseTheme' 	=> '@fecshop/app/appfront/theme/base/front',
		'appfrontBaseLayoutName'=> 'main.php',
		'mailer'	=> [
			# 用来发送邮件的函数helper类，用来处理数据，生成邮件内容，然后调用邮件service
			# 通过配置的方式去访问调用的目的是为了让用户可以通过配置 重写这个类
			'mailer_class' 	=> 'fecshop\app\appfront\helper\mailer\Email',
			#在邮件中显示的Store的名字
			'storeName' 	=> 'FecShop',
			# 在邮件中显示的电话
			'phone'			=> '11111111',
			# 在邮件中显示的联系邮箱地址。
			'contacts'	=> [
				'emailAddress' => '2358269014@qq.com',
			]
		],
	],
	
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
