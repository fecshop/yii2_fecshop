<?php

use yii\db\Migration;

/**
 * Class m210225_095812_fecshop_tables
 */
class m210225_095812_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Admin Url Key Sql Gii', 'extension_developer_center', 5, '/system/adminurlkey/manager', 1614245231, 1614245231, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1585655278, 1585655278)");
        
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210225_095812_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210225_095812_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
