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
                        'sort_order' => 1, // 菜单排序，如果不设置 sort_order， 默认值为0，倒序排列，该值越大，越排在前面
                        'child' => [
                            // 二级类
                            'product_manager' => [
                                'label' => 'Manage Product',
                                'child' => [
                                    // 三级类
                                    'product_info_manager' => [
                                        'label' => 'Product Info',
                                        'url_key' => '/catalog/productinfo/index',
                                    ],
                                    'product_attr_manager' => [
                                        'label' => 'Product Attribute',
                                        'url_key' => '/catalog/productattr/manager',
                                    ],
                                    'product_attr_group_manager' => [
                                        'label' => 'Product Attribute Group',
                                        'url_key' => '/catalog/productattrgroup/manager',
                                    ],
                                    'product_param_manager' => [
                                        'label' => 'Product Param Config',
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
                                        'label' => 'Product Excel Upload',
                                        'url_key' => '/catalog/productupload/manager',
                                    ],
                                    
                                    'product_brand_manager' => [
                                        'label' => 'Product Brand',
                                        'url_key' => '/catalog/productbrand/manager',
                                    ],
                                    
                                    'product_randcategory_manager' => [
                                        'label' => 'Product Brand Category',
                                        'url_key' => '/catalog/productbrandcategory/manager',
                                    ],
                                ]
                            ],
                            'category_manager' => [
                                'label' => 'Manage Category',
                                'child' => [
                                    // 三级类
                                    'category_info_manager' => [
                                        'label' => 'Category Info',
                                        'url_key' => '/catalog/category/index',
                                    ],
                                    'category_sort_manager' => [
                                        'label' => 'Category Sort Config',
                                        'url_key' => '/config/categorysort/manager',
                                    ],
                                    'category_upload_manager' => [
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
                                'url_key' => '/catalog/urlrewrite/index',
                            ],
                        ]
                    ],
                    'extension' => [
                        'label' => 'Extension Center',
                        'child' => [
                            'extension_manager' => [
                                'label' => 'Manage Extensions',
                                'child' => [
                                    'extension_market' => [
                                        'label' => 'Extention Market',
                                        'url_key' => '/system/extensionmarket/manager',
                                    ],
                                    'extension_installed' => [
                                        'label' => 'Extension Installed',
                                        'url_key' => '/system/extensioninstalled/manager',
                                    ],
                                    
                                ],
                                
                            ], 
                            'extension_developer' => [
                                'label' => 'Developer Center',
                                'child' => [
                                    'extension_market' => [
                                        'label' => 'Extention Gii',
                                        'url_key' => '/system/extensiongii/manager',
                                    ],
                                ],
                            ],
                            
                        ],
                    ],
                    'sales' => [
                        'label' => 'Mall Manage',
                        'child' => [
                            'order' => [
                                'label' => 'Order',
                                'child' => [
                                    'order_manager' => [
                                        'label' => 'Manage Order',
                                        'url_key' => '/sales/orderinfo/manager',
                                    ],
                                    'order_config' => [
                                        'label' => 'Order Param Config',
                                        'url_key' => '/config/order/manager',
                                    ],
                                ],
                            ],
                            'cart' => [
                                'label' => 'Cart Param Config',
                                'url_key' => '/config/cart/manager',
                            ], 
                            'coupon' => [
                                'label' => 'Coupon',
                                'url_key' => '/sales/coupon/manager',
                            ],
                            'customer' => [
                                'label' => 'Customer',
                                'child' => [
                                    'account' => [
                                        'label' => 'Manage Account',
                                        'url_key' => '/customer/account/index',
                                    ],
                                    'contacts' => [
                                        'label' => 'Customer Contacts',
                                        'url_key' => '/customer/contacts/index',
                                    ],
                                ],
                            ],
                            'newsletter' => [
                                'label' => 'NewsLetter',
                                'url_key' => '/customer/newsletter/index',
                            ],
                        ],
                    ],
                    'config' => [
                        'label' => 'Website Config',
                        'child' => [
                            'services' => [
                                'label' => 'Base Config',
                                'child' => [
                                    'base_manager' => [
                                        'label' => 'Base Config',
                                        'url_key' => '/config/base/manager',
                                    ],
                                    'service_manager' => [
                                        'label' => 'Service Db Config',
                                        'url_key' => '/config/service/db',
                                    ],
                                    'search' => [
                                        'label' => 'Search Engine Config',
                                        'url_key' => '/config/search/manager',
                                    ],
                                    'mutillang' => [
                                        'label' => 'Mutil-Lang Config',
                                        'url_key' => '/config/mutillang/manager',
                                    ],
                                    'currency' => [
                                        'label' => 'Currency Config',
                                        'url_key' => '/config/currency/manager',
                                    ],
                                    'email_manager' => [
                                        'label' => 'Email Config',
                                        'url_key' => '/config/email/manager',
                                    ],
                                    
                                    'fa' => [
                                        'label' => 'FA Config',
                                        'url_key' => '/config/fa/manager',
                                    ],
                                    
                                ],
                            ],
                            
                            'payment_config' => [
                                'label' => 'Payment Param Config',
                                'child' => [
                                    'payment_paypal' => [
                                        'label' => 'Paypal Config',
                                        'url_key' => '/config/paymentpaypal/manager',
                                    ],
                                    'payment_alipay' => [
                                        'label' => 'Alipay Config',
                                        'url_key' => '/config/paymentalipay/manager',
                                    ],
                                    'payment_wxpay' => [
                                        'label' => 'Wxpay Config',
                                        'url_key' => '/config/paymentwxpay/manager',
                                    ],
                                ],
                            ],
                            
                            'appfront_config' => [
                                'label' => 'Appfront Config',
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'url_key' => '/config/appfrontbase/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'url_key' => '/config/appfronthome/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'url_key' => '/config/appfrontcache/manager',
                                    ],
                                    'store' => [
                                        'label' => 'Store Config',
                                        'url_key' => '/config/appfrontstore/manager',
                                    ],
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'url_key' => '/config/appfrontcatalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'url_key' => '/config/appfrontaccount/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'url_key' => '/config/appfrontpayment/manager',
                                    ],
                                    
                                ],
                            ],
                            'apphtml5_config' => [
                                'label' => 'Apphtml5 Config',
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'url_key' => '/config/apphtml5base/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'url_key' => '/config/apphtml5home/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'url_key' => '/config/apphtml5cache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store Config',
                                        'url_key' => '/config/apphtml5store/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'url_key' => '/config/apphtml5catalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'url_key' => '/config/apphtml5account/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'url_key' => '/config/apphtml5payment/manager',
                                    ],
                                ],
                            ],
                            'appserver_config' => [
                                'label' => 'Appserver Config',
                                'child' => [
                                    'base' => [
                                        'label' => 'Base Config',
                                        'url_key' => '/config/appserverbase/manager',
                                    ],
                                    'home' => [
                                        'label' => 'Home Page Config',
                                        'url_key' => '/config/appserverhome/manager',
                                    ],
                                    'cache' => [
                                        'label' => 'Cache Config',
                                        'url_key' => '/config/appservercache/manager',
                                    ], 
                                    'store' => [
                                        'label' => 'Store Config',
                                        'url_key' => '/config/appserverstore/manager',
                                    ],
                                    'store_lang' => [
                                        'label' => 'Store Language Config',
                                        'url_key' => '/config/appserverstorelang/manager',
                                    ],
                                    
                                    'catalog' => [
                                        'label' => 'Catalog Config',
                                        'url_key' => '/config/appservercatalog/manager',
                                    ],
                                    'account' => [
                                        'label' => 'Customer Account Config',
                                        'url_key' => '/config/appserveraccount/manager',
                                    ],
                                    'payment' => [
                                        'label' => 'Payment Config',
                                        'url_key' => '/config/appserverpayment/manager',
                                    ],
                                ],
                            ],
                        ],
                        
                    ],
                    
                    'cms' => [
                        'label' => 'CMS',
                        'child' => [
                            'page' => [
                                'label' => 'Manage Page',
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
                                        'label' => 'Manage Account',
                                        'url_key' => '/fecadmin/account/manager',
                                    ],
                                    'role_manager' => [
                                        'label' => 'Manage Role',
                                        'url_key' => '/fecadmin/role/manager',
                                    ],
                                    'resource_manager' => [
                                        'label' => 'Manage Resource',
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
                                'label' => 'Manage Cache',
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