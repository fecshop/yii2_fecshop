<?php

use yii\db\Migration;

/**
 * Class m190814_025105_fecshop_tables
 */
class m190814_025105_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $sql = "UPDATE `store_base_config` SET `value` = 'a:7:{s:12:\"increment_id\";s:10:\"1100000000\";s:19:\"requiredAddressAttr\";s:57:\"first_name,email,telephone,street1,country,city,state,zip\";s:24:\"orderProductSaleInMonths\";s:1:\"3\";s:34:\"minuteBeforeThatReturnPendingStock\";s:2:\"60\";s:32:\"orderCountThatReturnPendingStock\";s:2:\"30\";s:20:\"orderRemarkStrMaxLen\";s:4:\"1000\";s:10:\"guestOrder\";s:1:\"2\";}' WHERE `store_base_config`.`id` = 30;";
        $this->execute($sql);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190814_025105_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190814_025105_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
