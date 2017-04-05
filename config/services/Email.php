<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */ 
return [
	'email' => [
		'class' => 'fecshop\services\Email',
		
		
		/**
		 * 下面为配置邮箱的smtp部分，你可以配置多个smtp，
		 * 在具体的邮件使用中，选择下面的数组的相应的key即可。
		'mailerConfig' => [
			# 默认通用配置
			'default' => [
				'class' => 'yii\swiftmailer\Mailer',
				'transport' => [
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.qq.com',
					'username' => '372716335@qq.com',
					'password' => 'wffmbummgnhhcbbj',
					
					'port' => '587',
					'encryption' => 'tls',
				],
				'messageConfig'=>[  
				   'charset'=>'UTF-8',  
				], 
				
			],
			
			
			'login' => [
				'class' => 'yii\swiftmailer\Mailer',
				'transport' => [
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.qq.com',
					'username' => '372716335@qq.com',
					'password' => 'wffmbummgnhhcbbj',
					'port' => '587',
					'encryption' => 'tls',
				],
				'messageConfig'=>[  
				   'charset'=>'UTF-8',  
				], 
			],
			
			
        ],
		*/
		
		
		# 公用配置
		'mailerInfo'	=> [
			#在邮件中显示的Store的名字
			'storeName' 	=> 'FecShop',
			# 在邮件中显示的电话
			'phone'			=> 'xxxxxxxxxx',
			# 在邮件中显示的联系邮箱地址。
			'contacts'	=> [
				'emailAddress' => '2358269014@qq.com',
			],
			
		],
		
		
		'childService' => [
			/**
			 * 用户中心部分的邮件的设置。
			 */
			'customer' => [
				'class' => 'fecshop\services\email\Customer',
				
				# 各个邮件的模板部分：
				'emailTheme' => [
					# 注册账户发送的邮件的模板配置
					'register' => [
						'enable' => true,
						# 邮件内容的动态数据提供部分
						'widget'		=> 'fecshop\services\email\widgets\customer\account\register\Body',
						# 邮件内容的view部分
						'viewPath' 		=> '@fecshop/services/email/views/customer/account/register',
						/**
						 * 1.默认是default，譬如下面的 'mailerConfig'  => 'default',你可以不填写，因为默认就是default
						 * 2.您可以使用上面email服务的配置项mailerConfig中的设置的各个项，譬如填写default 或者 login等。
						 * 3.您还可以直接填写数组的配置（完整配置），譬如：
						 * 'register' => [
						 *		'class' => 'yii\swiftmailer\Mailer',
						 *		'transport' => [
						 *			'class' => 'Swift_SmtpTransport',
						 *			'host' => 'smtp.qq.com',
						 *			'username' => '372716335@qq.com',
						 *			'password' => 'wffmbummgnhhcbbj',
						 *			'port' => '587',
						 *			'encryption' => 'tls',
						 *		],
						 *		'messageConfig'=>[  
						 *		   'charset'=>'UTF-8',  
						 *		], 
						 *		
						 *	],
						 */
						'mailerConfig'  => 'default',
					],
					# 登录用户发送邮件的模板的设置。
					'login' => [
						'enable' => false,
						# 邮件内容的动态数据提供部分
						'widget'		=> 'fecshop\services\email\widgets\customer\account\login\Body',
						# 邮件内容的view部分
						'viewPath' 	=> '@fecshop/services/email/views/customer/account/login',
						# 如果不定义 mailerConfig，则会使用email service里面的默认配置
						'mailerConfig'  => 'default',
					],
					# 忘记密码发送邮件的模板的设置
					'forgotPassword' => [
						'enable' => true,
						'widget'		=> 'fecshop\services\email\widgets\customer\account\forgotpassword\Body',
						# 邮件内容的view部分
						'viewPath' 	=> '@fecshop/services/email/views/customer/account/forgotpassword',
						#忘记密码邮件发送后的超时时间。
						'passwordResetTokenExpire' => 86400, # 3600*24*1, # 一天
						# 如果不定义 mailerConfig，则会使用email service里面的默认配置
						# 通过邮箱找回密码，发送的resetToken过期的秒数
						'mailerConfig'  => 'default',
					],
					# 联系我们发送的邮件模板
					'contacts' => [
						'enable' => true,
						# 联系我们的邮箱地址
						
						# widget  邮件动态数据提供部分。
						'widget'		=> 'fecshop\services\email\widgets\customer\contacts\Body',
						# 邮件内容的view部分
						'viewPath' 	=> '@fecshop/services/email/views/customer/contacts',
						'address'	=> '2358269014@qq.com',
						# 如果不定义 mailerConfig，则会使用email service里面的默认配置
						//'mailerConfig'  => 'default',
					],
					# 订阅newsletter后发送的邮件模板。
					'newsletter' => [
						# 订阅邮件成功后，是否发送邮件给用户
						'enable'	=> true,
						# widget  邮件动态数据提供部分。
						'widget'		=> 'fecshop\services\email\widgets\customer\newsletter\Body',
						# 邮件内容的view部分
						'viewPath' 	=> '@fecshop/services/email/views/customer/newsletter',
						# 如果不定义 mailerConfig，则会使用email service里面的默认配置
						'mailerConfig'  => 'default',
					],
				],
			],
			
			'order' => [
				'class' => 'fecshop\services\email\Order',
				# 各个邮件的模板部分：
				'emailTheme' => [
					# 游客发送的邮件的模板配置
					'guestCreate' => [
						'enable' => true,
						# 邮件内容的动态数据提供部分
						'widget'		=> 'fecshop\services\email\widgets\order\create\Body',
						# 邮件内容的view部分
						'viewPath' 		=> '@fecshop/services/email/views/order/create/guest',
						/**
						 * 1.默认是default，譬如下面的 'mailerConfig'  => 'default',你可以不填写，因为默认就是default
						 * 2.您可以使用上面email服务的配置项mailerConfig中的设置的各个项，譬如填写default 或者 login等。
						 * 3.您还可以直接填写数组的配置（完整配置），譬如：
						 * 'register' => [
						 *		'class' => 'yii\swiftmailer\Mailer',
						 *		'transport' => [
						 *			'class' => 'Swift_SmtpTransport',
						 *			'host' => 'smtp.qq.com',
						 *			'username' => '372716335@qq.com',
						 *			'password' => 'wffmbummgnhhcbbj',
						 *			'port' => '587',
						 *			'encryption' => 'tls',
						 *		],
						 *		'messageConfig'=>[  
						 *		   'charset'=>'UTF-8',  
						 *		], 
						 *		
						 *	],
						 */
						'mailerConfig'  => 'default',
					],
					# 登录用户发送邮件的模板的设置。
					'loginedCreate' => [
						'enable' => true,
						# 邮件内容的动态数据提供部分
						'widget'		=> 'fecshop\services\email\widgets\order\create\Body',
						# 邮件内容的view部分
						'viewPath' 	=> '@fecshop/services/email/views/order/create/logined',
						# 如果不定义 mailerConfig，则会使用email service里面的默认配置
						'mailerConfig'  => 'default',
					],
					
				],
			],
		],
		
	],
];