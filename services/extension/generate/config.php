<?php
/**
 * 应用卸载类生成模板
 */

echo "<?php\n";
?>
/**
 * Fecmall Addons Config File
 */

// set namespace alisa
Yii::setAlias('@<?= $namespaces ?>', dirname(dirname(dirname(__DIR__))).'/addons/<?= $package ?>/<?= $addon_folder ?>/');

return [
    // 插件信息
    'info'  => [
        'name' => '<?= $addon_name ?>',
        'author' => '<?= $addon_author ?>',
    ],
    // 插件管理部分
    'administer' => [
        'install' => [
            'class' => '<?= $namespaces ?>\administer\Install',
            // 其他引入的属性，类似yii2组件的方式写入即可
            'test' => 'test_data',
        ],
        'upgrade' => [
            'class' => '<?= $namespaces ?>\administer\Upgrade',
        ],
        'uninstall' => [
            'class' => '<?= $namespaces ?>\administer\Uninstall',
        ],
    ], 
    // 各个入口的配置
    'app' => [
        // 公共层部分配置
        'common' => [
            'enable' => true,
            // 公用层的具体配置下载下面
            'config' => [
                'services' => [
                    //'cart' => [
                    //    'class' => 'fecshop\rediscart\services\Cart',
                    //    'childService' => [
                    //        'quote' => [
                    //            'class' => 'fecshop\rediscart\services\cart\Quote',
                    //        ],
                    //        'quoteItem' => [
                    //            'class' => 'fecshop\rediscart\services\cart\QuoteItem',
                    //        ],
                    //    ]
                    //]
                ],
            ]
        ],
        // 1.appfront层
        'appfront' => [
            // appfront入口的开关，如果false，则会失效
            'enable' => true,
            'config' => [
                // yii class rewrite map
                'yiiClassMap' => [
                    // 'fecshop\app\appfront\helper\test\My' => '@appfront/helper/My.php',
                ],
                // 重写model和block
                'fecRewriteMap' => [
                    // '\fecshop\app\appfront\modules\Cms\block\home\Index'  => '\fectfurnilife\app\appfront\modules\Cms\block\home\Index',
                    // '\fecshop\app\appfront\modules\Customer\block\address\Edit'  => '\fectfurnilife\app\appfront\modules\Customer\block\address\Edit',
                ],
                'modules' => [
                    //'checkout' => [
                    //    'controllerMap' => [
                    //        'cartinfo' => 'fectfurnilife\app\appfront\modules\Checkout\controllers\CartInfoController',          
                    //    ],
                    //],
                ],
            
            ],
        ],
        // html5入口
        'apphtml5' =>[],
        // appserver入口（vue 微信小程序等api）
        'appserver' =>[],
        // appapi入口，和第三方交互的api
        'appapi' =>[],
        // 后台部分
        'appadmin' =>[],
        // console，命令行脚本端
        'console' =>[],
    ],
    
    
];

