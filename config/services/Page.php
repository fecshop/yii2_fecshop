<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'page' => [
        'class' => 'fecshop\services\Page',
        //'terry' => 'xxxx',
        // 子服务
        'childService' => [
            'breadcrumbs' => [
                'class'        => 'fecshop\services\page\Breadcrumbs',
                'homeName'        => 'Home',  // if homeName => '', Home will not show in breadcrums.
                'ifAddHomeUrl'    => true,    // default true, if set false, home will not add url (a).
                //'intervalSymbol'=> ' >> '	# default value:' > '
            ],
            'translate' => [
                'class'        => 'fecshop\services\page\Translate',
            ],
            'asset' => [
                'class' =>  'fecshop\services\page\Asset',
                // js 版本号，当更改了js，将这里的版本号+1，生成的js链接就会更改为  xxx.js?v=2 ,
                // 这样做的好处是，js的链接url改变了，可以防止浏览器继续使用缓存，而不是重新加载js文件的问题。
                //'jsVersion'        => 1,
                // css 版本号，原理同js
                // 关于版本号更多的信息，请参看：http://www.fancyecommerce.com/2017/04/17/css-js-%E5%90%8E%E9%9D%A2%E5%8A%A0%E7%89%88%E6%9C%AC%E5%8F%B7%E7%9A%84%E5%8E%9F%E5%9B%A0%E5%92%8C%E6%96%B9%E5%BC%8F/
                //'cssVersion'    => 1,
                /**
                 * @var string the root directory string the published asset files.
                 * 设置: js和css的发布路径，默认在web路径下的assets文件夹下，您可以放到其他的文件路径，然后用独立的jscss域名做指向
                 * 譬如设置为：'@appimage/assets'，也可以将 @appimage 换成绝对路径
                 */
                'basePath' => '@webroot/assets',
                /**
                 * @var string the base URL through which the published asset files can be accessed.
                 * 设置: js和css的URL路径
                 * 可以将 @web 换成域名 ， 譬如  `http:://www/fecshop.com/assets`
                 * 这样就可以将js和css文件使用独立的域名了【把域名对应的地址对应到$basePath】。
                 */
                'baseUrl' => '@web/assets',
                
                
                /* js and css config example:
                'jsOptions'	=> [
                    # js config 1
                    [
                        'options' => [
                            'position' =>  'POS_END',
                        //	'condition'=> 'lt IE 9',
                        ],
                        'js'	=>[
                            'js/jquery-3.0.0.min.js',
                            'js/js.js',
                        ],
                    ],
                    # js config 2
                    [
                        'options' => [
                            'condition'=> 'lt IE 9',
                        ],
                        'js'	=>[
                            'js/ie9js.js'
                        ],
                    ],
                ],
                # css config
                'cssOptions'	=> [
                    # css config 1.
                    [
                        'css'	=>[
                            'css/style.css',
                            'css/ie.css',
                        ],
                    ],

                    # css config 2.
                    [
                        'options' => [
                            'condition'=> 'lt IE 9',
                        ],
                        'css'	=>[
                            'css/ltie9.css',
                        ],
                    ],
                ],
                */
            ],

            'theme' => [
                'class'        => 'fecshop\services\page\Theme',
                /*
                 *
                # 这里是设置本地二开模板路径，如果您在每一个store中
                # 进行了配置，这里将被覆盖。
                'localThemeDir' 	=> '@appfront/theme/terry/theme01',
                # 这里设置的是第三方的模板路径
                'thirdThemeDir'		=> [],
                # 在文件 @fecshop/app/appName/modules/AppfrontController.php 初始化
                # 这里是fecshop的模板路径。
                #'fecshopThemeDir'	=> '',
                */
            ],
            'widget' => [
                'class'        => 'fecshop\services\page\Widget',
                /*
                'widgetConfig' => [
                    'head' => [
                        # 动态数据提供部分
                        'class' => 'fecshop\app\appfront\widgets\Head',
                        # 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                        'view'  => 'widgets/head.php',
                        # 缓存
                        'cache' => [
                            'enable'	=> false, # 是否开启
                            'timeout' 	=> 4500,  # 缓存过期时间
                        ],
                    ],
                    'header' => [
                        'class' => 'fecshop\app\appfront\widgets\Headers',
                        # 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                        'view'  => 'widgets/header.php',
                        'cache' => [
                            'enable'	=> false,
                            'timeout' 	=> 4500,
                        ],
                    ],
                    'topsearch' => [
                        'view'  => 'widgets/topsearch.php',
                    ],
                    'menu' => [
                        'class' => 'fecshop\app\appfront\widgets\Menu',
                        # 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                        'view'  => 'widgets/menu.php',
                        'cache' => [
                            'enable'	=> false,
                            //'timeout' 	=> 4500,
                        ],
                    ],
                    'footer' => [
                        'class' => 'fecshop\app\appfront\widgets\Footer',
                        # 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                        'view'  => 'widgets/footer.php',
                        'cache' => [
                            'enable'	=> false,
                            //'timeout' 	=> 4500,
                        ],
                    ],
                    'scroll' => [
                        #'class' => 'fecshop\app\appfront\modules\Cms\block\widgets\Scroll',
                        # 根据多模板的优先级，依次去模板找查找该文件，直到找到这个文件。
                        'view'  => 'widgets/scroll.php',
                    ],
                    'breadcrumbs' => [
                        'view'  => 'widgets/breadcrumbs.php',
                    ],
                    'flashmessage' => [
                        'view'  => 'widgets/flashmessage.php',
                    ],
                ]
                */
            ],
            'currency' => [
                'class' => 'fecshop\services\page\Currency',
                /* currency config example:
                'baseCurrecy' => 'USD',  # 产品的价格都使用基础货币填写价格值。
                'defaultCurrency' => 'USD', # 如果store不设置货币，就使用这个store默认货币
                'currencys' => [
                    'USD' => [
                        'rate' 		=> 1,
                        'symbol' 	=> '$',
                    ],
                    'CNY' => [
                        'rate' 		=> 6.3,
                        'symbol' 	=> '￥',
                    ],
                ],
                */
            ],

            'newsletter' => [
                'class'        => 'fecshop\services\page\Newsletter',
            ],

            'staticblock' => [
                'class'        => 'fecshop\services\page\StaticBlock',
            ],

            'menu' => [
                'class' => 'fecshop\services\page\Menu',

            ],
            'message' => [
                'class' => 'fecshop\services\page\Message',

            ],
            'trace' => [ 
                'class' => 'fecshop\services\page\Trace',
                // 关闭和打开Trace功能，默认关闭，打开前，请先联系申请下面的信息，QQ：2358269014
                //'traceJsEnable' => false,
                // trace系统的 站点唯一标示  website id
                //'website_id'    => '',
                // trace系统的Token，当fecshop给trace通过curl发送数据的时候，需要使用该token进行安全认证。
               //'access_token'  => '',
                // 当fecshop给trace通过curl发送数据，最大的超时时间，该时间是为了防止网络问题时间过长，影响正常的功能。
               // 'api_time_out' => 1, // 秒
                // 追踪js url，这个是在统计系统，由管理员提供
               // 'trace_url'     => '',  // 'trace.fecshop.com/fec_trace.js',
                // 管理员提供，用于发送登录注册邮件，下单信息等。
               // 'trace_api_url' => '',  // 'http://120.24.37.249:3000/fec/trace/api',
            ],
        ],
    ],
];
