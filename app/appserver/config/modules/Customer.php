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
            //'register' => [
                // 账号注册成功后，是否自动登录
             //   'successAutoLogin' => true,
                /**
                  * 1.注册页面的验证码是否开启
                  * 2.对于appserver 端，是基于api，默认验证码不可用，必须再@common/config/main-local.php中配置redis
                  *    验证码才可用，否则，强行开启会导致报错。
                  */
            //    'registerPageCaptcha' => false,

            //],
            //'login' => [
                /**
                  * 1.登陆页面的验证码是否开启
                  * 2.对于appserver 端，是基于api，默认验证码不可用，必须再@common/config/main-local.php中配置redis
                  *    验证码才可用，否则，强行开启会导致报错。
                  */
            //    'loginPageCaptcha' => false,
            //
            //],
            //'forgotPassword' => [
                /**
                  * 1.忘记密码页面的验证码是否开启
                  * 2.对于appserver 端，是基于api，默认验证码不可用，必须再@common/config/main-local.php中配置redis
                  *    验证码才可用，否则，强行开启会导致报错。
                  */
            //    'forgotCaptcha' => false,
            //
            //],

            'leftMenu'  => [
                'Account Information' => '/customer/editaccount',
                'Address Book' => '/customer/address',
                'My Orders' => '/customer/order',
                'My Product Reviews' => '/customer/productreview',
                'My Favorite' => '/customer/productfavorite',

            ],

            //'contacts'    => [
                /**
                  * 1.联系我们页面的验证码是否开启
                  * 2.对于appserver 端，是基于api，默认验证码不可用，必须再@common/config/main-local.php中配置redis
                  *    验证码才可用，否则，强行开启会导致报错。
                  */
            //    'contactsCaptcha' => false,
                // 设置联系我们邮箱，如果不设置，则从email service配置中读取。
                //'address' => '',
            //],
            //'newsletterSubscribe' => [

            //],
        ],
    ]
];