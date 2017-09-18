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
        'class' => '\fecshop\app\appserver\modules\Customer\Module',
        /**
         * 模块内部的params配置。
         */
        'params'=> [
            'register' => [
                // 账号注册成功后，是否自动登录
                'successAutoLogin' => true,
                // 注册页面的验证码是否开启
                'registerPageCaptcha' => true,

            ],
            'login' => [
                // 登录页面的验证码是否开启
                'loginPageCaptcha' => true,

            ],
            'forgotPassword' => [
                // 忘记密码页面的验证码是否开启
                'forgotCaptcha' => true,

            ],

            

            'contacts'    => [
                // 联系我们页面的验证码是否开启
                'contactsCaptcha' => true,
                // 设置联系我们邮箱，如果不设置，则从email service配置中读取。
                //'address' => '',
            ],
            'newsletterSubscribe' => [

            ],
        ],
    ]
];