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
		'paymentConfig' => [
			'standard' => [
				'check_money' => [
					'label' 				=> 'Check / Money Order',
					//'image' => ['images/mastercard.png','common'] ,# 支付页面显示的图片。
					'supplement' 			=> 'Off-line Money Payments', # 补充
					'style'					=> '<style></style>',  # 补充css
					'start_url' 			=> '@homeUrl/payment/checkmoney/start',
					'success_redirect_url' 	=> '@homeUrl/payment/checkmoney/success',
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
					'success_redirect_url' 	=> '@homeUrl/payment/paypal/standard/success',
					# 进入paypal支付页面，点击取消进入网站的页面。
					'cancel_url'			=> '@homeUrl/payment/paypal/standard/cancel',
					
					# 第三方支付网站的url
					'payment_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
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
					'nvp_url' => 'https://api-3t.sandbox.paypal.com/nvp',
					'api_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
					'account'=> 'zqy234api1-facilitator_api1.126.com',
					'password'=>'HF4TNTTXUD6YQREH',
					'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
					
					'enable'=> 1,
					'label'=>'PayPal Express Payments',
				],
			],
			
		]
	]
];


/*
 'payment_method'=>[
		'merchant_country' => 'US',
		'paypal'=>[
			'payments_standard'=>[
				'title'=>'PayPal Website Payments Standard',
				'enable'=> 1,
				'user' => 'zqy234api1-facilitator@126.com',
				'redirect_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
				'account'=> 'zqy234api1-facilitator@126.com',
				'password'=>'HF4TNTTXUD6YQREH',
				'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
				
				'label'=>'PayPal Website Payments Standard',
				'description'=>'You will be redirected to the PayPal website when you place an order.',
				'image'=> 'images/hm.png',
			],
			'express_checkout' =>[
				
				'nvp_url' => 'https://api-3t.sandbox.paypal.com/nvp',
				'api_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
				'account'=> 'zqy234api1-facilitator_api1.126.com',
				'password'=>'HF4TNTTXUD6YQREH',
				'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
				
				'enable'=> 1,
				'label'=>'PayPal Express Payments',
			],
		],
	],
*/