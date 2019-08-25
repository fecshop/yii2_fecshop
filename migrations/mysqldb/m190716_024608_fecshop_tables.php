<?php

use yii\db\Migration;

/**
 * Class m190716_024608_fecshop_tables
 */
class m190716_024608_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                CREATE TABLE IF NOT EXISTS `product_flat` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
              `created_user_id` int(11) DEFAULT NULL COMMENT '创建admin user id',
              `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
              `name` text NOT NULL COMMENT '产品名字',
              `spu` varchar(150) NOT NULL COMMENT 'spu',
              `sku` varchar(150) NOT NULL COMMENT 'sku',
              `score` int(11) DEFAULT NULL COMMENT '评分',
              `status` int(5) DEFAULT NULL COMMENT '产品状态，1代表激活，2代表关闭',
              `qty` int(11) NOT NULL COMMENT '库存',
              `min_sales_qty` int(11) DEFAULT NULL COMMENT '最小购买数',
              `is_in_stock` int(5) DEFAULT NULL COMMENT '库存状态，1代表有库存，2代表无库存',
              `url_key` varchar(255) DEFAULT NULL COMMENT '产品url',
              `meta_title` text COMMENT 'meta title',
              `price` decimal(12,2) NOT NULL,
              `cost_price` decimal(12,2) DEFAULT NULL,
              `special_price` decimal(12,2) DEFAULT NULL,
              `special_from` int(11) DEFAULT NULL,
              `special_to` int(11) DEFAULT NULL,
              `tier_price` text,
              `final_price` decimal(12,2) DEFAULT NULL,
              `new_product_from` int(11) DEFAULT NULL,
              `new_product_to` int(11) DEFAULT NULL,
              `meta_keywords` text,
              `meta_description` text,
              `image` text COMMENT '图片信息',
              `description` text,
              `short_description` text,
              `custom_option` text,
              `remark` text COMMENT '备注',
              `long` int(11) DEFAULT NULL COMMENT '产品的长度',
              `width` int(11) DEFAULT NULL COMMENT '产品的宽度',
              `high` int(11) DEFAULT NULL COMMENT '产品的高度',
              `weight` decimal(11,2) DEFAULT NULL COMMENT '重量',
              `volume_weight` decimal(11,2) DEFAULT NULL COMMENT '体积重',
              `package_number` int(11) DEFAULT NULL COMMENT '打包销售个数',
              `favorite_count` int(11) DEFAULT NULL COMMENT '收藏数',
              `relation_sku` text COMMENT '相关产品',
              `buy_also_buy_sku` text COMMENT '买了还买',
              `see_also_see_sku` text COMMENT '看了还看',
              `attr_group` varchar(255) DEFAULT NULL COMMENT '属性组',
              `attr_group_info` text COMMENT '属性组对应的属性以及值',
              `reviw_rate_star_average` int(11) DEFAULT NULL COMMENT '评星平均值',
              `review_count` int(11) DEFAULT NULL COMMENT '评论数',
              `reviw_rate_star_average_lang` text COMMENT '评星平均值（语言）',
              `review_count_lang` text COMMENT '评论数（语言）',
              `reviw_rate_star_info` text COMMENT '评星详细',
              `reviw_rate_star_info_lang` text COMMENT '评星详细（语言）',
              PRIMARY KEY (`id`),
              UNIQUE KEY `sku` (`sku`,`url_key`),
              KEY `spu` (`spu`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `category_product` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `category_id` varchar(50) NOT NULL,
                  `product_id` varchar(50) NOT NULL,
                  `created_at` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `category_id` (`category_id`,`product_id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=233 ;
            ",
            
            "
            ALTER TABLE  `url_rewrite` ADD  `created_at` INT( 12 ) NULL ,
            ADD  `updated_at` INT( 12 ) NULL
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `full_search_product` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `lang` varchar(20) DEFAULT NULL COMMENT '语言简码',
              `product_id` varchar(50) DEFAULT NULL COMMENT '产品id',
              `name` varchar(255) DEFAULT NULL COMMENT '产品name',
              `spu` varchar(100) DEFAULT NULL COMMENT 'spu',
              `sku` varchar(100) DEFAULT NULL COMMENT 'sku',
              `score` int(11) DEFAULT NULL COMMENT '产品分值',
              `status` int(5) DEFAULT NULL COMMENT '产品状态',
              `is_in_stock` int(5) DEFAULT NULL COMMENT '产品库存状态',
              `url_key` varchar(255) DEFAULT NULL COMMENT '产品url key',
              `price` decimal(12,2) DEFAULT NULL COMMENT '产品价格',
              `cost_price` decimal(12,2) DEFAULT NULL COMMENT '产品成本价',
              `special_price` decimal(12,2) DEFAULT NULL COMMENT '产品特价',
              `special_from` int(12) DEFAULT NULL COMMENT '产品特价开始时间',
              `special_to` int(12) DEFAULT NULL COMMENT '产品特价结束时间',
              `final_price` decimal(12,2) DEFAULT NULL COMMENT '产品最终时间',
              `image` text COMMENT '产品图片',
              `short_description` text COMMENT '产品简短描述',
              `description` text COMMENT '产品描述',
              `created_at` int(12) DEFAULT NULL COMMENT '产品创建时间',
              `sync_updated_at` int(12) DEFAULT NULL COMMENT '产品同步时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `category` (
              `id` int(12) NOT NULL AUTO_INCREMENT,
              `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
              `created_user_id` int(12) DEFAULT NULL COMMENT '创建分类的userId',
              `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
              `parent_id` int(12) DEFAULT NULL COMMENT '上级分类id，一级分类的值为0',
              `name` text COMMENT '分类名称',
              `status` int(5) DEFAULT NULL COMMENT '分类状态',
              `url_key` varchar(255) DEFAULT NULL COMMENT '分类url key',
              `description` text COMMENT '分类描述',
              `menu_custom` text,
              `title` text COMMENT '分类页面meta title',
              `meta_description` text COMMENT '分类页面meta description',
              `meta_keywords` text COMMENT '分类页面meta keywords',
              `level` int(5) DEFAULT NULL COMMENT '分类等级',
              `filter_product_attr_selected` varchar(255) DEFAULT NULL COMMENT '分类页面进行过滤的属性',
              `filter_product_attr_unselected` varchar(255) DEFAULT NULL COMMENT '分类页面不进行过滤的属性',
              `menu_show` int(5) DEFAULT NULL COMMENT '是否在菜单中显示该分类',
              `thumbnail_image` varchar(255) DEFAULT NULL COMMENT '缩略图',
              `image` varchar(255) DEFAULT NULL COMMENT '分类图',
              `origin_mongo_id` varchar(100) DEFAULT NULL COMMENT '同步数据使用的字段：作为mongodb和mysql，在services切换的时候进行数据同步的id',
              `origin_mongo_parent_id` varchar(100) DEFAULT NULL COMMENT '同步数据使用的字段：mongo中的上级分类id',
              PRIMARY KEY (`id`),
              KEY `parent_id` (`parent_id`,`menu_show`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
            ",
            
            "
                ALTER TABLE  `product_flat` ADD  `origin_mongo_id` VARCHAR( 80 ) NULL COMMENT  'mongodb表的产品id'
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `review` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `product_spu` varchar(100) DEFAULT NULL,
                  `product_sku` varchar(100) DEFAULT NULL,
                  `product_id` varchar(50) DEFAULT NULL,
                  `rate_star` int(5) DEFAULT NULL COMMENT '评星',
                  `name` varchar(255) DEFAULT NULL COMMENT '评论人姓名',
                  `user_id` int(12) DEFAULT NULL COMMENT '评论人userId',
                  `ip` varchar(50) DEFAULT NULL COMMENT '评论人ip',
                  `summary` varchar(255) DEFAULT NULL COMMENT '评论标题',
                  `review_content` text COMMENT '评论内容',
                  `review_date` int(12) DEFAULT NULL COMMENT '评论日期',
                  `store` varchar(100) DEFAULT NULL COMMENT 'store',
                  `lang_code` varchar(20) DEFAULT NULL COMMENT '语言简码',
                  `status` int(5) DEFAULT NULL COMMENT '状态',
                  
                  `audit_user` int(12) DEFAULT NULL COMMENT '评论审核用户id',
                  `audit_date` int(12) DEFAULT NULL COMMENT '评论审核时间',
                  `origin_mongo_id` varchar(50) DEFAULT NULL COMMENT 'mongodb review的id（数据同步）',
                  PRIMARY KEY (`id`),
                  KEY `product_spu` (`product_spu`,`product_id`),
                  KEY `product_sku` (`product_sku`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


            ",
            "
            CREATE TABLE IF NOT EXISTS `newsletter` (
              `id` int(12) NOT NULL AUTO_INCREMENT,
              `email` varchar(150) NOT NULL,
              `created_at` int(12) NOT NULL,
              `status` int(5) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `favorite` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `product_id` varchar(50) DEFAULT NULL,
                  `user_id` int(12) DEFAULT NULL,
                  `created_at` int(12) DEFAULT NULL,
                  `updated_at` int(12) DEFAULT NULL,
                  `store` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
            CREATE TABLE IF NOT EXISTS `error_handler_log` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `category` varchar(100) DEFAULT NULL,
              `code` int(12) DEFAULT NULL,
              `message` varchar(255) DEFAULT NULL,
              `file` varchar(255) DEFAULT NULL,
              `line` int(12) DEFAULT NULL,
              `created_at` int(12) DEFAULT NULL,
              `ip` varchar(100) DEFAULT NULL,
              `name` varchar(255) DEFAULT NULL,
              `url` varchar(255) DEFAULT NULL,
              `request_info` text,
              `trace_string` text,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `store_base_config` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `key` varchar(100) NOT NULL,
                  `value` text NOT NULL,
                  `created_at` int(12) NOT NULL,
                  `updated_at` int(12) NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `key` (`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `store_domain` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `key` varchar(255) DEFAULT NULL COMMENT 'store的domain key，譬如：fecshop.appfront.fancyecommerce.com',
                  `app_name` varchar(50) DEFAULT NULL COMMENT 'App入口的名字，譬如appfront，apphtml5',
                  `lang` varchar(20) DEFAULT NULL COMMENT 'store对应的语言code',
                  `lang_name` varchar(50) DEFAULT NULL COMMENT 'store语言简码对应的文字名称，将会出现在语言切换列表中显示',
                  `local_theme_dir` varchar(255) DEFAULT NULL COMMENT '设置store对应的本地local模板路径',
                  `third_theme_dir` text COMMENT '序列化字段：设置store对应的第三方模板路径，该字段存储将会序列化',
                  `currency` varchar(20) DEFAULT NULL COMMENT 'store对应的默认货币',
                  `mobile_enable` int(5) DEFAULT NULL COMMENT '是否开启移动设备访问跳转，1是，2否',
                  `mobile_condition` varchar(255) DEFAULT NULL COMMENT '序列化字段：进行跳转的条件：phone 代表手机，tablet代表平板，当都填写，代表手机和平板都会进行跳转',
                  `mobile_redirect_domain` varchar(255) DEFAULT NULL COMMENT '移动设备访问跳转的域名',
                  `mobile_https_enable` int(5) DEFAULT NULL COMMENT '跳转的域名是否是https，1是，2否',
                  `mobile_type` varchar(50) DEFAULT NULL COMMENT '填写值选择：[apphtml5, appserver]，如果是 apphtml5 ， 则表示跳转到html5入口，如果是appserver，则表示跳转到vue这种appserver对应的入口',
                  `facebook_login_app_id` varchar(100) DEFAULT NULL COMMENT 'facebook帐号登陆的appId',
                  `facebook_login_app_secret` varchar(100) DEFAULT NULL COMMENT 'facebook帐号登陆的appSecret',
                  `google_login_client_id` varchar(150) DEFAULT NULL COMMENT 'google帐号登陆的clientId',
                  `google_login_client_secret` varchar(100) DEFAULT NULL COMMENT 'google帐号登陆的client secret',
                  `https_enable` int(5) DEFAULT NULL COMMENT '当前store是否使用https，1是，2否',
                  `sitemap_dir` varchar(255) DEFAULT NULL COMMENT 'sitemap地址，譬如：@appfront/web/sitemap.xml',
                  `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
                  `status` int(5) DEFAULT NULL COMMENT 'store状态，1为激活，2为关闭',
                  PRIMARY KEY (`id`),
                  KEY `app_name` (`app_name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            
            "
                CREATE TABLE IF NOT EXISTS `product_attribute` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `attr_type` varchar(50) DEFAULT NULL COMMENT '属性类型，general_attr or  spu_attr',
              `name` varchar(50) DEFAULT NULL COMMENT '属性名称',
              `status` int(5) DEFAULT NULL COMMENT '属性状态，1代表激活，2代表关闭',
              `db_type` varchar(50) DEFAULT NULL COMMENT '属性值的字符类型，string，int等',
              `show_as_img` int(5) DEFAULT NULL COMMENT '是否以图片的方式显示，1代表是，2代表否',
              `display_type` varchar(50) DEFAULT NULL COMMENT '显示方式：select ， inputString，inputEmail，inputDate等',
              `display_data` text COMMENT '显示对应的值',
              `is_require` int(5) DEFAULT NULL COMMENT '是否必填值，1代表是，2代表否',
              `default` varchar(150) DEFAULT NULL COMMENT '默认值',
              `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
              `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

            ",
            
            "
                CREATE TABLE IF NOT EXISTS `product_attribute_group` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) DEFAULT NULL COMMENT '属性组名称',
                  `attr_ids` text COMMENT '属性ids',
                  `status` int(5) DEFAULT NULL COMMENT '状态，1代表激活，2代表关闭',
                  `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

            ",
            
            '
            INSERT INTO `store_base_config` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
            (2, \'service_db\', \'a:7:{s:20:"category_and_product";s:7:"mysqldb";s:16:"product_favorite";s:7:"mysqldb";s:14:"product_review";s:7:"mysqldb";s:23:"article_and_staticblock";s:7:"mysqldb";s:11:"url_rewrite";s:7:"mysqldb";s:10:"newsletter";s:7:"mysqldb";s:16:"error_handle_log";s:7:"mysqldb";}\', 1563602730, 1564392254),
            (3, \'search_engine\', \'a:3:{s:11:"mysqlSearch";s:1:"1";s:11:"mongoSearch";s:1:"2";s:9:"xunSearch";s:1:"2";}\', 1563630981, 1564494197),
            (4, \'mutil_lang\', \'a:8:{i:0;a:3:{s:9:"lang_name";s:5:"en_US";s:9:"lang_code";s:2:"en";s:13:"search_engine";s:11:"mysqlSearch";}i:1;a:3:{s:9:"lang_name";s:5:"zh_CN";s:9:"lang_code";s:2:"zh";s:13:"search_engine";s:11:"mysqlSearch";}i:2;a:3:{s:9:"lang_name";s:5:"fr_FR";s:9:"lang_code";s:2:"fr";s:13:"search_engine";s:11:"mysqlSearch";}i:3;a:3:{s:9:"lang_name";s:5:"de_DE";s:9:"lang_code";s:2:"de";s:13:"search_engine";s:11:"mysqlSearch";}i:4;a:3:{s:9:"lang_name";s:5:"es_ES";s:9:"lang_code";s:2:"es";s:13:"search_engine";s:11:"mysqlSearch";}i:5;a:3:{s:9:"lang_name";s:5:"pt_PT";s:9:"lang_code";s:2:"pt";s:13:"search_engine";s:11:"mysqlSearch";}i:6;a:3:{s:9:"lang_name";s:5:"ru_RU";s:9:"lang_code";s:2:"ru";s:13:"search_engine";s:11:"mysqlSearch";}i:7;a:3:{s:9:"lang_name";s:5:"it_IT";s:9:"lang_code";s:2:"it";s:13:"search_engine";s:11:"mysqlSearch";}}\', 1563681314, 1563861226),
            (5, \'appfront_cache\', \'a:9:{s:12:"allPageCache";s:1:"2";s:13:"homePageCache";s:1:"2";s:17:"categoryPageCache";s:1:"2";s:16:"productPageCache";s:1:"2";s:16:"articlePageCache";s:1:"2";s:14:"headBlockCache";s:1:"2";s:16:"headerBlockCache";s:1:"2";s:14:"menuBlockCache";s:1:"2";s:16:"footerBlockCache";s:1:"2";}\', 1563785109, 1564494161),
            (6, \'apphtml5_cache\', \'a:9:{s:12:"allPageCache";s:1:"2";s:13:"homePageCache";s:1:"2";s:17:"categoryPageCache";s:1:"2";s:16:"productPageCache";s:1:"2";s:16:"articlePageCache";s:1:"2";s:14:"headBlockCache";s:1:"2";s:16:"headerBlockCache";s:1:"2";s:14:"menuBlockCache";s:1:"2";s:16:"footerBlockCache";s:1:"2";}\', 1563789953, 1564494282),
            (7, \'appserver_cache\', \'a:5:{s:12:"allPageCache";s:1:"2";s:13:"homePageCache";s:1:"2";s:17:"categoryPageCache";s:1:"2";s:16:"productPageCache";s:1:"2";s:16:"articlePageCache";s:1:"2";}\', 1563789975, 1564494295),
            (8, \'currency\', \'a:4:{i:0;a:3:{s:13:"currency_code";s:3:"EUR";s:15:"currency_symbol";s:3:"€";s:13:"currency_rate";s:4:"0.93";}i:1;a:3:{s:13:"currency_code";s:3:"USD";s:15:"currency_symbol";s:1:"$";s:13:"currency_rate";s:1:"1";}i:2;a:3:{s:13:"currency_code";s:3:"GBP";s:15:"currency_symbol";s:2:"£";s:13:"currency_rate";s:3:"0.8";}i:3;a:3:{s:13:"currency_code";s:3:"CNY";s:15:"currency_symbol";s:3:"￥";s:13:"currency_rate";s:3:"6.3";}}\', 1563809273, 1563809339),
            (9, \'base_info\', \'a:3:{s:12:"default_lang";s:2:"en";s:16:"default_currency";s:3:"USD";s:13:"base_currency";s:3:"USD";}\', 1563810520, 1563810949),
            (10, \'appserver_store\', \'a:9:{s:3:"key";s:36:"fecshop.appserver.fancyecommerce.com";s:4:"lang";s:5:"en_US";s:9:"lang_name";s:7:"English";s:8:"currency";s:3:"USD";s:12:"https_enable";s:1:"1";s:21:"facebook_login_app_id";s:16:"1108618299786621";s:25:"facebook_login_app_secret";s:32:"420b56da4f4664a4d1065a1d31e5ec73";s:22:"google_login_client_id";s:72:"380372364773-qdj1seag9bh2n0pgrhcv2r5uoc58ltp3.apps.googleusercontent.com";s:26:"google_login_client_secret";s:24:"ei8RaoCDoAlIeh1nHYm0rrwO";}\', 1563872863, 1563873396),
            (11, \'appserver_store_lang\', \'a:4:{i:0;a:3:{s:12:"languageName";s:9:"Français";s:4:"code";s:2:"fr";s:8:"language";s:5:"fr_FR";}i:1;a:3:{s:12:"languageName";s:7:"English";s:4:"code";s:2:"en";s:8:"language";s:5:"en_US";}i:2;a:3:{s:12:"languageName";s:8:"Español";s:4:"code";s:2:"es";s:8:"language";s:5:"es_ES";}i:3;a:3:{s:12:"languageName";s:6:"中文";s:4:"code";s:2:"zh";s:8:"language";s:5:"zh_CN";}}\', 1563879849, 1563890840);
             

            ',
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
        echo "m190716_024608_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190716_024608_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
