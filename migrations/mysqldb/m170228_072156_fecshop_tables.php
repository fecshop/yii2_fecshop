<?php

use yii\db\Migration;

class m170228_072156_fecshop_tables extends Migration
{
    public function safeUp()
    {
		$arr = [
			"CREATE TABLE IF NOT EXISTS `admin_config` (
			  `id` int(20) NOT NULL AUTO_INCREMENT,
			  `label` varchar(150) DEFAULT NULL,
			  `key` varchar(255) DEFAULT NULL,
			  `value` varchar(2555) DEFAULT NULL,
			  `description` varchar(255) DEFAULT NULL,
			  `created_at` datetime DEFAULT NULL,
			  `updated_at` datetime DEFAULT NULL,
			  `created_person` varchar(150) DEFAULT NULL,
			  KEY `key` (`key`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;
			","
			INSERT INTO `admin_config` (`id`, `label`, `key`, `value`, `description`, `created_at`, `updated_at`, `created_person`) VALUES
			(10, '11', '111', '111', '111', '2016-10-07 15:42:01', '2016-10-07 15:42:01', 'admin'),
			(11, '11', '11', '11', '11', '2016-10-07 15:42:14', '2016-10-07 15:42:14', 'admin');
			","
			CREATE TABLE IF NOT EXISTS `admin_menu` (
			  `id` int(15) NOT NULL AUTO_INCREMENT,
			  `name` varchar(150) DEFAULT NULL,
			  `level` int(5) DEFAULT NULL,
			  `parent_id` int(15) DEFAULT NULL,
			  `url_key` varchar(255) DEFAULT NULL,
			  `role_key` varchar(150) DEFAULT NULL COMMENT '权限key，也就是controller的路径，譬如/fecadmin/menu/managere,controller 是MenuController，当前的值为：/fecadmin/menu',
			  `created_at` datetime DEFAULT NULL,
			  `updated_at` datetime DEFAULT NULL,
			  `sort_order` int(10) NOT NULL DEFAULT '0',
			  `can_delete` int(5) DEFAULT '2' COMMENT '是否可以被删除，1代表不可以删除，2代表可以删除',
			  KEY `url_key` (`url_key`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=199 ;
			","
			INSERT INTO `admin_menu` (`id`, `name`, `level`, `parent_id`, `url_key`, `role_key`, `created_at`, `updated_at`, `sort_order`, `can_delete`) VALUES
			(164, '控制面板', 1, 0, '/ddd', NULL, '2016-01-15 10:21:36', '2016-01-15 10:21:36', 0, 1),
			(165, '用户管理', 2, 164, '/ddd', '', '2016-01-15 10:23:01', '2016-07-29 17:33:50', 1111, 1),
			(166, '菜单管理', 2, 164, '/fecadmin/menu/manager', '/fecadmin/menu', '2016-01-15 10:23:22', '2016-07-29 17:33:59', 1100, 1),
			(167, '我的账户', 3, 165, '/fecadmin/myaccount/index', '/fecadmin/myaccount', '2016-01-15 10:24:29', '2016-01-16 16:07:58', 0, 1),
			(168, '账户管理', 3, 165, '/fecadmin/account/manager', '/fecadmin/account', '2016-01-15 10:24:51', '2016-01-21 15:24:18', 0, 1),
			(169, '权限管理', 3, 165, '/fecadmin/role/manager', '/fecadmin/role', '2016-01-15 10:25:10', '2016-01-21 13:22:39', 0, 1),
			(170, '操作日志', 2, 164, '/fecadmin/log/index', '/fecadmin/log', '2016-01-15 10:35:19', '2016-07-29 17:34:05', 999, 1),
			(171, '缓存管理', 2, 164, '/fecadmin/cache/index', '/fecadmin/cache', '2016-01-15 10:35:40', '2016-01-16 16:45:14', 0, 1),
			(177, 'CMS', 1, 0, '/x/x/x', '/x/x', '2016-07-11 21:16:56', '2016-07-16 09:32:30', 5, 2),
			(178, '文章-Article', 2, 177, '/cms/article/index', '/cms/article', '2016-07-11 21:17:17', '2016-08-08 11:31:26', 0, 2),
			(179, 'Catalog', 1, 0, '/x/x/x', '/x/x', '2016-07-22 16:01:37', '2016-07-22 16:01:44', 100, 2),
			(180, '产品管理', 2, 179, '/catalog/product/index', '/catalog/product', '2016-07-22 16:02:01', '2016-07-22 16:07:03', 100, 2),
			(181, 'Url 重写管理', 2, 179, '/catalog/urlrewrite/index', '/catalog/urlrewrite', '2016-07-22 16:02:49', '2016-07-22 16:10:14', 0, 2),
			(182, '分类管理', 2, 179, '/catalog/category/index', '/catalog/category', '2016-07-22 16:03:05', '2016-07-22 16:07:11', 90, 2),
			(183, '后台配置', 2, 164, '/fecadmin/config/manager', '/fecadmin/config', '2016-07-22 16:05:31', '2016-07-22 16:05:31', 0, 2),
			(184, 'LOG打印输出', 2, 164, '/fecadmin/systemlog/manager', '/fecadmin/systemlog', '2016-07-22 16:05:56', '2016-07-22 16:05:56', 0, 2),
			(185, '产品信息管理', 3, 180, '/catalog/productinfo/index', '/catalog/productinfo', '2016-07-22 16:08:17', '2016-07-22 16:08:17', 0, 2),
			(186, '产品评论管理', 3, 180, '/catalog/productreview/index', '/catalog/productreview', '2016-07-22 16:08:35', '2016-07-22 16:08:35', 0, 2),
			(187, '产品搜索管理', 3, 180, '/catalog/productsearch/index', '/catalog/productsearch', '2016-07-22 16:09:41', '2016-07-22 16:09:41', 0, 2),
			(189, '日志统计', 2, 164, '/fecadmin/logtj/index', '/fecadmin/logtj', '2016-07-29 17:33:34', '2016-07-29 17:33:44', 11, 2),
			(190, '静态块-StaticBlock', 2, 177, '/cms/staticblock/index', '/cms/staticblock', '2016-08-08 11:31:58', '2016-08-08 11:31:58', 0, 2),
			(191, '用户管理', 1, 0, '/x/x/x', '/x/x', '2016-11-01 09:26:56', '2016-11-01 09:27:05', 10, 2),
			(192, '账号管理', 2, 191, '/customer/account/index', '/customer/account', '2016-11-01 09:27:33', '2016-11-01 09:27:33', 0, 2),
			(193, '产品收藏管理', 3, 180, '/catalog/productfavorite/index', '/catalog/productfavorite', '2016-11-01 09:31:03', '2016-11-01 09:31:03', 0, 2),
			(195, '销售', 1, 0, '/x/x/x', '/x/x', '2016-12-12 20:52:03', '2016-12-12 20:52:33', 14, 2),
			(196, '优惠券', 2, 195, '/sales/coupon/manager', '/sales/coupon', '2016-12-12 20:53:02', '2016-12-12 21:43:09', 0, 2),
			(197, '订单', 2, 195, '/x/x/x', '/x/x', '2016-12-12 20:53:41', '2016-12-12 20:54:07', 9999, 2),
			(198, '订单管理', 3, 197, '/sales/orderinfo/manager', '/sales/orderinfo', '2016-12-12 20:54:01', '2016-12-12 21:43:18', 0, 2);
			","
			CREATE TABLE IF NOT EXISTS `admin_role` (
			  `role_id` int(15) NOT NULL AUTO_INCREMENT,
			  `role_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '权限名字',
			  `role_description` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '权限描述',
			  PRIMARY KEY (`role_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;
			","
			INSERT INTO `admin_role` (`role_id`, `role_name`, `role_description`) VALUES
			(4, 'admin', '超级用户'),
			(12, '账户管理员', '账户管理员'),
			(13, 'ceshi', 'ceshi');
			","
			CREATE TABLE IF NOT EXISTS `admin_role_menu` (
			  `id` int(15) NOT NULL AUTO_INCREMENT,
			  `menu_id` int(15) NOT NULL,
			  `role_id` int(15) NOT NULL,
			  `created_at` datetime DEFAULT NULL,
			  `updated_at` datetime DEFAULT NULL,
			  KEY `role_id` (`role_id`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124 ;
			","
			INSERT INTO `admin_role_menu` (`id`, `menu_id`, `role_id`, `created_at`, `updated_at`) VALUES
			(4, 164, 4, '2016-01-16 11:19:15', '2016-01-16 11:19:15'),
			(38, 165, 4, '2016-01-16 14:46:17', '2016-01-16 14:46:17'),
			(39, 167, 4, '2016-01-16 14:46:17', '2016-01-16 14:46:17'),
			(41, 169, 4, '2016-01-16 14:46:17', '2016-01-16 14:46:17'),
			(43, 171, 4, '2016-01-16 14:46:17', '2016-01-16 14:46:17'),
			(46, 166, 4, '2016-01-16 17:47:30', '2016-01-16 17:47:30'),
			(49, 168, 4, '2016-01-18 12:16:49', '2016-01-18 12:16:49'),
			(50, 170, 4, '2016-01-18 12:16:49', '2016-01-18 12:16:49'),
			(51, 164, 13, '2016-01-21 14:12:09', '2016-01-21 14:12:09'),
			(56, 166, 13, '2016-01-21 14:12:09', '2016-01-21 14:12:09'),
			(57, 170, 13, '2016-01-21 14:12:09', '2016-01-21 14:12:09'),
			(58, 171, 13, '2016-01-21 14:12:09', '2016-01-21 14:12:09'),
			(64, 177, 4, '2016-07-11 21:17:46', '2016-07-11 21:17:46'),
			(65, 178, 4, '2016-07-11 21:17:46', '2016-07-11 21:17:46'),
			(66, 179, 4, '2016-07-22 16:04:25', '2016-07-22 16:04:25'),
			(67, 180, 4, '2016-07-22 16:04:25', '2016-07-22 16:04:25'),
			(68, 182, 4, '2016-07-22 16:04:25', '2016-07-22 16:04:25'),
			(69, 181, 4, '2016-07-22 16:04:25', '2016-07-22 16:04:25'),
			(70, 183, 4, '2016-07-22 16:06:10', '2016-07-22 16:06:10'),
			(71, 184, 4, '2016-07-22 16:06:10', '2016-07-22 16:06:10'),
			(72, 185, 4, '2016-07-22 16:10:22', '2016-07-22 16:10:22'),
			(73, 186, 4, '2016-07-22 16:10:22', '2016-07-22 16:10:22'),
			(74, 187, 4, '2016-07-22 16:10:22', '2016-07-22 16:10:22'),
			(76, 189, 4, '2016-07-29 17:34:17', '2016-07-29 17:34:17'),
			(77, 190, 4, '2016-08-08 11:32:12', '2016-08-08 11:32:12'),
			(83, 185, 12, '2016-10-07 15:07:22', '2016-10-07 15:07:22'),
			(84, 186, 12, '2016-10-07 15:07:22', '2016-10-07 15:07:22'),
			(86, 180, 12, '2016-10-07 15:07:22', '2016-10-07 15:07:22'),
			(87, 179, 12, '2016-10-07 15:07:22', '2016-10-07 15:07:22'),
			(88, 187, 12, '2016-10-07 15:14:02', '2016-10-07 15:14:02'),
			(93, 164, 12, '2016-10-07 15:15:54', '2016-10-07 15:15:54'),
			(94, 168, 12, '2016-10-07 15:16:16', '2016-10-07 15:16:16'),
			(99, 193, 4, '2016-11-01 09:31:57', '2016-11-01 09:31:57'),
			(100, 191, 4, '2016-11-01 09:31:57', '2016-11-01 09:31:57'),
			(101, 192, 4, '2016-11-01 09:31:57', '2016-11-01 09:31:57'),
			(102, 193, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(103, 182, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(104, 181, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(105, 191, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(106, 192, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(107, 165, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(108, 167, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(109, 169, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(110, 166, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(111, 170, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(112, 189, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(113, 171, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(114, 183, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(115, 184, 12, '2016-11-03 16:29:00', '2016-11-03 16:29:00'),
			(120, 195, 4, '2016-12-12 21:35:22', '2016-12-12 21:35:22'),
			(121, 197, 4, '2016-12-12 21:35:22', '2016-12-12 21:35:22'),
			(122, 198, 4, '2016-12-12 21:35:22', '2016-12-12 21:35:22'),
			(123, 196, 4, '2016-12-12 21:35:22', '2016-12-12 21:35:22');
			","
			CREATE TABLE IF NOT EXISTS `admin_user` (
			  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
			  `password_hash` varchar(80) DEFAULT NULL COMMENT '密码',
			  `password_reset_token` varchar(60) DEFAULT NULL COMMENT '密码token',
			  `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
			  `person` varchar(100) DEFAULT NULL COMMENT '用户姓名',
			  `code` varchar(100) DEFAULT NULL,
			  `auth_key` varchar(60) DEFAULT NULL,
			  `status` int(5) DEFAULT NULL COMMENT '状态',
			  `created_at` int(18) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(18) DEFAULT NULL COMMENT '更新时间',
			  `password` varchar(50) DEFAULT NULL COMMENT '密码',
			  `access_token` varchar(60) DEFAULT NULL,
			  `allowance` int(20) DEFAULT NULL,
			  `allowance_updated_at` int(20) DEFAULT NULL,
			  `created_at_datetime` datetime DEFAULT NULL,
			  `updated_at_datetime` datetime DEFAULT NULL,
			  `birth_date` datetime DEFAULT NULL COMMENT '出生日期',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `username` (`username`),
			  UNIQUE KEY `access_token` (`access_token`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
			","
			INSERT INTO `admin_user` (`id`, `username`, `password_hash`, `password_reset_token`, `email`, `person`, `code`, `auth_key`, `status`, `created_at`, `updated_at`, `password`, `access_token`, `allowance`, `allowance_updated_at`, `created_at_datetime`, `updated_at_datetime`, `birth_date`) VALUES
			(1, 'terry', '".'$2y$13$EyK1HyJtv4A/19Jb8gB5y.4SQm5y93eMeHjUf35ryLyd2dWPJlh8y'."', NULL, 'zqy234@126.com', '', '3333', 'HH-ZlZXirlG-egyz8OTtl9EVj9fvKW00', 1, 1441763620, 1475825406, '', 'yrYWR7kY-A9bUAP6UUZgCR3yi3ALtUh-', 599, 1452491877, '2016-01-12 09:41:44', '2016-10-07 15:30:06', NULL),
			(2, 'admin', '".'$2y$13$T5ZFrLpJdTEkAoAdnC6A/u8lh/pG.6M0qAZBo1lKE.6x6o3V6yvVG'."', NULL, '3727@qq.com', '超级管理员', '111', '_PYjb4PdIIY332LquBRC5tClZUXV0zm_', 1, NULL, 1468917063, '', '1Gk6ZNn-QaBaKFI4uE2bSw0w3N7ej72q', NULL, NULL, '2016-01-11 09:41:52', '2016-06-26 01:40:55', NULL);
			","
			CREATE TABLE IF NOT EXISTS `admin_user_role` (
			  `id` int(20) NOT NULL AUTO_INCREMENT,
			  `user_id` int(30) NOT NULL,
			  `role_id` int(30) NOT NULL,
			  KEY `user_id` (`user_id`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
			","
			INSERT INTO `admin_user_role` (`id`, `user_id`, `role_id`) VALUES
			(1, 2, 4),
			(2, 2, 12),
			(7, 1, 12);
			","
			CREATE TABLE IF NOT EXISTS `admin_visit_log` (
			  `id` int(15) NOT NULL AUTO_INCREMENT,
			  `account` varchar(25) DEFAULT NULL COMMENT '操作账号',
			  `person` varchar(25) DEFAULT NULL COMMENT '操作人姓名',
			  `created_at` datetime DEFAULT NULL COMMENT '操作时间',
			  `menu` varchar(100) DEFAULT NULL COMMENT '菜单',
			  `url` text COMMENT 'URL',
			  `url_key` varchar(150) DEFAULT NULL COMMENT '参数',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			","

			CREATE TABLE IF NOT EXISTS `article` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `url_key` varchar(255) DEFAULT NULL COMMENT 'url的path部分，属于自定义url',
			  `title` text COMMENT '标题',
			  `meta_keywords` text COMMENT '关键字',
			  `meta_description` text,
			  `content` text,
			  `status` int(5) DEFAULT '1' COMMENT '1代表激活，2代表关闭',
			  `created_at` int(10) DEFAULT NULL,
			  `updated_at` int(10) DEFAULT NULL,
			  `created_user_id` int(20) DEFAULT NULL,
			  KEY `url_key` (`url_key`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;
			","
			INSERT INTO `article` (`id`, `url_key`, `title`, `meta_keywords`, `meta_description`, `content`, `status`, `created_at`, `updated_at`, `created_user_id`) VALUES
			(25, '/fdsafsd', 'a:7:{s:8:\"title_en\";s:5:\"22222\";s:8:\"title_fr\";s:0:\"\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:0:\"\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:0:\"\";}', 'a:7:{s:16:\"meta_keywords_en\";s:2:\"33\";s:16:\"meta_keywords_fr\";s:0:\"\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:4:\"4444\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:0:\"\";}', 'a:7:{s:19:\"meta_description_en\";s:17:\"<h3>32323233</h3>\";s:19:\"meta_description_fr\";s:5:\"44444\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:0:\"\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:0:\"\";}', 'a:7:{s:10:\"content_en\";s:0:\"\";s:10:\"content_fr\";s:0:\"\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:0:\"\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:0:\"\";}', 2, 1469161277, 1469173934, 2),
			(26, '/55555555555', 'a:7:{s:8:\"title_en\";s:15:\"222444444444444\";s:8:\"title_fr\";s:0:\"\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:0:\"\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:0:\"\";}', 'a:7:{s:16:\"meta_keywords_en\";s:3:\"222\";s:16:\"meta_keywords_fr\";s:4:\"3333\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:0:\"\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:0:\"\";}', 'a:7:{s:19:\"meta_description_en\";s:3:\"222\";s:19:\"meta_description_fr\";s:0:\"\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:0:\"\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:0:\"\";}', 'a:7:{s:10:\"content_en\";s:1712:\"<p>六.Yii2实战方面的文章:<a href=\"http://www.fancyecommerce.com/category/yii2-%e5%ae%9e%e6%88%98/\">Yii2 实战</a></p><table><tbody><tr><td><a href=\"http://www.fancyecommerce.com/2016/05/28/yii2-%E9%A1%B5%E9%9D%A2%E5%8A%9F%E8%83%BD%E5%9D%97%EF%BC%88%E5%89%8D%E7%AB%AF%E5%90%8E%E7%AB%AF%E6%8F%90%E4%BE%9B%E6%95%B0%E6%8D%AE%E7%B1%BB%EF%BC%89%EF%BC%8C%E4%BB%A5%E5%8F%8A%E6%B7%B1%E5%BA%A6/\">yii2 页面功能块配置实现原理（前端+后端提供数据类），以及Views深度嵌套</a></td><td><a href=\"http://www.fancyecommerce.com/2016/06/26/yii2-%e5%9c%a8%e5%9f%9f%e5%90%8d%e5%90%8e%e9%9d%a2%e5%8a%a0%e4%b8%80%e4%b8%aa%e8%b7%af%e5%be%84%e4%bd%9c%e4%b8%ba%e9%a6%96%e9%a1%b5/\">yii2 在域名后面加一个路径作为首页</a></td></tr><tr><td><a href=\"http://www.fancyecommerce.com/2016/07/06/yii2-%e5%a4%9a%e6%a8%a1%e6%9d%bf%e8%b7%af%e5%be%84%e4%bc%98%e5%85%88%e7%ba%a7%e5%8a%a0%e8%bd%bdview%e6%96%b9%e5%bc%8f%e4%b8%8b-js%e5%92%8ccss-%e7%9a%84%e8%a7%a3%e5%86%b3/\">yii2 多模板路径优先级加载view方式下- js和css 的解决</a></td><td><a href=\"http://www.fancyecommerce.com/2016/06/30/yii2-fecshop-%e5%a4%9a%e6%a8%a1%e6%9d%bf%e7%9a%84%e4%bb%8b%e7%bb%8d/\">yii2 fecshop 多模板的介绍</a></td></tr></tbody></table><p>七.Nosql方面的知识: <a href=\"http://www.fancyecommerce.com/category/19-nosql-mongodbredis/\">Nosql – Mongodb,Redis</a></p><table><tbody><tr><td><a href=\"http://www.fancyecommerce.com/2016/06/21/%e9%85%8d%e7%bd%aemongodb-%e5%a4%8d%e5%88%b6%e9%9b%863-2/\">配置mongodb 复制集3.2</a></td><td><a href=\"http://www.fancyecommerce.com/2016/06/25/redis-%E5%88%86%E5%8C%BA%E5%AE%9E%E7%8E%B0%E5%8E%9F%E7%90%86/\">Redis 分区实现原理</a></td></tr></tbody></table>\";s:10:\"content_fr\";s:0:\"\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:0:\"\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:0:\"\";}', 1, 1469161289, 1469502714, 2),
			(27, '/98145363', 'a:7:{s:8:\"title_en\";s:21:\"发大水发发呆时\";s:8:\"title_fr\";s:6:\"frfrfr\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:0:\"\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:0:\"\";}', 'a:7:{s:16:\"meta_keywords_en\";s:21:\"发大水发发呆时\";s:16:\"meta_keywords_fr\";s:6:\"fr  fr\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:0:\"\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:0:\"\";}', 'a:7:{s:19:\"meta_description_en\";s:21:\"发大水发发呆时\";s:19:\"meta_description_fr\";s:4:\"frfr\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:0:\"\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:0:\"\";}', 'a:7:{s:10:\"content_en\";s:391:\"<p>发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时</p><p>发大水发发呆时发大水发发呆时发大水发发呆时发大水发发呆时</p><p>发大水发发呆时发大水发发呆时发大水发发呆时</p><p>发大水发发呆时发大水发发呆时<br /></p>\";s:10:\"content_fr\";s:29:\"frfr&nbsp; fadashuifrfr<br />\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:0:\"\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:0:\"\";}', 1, 1469280804, 1469772774, 2),
			(28, '/97282553', 'a:7:{s:8:\"title_en\";s:26:\"fashion hand bag for women\";s:8:\"title_fr\";s:0:\"\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:0:\"\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:16:\"meta_keywords_en\";s:26:\"fashion hand bag for women\";s:16:\"meta_keywords_fr\";s:0:\"\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:0:\"\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:19:\"meta_description_en\";s:26:\"fashion hand bag for women\";s:19:\"meta_description_fr\";s:0:\"\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:0:\"\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:10:\"content_en\";s:118:\"<img src=\"http://img.fancyecommerce.com/media/upload/e/n_/en_a147177412985490.jpg\" alt=\"\" />fashion hand bag for women\";s:10:\"content_fr\";s:0:\"\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:0:\"\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:12:\"时尚衣服\";}', 1, 1470123712, 1471774138, 2),
			(29, '/faq', 'a:7:{s:8:\"title_en\";s:26:\"fashion hand bag for women\";s:8:\"title_fr\";s:0:\"\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:0:\"\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:16:\"meta_keywords_en\";s:26:\"fashion hand bag for women\";s:16:\"meta_keywords_fr\";s:0:\"\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:0:\"\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:19:\"meta_description_en\";s:26:\"fashion hand bag for women\";s:19:\"meta_description_fr\";s:0:\"\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:0:\"\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:12:\"时尚衣服\";}', 'a:7:{s:10:\"content_en\";s:26:\"fashion hand bag for women\";s:10:\"content_fr\";s:0:\"\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:0:\"\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:12:\"时尚衣服\";}', 1, 1470123841, 1483609161, 2),
			(30, '/fashion-hand-bag-for-women22', 'a:7:{s:8:\"title_en\";s:28:\"fashion hand bag for women22\";s:8:\"title_fr\";s:0:\"\";s:8:\"title_de\";s:0:\"\";s:8:\"title_es\";s:10:\"eseseseses\";s:8:\"title_ru\";s:0:\"\";s:8:\"title_pt\";s:0:\"\";s:8:\"title_zh\";s:3:\"发\";}', 'a:7:{s:16:\"meta_keywords_en\";s:28:\"fashion hand bag for women22\";s:16:\"meta_keywords_fr\";s:0:\"\";s:16:\"meta_keywords_de\";s:0:\"\";s:16:\"meta_keywords_es\";s:0:\"\";s:16:\"meta_keywords_ru\";s:0:\"\";s:16:\"meta_keywords_pt\";s:0:\"\";s:16:\"meta_keywords_zh\";s:15:\"发的发生的\";}', 'a:7:{s:19:\"meta_description_en\";s:28:\"fashion hand bag for women22\";s:19:\"meta_description_fr\";s:4:\"frfr\";s:19:\"meta_description_de\";s:0:\"\";s:19:\"meta_description_es\";s:10:\"eseseseses\";s:19:\"meta_description_ru\";s:0:\"\";s:19:\"meta_description_pt\";s:0:\"\";s:19:\"meta_description_zh\";s:18:\"爱的色放打算\";}', 'a:7:{s:10:\"content_en\";s:6:\"发生\";s:10:\"content_fr\";s:60:\"芙蓉&nbsp;&nbsp; fr&nbsp;&nbsp; fashion hand bag for women\";s:10:\"content_de\";s:0:\"\";s:10:\"content_es\";s:10:\"eseseseses\";s:10:\"content_ru\";s:0:\"\";s:10:\"content_pt\";s:0:\"\";s:10:\"content_zh\";s:1132:\"<br />LuLu CMS系统优势<br /><br />&nbsp;&nbsp;&nbsp; 容易整合<br />&nbsp;&nbsp;&nbsp; 2016-01-01 18:56:48<br />&nbsp;&nbsp;&nbsp; LuLu CMS让整合第三方厂商解决方案变得更加容易，透过LuLu CMS建立客制化网站可以节省您很多的时间与资源。<br />&nbsp;&nbsp;&nbsp; 新颖的功能<br />&nbsp;&nbsp;&nbsp; 2016-01-01 18:57:07<br />&nbsp;&nbsp;&nbsp; 像是产品标签、多送货地址或产品比较系统等功能，您不需要支付额外的费用来取得，在现成的LuLu CMS系统中，您可以发现更多。<br />&nbsp;&nbsp;&nbsp; 专业与社群支援<br />&nbsp;&nbsp;&nbsp; 2016-01-01 18:57:29<br />&nbsp;&nbsp;&nbsp; 不像是其他的开放原始码解决方案，LuLu CMS提供专业、可信赖的支援，您也可以从热情的社群中取得协助,国内也有LuLu CMS的爱好者创建中文社区。<br />&nbsp;&nbsp;&nbsp; 完整的扩充性<br />&nbsp;&nbsp;&nbsp; 2016-01-01 18:57:48<br />&nbsp;&nbsp;&nbsp; 无论网站经过了一夜或是一年的成长，您不需要担心选择的方案无法应付，LuLu CMS提供了完整的扩充性。<br /><br /><br />\";}', 1, 1470123879, 1470209419, 2),
			(31, '/61493194', NULL, NULL, NULL, NULL, 1, 1477539885, 1477539885, 2);
			","
			CREATE TABLE IF NOT EXISTS `customer` (
			  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
			  `password_hash` varchar(80) DEFAULT NULL COMMENT '密码',
			  `password_reset_token` varchar(60) DEFAULT NULL COMMENT '密码token',
			  `email` varchar(60) DEFAULT NULL COMMENT '邮箱',
			  `firstname` varchar(100) DEFAULT NULL,
			  `lastname` varchar(100) DEFAULT NULL,
			  `is_subscribed` int(5) NOT NULL DEFAULT '2' COMMENT '1代表订阅，2代表不订阅邮件',
			  `auth_key` varchar(60) DEFAULT NULL,
			  `status` int(5) DEFAULT NULL COMMENT '状态',
			  `created_at` int(18) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(18) DEFAULT NULL COMMENT '更新时间',
			  `password` varchar(50) DEFAULT NULL COMMENT '密码',
			  `access_token` varchar(60) DEFAULT NULL,
			  `birth_date` datetime DEFAULT NULL COMMENT '出生日期',
			  `favorite_product_count` int(15) NOT NULL DEFAULT '0' COMMENT '用户收藏的产品的总数',
			  `type` varchar(35) DEFAULT 'default' COMMENT '默认为default，如果是第三方登录，譬如google账号登录注册，那么这里的值为google',
			  PRIMARY KEY (`id`),
			  KEY `email` (`email`),
			  UNIQUE KEY `access_token` (`access_token`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			","

			CREATE TABLE IF NOT EXISTS `customer_address` (
			  `address_id` int(15) NOT NULL AUTO_INCREMENT,
			  `first_name` varchar(150) DEFAULT NULL,
			  `email` varchar(155) DEFAULT NULL,
			  `last_name` varchar(150) DEFAULT NULL,
			  `company` varchar(255) DEFAULT NULL,
			  `telephone` varchar(100) DEFAULT NULL,
			  `fax` varchar(150) DEFAULT NULL,
			  `street1` text,
			  `street2` varchar(255) DEFAULT NULL,
			  `city` varchar(150) DEFAULT NULL,
			  `state` varchar(255) DEFAULT NULL,
			  `zip` varchar(50) DEFAULT NULL,
			  `country` varchar(50) DEFAULT NULL,
			  `customer_id` int(20) DEFAULT NULL,
			  `created_at` int(20) DEFAULT NULL,
			  `updated_at` int(20) DEFAULT NULL,
			  `is_default` int(11) NOT NULL DEFAULT '2' COMMENT '1代表是默认地址，2代表不是',
			  PRIMARY KEY (`address_id`),
			  KEY `customer_id` (`customer_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

			","
			CREATE TABLE IF NOT EXISTS `ipn_message` (
			  `ipn_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `txn_id` varchar(20) DEFAULT NULL COMMENT 'transaction id',
			  `payment_status` varchar(20) DEFAULT NULL COMMENT '支付状态',
			  `updated_at` int(15) DEFAULT NULL COMMENT '更新时间',
			  PRIMARY KEY (`ipn_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

			","
			CREATE TABLE IF NOT EXISTS `sales_coupon` (
			  `coupon_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `created_at` int(15) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(15) DEFAULT NULL COMMENT '更新时间',
			  `created_person` int(15) NOT NULL COMMENT '创建人的id',
			  `coupon_name` varchar(100) DEFAULT NULL COMMENT '优惠劵名称',
			  `coupon_description` varchar(255) DEFAULT NULL COMMENT '优惠劵描述',
			  `coupon_code` varchar(100) DEFAULT NULL COMMENT '优惠劵编号',
			  `expiration_date` int(15) DEFAULT NULL COMMENT '过期时间',
			  `users_per_customer` int(15) DEFAULT '0' COMMENT '优惠劵被每个客户使用的最大次数',
			  `times_used` int(15) DEFAULT '0' COMMENT '优惠劵被使用了多少次',
			  `type` int(5) DEFAULT NULL COMMENT '优惠劵的类型，1代表按照百分比对产品打折，2代表在总额上减少多少',
			  `conditions` int(15) DEFAULT NULL COMMENT '优惠劵使用的条件，如果类型为1，则没有条件，如果类型是2，则购物车中产品总额满足多少的时候进行打折。这里填写的是美元金额',
			  `discount` int(15) DEFAULT NULL COMMENT '优惠劵的折扣，如果类型为1，这里填写的是百分比，如果类型是2，这里代表的是在总额上减少的金额，货币为美金',
			  PRIMARY KEY (`coupon_id`),
			  KEY `coupon_code` (`coupon_code`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
			","
			INSERT INTO `sales_coupon` (`coupon_id`, `created_at`, `updated_at`, `created_person`, `coupon_name`, `coupon_description`, `coupon_code`, `expiration_date`, `users_per_customer`, `times_used`, `type`, `conditions`, `discount`) VALUES
			(4, 1481629639, 1481880122, 2, NULL, NULL, 'weqwwqw', 1543593600, 4, 452, 1, 11, 10);
			","
			CREATE TABLE IF NOT EXISTS `sales_coupon_usage` (
			  `id` int(15) NOT NULL AUTO_INCREMENT,
			  `coupon_id` int(25) DEFAULT '0' COMMENT '客户id',
			  `customer_id` int(25) DEFAULT '0' COMMENT '客户id',
			  `times_used` int(15) DEFAULT '0' COMMENT '使用次数',
			  PRIMARY KEY (`id`),
			  KEY `coupon_id` (`coupon_id`),
			  KEY `customer_id` (`customer_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;
			","
			INSERT INTO `sales_coupon_usage` (`id`, `coupon_id`, `customer_id`, `times_used`) VALUES
			(1, 4, 16, 2),
			(7, 4, 37, 0),
			(8, 4, 38, 1),
			(9, 4, 39, 4),
			(10, 4, 45, 1),
			(11, 4, 46, 1);
			","
			CREATE TABLE IF NOT EXISTS `sales_flat_cart` (
			  `cart_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `store` varchar(100) DEFAULT NULL COMMENT 'store name',
			  `created_at` int(15) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(15) DEFAULT NULL COMMENT '更新时间',
			  `items_count` int(10) DEFAULT '0' COMMENT '购物车中产品的总个数，默认为0个',
			  `customer_id` int(15) DEFAULT NULL COMMENT '客户id',
			  `customer_email` varchar(90) DEFAULT NULL COMMENT '客户邮箱',
			  `customer_firstname` varchar(50) DEFAULT NULL COMMENT '客户名字',
			  `customer_lastname` varchar(50) DEFAULT NULL COMMENT '客户名字',
			  `customer_is_guest` int(5) DEFAULT NULL COMMENT '是否是游客，1代表是游客，2代表不是游客',
			  `remote_ip` varchar(26) DEFAULT NULL COMMENT 'ip地址',
			  `coupon_code` varchar(20) DEFAULT NULL COMMENT '优惠劵',
			  `payment_method` varchar(20) DEFAULT NULL COMMENT '支付方式',
			  `shipping_method` varchar(20) DEFAULT NULL COMMENT '货运方式',
			  `customer_telephone` varchar(25) DEFAULT NULL COMMENT '客户电话',
			  `customer_address_id` int(20) DEFAULT NULL COMMENT '客户地址id',
			  `customer_address_country` varchar(50) DEFAULT NULL COMMENT '客户国家',
			  `customer_address_state` varchar(50) DEFAULT NULL COMMENT '客户省',
			  `customer_address_city` varchar(50) DEFAULT NULL COMMENT '客户市',
			  `customer_address_zip` varchar(20) DEFAULT NULL COMMENT '客户zip',
			  `customer_address_street1` text COMMENT '客户街道地址1',
			  `customer_address_street2` text COMMENT '客户街道地址2',
			  `app_name` varchar(20) DEFAULT NULL COMMENT '属于哪个app',
			  PRIMARY KEY (`cart_id`),
			  KEY `customer_id` (`customer_id`),
			  KEY `customer_email` (`customer_email`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

			","
			CREATE TABLE IF NOT EXISTS `sales_flat_cart_item` (
			  `item_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `store` varchar(100) DEFAULT NULL COMMENT 'store name',
			  `cart_id` int(15) DEFAULT NULL,
			  `created_at` int(15) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(15) DEFAULT NULL COMMENT '更新时间',
			  `product_id` varchar(100) DEFAULT NULL COMMENT '产品id',
			  `qty` int(10) DEFAULT NULL COMMENT '个数',
			  `custom_option_sku` varchar(50) DEFAULT NULL COMMENT '产品的自定义属性',
			  PRIMARY KEY (`item_id`),
			  KEY `quote_id` (`cart_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			","

			CREATE TABLE IF NOT EXISTS `sales_flat_order` (
			  `order_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `increment_id` varchar(25) DEFAULT NULL COMMENT '递增个数',
			  `order_status` text COMMENT '订单状态',
			  `store` varchar(100) DEFAULT NULL COMMENT 'store name',
			  `created_at` int(15) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(15) DEFAULT NULL COMMENT '更新时间',
			  `items_count` int(10) DEFAULT '0' COMMENT '订单中产品的总个数，默认为0个',
			  `total_weight` decimal(12,4) DEFAULT '0.0000' COMMENT '总重量',
			  `order_currency_code` varchar(10) DEFAULT NULL COMMENT '当前货币',
			  `order_to_base_rate` decimal(12,4) DEFAULT NULL COMMENT '当前货币和默认货币的比率',
			  `grand_total` decimal(12,4) DEFAULT NULL COMMENT '当前订单的总额',
			  `base_grand_total` decimal(12,4) DEFAULT NULL COMMENT '当前订单的默认货币总额',
			  `subtotal` decimal(12,4) DEFAULT NULL COMMENT '当前订单的产品总额',
			  `base_subtotal` decimal(12,4) DEFAULT NULL COMMENT '当前订单的产品默认货币总额',
			  `subtotal_with_discount` decimal(12,4) DEFAULT NULL COMMENT '当前订单的去掉的总额',
			  `base_subtotal_with_discount` decimal(12,4) DEFAULT NULL COMMENT '当前订单的去掉的默认货币总额',
			  `is_changed` int(5) DEFAULT '1' COMMENT '是否change，1代表是，2代表否',
			  `checkout_method` varchar(20) DEFAULT NULL COMMENT 'guest，register，代表是游客还是登录客户。',
			  `customer_id` int(15) DEFAULT NULL COMMENT '客户id',
			  `customer_group` varchar(20) DEFAULT NULL COMMENT '客户组id',
			  `customer_email` varchar(90) DEFAULT NULL COMMENT '客户邮箱',
			  `customer_firstname` varchar(50) DEFAULT NULL COMMENT '客户名字',
			  `customer_lastname` varchar(50) DEFAULT NULL COMMENT '客户名字',
			  `customer_is_guest` int(5) DEFAULT NULL COMMENT '是否是游客，1代表是游客，2代表不是游客',
			  `remote_ip` varchar(26) DEFAULT NULL COMMENT 'ip地址',
			  `coupon_code` varchar(20) DEFAULT NULL COMMENT '优惠劵',
			  `payment_method` varchar(20) DEFAULT NULL COMMENT '支付方式',
			  `shipping_method` varchar(20) DEFAULT NULL COMMENT '货运方式',
			  `shipping_total` decimal(12,4) DEFAULT NULL COMMENT '运费总额',
			  `base_shipping_total` decimal(12,4) DEFAULT NULL COMMENT '默认货币运费总额',
			  `customer_telephone` varchar(25) DEFAULT NULL COMMENT '客户电话',
			  `customer_address_country` varchar(50) DEFAULT NULL COMMENT '客户国家',
			  `customer_address_state` varchar(50) DEFAULT NULL COMMENT '客户省',
			  `customer_address_city` varchar(50) DEFAULT NULL COMMENT '客户市',
			  `customer_address_zip` varchar(20) DEFAULT NULL COMMENT '客户zip',
			  `customer_address_street1` text COMMENT '客户地址1',
			  `customer_address_street2` text COMMENT '客户地址2',
			  `txn_type` varchar(20) DEFAULT NULL COMMENT 'translate类型',
			  `txn_id` varchar(30) DEFAULT NULL COMMENT 'translate id',
			  `payer_id` varchar(30) DEFAULT NULL COMMENT '交易号',
			  `ipn_track_id` varchar(20) DEFAULT NULL,
			  `receiver_id` varchar(20) DEFAULT NULL,
			  `verify_sign` varchar(80) DEFAULT NULL,
			  `charset` varchar(20) DEFAULT NULL,
			  `payment_fee` decimal(12,4) DEFAULT NULL COMMENT '交易服务费',
			  `payment_type` varchar(20) DEFAULT NULL COMMENT '交易类型',
			  `correlation_id` varchar(20) DEFAULT NULL COMMENT '相关id，快捷支付里面的字段',
			  `base_payment_fee` decimal(12,4) DEFAULT NULL COMMENT '交易费用，基础货币值，通过货币进行的转换',
			  `protection_eligibility` varchar(20) DEFAULT NULL COMMENT '保护资格，快捷支付里面的字段',
			  `protection_eligibility_type` varchar(255) DEFAULT NULL COMMENT '保护资格类型，快捷支付里面的字段',
			  `secure_merchant_account_id` varchar(20) DEFAULT NULL COMMENT '商人账户安全id',
			  `build` varchar(20) DEFAULT NULL COMMENT 'build',
			  `paypal_order_datetime` datetime DEFAULT NULL COMMENT '订单创建，Paypal时间',
			  `theme_type` int(5) DEFAULT NULL COMMENT '1-pc,2-mobile',
			  `if_is_return_stock` int(5) NOT NULL DEFAULT '2' COMMENT '1,代表订单归还了库存，2代表订单没有归还库存，此状态作用：用来标示pending订单是否释放产品库存',
			  PRIMARY KEY (`order_id`),
			  KEY `customer_id` (`customer_id`),
			  KEY `increment_id` (`increment_id`)
			  
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			
			
			"ALTER TABLE `sales_flat_order` CHANGE `order_status` `order_status` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单状态'",
			"ALTER TABLE `sales_flat_order` ADD INDEX oupload_at_order_status ( `updated_at`, `order_status`, `if_is_return_stock` )",

			"
			CREATE TABLE IF NOT EXISTS `sales_flat_order_item` (
			  `item_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
			  `store` varchar(100) DEFAULT NULL COMMENT 'store name',
			  `order_id` int(15) DEFAULT NULL COMMENT '产品对应的订单表id',
			  `created_at` int(16) DEFAULT NULL COMMENT '创建时间',
			  `updated_at` int(16) DEFAULT NULL COMMENT '更新时间',
			  `product_id` varchar(100) DEFAULT NULL COMMENT '产品id',
			  `sku` varchar(100) DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  `custom_option_sku` varchar(50) DEFAULT NULL COMMENT '自定义属性',
			  `image` varchar(255) DEFAULT NULL COMMENT '图片',
			  `weight` decimal(12,4) DEFAULT NULL COMMENT '重量',
			  `qty` int(10) DEFAULT NULL COMMENT '个数',
			  `row_weight` decimal(12,4) DEFAULT NULL COMMENT '一个产品重量*个数',
			  `price` decimal(12,4) DEFAULT NULL COMMENT '产品价格',
			  `base_price` decimal(12,4) DEFAULT NULL COMMENT '默认货币价格',
			  `row_total` decimal(12,4) DEFAULT NULL COMMENT '一个产品价格*个数',
			  `base_row_total` decimal(12,4) DEFAULT NULL COMMENT '一个产品默认货币价格*个数',
			  `redirect_url` varchar(200) DEFAULT NULL COMMENT '产品url',
			  PRIMARY KEY (`item_id`),
			  KEY `order_id` (`order_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			","

			CREATE TABLE IF NOT EXISTS `static_block` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `identify` varchar(100) DEFAULT NULL,
			  `title` text,
			  `status` int(5) DEFAULT NULL,
			  `content` text,
			  `created_at` int(11) DEFAULT NULL,
			  `updated_at` int(11) DEFAULT NULL,
			  `created_user_id` int(20) DEFAULT NULL,
			  KEY `identify` (`identify`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			","

			CREATE TABLE IF NOT EXISTS `url_rewrite` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `type` varchar(50) DEFAULT NULL COMMENT '类型',
			  `custom_url_key` varchar(255) DEFAULT NULL COMMENT '自定义url key',
			  `origin_url` varchar(255) DEFAULT NULL COMMENT '原来的url ',
			  KEY `custom_url_key` (`custom_url_key`),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;
			","
			INSERT INTO `url_rewrite` (`id`, `type`, `custom_url_key`, `origin_url`) VALUES
			(1, '1', '1', '1'),
			(2, 'system', '/4444444444.html', '/cms/article/index?_id=57919ea0f656f2154de25ca9'),
			(3, 'system', '/72527', '/cms/article/index?_id=0'),
			(4, 'system', '/fashion-women.html', '/cms/article/index?_id=57936c63f656f2f42ce25ca4'),
			(5, 'system', '/fashion-women-74341929', '/cms/article/index?_id=57936ae1f656f2f42ce25ca3'),
			(6, 'system', '/67535963', '/cms/article/index?_id=57937062f656f2e944e25ca5'),
			(7, 'system', '/11571166', '/cms/article/index?_id=57937114f656f2f42ce25ca5'),
			(8, 'system', '/98145363', '/cms/article/index?id=27'),
			(9, 'system', '/55555555555', '/cms/article/index?id=26'),
			(10, 'system', '/67786962', '/cms/article/index?id=29'),
			(11, 'system', '/fashion-hand-bag-for-women22', '/cms/article/index?id=30'),
			(12, 'system', '/57161191', '/?_id=57aa815bf656f26e70e25ca3'),
			(13, 'system', '/67274789', '/?_id=57aa897cf656f26e70e25ca4'),
			(14, 'system', '/432432', '/catalog/category/index?_id=57aa8d7ff656f2107ee25ca3'),
			(15, 'system', '/111111111111111', '/catalog/category/index?_id=57aa8d91f656f24c5fe25ca3'),
			(16, 'system', '/women', '/catalog/category/index?_id=57aa8f18f656f24c5fe25ca4'),
			(17, 'system', '/men', '/catalog/category/index?_id=57aa8f1ef656f26e70e25ca5'),
			(18, 'system', '/lady', '/catalog/category/index?_id=57aa8f27f656f2107ee25ca4'),
			(19, 'system', '/2121', '/catalog/category/index?_id=57aacb89f656f22e0be25ca3'),
			(20, 'system', '/1111en', '/catalog/category/index?_id=57aacbbcf656f2f610e25ca3'),
			(21, 'system', '/1111en-66254841', '/catalog/category/index?_id=57aacbe3f656f22e0be25ca4'),
			(22, 'system', '/2222222', '/catalog/category/index?_id=57aacc35f656f22e0be25ca5'),
			(23, 'system', '/1111', '/catalog/category/index?_id=57aacdf0f656f2b00ee25ca3'),
			(45, 'system', '/1111111111111111', '/catalog/product/index?_id=57b5936af656f2ff293bf56e');"

		];
		//  ALTER TABLE `admin_role_menu` ADD INDEX ( `created_at` ) 
		
		foreach($arr as $sql){
			$this->execute($sql);
		}
	
    }

    public function safeDown()
    {
        echo "m170228_072156_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
