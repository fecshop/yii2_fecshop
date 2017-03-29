<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
	'payment' => [
		'class' => 'fecshop\services\Payment',
		/*
		'noRelasePaymentMethod' => 'check_money',  	# 不需要释放库存的支付方式。譬如货到付款，在系统中
													# pending订单，如果一段时间未付款，会释放产品库存，但是货到付款类型的订单不会释放，
													# 如果需要释放产品库存，客服在后台取消订单即可释放产品库存。
		'paymentConfig' => [
			'standard' => [
				'check_money' => [
					'label' 				=> 'Check / Money Order',
					//'image' => ['images/mastercard.png','common'] ,# 支付页面显示的图片。
					'supplement' 			=> 'Off-line Money Payments', # 补充
					'style'					=> '<style></style>',  # 补充css
					'start_url' 			=> '@homeUrl/payment/checkmoney/start',
					'success_redirect_url' 	=> '@homeUrl/payment/success',
				],
				'paypal_standard' => [
					'label' 				=> 'PayPal Website Payments Standard',
					'image' 				=> ['images/paypal_standard.png','common'], # 支付页面显示的图片。
					'supplement' 			=> 'You will be redirected to the PayPal website when you place an order. ', # 补充
					# 选择支付后，进入到相应支付页面的start页面。
					'start_url' 			=> '@homeUrl/payment/paypal/standard/start',
					# 接收IPN消息的页面。
					'IPN_url' 				=> '@homeUrl/payment/paypal/standard/ipn',
					# 在第三方支付成功后，跳转到网站的页面
					'success_redirect_url' 	=> '@homeUrl/payment/success',
					# 进入paypal支付页面，点击取消进入网站的页面。
					'cancel_url'			=> '@homeUrl/payment/paypal/standard/cancel',
					
					# 第三方支付网站的url
					'payment_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
					//'ipn_url'	 => 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'
					# 用户名
					'user' => 'zqy234api1-facilitator@126.com',
					# 账号
					'account'=> 'zqy234api1-facilitator@126.com',
					# 密码
					'password'=>'HF4TNTTXUD6YQREH',
					# 签名
					'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
					
						
					//'info'		=> [
				
						//'title'=>'PayPal Website Payments Standard',
						//'enable'=> 1,
						
						//'label'=>'PayPal Website Payments Standard',
						//'description'=>'You will be redirected to the PayPal website when you place an order.',
						//'image'=> 'images/hm.png',
			
			
					//],
				],
			],
			
			'express' => [
				'paypal_express' =>[
					# 用来获取token
					'nvp_url' => 'https://api-3t.sandbox.paypal.com/nvp',
					'api_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
					'account'=> 'zqy234api1-facilitator_api1.126.com',
					'password'=>'HF4TNTTXUD6YQREH',
					'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
					
					'label'=>'PayPal Express Payments',
					
					'return_url' => '@homeUrl/payment/paypal/express/review',
					'cancel_url' => '@homeUrl/payment/paypal/express/cancel',
				],
			],
			
		],
		*/
		'childService' => [
			'paypal' => [
				'class' 	=> 'fecshop\services\payment\Paypal',
				/*
				'express_payment_method' => 'paypal_express',
				'version' => '109.0',
				
				# 是否使用证书的方式进行paypal api对接（https ssl）
				# 如果配置为true，那么必须在crt_file中配置证书地址。
				# 默认不使用证书验证
				'use_local_certs' => false,	
				'crt_file' 	=> [
					'www.paypal.com' 	=>'@fecshop/services/payment/cert/paypal.crt',
					'api-3t.paypal.com' =>'@fecshop/services/payment/cert/api-3tsandboxpaypalcom.crt',
				
				],
				*/
			],
		],
	]
];
