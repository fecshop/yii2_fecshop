<?php

use yii\db\Migration;

/**
 * Class m190701_004812_fecshop_tables
 */
class m190701_004812_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE  `customer_address` ADD  `area` VARCHAR( 50 ) NULL COMMENT  '市区' AFTER  `state`
            ",
            
            "
                ALTER TABLE  `sales_flat_order` ADD  `tracking_company` VARCHAR( 150 ) NULL COMMENT  '快递公司' AFTER  `tracking_number`
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
        echo "m190701_004812_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190701_004812_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
