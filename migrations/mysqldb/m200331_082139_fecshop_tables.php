<?php

use yii\db\Migration;

/**
 * Class m200331_082139_fecshop_tables
 */
class m200331_082139_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand List', 'catalog_product_brand_manager', 1, '/catalog/productbrand/manager', 1585655278, 1585655278, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Edit', 'catalog_product_brand_manager', 2, '/catalog/productbrand/manageredit', 1585655306, 1585655306, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
        
         // 3
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Save', 'catalog_product_brand_manager', 3, '/catalog/productbrand/managereditsave', 1585655334, 1585655334, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
         // 4
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Delete', 'catalog_product_brand_manager', 4, '/catalog/productbrand/managerdelete', 1585655374, 1585655374, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
         // 5
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Category List', 'catalog_product_brand_category_manager', 1, '/catalog/productbrandcategory/manager', 1585655404, 1585655404, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
         // 6
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Category Edit', 'catalog_product_brand_category_manager', 2, '/catalog/productbrandcategory/manageredit', 1585655430, 1585655430, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
         // 7
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Category Save', 'catalog_product_brand_category_manager', 3, '/catalog/productbrandcategory/managereditsave', 1585655456, 1585655456, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
         // 8
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Brand Category Delete', 'catalog_product_brand_category_manager', 4, '/catalog/productbrandcategory/managerdelete', 1585655482, 1585655482, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        // 9
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ( 'Product Brand Image Upload', 'catalog_product_brand_manager', 5, '/catalog/productbrand/imageupload', 1585743582, 1585743582 , 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");

        
        $arr = [
            // 品牌分类表
            "
                CREATE TABLE IF NOT EXISTS `product_brand_category` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `name` text COMMENT '品牌类别名称',
                  `sort_order` int(11) DEFAULT NULL COMMENT '排序',
                  `status` int(5) DEFAULT NULL COMMENT '状态',
                  `created_at` int(12) DEFAULT NULL,
                  `updated_at` int(12) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `status` (`status`,`sort_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            // 产品品牌表
            "
                CREATE TABLE IF NOT EXISTS `product_brand` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `name` text COMMENT '品牌名称',
                  `brand_category_id` int(12) DEFAULT NULL COMMENT '品牌类别id',
                  `logo` varchar(255) DEFAULT NULL COMMENT '品牌log',
                  `website_url` varchar(255) DEFAULT NULL COMMENT '品牌官网url',
                  `status` int(5) DEFAULT NULL COMMENT '品牌状态，1代表激活，2代表关闭',
                  `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
                  PRIMARY KEY (`id`),
                  KEY `status` (`status`, `brand_category_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ",
            "
                ALTER TABLE `product_flat` ADD `brand_id` INT( 12 ) NULL COMMENT '品牌id'
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
        echo "m200331_082139_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200331_082139_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
