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
					'dashboard_main' 								=> 'Dashboard-Main',
					'catalog_product_info_manager' 			=> 'Catalog-Product',
                    'catalog_product_attr_manager' 		    => 'Catalog-Product-Attr',
                    'catalog_product_attr_group_manager' 		=> 'Catalog-Product-Attr-Group',
					'catalog_product_review_manager' 		=> 'Catalog-Product-Review',
					'catalog_product_search_manager' 		=> 'Catalog-Product-Search',
					'catalog_product_favorite_manager' 	=> 'Catalog-Product-Favorite',
                    'catalog_product_upload_manager' 	    => 'Catalog-Product-Upload',
					'catalog_category_manager' 				=> 'Catalog-Category',
					'catalog_url_rewrite_manager' 			    => 'Catalog-Url-Rewrite',
                    'sales_cart_manager' 						=> 'Sales-Cart',
                    'sales_order_manager' 						=> 'Sales-Order',
					'sales_coupon_manager' 					    => 'Sales-Coupon',
                    'customer_account' 							    => 'Customer-Account',
					'customer_newsletter' 						    => 'Customer-Newsletter',
                    'cms_page' 										    => 'CMS-Page',
					'cms_static_block' 								=> 'CMS-StaticBlock',
					'dashboard_user_myaccount' 				=> 'Dashboard-User-MyAccount',
					'dashboard_user_account_manager' 	=> 'Dashboard-User-Account',
					'dashboard_user_role' 						    => 'Dashboard-User-Role',
					'dashboard_user_resource' 				    => 'Dashboard-User-Resource',
					'dashboard_log_info' 							=> 'Dashboard-Log-Info',
					'dashboard_log_manager' 					=> 'Dashboard-Log',
					'dashboard_cache' 							    => 'Dashboard-Cache',
					'dashboard_config' 							    => 'Dashboard-Config',
					'dashboard_error_handler'					=> 'Dashboard-ErrorHandler',
                    
                    'config_base_manager' 					    => 'Config-Base',
                    'config_payment_manager' 					    => 'Config-Payment',
                    'config_appfront_manager' 					=> 'Config-Appfront',
                    'config_apphtml5_manager' 					=> 'Config-Apphtml5',
                    'config_appserver_manager' 				=> 'Config-Appserver',
                    'config_appadmin_manager' 				=> 'Config-Appadmin',
				],
            ],
            'roleUrlKey' => [
                'class' => 'fecshop\services\admin\RoleUrlKey',
            ],
            'role' => [
                'class' => 'fecshop\services\admin\Role',
            ],
            'config' => [
                'class' => 'fecshop\services\admin\Config',
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
                        'label' => 'Category & Prodcut',
                        'child' => [
                            // 二级类
                            'product_manager' => [
                                'label' => 'Manager Product',
                                'child' => [
                                    // 三级类
                                    'product_info_manager' => [
                                        'label' => 'Product Info',
                                        'url_key' => '/catalog/productinfo/index',
                                    ],
                                    'product_attr_manager' => [
                                        'label' => '产品属性管理',
                                        'url_key' => '/catalog/productattr/manager',
                                    ],
                                    'product_attr_group_manager' => [
                                        'label' => '产品属性组管理',
                                        'url_key' => '/catalog/productattrgroup/manager',
                                    ],
                                    'product_param_manager' => [
                                        'label' => '产品参数管理',
                                        'url_key' => '/config/product/manager',
                                    ],
                                    // 三级类
                                    'product_review_manager' => [
                                        'label' => 'Product Reveiew',
                                        'url_key' => '/catalog/productreview/index',
                                    ],
                                    
                                    'product_favorite_manager' => [
                                        'label' => 'Product Favorite',
                                        'url_key' => '/catalog/productfavorite/index',
                                    ],
                                    
                                    'product_upload_manager' => [
                                        'label' => '产品批量上传',
                                        'url_key' => '/catalog/productupload/manager',
                                    ],
                                ]
                            ],
                            'category_manager' => [
                                'label' => 'Manager Category',
                                'child' => [
                                    // 三级类
                                    'category_info_manager' => [
                                        'label' => 'Category Info',
                                        'url_key' => '/catalog/category/index',
                                    ],
                                    'category_sort_manager' => [
                                        'label' => 'Category Sort',
                                        'url_key' => '/config/categorysort/manager',
                                    ],
                                    //'category_info_config' => [
                                    //    'label' => '分类配置',
                                    //    'url_key' => '/catalog/category/index',
                                    //],
                                ],  
                            ],
                            'urlrewrite_manager' => [
                                'label' => 'URL Rewrite',
                                'url_key' => '/catalog/urlrewrite/index',
                            ],
                        ]
                    ],
                    'sales' => [
                        'label' => 'Sales',
                        'child' => [
                            'order' => [
                                'label' => 'Order',
                                'child' => [
                                    'order_manager' => [
                                        'label' => 'Manager Order',
                                        'url_key' => '/sales/orderinfo/manager',
                                    ],
                                    'order_config' => [
                                        'label' => '订单参数配置',
                                        'url_key' => '/config/order/manager',
                                    ],
                                ],
                            ],
                            'cart' => [
                                'label' => '购物车参数配置',
                                'url_key' => '/config/cart/manager',
                            ], 
                            'coupon' => [
                                'label' => 'Coupon',
                                'url_key' => '/sales/coupon/manager',
                            ],
                        ],
                    ],
                    'config' => [
                        'label' => '网站配置',
                        'child' => [
                            'services' => [
                                'label' => '基础配置',
                                'child' => [
                                    'base_manager' => [
                                        'label' => '基础配置',
                                        'url_key' => '/config/base/manager',
                                    ],
                                    'service_manager' => [
                                        'label' => 'Service数据库配置',
                                        'url_key' => '/config/service/db',
                                    ],
                                    'search' => [
                                        'label' => '搜索引擎配置',
                                        'url_key' => '/config/search/manager',
                                    ],
                                    'mutillang' => [
                                        'label' => '多语言配置',
                                        'url_key' => '/config/mutillang/manager',
                                    ],
                                    'currency' => [
                                        'label' => '货币配置',
                                        'url_key' => '/config/currency/manager',
                                    ],
                                    'email_manager' => [
                                        'label' => '邮件配置',
                                        'url_key' => '/config/email/manager',
                                    ],
                                    
                                ],
                            ],
                            
                            'payment_config' => [
                                'label' => '支付参数配置',
                                'child' => [
                                    'payment_paypal' => [
                                        'label' => 'Paypal支付配置',
                                        'url_key' => '/config/paymentpaypal/manager',
                                    ],
                                    'payment_alipay' => [
                                        'label' => '支付宝支付配置',
                                        'url_key' => '/config/paymentalipay/manager',
                                    ],
                                    'payment_wxpay' => [
                                        'label' => '微信支付配置',
                                        'url_key' => '/config/paymentwxpay/manager',
                                    ],
                                ],
                            ],
                            
                            'appfront_config' => [
                                'label' => 'Appfront配置',
                                'child' => [
                                    'base' => [
                                        'label' => '基础配置',
                                        'url_key' => '/config/appfrontbase/manager',
                                    ],
                                    'home' => [
                                        'label' => '首页配置',
                                        'url_key' => '/config/appfronthome/manager',
                                    ],
                                    'cache' => [
                                        'label' => '缓存配置',
                                        'url_key' => '/config/appfrontcache/manager',
                                    ],
                                    'store' => [
                                        'label' => 'Store配置',
                                        'url_key' => '/config/appfrontstore/manager',
                                    ],
                                    'catalog' => [
                                        'label' => '分类产品配置',
                                        'url_key' => '/config/appfrontcatalog/manager',
                                    ],
                                    'account' => [
                                        'label' => '账户配置',
                                        'url_key' => '/config/appfrontaccount/manager',
                                    ],
                                    'payment' => [
                                        'label' => '支付配置',
                                        'url_key' => '/config/appfrontpayment/manager',
                                    ],
                                    
                                ],
                            ],
                            'apphtml5_config' => [
                                'label' => 'Apphtml5配置',
                                'child' => [
                                    'base' => [
                                        'label' => '基础配置',
                                        'url_key' => '/config/apphtml5base/manager',
                                    ],
                                    'home' => [
                                        'label' => '首页配置',
                                        'url_key' => '/config/apphtml5home/manager',
                                    ],
                                    'cache' => [
                                        'label' => '缓存配置',
                                        'url_key' => '/config/apphtml5cache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store配置',
                                        'url_key' => '/config/apphtml5store/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => '分类产品配置',
                                        'url_key' => '/config/apphtml5catalog/manager',
                                    ],
                                    'payment' => [
                                        'label' => '支付配置',
                                        'url_key' => '/config/apphtml5payment/manager',
                                    ],
                                ],
                            ],
                            'appserver_config' => [
                                'label' => 'Appserver配置',
                                'child' => [
                                    'base' => [
                                        'label' => '基础配置',
                                        'url_key' => '/config/appserverbase/manager',
                                    ],
                                    'home' => [
                                        'label' => '首页配置',
                                        'url_key' => '/config/appserverhome/manager',
                                    ],
                                    'cache' => [
                                        'label' => '缓存配置',
                                        'url_key' => '/config/appservercache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store配置',
                                        'url_key' => '/config/appserverstore/manager',
                                    ],
                                    'store_lang' => [
                                        'label' => 'Store语言配置',
                                        'url_key' => '/config/appserverstorelang/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => '分类产品配置',
                                        'url_key' => '/config/appservercatalog/manager',
                                    ],
                                    'payment' => [
                                        'label' => '支付配置',
                                        'url_key' => '/config/appserverpayment/manager',
                                    ],
                                ],
                            ],
                        ],
                        
                    ],
                    'customer' => [
                        'label' => 'Manager User',
                        'child' => [
                            'account' => [
                                'label' => 'Manager Account',
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
                                'label' => 'Manager Page',
                                'url_key' => '/cms/article/index',
                            ],
                            'staticblock' => [
                                'label' => 'Static Block',
                                'url_key' => '/cms/staticblock/index',
                            ],
                        ],
                    ],
                    'dashboard' => [
                        'label' => 'Dashboard',
                        'child' => [
                            'adminuser' => [
                                'label' => 'Admin User',
                                'child' => [
                                    'myaccount' => [
                                        'label' => 'My Account',
                                        'url_key' => '/fecadmin/myaccount/index',
                                    ],
                                    'account_manager' => [
                                        'label' => 'Manager Account',
                                        'url_key' => '/fecadmin/account/manager',
                                    ],
                                    'role_manager' => [
                                        'label' => 'Manager Role',
                                        'url_key' => '/fecadmin/role/manager',
                                    ],
                                    'resource_manager' => [
                                        'label' => 'Manager Resource',
                                        'url_key' => '/fecadmin/resource/manager',
                                    ],
                                ],
                            ],
                            //'menu' => [
                            //    'label' => '菜单管理',
                            //    'url_key' => '/fecadmin/menu/manager',
                            //],
                            'log' => [
                                'label' => 'Log Info',
                                'url_key' => '/fecadmin/log/index',
                            ],
                            'logtj' => [
                                'label' => 'Log Statistics',
                                'url_key' => '/fecadmin/logtj/index',
                            ],
                            'cache' => [
                                'label' => 'Manager Cache',
                                'url_key' => '/fecadmin/cache/index',
                            ],
                            'config' => [
                                'label' => 'Admin Config',
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