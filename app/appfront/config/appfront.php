<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
/**
 * 本文件在@appfront/web/index.php 处，会被引入。
 * 该配置文件会加载./modules/*.php，并合并成一个数组，返回。
 */
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename) {
    $modules = array_merge($modules, require($filename));
}
$params = require __DIR__ .'/params.php';
// 此处也可以重写fecshop的组件。供调用。
$config = [
    'modules'=>$modules,
    /**
     * bootstrap指的是yii2中的初始化（注意，这个bootstrap不是css那个bootstrap），关于Yii2 bootstrap的功能描述可以
     * 参看地址：http://www.yiichina.com/doc/guide/2.0/runtime-bootstrapping
     *
     * 下面的配置的作用为：在fecshop初始化的时候，执行store component的bootstrap方法。
     * 也就是 @fecshop/components/Store.php的bootstrap($app)函数。
     * 最终是Yii::$service->store->bootstrap($app); 这样，无论执行任何controller，都会执行该函数
     * 这也就是bootstap的作用，在Yii2初始化的时候执行的部分代码。
     */
    'bootstrap' => ['store'],
    // 参数配置部分
    'params'    => $params,

    // Yii组件配置 ，关于yii2组件，可以参看：http://www.yiichina.com/doc/guide/2.0/structure-application-components
    'components' => [
        // yii2 语言组件配置，关于Yii2国际化，可以参看：http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
        'i18n' => [
            'translations' => [
                'appfront' => [
                    //'class' => 'yii\i18n\PhpMessageSource',
                    'class' => 'fecshop\yii\i18n\PhpMessageSource',
                    'basePaths' => [
                        '@fecshop/app/appfront/languages',
                    ],
                ],
            ],
        ],
        // Yii2 user组件配置，可以参看：http://www.yiichina.com/doc/guide/2.0/input-validation#client-side-validation
        'user' => [
            'class'            => 'fecshop\yii\web\User',
            'identityClass'    => 'fecshop\models\mysqldb\Customer',
            // 是否cookie 登录。
            /*
             * @var boolean whether to enable cookie-based login. Defaults to false.
             * Note that this property will be ignored if [[enableSession]] is false.
             * 设置为true的好处为，当浏览器关掉在打开，可以自动登录。
             */
            'enableAutoLogin'    => true,

            /*
             * authTimeout => 56666,
             * 这里请不要设置authTimeout，为了让customer账户session
             * 和cart的session保持一致，设置超时时间请统一在session组件
             * 中设置超时时间。
             */
            //'authTimeout' 		=> 56666,
        ],
        // 404页面对应的url key
        'errorHandler' => [
            'errorAction' => 'site/helper/error',
        ],
        // 首页对应的url key
        'urlManager' => [
            'rules' => [
                '' => 'cms/home/index',
            ],
        ],
        /**
         * Yii2 Request组件，这里进行了重写，目的是为了实现URL自定义伪静态功能。
         * 关于fecshop的url伪静态部分，可以参看：http://www.fancyecommerce.com/2016/05/18/yii2-url-%E8%87%AA%E5%AE%9A%E4%B9%89-%E4%BC%AA%E9%9D%99%E6%80%81url/
         * 关于Yii2 request的一些知识，可以参看：http://www.yiichina.com/doc/guide/2.0/runtime-requests
         */
        'request' => [
            'class' => 'fecshop\yii\web\Request',
            /*
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'O1d232trde1x-M97_7QvwPo-5QGdkLMp#@#@',
            'noCsrfRoutes' => [
                'catalog/product/addreview',
                'favorite/product/remark',
                'paypal/ipn/index',
                'paypal/ipn',
            ],
            */
        ],
    ],
    'services' => [
        'page' => [
            'childService' => [
                'theme' => [
                    'viewFileConfig' => [
                        // 'catalog/category/index' => '@fecshop/app/appfront/theme/base/front/catalog/category/index.php',
                    ],
                ],
                'widget' => [
                    'widgetConfig' => [
                        'base' => [
                            'head' => [
                                // 动态数据提供部分
                                'class' => 'fecshop\app\appfront\widgets\Head',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/head.php',
                                // 缓存
                                'cache' => [
                                    'timeout'    => 4500,  // 缓存过期时间
                                ],
                            ],
                            'header' => [
                                'class' => 'fecshop\app\appfront\widgets\Headers',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/header.php',
                                'cache' => [
                                    'timeout'    => 4500,
                                ],
                            ],
                            'topsearch' => [
                                'view'  => 'widgets/topsearch.php',
                            ],
                            'menu' => [
                                'class' => 'fecshop\app\appfront\widgets\Menu',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/menu.php',
                                'cache' => [
                                    //'timeout' 	=> 4500,
                                ],
                            ],
                            'footer' => [
                                'class' => 'fecshop\app\appfront\widgets\Footer',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/footer.php',
                                'cache' => [
                                    //'timeout' 	=> 4500,
                                ],
                            ],
                            'scroll' => [
                                // 'class' => 'fecshop\app\appfront\modules\Cms\block\widgets\Scroll',
                                // 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                                'view'  => 'widgets/scroll.php',
                            ],
                            'breadcrumbs' => [
                                'view'  => 'widgets/breadcrumbs.php',
                            ],
                            'flashmessage' => [
                                'view'  => 'widgets/flashmessage.php',
                            ],
                            'trace' => [
                                'view'  => 'widgets/trace.php',
                            ],
                            'beforeContent' => [
                                'view'  => 'widgets/beforeContent.php',
                            ],
                        ],
                        'home' => [
                            'product_price' => [
                                'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
                                'view'  		=> 'cms/home/index/price.php',
                            ],
                        ],
                        'customer' => [
                            'left_menu' => [
                                'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
                                'view'	=> 'customer/leftmenu.php'
                            ],
                        ],
                        'cms' => [
                            'productlist' => [
                                'view'  => 'cms/home/index/product.php',
                            ],
                        ],
                        'category' => [
                            'price' => [
                                'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
                                'view'  		=> 'catalog/category/price.php',
                            ],
                            'toolbar' => [
                                'view'  		=> 'catalog/category/index/toolbar.php',
                            ],
                            'filter_refineby' => [
                                'view'  		=> 'catalog/category/index/filter/refineby.php',
                            ],
                            'filter_subcategory' => [
                               'view'  		=> 'catalog/category/index/filter/subcategory.php',
                            ],
                            'filter_attr' => [
                               'view'  		=> 'catalog/category/index/filter/attr.php',
                            ],
                            'filter_price' => [
                               'view'  		=> 'catalog/category/index/filter/price.php',
                            ],
                            
                        ],
                        'product' => [
                            'price' => [
                               'view'	=> 'catalog/product/index/price.php'
                            ],
                            'options' => [
                                'view'	=> 'catalog/product/index/options.php'
                            ],
                            'tier_price' => [
                                'view'	=> 'catalog/product/index/tier_price.php'
                            ],
                            'image' => [
                                'view'	=> 'catalog/product/index/image.php'
                            ],
                            'buy_also_buy' => [
                                'view'	=> 'catalog/product/index/buy_also_buy.php'
                            ],
                            'review' => [
                                'class'  => 'fecshop\app\appfront\modules\Catalog\block\product\Review',
                                'view'  => 'catalog/product/index/review.php',
                            ],
                            'payment' => [
                                'view'			=> 'catalog/product/index/payment.php',
                            ],
                        ],
                        'search' => [
                            'toolbar' => [
                                'view'  		=> 'catalogsearch/index/index/toolbar.php',
                            ],
                        ],
                        // 下单页面
                        'order' => [
                            'shipping' => [
                                'view'	=> 'checkout/onepage/index/shipping.php'
                            ],
                            'payment' => [
                                'view'	=> 'checkout/onepage/index/payment.php'
                            ],
                            'view' => [
                                'view'	=> 'checkout/onepage/index/review_order.php'
                            ],
                        ],
                        'payment' => [
                            'paypal_express_address' => [
                                'view'	=> 'payment/paypal/express/review/address.php',
                            ],
                            'paypal_express_shipping' => [
                                'view'	=> 'payment/paypal/express/review/shipping.php'
                            ],
                            'paypal_express_orderview' => [
                                'view'	=> 'payment/paypal/express/review/review_order.php'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    
    ],

];
// product 生产环境，errorHandler使用 AppfrontErrorHandler
if (YII_ENV_PROD) {
    $config['components']['errorHandler']['class'] = 'fecshop\components\AppfrontErrorHandler';
}

return $config;