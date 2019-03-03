<?php

use yii\db\Migration;

/**
 * Class m181118_003931_fecshop_tables
 */
class m181118_003931_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Product Review Save', 'catalog_product_review_manager', 2, '/catalog/productreview/managereditsave', 1542501354, 1542501354, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181118_003931_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181118_003931_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
