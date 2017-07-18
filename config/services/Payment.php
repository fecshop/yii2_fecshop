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
                'class'    => 'fecshop\services\payment\Paypal',
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
            'alipay' => [
                'class'         => 'fecshop\services\payment\Alipay',
                // 商家appId
                //'appId'       => '2016080500172713',
                // 应用私钥，可以在这里通过工具生成：https://docs.open.alipay.com/291/105971/
                //'rsaPrivateKey' => 'MIIEpAIBAAKCAQEApIw+Hsk65Z+mieDsEiTkhtf7ZNBgks83DLUDb1yh2d/HDB0s9zHFzsgQGny0kUTM0fJ43h7WydyUG9Kuv4fxD5iVfM2xkUYW5bvfTXVaj5LLj8rTKL+nnFybzzM5rewqh2u1Gzd7BbpOnhMn4Y+7JyyaWXsnRFBxIrmRAqQJVlVUG4RclLHfplFkMVcEMzoRda2UV54oQDMg8ZxignCqxgIKr7bpwpgdpdqZArHtmyEjhQfIblCLDjVk0rKxGsaz+ATYVt3eQozdyNEuKFRhy0VGmwmdQYhQFbge7SS6bVqXZHsq2fNZ6hMJ2XNOZajFm5jXMksnaX85PzdJ58HFewIDAQABAoIBAAn/c27Pb0Kwdp/+CJn5n+EJkn7HonaJHKErBnBnwnXIgQGdbDQA1DICOehCF36UHZXME8f7O7W8L0uZe4Crs9vsu3h/zwAysAV5atH8BWqf0rqD6lyZeIepoNXwGNsWdGcSBkkHD/SDI2+7Xjr4TrjMnvw83V/rO1SOzd7JNMAICj6NZ2tteIqQCn+BriEEawRDimSAWvVaCbwnbCDF8y40MxZ4K6picBQ0gsbC6eQuXRqzB6CoFBkQsXGtK0VXvlJXVmKRzRqPxjD6Cer21tF1CDryVedSWKsdwEXvOdO8LdPZpnmQMvwyTuhM0V9L3rif4spIK9ML3lZLzM47rpECgYEA2XzyRUEni4jKmWcE3oSZjCvp5BJwi6DSRkAphGTwoW/8oTCJhx1B43Qusxv0bUwGzN/KlRHwgNRerQ9xqWMYnIIfBJLOqASunB8eHMBDN+zC6TnUKOu43CpZ+fGVVm2VUbWLHr5h93AOBSQhtvvegbEk9hbNRCCbcY6jbZZmgkUCgYEAwa9v5Bk8q0obGonDUd5LZkHBt7mfT12cUPkfBClz8/tpv7rirCg5I4XaQHerEo+iCOpn3iIl37ix6V7LcspjJuJwpTn0OzugO7MzEyRi0zAqkNAB1voeJL/hl08rHVkA9fZ2AVuOhUvG2A1pqB8BjY9AW1/2W/EXH16qCKzfhL8CgYEAjK/QoJAHHrH8LMOBWNf547y8bfanqwr7OspikOwi5Ktmhna5YBfC+Xm8g8w/jzww4fKaP1f9dbjrDZQB+IrL7uIVYoX8/J8avI88kWilktWrN+daoKXrTTBwR8jIy8HTZ6nCNr787G0mBJlc3duMEeUffbk+SyW0p/6XJVq3MOkCgYEAhXqPJOZTjkRS63YXels1ITKd+yzcYojDynX07xxWQcV4+l4kCrrprdZ4M8eEyRTdeUF59XcZHNYfHhJrKR/bNxgEw4luDEgqRBpaT43a4WonW4dOTUYv8eme4XT45I/K/rcsWgEr9ibj0U9lCizcGB+qHY7DrFc5NTA7BCGHJOcCgYAN82UigyX4qyqpQDofP/fQOybE2QJuG4pG3x3k/nMxCYm6DAcDS9WyRIAlNwOLXDFLICPa3SlaFjC4A0hLh1CU0465Bau+/q8Avs2/Hz1SMoeqyKf8Sq3RyCFFSb0Zsq26Tr8BtyRjHfFRDiZe5O9H7lOCGqiQEgUuAE9aCgCYVA==',
                // 支付宝公钥，注意，这里不是应用私钥，需要把应用公钥提交后获取的支付宝公钥
                // 对于沙盒账户的步骤可以参看：http://blog.csdn.net/terry_water/article/details/75258175
                //'alipayrsaPublicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAt5egD1BQCK5fCQXKsgWh+VFj9zanV9cdwVmM/MOQ/zrwMBHMIRO0IdJMft351iXtyACKVX+noK1qzkiVOdg3MxLjbGoMDKR+/1PDxoxtWSVUJBywoYHH/Dh7TCi5GWGasOlXV4qWi0e5Yfa2x/Wi0cxqx76aY5izXEyabHAvWgTWNv121ZRNhl4qcuoWZYiMIQpTst6hEhRn/isUMgdtLRQ1a06q+qOkLmJ99vq8cqbfduAdOuhzbZNWqLV76CSc0meurlVtDoIn5kVAZdzjNTA2rlqSCgs/OZxaL8s/qrIynhLoB6U6i0fj4RsIsbrvoSnrPWo98rsM0RrlU8fpdwIDAQAB',  //'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApIw+Hsk65Z+mieDsEiTkhtf7ZNBgks83DLUDb1yh2d/HDB0s9zHFzsgQGny0kUTM0fJ43h7WydyUG9Kuv4fxD5iVfM2xkUYW5bvfTXVaj5LLj8rTKL+nnFybzzM5rewqh2u1Gzd7BbpOnhMn4Y+7JyyaWXsnRFBxIrmRAqQJVlVUG4RclLHfplFkMVcEMzoRda2UV54oQDMg8ZxignCqxgIKr7bpwpgdpdqZArHtmyEjhQfIblCLDjVk0rKxGsaz+ATYVt3eQozdyNEuKFRhy0VGmwmdQYhQFbge7SS6bVqXZHsq2fNZ6hMJ2XNOZajFm5jXMksnaX85PzdJ58HFewIDAQAB',
                'format'        => 'json',
                'charset'       => 'utf-8',
                'signType'      => 'RSA2',
                //'devide'        => 'pc' ,  // 填写pc或者wap，pc代表pc机浏览器支付类型，wap代表手机浏览器支付类型 
                // 下面是沙盒地址， 正式环境请改为：https://openapi.alipay.com/gateway.do
                //'gatewayUrl'    => 'https://openapi.alipaydev.com/gateway.do', 
            ],
        ],
    ],
];
