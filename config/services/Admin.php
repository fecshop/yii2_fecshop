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
                    'catalog_product_brand_manager' 	    => 'Catalog-Product-Brand',
                    'catalog_product_brand_category_manager' 	    => 'Catalog-Product-Brand-Category',
					'catalog_category_manager' 				=> 'Catalog-Category',
					'catalog_url_rewrite_manager' 			    => 'Catalog-Url-Rewrite',
                    'sales_cart_manager' 						=> 'Sales-Cart',
                    'sales_order_manager' 						=> 'Sales-Order',
					'sales_coupon_manager' 					    => 'Sales-Coupon',
                    'customer_account' 							    => 'Customer-Account',
                    'customer_contacts' 							    => 'Customer-Contacts',
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
                    'extension_manager' 					    => 'Extension-Center',
                    'extension_installed' 					    => 'Extension-Installed',
                    'extension_developer_center' 					    => 'Extension-Developer-Center',
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
                        'enable' => true,  // 显示和隐藏菜单选项的开关，true代表显示，false代表隐藏
                        'sort_order' => 900, // 菜单排序，如果不设置 sort_order， 默认值为0，倒序排列，该值越大，越排在前面
                        'child' => [
                            // 二级类
                            'product_manager' => [
                                'label' => 'Manage Product',
                                'sort_order' => 200,
                                'child' => [
                                    // 三级类
                                    'product_info_manager' => [
                                        'label' => 'Product Info',
                                        'url_key' => '/catalog/productinfo/index',
                                        'sort_order' => 500,
                                    ],
                                    'product_attr_manager' => [
                                        'label' => 'Product Attribute',
                                        'url_key' => '/catalog/productattr/manager',
                                        'sort_order' => 460,
                                    ],
                                    'product_attr_group_manager' => [
                                        'label' => 'Product Attribute Group',
                                        'url_key' => '/catalog/productattrgroup/manager',
                                        'sort_order' => 420,
                                    ],
                                    'product_param_manager' => [
                                        'label' => 'Product Param Config',
                                        'url_key' => '/config/product/manager',
                                        'sort_order' => 380,
                                    ],
                                    // 三级类
                                    'product_review_manager' => [
                                        'label' => 'Product Reveiew',
                                        'url_key' => '/catalog/productreview/index',
                                        'sort_order' => 340,
                                    ],
                                    
                                    'product_favorite_manager' => [
                                        'label' => 'Product Favorite',
                                        'url_key' => '/catalog/productfavorite/index',
                                        'sort_order' => 300,
                                    ],
                                    
                                    'product_upload_manager' => [
                                        'label' => 'Product Excel Upload',
                                        'url_key' => '/catalog/productupload/manager',
                                        'sort_order' => 260,
                                    ],
                                    
                                    'product_brand_manager' => [
                                        'label' => 'Product Brand',
                                        'url_key' => '/catalog/productbrand/manager',
                                        'sort_order' => 220,
                                    ],
                                    
                                    'product_randcategory_manager' => [
                                        'label' => 'Product Brand Category',
                                        'url_key' => '/catalog/productbrandcategory/manager',
                                        'sort_order' => 180,
                                    ],
                                ]
                            ],
                            'category_manager' => [
                                'label' => 'Manage Category',
                                'sort_order' => 100,
                                'child' => [
                                    // 三级类
                                    'category_info_manager' => [
                                        'label' => 'Category Info',
                                        'sort_order' => 500,
                                        'url_key' => '/catalog/category/index',
                                    ],
                                    'category_sort_manager' => [
                                        'sort_order' => 400,
                                        'label' => 'Category Sort Config',
                                        'url_key' => '/config/categorysort/manager',
                                    ],
                                    'category_upload_manager' => [
                                        'sort_order' => 300,
                                        'label' => 'Category Excel Upload',
                                        'url_key' => '/catalog/categoryupload/manager',
                                    ],
                                    
                                    //'category_info_config' => [
                                    //    'label' => '分类配置',
                                    //    'url_key' => '/catalog/category/index',
                                    //],
                                ],  
                            ],
                            'urlrewrite_manager' => [
                                'label' => 'URL Rewrite',
                                'sort_order' => 100,
                                'child' => [
                                    'urlrewrite_manager' => [
                                        'label' => 'URL Rewrite',
                                        'url_key' => '/catalog/urlrewrite/index',
                                    ],
                                ]
                            ],
                        ]
                    ],
                    
                    'sales' => [
                        'label' => 'Mall Manage',
                        'sort_order' => 800,
                        'child' => [
                            'order' => [
                                'label' => 'Order',
                                'sort_order' => 500,
                                'child' => [
                                    'order_manager' => [
                                        'sort_order' => 500,
                                        'label' => 'Manage Order',
                                        'url_key' => '/sales/orderinfo/manager',
                                    ],
                                    'order_config' => [
                                        'sort_order' => 400,
                                        'label' => 'Order Param Config',
                                        'url_key' => '/config/order/manager',
                                    ],
                                ],
                            ],
                            
                            'cart_coupon' => [
                                'label' => 'Cart & Coupon',
                                'sort_order' => 450,
                                'child' => [
                                   'cart' => [
                                        'sort_order' => 500,
                                        'label' => 'Cart Param Config',
                                        'url_key' => '/config/cart/manager',
                                    ], 
                                    'coupon' => [
                                        'sort_order' => 400,
                                        'label' => 'Coupon',
                                        'url_key' => '/sales/coupon/manager',
                                    ],
                                ],
                            ],
                            
                            'customer' => [
                                'label' => 'Customer',
                                'sort_order' => 400,
                                'child' => [
                                    'account' => [
                                        'sort_order' => 500,
                                        'label' => 'Manage Account',
                                        'url_key' => '/customer/account/index',
                                    ],
                                    'contacts' => [
                                        'sort_order' => 400,
                                        'label' => 'Customer Contacts',
                                        'url_key' => '/customer/contacts/index',
                                    ],
                                    'newsletter' => [
                                        'sort_order' => 300,
                                        'label' => 'NewsLetter',
                                        'url_key' => '/customer/newsletter/index',
                                    ],
                                ],
                            ],
                            'cms' => [
                                'label' => 'CMS',
                                'sort_order' => 350,
                                'child' => [
                                    'page' => [
                                        'label' => 'Manage Page',
                                        'sort_order' => 500,
                                        'url_key' => '/cms/article/index',
                                    ],
                                    'staticblock' => [
                                        'label' => 'Static Block',
                                        'sort_order' => 400,
                                        'url_key' => '/cms/staticblock/index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'config' => [
                        'label' => 'Website Config',
                        'sort_order' => 700,
                        'child' => [
                            'services' => [
                                'label' => 'Base Config',
                                'sort_order' => 900,
                                'child' => [
                                    'base_manager' => [
                                        'sort_order' => 500,
                                        'label' => 'Base Config',
                                        'url_key' => '/config/base/manager',
                                    ],
                                    'service_manager' => [
                                        'sort_order' => 900,
                                        'label' => 'Service Db Config',
                                        'url_key' => '/config/service/db',
                                    ],
                                    'search' => [
                                        'sort_order' => 800,
                                        'label' => 'Search Engine Config',
                                        'url_key' => '/config/search/manager',
                                    ],
                                    'mutillang' => [
                                        'sort_order' => 750,
                                        'label' => 'Mutil-Lang Config',
                                        'url_key' => '/config/mutillang/manager',
                                    ],
                                    'currency' => [
                                        'sort_order' => 700,
                                        'label' => 'Currency Config',
                                        'url_key' => '/config/currency/manager',
                                    ],
                                    'email_manager' => [
                                        'sort_order' => 600,
                                        'label' => 'Email Config',
                                        'url_key' => '/config/email/manager',
                                    ],
                                    
                                    'fa' => [
                                        'sort_order' => 500,
                                        'label' => 'FA Config',
                                        'url_key' => '/config/fa/manager',
                                    ],
                                    
                                ],
                            ],
                            
                            'payment_config' => [
                                'label' => 'Payment Param Config',
                                'sort_order' => 800,
                                'child' => [
                                    'payment_paypal' => [
                                        'sort_order' => 700,
                                        'label' => 'Paypal Config',
                                        'url_key' => '/config/paymentpaypal/manager',
                                    ],
                                    'payment_alipay' => [
                                        'sort_order' => 600,
                                        'label' => 'Alipay Config',
                                        'url_key' => '/config/paymentalipay/manager',
                                    ],
                                    'payment_wxpay' => [
                                        'sort_order' => 500,
                                        'label' => 'Wxpay Config',
                                        'url_key' => '/config/paymentwxpay/manager',
                                    ],
                                ],
                            ],
                            
                            'appfront_config' => [
                                'label' => 'Appfront Config',
                                'sort_order' => 700,
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'sort_order' => 900,
                                        'url_key' => '/config/appfrontbase/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'sort_order' => 850,
                                        'url_key' => '/config/appfronthome/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'sort_order' => 800,
                                        'url_key' => '/config/appfrontcache/manager',
                                    ],
                                    'store' => [
                                        'label' => 'Store Config',
                                        'sort_order' => 700,
                                        'url_key' => '/config/appfrontstore/manager',
                                    ],
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'sort_order' => 650,
                                        'url_key' => '/config/appfrontcatalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'sort_order' => 600,
                                        'url_key' => '/config/appfrontaccount/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'sort_order' => 500,
                                        'url_key' => '/config/appfrontpayment/manager',
                                    ],
                                    
                                ],
                            ],
                            'apphtml5_config' => [
                                'label' => 'Apphtml5 Config',
                                'sort_order' => 600,
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'sort_order' => 900,
                                        'url_key' => '/config/apphtml5base/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'sort_order' => 800,
                                        'url_key' => '/config/apphtml5home/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'sort_order' => 750,
                                        'url_key' => '/config/apphtml5cache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store Config',
                                        'sort_order' => 700,
                                        'url_key' => '/config/apphtml5store/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'sort_order' => 600,
                                        'url_key' => '/config/apphtml5catalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'sort_order' => 550,
                                        'url_key' => '/config/apphtml5account/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'sort_order' => 500,
                                        'url_key' => '/config/apphtml5payment/manager',
                                    ],
                                ],
                            ],
                            'appserver_config' => [
                                'label' => 'Appserver Config',
                                'sort_order' => 500,
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'sort_order' => 900,
                                        'url_key' => '/config/appserverbase/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'sort_order' => 800,
                                        'url_key' => '/config/appserverhome/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'sort_order' => 750,
                                        'url_key' => '/config/appservercache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store Config',
                                        'sort_order' => 700,
                                        'url_key' => '/config/appserverstore/manager',
                                    ],
                                    'store_lang' => [
                                        'label' => 'Store Language Config',
                                        'sort_order' => 650,
                                        'url_key' => '/config/appserverstorelang/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'sort_order' => 600,
                                        'url_key' => '/config/appservercatalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'sort_order' => 550,
                                        'url_key' => '/config/appserveraccount/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'sort_order' => 500,
                                        'url_key' => '/config/appserverpayment/manager',
                                    ],
                                ],
                            ],
                        ],
                        
                    ],
                    
                    'extension' => [
                        'label' => 'Extension Center',
                        'sort_order' => 600,
                        'child' => [
                            'extension_manager' => [
                                'label' => 'Manage Extensions',
                                'sort_order' => 500,
                                'child' => [
                                    'extension_market' => [
                                        'sort_order' => 500,
                                        'label' => 'Extention Market',
                                        'url_key' => '/system/extensionmarket/manager',
                                    ],
                                    'extension_installed' => [
                                        'sort_order' => 400,
                                        'label' => 'Extension Installed',
                                        'url_key' => '/system/extensioninstalled/manager',
                                    ],
                                    
                                ],
                                
                            ], 
                            'extension_developer' => [
                                'label' => 'Developer Center',
                                'sort_order' => 400,
                                'child' => [
                                    'extension_market' => [
                                        'sort_order' => 500,
                                        'label' => 'Extention Gii',
                                        'url_key' => '/system/extensiongii/manager',
                                    ],
                                    'admin_url_key' => [
                                        'sort_order' => 500,
                                        'label' => 'Admin Url Key Sql Gii',
                                        'url_key' => '/system/adminurlkey/manager',
                                    ],
                                ],
                            ],
                            
                        ],
                    ],
                    'dashboard' => [
                        'label' => 'Dashboard',
                        'sort_order' => 500,
                        'child' => [
                            'adminuser' => [
                                'label' => 'Admin User',
                                'sort_order' => 500,
                                'child' => [
                                    'myaccount' => [
                                        'sort_order' => 500,
                                        'label' => 'My Account',
                                        'url_key' => '/fecadmin/myaccount/index',
                                    ],
                                    'account_manager' => [
                                        'sort_order' => 450,
                                        'label' => 'Manage Account',
                                        'url_key' => '/fecadmin/account/manager',
                                    ],
                                    'role_manager' => [
                                        'sort_order' => 400,
                                        'label' => 'Manage Role',
                                        'url_key' => '/fecadmin/role/manager',
                                    ],
                                    'resource_manager' => [
                                        'sort_order' => 350,
                                        'label' => 'Manage Resource',
                                        'url_key' => '/fecadmin/resource/manager',
                                    ],
                                ],
                            ],
                            'dashboard' => [
                                'label' => 'Dashboard',
                                'sort_order' => 400,
                                'child' => [
                                    'log' => [
                                        'sort_order' => 500,
                                        'label' => 'Log Info',
                                        'url_key' => '/fecadmin/log/index',
                                    ],
                                    'logtj' => [
                                        'sort_order' => 450,
                                        'label' => 'Log Statistics',
                                        'url_key' => '/fecadmin/logtj/index',
                                    ],
                                    'cache' => [
                                        'sort_order' => 400,
                                        'label' => 'Manage Cache',
                                        'url_key' => '/fecadmin/cache/index',
                                    ],
                                    'config' => [
                                        'sort_order' => 350,
                                        'label' => 'Admin Config',
                                        'url_key' => '/fecadmin/config/manager',
                                    ],
                                    'error_handler' => [
                                        'sort_order' => 300,
                                        'label' => 'Error Handler',
                                        'url_key' => '/system/error/index',
                                    ],
                                ],
                            ],
                            //'menu' => [
                            //    'label' => '菜单管理',
                            //    'url_key' => '/fecadmin/menu/manager',
                            //],
                            
                        ],
                    ],
                ],
            ],
        ],
    ],
];