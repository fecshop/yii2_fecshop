<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'customer' => [
		'class' => '\fecshop\app\appfront\modules\Customer\Module',
		'params'=> [
			'register' => [
				# 账号注册成功后，是否自动登录
				'successAutoLogin' => true, 
				# 注册登录成功后，跳转的url
				'loginSuccessRedirectUrlKey' => 'customer/account', 
				'registerPageCaptcha' => true, 
				'email' => [
					'enable' => true,
					'block'		=> 'fecshop\app\appfront\modules\Customer\block\mailer\account\register\EmailBody',
					'viewPath' 	=> 'mailer/customer/account/register',
				
					'mailerConfig' => [
						'appfront_register' => [
							'class' => 'yii\swiftmailer\Mailer',
							'transport' => [
								'class' => 'Swift_SmtpTransport',
								'host' => 'smtp.sendgrid.net',
								'username' => 'support@onfancymail.com',
								'password' => 'check301',
								'port' => '587',
								'encryption' => 'tls',
							],
							'messageConfig'=>[  
							   'charset'=>'UTF-8',  
							], 
						],
					],
				]
			],
			'login' => [
				# 在登录页面 customer/account/login 页面登录成功后跳转的urlkey，
				'loginPageSuccessRedirectUrlKey' => 'customer/account',
				# 在其他页面的弹框方式登录的账号成功后，的页面跳转，如果是false，则代表返回原来的页面。
				'otherPageSuccessRedirectUrlKey' => false  ,
				
				'loginPageCaptcha' => true,  // 登录验证码是否开启
				'email' => [
					'enable' => true,
					'block'		=> 'fecshop\app\appfront\modules\Customer\block\mailer\account\login\EmailBody',
					'viewPath' 	=> 'mailer/customer/account/login',
				
					# 如果不定义 mailerConfig，则会使用email service里面的默认配置
				]
			],
		],
	],
];




