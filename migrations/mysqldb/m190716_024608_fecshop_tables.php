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
