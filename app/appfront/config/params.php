<?php
return [
	/* appfront base theme dir   */
	'appfrontBaseTheme' 	=> '@fecshop/app/appfront/theme/base/front',
	'appfrontBaseLayoutName'=> 'main.php',
	'appName' => 'appfront',
	'mailer'	=> [
		
		#在邮件中显示的Store的名字
		'storeName' 	=> 'FecShop',
		# 在邮件中显示的电话
		'phone'			=> '11111111',
		# 在邮件中显示的联系邮箱地址。
		'contacts'	=> [
			'emailAddress' => '2358269014@qq.com',
		]
	],
	# 通过邮箱找回密码，发送的resetToken过期的秒数
	'user.passwordResetTokenExpire' => 3600*24*1, # 一天
];