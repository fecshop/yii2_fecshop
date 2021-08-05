<?php

use yii\db\Migration;

/**
 * Class m210805_024643_fecshop_tables
 */
class m210805_024643_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order` ADD `tracking_company_name` VARCHAR( 50 ) NULL AFTER `tracking_company`
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
        echo "m210805_024643_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210805_024643_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
