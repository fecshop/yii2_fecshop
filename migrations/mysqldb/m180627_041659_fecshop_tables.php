<?php

use yii\db\Migration;

/**
 * Class m180627_041659_fecshop_tables
 */
class m180627_041659_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `customer_address` CHANGE `first_name` `first_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `email` `email` VARCHAR( 90 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `last_name` `last_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `telephone` `telephone` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `street2` `street2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `city` `city` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE `state` `state` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
            "
            ,
            
            "
            ALTER TABLE `sales_flat_order` CHANGE `coupon_code` `coupon_code` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '优惠劵',
            CHANGE `customer_telephone` `customer_telephone` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客户电话'
            "
            
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
        echo "m180627_041659_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180627_041659_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
