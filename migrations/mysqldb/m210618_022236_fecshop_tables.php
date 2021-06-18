<?php

use yii\db\Migration;

/**
 * Class m210618_022236_fecshop_tables
 */
class m210618_022236_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `sales_flat_order` CHANGE `txn_id` `txn_id` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Transaction Id 支付平台唯一交易号,通过这个可以在第三方支付平台查找订单'
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
        echo "m210618_022236_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210618_022236_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
