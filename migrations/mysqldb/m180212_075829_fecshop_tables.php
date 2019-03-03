<?php

use yii\db\Migration;

/**
 * Class m180212_075829_fecshop_tables
 */
class m180212_075829_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order_item` ADD `customer_id` INT( 11 ) NULL COMMENT '用户的id' AFTER `order_id` , ADD INDEX ( `customer_id` ) 
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
        echo "m180212_075829_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180212_075829_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
