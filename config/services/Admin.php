<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'admin' => [
        'class' => 'fecshop\services\Admin',
        // 子服务
        'childService' => [
            'urlKey' => [
                'class' => 'fecshop\services\admin\UrlKey',
                'urlKeyTags' => [
					'dashboard_main' 								=> '控制面板-主界面',
					'catalog_product_info_manager' 			=> '产品分类-产品管理-产品信息管理',
					'catalog_product_review_manager' 		=> '产品分类-产品管理-产品评论管理',
					'catalog_product_search_manager' 		=> '产品分类-产品管理-产品搜索管理',
					'catalog_product_favorite_manager' 	=> '产品分类-产品管理-产品收藏管理',
					'catalog_category_manager' 				=> '产品分类-分类管理',
					'catalog_url_rewrite_manager' 			=> '产品分类-URL重写管理',
                    'sales_order_manager' 						=> '销售-订单-订单管理',
					'sales_coupon_manager' 					=> '销售-优惠券',
                    'customer_account' 							=> '用户管理-帐号管理',
					'customer_newsletter' 						=> '用户管理-NewsLetter',
                    'cms_page' 										=> 'CMS-Page管理',
					'cms_static_block' 								=> 'CMS-静态块',
					'dashboard_user_myaccount' 				=> '控制面板-用户管理-我的账户',
					'dashboard_user_account_manager' 	=> '控制面板-用户管理-账户管理',
					'dashboard_user_role' 						=> '控制面板-用户管理-权限管理',
					'dashboard_user_resource' 				=> '控制面板-用户管理-资源管理',
					'dashboard_log_info' 							=> '控制面板-操作日志',
					'dashboard_log_manager' 					=> '控制面板-日志管理',
					'dashboard_cache' 							=> '控制面板-缓存管理',
					'dashboard_config' 							=> '控制面板-后台配置',
					'dashboard_error_handler'					=> '控制面板-ErrorHandler',

				],
            ],
            'roleUrlKey' => [
                'class' => 'fecshop\services\admin\RoleUrlKey',
            ],
            'role' => [
                'class' => 'fecshop\services\admin\Role',
            ],
            'userRole' => [
                'class' => 'fecshop\services\admin\UserRole',
            ],
            'systemLog' => [
                'class' => 'fecshop\services\admin\SystemLog',
            ],
            'menu' => [
                'class'        => 'fecshop\services\admin\Menu',
                'menuConfig' => [
                    // 一级大类
                    'catalog' => [
                        'label' => '产品分类',
                        'child' => [
                            // 二级类
                            'product_manager' => [
                                'label' => '产品管理',
                                'child' => [
                                    // 三级类
                                    'product_info_manager' => [
                                        'label' => '产品信息管理',
                                        'url_key' => '/catalog/productinfo/index',
                                    ],
                                    // 三级类
                                    'product_review_manager' => [
                                        'label' => '产品评论管理',
                                        'url_key' => '/catalog/productreview/index',
                                    ],
                                    // 三级类
                                    'product_search_manager' => [
                                        'label' => '产品搜索管理',
                                        'url_key' => '/catalog/productsearch/index',
                                    ],

                                    'product_favorite_manager' => [
                                        'label' => '产品收藏管理',
                                        'url_key' => '/catalog/productfavorite/index',
                                    ],
                                ]
                            ],
                            'category_manager' => [
                                'label' => '分类管理',
                                'url_key' => '/catalog/category/index',
                            ],
                            'urlrewrite_manager' => [
                                'label' => 'URL重写管理',
                                'url_key' => '/catalog/urlrewrite/index',
                            ],
                        ]
                    ],
                    'sales' => [
                        'label' => '销售',
                        'child' => [
                            'order' => [
                                'label' => '订单',
                                'child' => [
                                    'order_manager' => [
                                        'label' => '订单管理',
                                        'url_key' => '/sales/orderinfo/manager',
                                    ],
                                ],
                            ],
                            'coupon' => [
                                'label' => '优惠券',
                                'url_key' => '/sales/coupon/manager',
                            ],
                        ],
                    ],
                    'customer' => [
                        'label' => '用户管理',
                        'child' => [
                            'account' => [
                                'label' => '账号管理',
                                'url_key' => '/customer/account/index',
                            ],
                            'newsletter' => [
                                'label' => 'NewsLetter',
                                'url_key' => '/customer/newsletter/index',
                            ],

                        ],
                    ],
                    'cms' => [
                        'label' => 'CMS',
                        'child' => [
                            'page' => [
                                'label' => 'Page管理',
                                'url_key' => '/cms/article/index',
                            ],
                            'staticblock' => [
                                'label' => '静态块',
                                'url_key' => '/cms/staticblock/index',
                            ],
                        ],
                    ],
                    'dashboard' => [
                        'label' => '控制面板',
                        'child' => [
                            'adminuser' => [
                                'label' => '用户管理',
                                'child' => [
                                    'myaccount' => [
                                        'label' => '我的账户',
                                        'url_key' => '/fecadmin/myaccount/index',
                                    ],
                                    'account_manager' => [
                                        'label' => '账户管理',
                                        'url_key' => '/fecadmin/account/manager',
                                    ],
                                    'role_manager' => [
                                        'label' => '权限管理',
                                        'url_key' => '/fecadmin/role/manager',
                                    ],
                                    'resource_manager' => [
                                        'label' => '资源管理',
                                        'url_key' => '/fecadmin/resource/manager',
                                    ],
                                ],
                            ],
                            //'menu' => [
                            //    'label' => '菜单管理',
                            //    'url_key' => '/fecadmin/menu/manager',
                            //],
                            'log' => [
                                'label' => '操作日志',
                                'url_key' => '/fecadmin/log/index',
                            ],
                            'logtj' => [
                                'label' => '日志统计',
                                'url_key' => '/fecadmin/logtj/index',
                            ],
                            'cache' => [
                                'label' => '缓存管理',
                                'url_key' => '/fecadmin/cache/index',
                            ],
                            'config' => [
                                'label' => '后台配置',
                                'url_key' => '/fecadmin/config/manager',
                            ],
                            'error_handler' => [
                                'label' => 'Error Handler',
                                'url_key' => '/system/error/index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];