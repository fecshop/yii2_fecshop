<?php

use yii\db\Migration;

/**
 * Class m180629_052649_fecshop_tables
 */
class m180629_052649_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order` ADD `tracking_number` VARCHAR( 100 ) NULL COMMENT '订单追踪号' AFTER `shipping_total` ,
            ADD INDEX ( `tracking_number` ) 
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
        echo "m180629_052649_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180629_052649_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
