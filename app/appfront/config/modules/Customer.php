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
				# 注册页面的验证码是否开启
				'registerPageCaptcha' => true, 
				# 注册账号后发送的邮件信息。
				'email' => [
					'enable' => true,
					# 邮件内容的动态数据提供部分
					'block'		=> 'fecshop\app\appfront\modules\Customer\block\mailer\account\register\EmailBody',
					# 邮件内容的view部分
					'viewPath' 	=> 'mailer/customer/account/register',
					/*
					'mailerConfig' => [
						'appfront_register' => [
							'class' => 'yii\swiftmailer\Mailer',
							'transport' => [
								'class' => 'Swift_SmtpTransport',
								'host' => 'smtp.qq.com',
								'username' => '2358269014@qq.com',
								'password' => 'bjxpkyzfwkxnebai',
								'port' => '587',
								'encryption' => 'tls',
							],
							'messageConfig'=>[  
							   'charset'=>'UTF-8',  
							], 
						],
					],
					*/
				]
			],
			'login' => [
				# 在登录页面 customer/account/login 页面登录成功后跳转的urlkey，
				'loginPageSuccessRedirectUrlKey' => 'customer/account',
				# 在其他页面的弹框方式登录的账号成功后，的页面跳转，如果是false，则代表返回原来的页面。
				'otherPageSuccessRedirectUrlKey' => false  ,
				# 登录页面的验证码是否开启
				'loginPageCaptcha' => false,  
				# 邮件信息，登录账号后是否发送邮件
				'email' => [
					'enable' => false,
					# 邮件内容的动态数据提供部分
					'block'		=> 'fecshop\app\appfront\modules\Customer\block\mailer\account\login\EmailBody',
					# 邮件内容的view部分
					'viewPath' 	=> 'mailer/customer/account/login',
					# 如果不定义 mailerConfig，则会使用email service里面的默认配置
				]
			],
			'forgotPassword' => [
				'forgotCaptcha' => true, 
				'email' => [
					'block'		=> 'fecshop\app\appfront\modules\Customer\block\mailer\account\forgotpassword\EmailBody',
					# 邮件内容的view部分
					'viewPath' 	=> 'mailer/customer/account/forgotpassword',
					# 如果不定义 mailerConfig，则会使用email service里面的默认配置
					
					//'mailerConfig' => []
				],
			],
		],
	],
];




