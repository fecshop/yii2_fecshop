<?php

use yii\db\Migration;

class m171206_101231_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order` ADD `order_remark` TEXT NULL COMMENT '订单的备注信息，有买家填写提交' AFTER `customer_address_street2` 
            "
            ,
            
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    
    }

    public function safeDown()
    {
        echo "m171206_101231_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171206_101231_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
