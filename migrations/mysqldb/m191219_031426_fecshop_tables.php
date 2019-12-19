<?php

use yii\db\Migration;

/**
 * Class m191219_031426_fecshop_tables
 */
class m191219_031426_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Hand Install', 'extension_manager', 50, '/system/extensionmarket/handinstall', 1576725104, 1576725104, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Hand Upgrade', 'extension_manager', 51, '/system/extensionmarket/handupgrade', 1576728494, 1576728494, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191219_031426_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191219_031426_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
