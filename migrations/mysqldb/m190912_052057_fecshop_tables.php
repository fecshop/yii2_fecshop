<?php

use yii\db\Migration;

/**
 * Class m190912_052057_fecshop_tables
 */
class m190912_052057_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE product_flat DROP INDEX sku;
            ",
            "
                ALTER TABLE `product_flat` ADD UNIQUE (`sku`);
            ",
            "
                ALTER TABLE `product_flat` ADD UNIQUE (`url_key`);
            ",
        ];

        foreach ($arr as $sql) {
            $this->execute($sql);
        }
        
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Market Administer Test', 'extension_manager', 11, '/system/extensionmarket/administertest', 1574306889, 1574306889, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190912_052057_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190912_052057_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
