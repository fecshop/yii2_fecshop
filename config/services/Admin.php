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
					'catalog_product_review_manager' 		=> 'Catalog-Product-Review',
					'catalog_product_search_manager' 		=> 'Catalog-Product-Search',
					'catalog_product_favorite_manager' 	=> 'Catalog-Product-Favorite',
					'catalog_category_manager' 				=> 'Catalog-Category',
					'catalog_url_rewrite_manager' 			=> 'Catalog-Url-Rewrite',
                    'sales_order_manager' 						=> 'Sales-Order',
					'sales_coupon_manager' 					    => 'Sales-Coupon',
                    'customer_account' 							=> 'Customer-Account',
					'customer_newsletter' 						=> 'Customer-Newsletter',
                    'cms_page' 										=> 'CMS-Page',
					'cms_static_block' 								=> 'CMS-StaticBlock',
					'dashboard_user_myaccount' 				=> 'Dashboard-User-MyAccount',
					'dashboard_user_account_manager' 	=> 'Dashboard-User-Account',
					'dashboard_user_role' 						=> 'Dashboard-User-Role',
					'dashboard_user_resource' 				    => 'Dashboard-User-Resource',
					'dashboard_log_info' 							=> 'Dashboard-Log-Info',
					'dashboard_log_manager' 					=> 'Dashboard-Log',
					'dashboard_cache' 							    => 'Dashboard-Cache',
					'dashboard_config' 							    => 'Dashboard-Config',
					'dashboard_error_handler'					=> 'Dashboard-ErrorHandler',

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
                                    // 三级类
                                    'product_review_manager' => [
                                        'label' => 'Product Reveiew',
                                        'url_key' => '/catalog/productreview/index',
                                    ],
                                    
                                    'product_favorite_manager' => [
                                        'label' => 'Product Favorite',
                                        'url_key' => '/catalog/productfavorite/index',
                                    ],
                                ]
                            ],
                            'category_manager' => [
                                'label' => 'Manager Category',
                                'url_key' => '/catalog/category/index',
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
                                ],
                            ],
                            'coupon' => [
                                'label' => 'Coupon',
                                'url_key' => '/sales/coupon/manager',
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