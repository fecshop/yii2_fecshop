<?php

use yii\db\Migration;

/**
 * Class m200924_022049_fecshop_tables
 */
class m200924_022049_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Catalog Product SpuCode', 'catalog_product_info_manager', 30, '/catalog/productinfo/spucode', 1600914154, 1600914154, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Catalog Product AttrGroup', 'catalog_product_info_manager', 22, '/catalog/productinfo/getattrgroupinfo', 1601025890, 1601025890, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200924_022049_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200924_022049_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
