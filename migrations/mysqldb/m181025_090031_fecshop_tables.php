<?php

use yii\db\Migration;

/**
 * Class m181025_090031_fecshop_tables
 */
class m181025_090031_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            DROP TABLE `admin_menu` 
            ",

            "
            DROP TABLE `admin_role_menu` 
            ",

            "
            CREATE TABLE IF NOT EXISTS `admin_url_key` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(150) DEFAULT NULL COMMENT 'url key 的名称',
              `tag` varchar(40) NOT NULL COMMENT 'tag名称，在同一个菜单里面的url_key可以设置成同一个Tag',
              `tag_sort_order` int(11) DEFAULT '0',
              `url_key` varchar(255) NOT NULL COMMENT '资源，可以是url_key, 也可以是某个字符串标示',
              `created_at` int(11) DEFAULT NULL,
              `updated_at` int(11) DEFAULT NULL,
              `can_delete` int(5) DEFAULT '2' COMMENT '是否可以被删除，1代表不可以删除，2代表可以删除',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;
            ",

            "
                INSERT INTO `admin_url_key` (`id`, `name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES
                (1, '账户列表', 'dashboard_user_account_manager', 2, '/fecadmin/account/manager', 1511948105, 1540730009, 1),
                (2, '主页面', 'dashboard_main', 1, '/fecadmin/index/index', 1540564301, 1540729790, 1),
                (3, '我的账户', 'dashboard_user_myaccount', 1, '/fecadmin/myaccount/index', 1540729847, 1540729847, 1),
                (4, '账户编辑', 'dashboard_user_account_manager', 4, '/fecadmin/account/manageredit', 1540730156, 1540730156, 1),
                (5, '账户保存', 'dashboard_user_account_manager', 5, '/fecadmin/account/managereditsave', 1540730213, 1540730213, 1),
                (6, '账户删除', 'dashboard_user_account_manager', 6, '/fecadmin/account/managerdelete', 1540730253, 1540730253, 1),
                (7, '权限组列表', 'dashboard_user_role', 1, '/fecadmin/role/manager', 1540730369, 1540730429, 1),
                (8, '权限组编辑', 'dashboard_user_role', 3, '/fecadmin/role/manageredit', 1540730420, 1540730437, 1),
                (9, '权限组保存', 'dashboard_user_role', 4, '/fecadmin/role/managereditsave', 1540730497, 1540730497, 1),
                (10, '权限组删除', 'dashboard_user_role', 5, '/fecadmin/role/managerdelete', 1540730559, 1540730567, 1),
                (11, '资源列表', 'dashboard_user_resource', 1, '/fecadmin/resource/manager', 1540730606, 1540730606, 1),
                (12, '资源编辑', 'dashboard_user_resource', 2, '/fecadmin/resource/manageredit', 1540730631, 1540730631, 1),
                (13, '资源保存', 'dashboard_user_resource', 3, '/fecadmin/resource/managereditsave', 1540730665, 1540730665, 1),
                (14, '资源删除', 'dashboard_user_resource', 5, '/fecadmin/resource/managerdelete', 1540730690, 1540730690, 1),
                (15, '日志列表', 'dashboard_log_info', 1, '/fecadmin/log/index', 1540730748, 1540730748, 1),
                (16, '日志统计列表', 'dashboard_log_manager', 1, '/fecadmin/logtj/index', 1540730845, 1540730845, 1),
                (17, '缓存列表', 'dashboard_cache', 1, '/fecadmin/cache/index', 1540730881, 1540730881, 1),
                (18, '后台配置列表', 'dashboard_config', 1, '/fecadmin/config/manager', 1540730947, 1540730947, 1),
                (19, '后台配置编辑', 'dashboard_config', 2, '/fecadmin/config/manageredit', 1540730992, 1540730992, 1),
                (20, '后台配置保存', 'dashboard_config', 3, '/fecadmin/config/managereditsave', 1540731044, 1540731044, 1),
                (21, '后台配置删除', 'dashboard_config', 4, '/fecadmin/config/managerdelete', 1540731069, 1540731069, 1),
                (22, '列表查看', 'dashboard_error_handler', 1, '/system/error/index', 1540731108, 1540731108, 1),
                (23, '详细信息', 'dashboard_error_handler', 2, '/system/error/manageredit', 1540731131, 1540731131, 1),
                (24, '产品列表查看', 'catalog_product_info_manager', 1, '/catalog/productinfo/index', 1540731188, 1540731188, 1),
                (25, '产品信息编辑', 'catalog_product_info_manager', 1, '/catalog/productinfo/manageredit', 1540731229, 1540731394, 1),
                (26, '获取产品分类', 'catalog_product_info_manager', 2, '/catalog/productinfo/getproductcategory', 1540731325, 1540731398, 1),
                (27, '上传产品图片', 'catalog_product_info_manager', 4, '/catalog/productinfo/imageupload', 1540731388, 1540731388, 1),
                (28, '产品信息保存', 'catalog_product_info_manager', 5, '/catalog/productinfo/managereditsave', 1540731483, 1540731483, 1),
                (29, '产品信息删除', 'catalog_product_info_manager', 6, '/catalog/productinfo/managerdelete', 1540731557, 1540731557, 1),
                (30, '产品评论列表', 'catalog_product_review_manager', 1, '/catalog/productreview/index', 1540731609, 1540731609, 1),
                (31, '产品评论编辑', 'catalog_product_review_manager', 2, '/catalog/productreview/manageredit', 1540731636, 1540731636, 1),
                (32, '产品评论审核通过', 'catalog_product_review_manager', 3, '/catalog/productreview/manageraudit', 1540731703, 1540731745, 1),
                (33, '产品评论审核拒绝', 'catalog_product_review_manager', 4, '/catalog/productreview/managerauditrejected', 1540731737, 1540731737, 1),
                (34, '产品评论删除', 'catalog_product_review_manager', 5, '/catalog/productreview/managerdelete', 1540731786, 1540731786, 1),
                (35, '产品搜索管理', 'catalog_product_search_manager', 1, '/catalog/productsearch/index', 1540731820, 1540731820, 1),
                (36, '产品收藏列表', 'catalog_product_favorite_manager', 1, '/catalog/productfavorite/index', 1540731876, 1540731876, 1),
                (37, '查看分类', 'catalog_category_manager', 1, '/catalog/category/index', 1540731908, 1540732017, 1),
                (38, '分类查看产品列表', 'catalog_category_manager', 2, '/catalog/category/product', 1540731955, 1540731955, 1),
                (39, 'URL重写列表', 'catalog_url_rewrite_manager', 1, '/catalog/urlrewrite/index', 1540732107, 1540732107, 1),
                (40, '订单列表', 'sales_order_manager', 1, '/sales/orderinfo/manager', 1540732241, 1540732241, 1),
                (41, '查看订单详细', 'sales_order_manager', 2, '/sales/orderinfo/manageredit', 1540732270, 1540732270, 1),
                (42, '订单导出', 'sales_order_manager', 3, '/sales/orderinfo/managerexport', 1540732340, 1540732340, 1),
                (43, '订单保存', 'sales_order_manager', 4, '/sales/orderinfo/managereditsave', 1540732381, 1540732381, 1),
                (44, '优惠券列表', 'sales_coupon_manager', 1, '/sales/coupon/manager', 1540732426, 1540732426, 1),
                (45, '优惠券编辑', 'sales_coupon_manager', 3, '/sales/coupon/manageredit', 1540732455, 1540732455, 1),
                (46, '优惠券保存', 'sales_coupon_manager', 4, '/sales/coupon/managereditsave', 1540732491, 1540732491, 1),
                (47, '优惠券删除', 'sales_coupon_manager', 6, '/sales/coupon/managerdelete', 1540732525, 1540732525, 1),
                (48, '账户列表', 'customer_account', 1, '/customer/account/index', 1540732560, 1540732560, 1),
                (49, '账户编辑', 'customer_account', 3, '/customer/account/manageredit', 1540732594, 1540732594, 1),
                (50, '账户保存', 'customer_account', 4, '/customer/account/managereditsave', 1540732633, 1540732633, 1),
                (51, '账户删除', 'customer_account', 6, '/customer/account/managerdelete', 1540732738, 1540732738, 1),
                (52, 'Newsletter列表', 'customer_newsletter', 1, '/customer/newsletter/index', 1540732770, 1540732770, 1),
                (53, 'page列表', 'cms_page', 1, '/cms/article/index', 1540732806, 1540732806, 1),
                (54, 'Page编辑', 'cms_page', 2, '/cms/article/manageredit', 1540732834, 1540732834, 1),
                (55, 'Page保存', 'cms_page', 4, '/cms/article/managereditsave', 1540732868, 1540732868, 1),
                (56, 'Page删除', 'cms_page', 6, '/cms/article/managerdelete', 1540732923, 1540732923, 1),
                (57, '静态块列表', 'cms_static_block', 1, '/cms/staticblock/index', 1540732964, 1540732964, 1),
                (58, '静态块编辑', 'cms_static_block', 2, '/cms/staticblock/manageredit', 1540732986, 1540732986, 1),
                (59, '静态块保存', 'cms_static_block', 4, '/cms/staticblock/managereditsave', 1540733012, 1540733012, 1),
                (60, '静态块删除', 'cms_static_block', 6, '/cms/staticblock/managerdelete', 1540733034, 1540733034, 1),
                (61, '分类删除', 'catalog_category_manager', 6, 'catalog/category/remove', 1540782902, 1541060426, 1),
                (62, '保存分类', 'catalog_category_manager', 4, 'catalog/category/save', 1540782923, 1540782923, 1),
                (63, '查看所有产品（默认仅可以查看自己创建的产品）', 'catalog_product_info_manager', 8, 'catalog_product_view_all', 1541061023, 1541065009, 1),
                (64, '编辑查看所有产品（默认仅可以编辑自己创建的产品）', 'catalog_product_info_manager', 9, 'catalog_product_edit_all', 1541064880, 1541124984, 1),
                (66, '删除所有产品（默认仅可以删除自己创建的产品）', 'catalog_product_info_manager', 15, 'catalog_product_remove_all', 1541064963, 1541125092, 1),
                (70, '保存所有产品（默认仅可以保存自己创建的产品）', 'catalog_product_info_manager', 10, 'catalog_product_save_all', 1541125009, 1541125031, 1);

            ",

            "
            CREATE TABLE IF NOT EXISTS `admin_role_url_key` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `role_id` int(11) NOT NULL,
              `url_key_id` int(11) NOT NULL,
              `created_at` int(11) DEFAULT NULL,
              `updated_at` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2029 ;
            ",

            "
            INSERT INTO `admin_role_url_key` (`id`, `role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES
            (178, 13, 2, 1540989251, 1540989251),
            (179, 13, 24, 1540989251, 1540989251),
            (180, 13, 25, 1540989251, 1540989251),
            (181, 13, 26, 1540989251, 1540989251),
            (182, 13, 27, 1540989251, 1540989251),
            (183, 13, 28, 1540989251, 1540989251),
            (184, 13, 29, 1540989251, 1540989251),
            (185, 13, 30, 1540989251, 1540989251),
            (186, 13, 31, 1540989251, 1540989251),
            (187, 13, 32, 1540989251, 1540989251),
            (188, 13, 33, 1540989251, 1540989251),
            (189, 13, 34, 1540989251, 1540989251),
            (190, 13, 35, 1540989251, 1540989251),
            (191, 13, 36, 1540989251, 1540989251),
            (192, 13, 37, 1540989251, 1540989251),
            (193, 13, 38, 1540989251, 1540989251),
            (194, 13, 61, 1540989251, 1540989251),
            (195, 13, 62, 1540989251, 1540989251),
            (196, 13, 39, 1540989251, 1540989251),
            (197, 13, 40, 1540989251, 1540989251),
            (198, 13, 41, 1540989251, 1540989251),
            (199, 13, 42, 1540989251, 1540989251),
            (200, 13, 43, 1540989251, 1540989251),
            (201, 13, 47, 1540989251, 1540989251),
            (264, 12, 2, 1540989358, 1540989358),
            (265, 12, 3, 1540989358, 1540989358),
            (266, 12, 1, 1540989358, 1540989358),
            (267, 12, 4, 1540989358, 1540989358),
            (268, 12, 5, 1540989358, 1540989358),
            (269, 12, 6, 1540989358, 1540989358),
            (270, 12, 7, 1540989358, 1540989358),
            (271, 12, 8, 1540989358, 1540989358),
            (272, 12, 9, 1540989358, 1540989358),
            (273, 12, 10, 1540989358, 1540989358),
            (1963, 4, 2, 1541129239, 1541129239),
            (1964, 4, 24, 1541129239, 1541129239),
            (1965, 4, 25, 1541129239, 1541129239),
            (1966, 4, 26, 1541129239, 1541129239),
            (1967, 4, 27, 1541129239, 1541129239),
            (1968, 4, 28, 1541129239, 1541129239),
            (1969, 4, 29, 1541129239, 1541129239),
            (1970, 4, 63, 1541129239, 1541129239),
            (1971, 4, 64, 1541129239, 1541129239),
            (1972, 4, 70, 1541129239, 1541129239),
            (1973, 4, 66, 1541129239, 1541129239),
            (1974, 4, 30, 1541129239, 1541129239),
            (1975, 4, 31, 1541129239, 1541129239),
            (1976, 4, 32, 1541129239, 1541129239),
            (1977, 4, 33, 1541129239, 1541129239),
            (1978, 4, 34, 1541129239, 1541129239),
            (1979, 4, 35, 1541129239, 1541129239),
            (1980, 4, 36, 1541129239, 1541129239),
            (1981, 4, 37, 1541129239, 1541129239),
            (1982, 4, 38, 1541129239, 1541129239),
            (1983, 4, 62, 1541129239, 1541129239),
            (1984, 4, 61, 1541129239, 1541129239),
            (1985, 4, 39, 1541129239, 1541129239),
            (1986, 4, 40, 1541129239, 1541129239),
            (1987, 4, 41, 1541129239, 1541129239),
            (1988, 4, 42, 1541129239, 1541129239),
            (1989, 4, 43, 1541129239, 1541129239),
            (1990, 4, 44, 1541129239, 1541129239),
            (1991, 4, 45, 1541129239, 1541129239),
            (1992, 4, 46, 1541129239, 1541129239),
            (1993, 4, 47, 1541129239, 1541129239),
            (1994, 4, 48, 1541129239, 1541129239),
            (1995, 4, 49, 1541129239, 1541129239),
            (1996, 4, 50, 1541129239, 1541129239),
            (1997, 4, 51, 1541129239, 1541129239),
            (1998, 4, 52, 1541129239, 1541129239),
            (1999, 4, 53, 1541129239, 1541129239),
            (2000, 4, 54, 1541129239, 1541129239),
            (2001, 4, 55, 1541129239, 1541129239),
            (2002, 4, 56, 1541129239, 1541129239),
            (2003, 4, 57, 1541129239, 1541129239),
            (2004, 4, 58, 1541129239, 1541129239),
            (2005, 4, 59, 1541129239, 1541129239),
            (2006, 4, 60, 1541129239, 1541129239),
            (2007, 4, 3, 1541129239, 1541129239),
            (2008, 4, 1, 1541129239, 1541129239),
            (2009, 4, 4, 1541129239, 1541129239),
            (2010, 4, 5, 1541129239, 1541129239),
            (2011, 4, 6, 1541129239, 1541129239),
            (2012, 4, 7, 1541129239, 1541129239),
            (2013, 4, 8, 1541129239, 1541129239),
            (2014, 4, 9, 1541129239, 1541129239),
            (2015, 4, 10, 1541129239, 1541129239),
            (2016, 4, 11, 1541129239, 1541129239),
            (2017, 4, 12, 1541129239, 1541129239),
            (2018, 4, 13, 1541129239, 1541129239),
            (2019, 4, 14, 1541129239, 1541129239),
            (2020, 4, 15, 1541129239, 1541129239),
            (2021, 4, 16, 1541129239, 1541129239),
            (2022, 4, 17, 1541129239, 1541129239),
            (2023, 4, 18, 1541129239, 1541129239),
            (2024, 4, 19, 1541129239, 1541129239),
            (2025, 4, 20, 1541129239, 1541129239),
            (2026, 4, 21, 1541129239, 1541129239),
            (2027, 4, 22, 1541129239, 1541129239),
            (2028, 4, 23, 1541129239, 1541129239);
            ",
        ];

        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181025_090031_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181025_090031_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
