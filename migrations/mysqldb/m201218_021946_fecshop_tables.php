<?php

use yii\db\Migration;

/**
 * Class m201218_021946_fecshop_tables
 */
class m201218_021946_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config FA Manager', 'config_base_manager', 101, '/config/fa/manager', 1608256769, 1608256847, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config FA Save', 'config_base_manager', 102, '/config/fa/managersave', 1608256793, 1608256984, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201218_021946_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201218_021946_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
