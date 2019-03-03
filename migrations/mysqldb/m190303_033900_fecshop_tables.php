<?php

use yii\db\Migration;

/**
 * Class m190303_033900_fecshop_tables
 */
class m190303_033900_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE  `sales_flat_cart` CHANGE  `items_count`  `items_count` INT( 10 ) NULL DEFAULT  '0' COMMENT  '购物车中active状态产品的总个数，默认为0个'
            ",
            "
                ALTER TABLE  `sales_flat_cart` ADD  `items_all_count` INT( 10 ) NULL DEFAULT  '0' COMMENT  '购物车中全部产品的总个数，默认为0个' AFTER  `items_count`
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
        echo "m190303_033900_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190303_033900_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
