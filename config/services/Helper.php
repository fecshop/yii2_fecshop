<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'helper' => [
        'class' => 'fecshop\services\Helper',
        // 子服务
        'childService' => [
            'ar' => [
                'class' => 'fecshop\services\helper\AR',
            ],
            /* 已经废除，现在使用Yii::$app->serviceLog
            'log' => [
                'class' => 'fecshop\services\helper\Log',
                'log_config' => [
                    // service log config
                    'services' => [
                        // if enable is false , all services will be close
                        'enable' => false,
                        // print log info to db.
                        'dbprint'        => false,
                        // print log info to front html
                        'htmlprint'    => false,
                        // print log
                        'htmlprintbyparam'  => [
                            // like :http://fecshop.appfront.fancyecommerce.com/cn/?servicelog=xxxxxxxx
                            'enable'        => false,
                            'paramKey'        => 'servicelog',
                            'paramVal'            => 'xxxxxxxx',
                        ],
                    ],
                ],
            ],
            */
            'wx' => [
                'class' => 'fecshop\services\helper\Wx',
                'configFile'    => '@common/config/payment/wxpay/lib/WxPay.Micro.Config.php',
            ],
            'errors' => [
                'class' => 'fecshop\services\helper\Errors',
            ],
            'zipFile' => [
                'class' => 'fecshop\services\helper\ZipFile',
            ],
            'errorHandler' => [
                'class' => 'fecshop\services\helper\ErrorHandler',
            ],
            'mobileDetect' => [
                'class' => 'fecshop\services\helper\MobileDetect',
            ],
            'captcha' => [
                'class'        => 'fecshop\services\helper\Captcha',
                'charset'    => '023456789', //随机因子
                'codelen'        => 4,  //验证码长度
                'width'        => 130, //宽度
                'height'        => 50, //高度
                'fontsize'        => 20, //子体大小
                'case_sensitive'=> false, // 是否区分大小写，false代表不区分
            ],

            'country' => [
                'class' => 'fecshop\services\helper\Country',
                //'default_country' => 'US',
            ],
            'format' => [
                'class' => 'fecshop\services\helper\Format',
                //'default_country' => 'US',
            ],
            'appserver' => [
                'class' => 'fecshop\services\helper\Appserver',
            ],
            'appapi' => [
                'class' => 'fecshop\services\helper\Appapi',
            ],
            'echart' => [
                'class' => 'fecshop\services\helper\Echart',
            ],
        ],
    ],
];
