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
				'paypal_standard' => [
					'label' => 'PayPal Website Payments Standard',
					'image' => ['images/paypal_standard.png','common'], # Ö§¸¶Ò³ÃæÏÔÊ¾µÄÍ¼Æ¬¡£
					'supplement' => 'You will be redirected to the PayPal website when you place an order. ', # ²¹³ä
					
				],
				'credit_card' => [
					'label' => 'Credit Card',
					'image' => ['images/mastercard.png','common'] ,# Ö§¸¶Ò³ÃæÏÔÊ¾µÄÍ¼Æ¬¡£
					'supplement' => '', # ²¹³ä
					'style'	=> '<style></style>',  # ²¹³äcss
				
				],
			],
			
		]
	]
];