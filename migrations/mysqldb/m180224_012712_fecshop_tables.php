<?php

use yii\db\Migration;

/**
 * Class m180224_012712_fecshop_tables
 */
class m180224_012712_fecshop_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_cart_item` ADD `active` INT( 5 ) NOT NULL DEFAULT '1' COMMENT '1代表勾选状态，2代表不勾选状态'
            "
            ,
            
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180224_012712_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180224_012712_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
