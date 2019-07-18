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
