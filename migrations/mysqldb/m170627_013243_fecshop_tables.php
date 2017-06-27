<?php

use yii\db\Migration;

class m170627_013243_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `sales_flat_order` ADD `payment_token` VARCHAR( 255 ) NULL COMMENT '支付过程中使用的token，譬如paypal express支付',
                ADD INDEX ( `payment_token` ) 
            "
            ,
           
            
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170619_014655_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
