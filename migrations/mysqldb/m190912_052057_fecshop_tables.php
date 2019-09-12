<?php

use yii\db\Migration;

/**
 * Class m190912_052057_fecshop_tables
 */
class m190912_052057_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE product_flat DROP INDEX sku;
            ",
            "
                ALTER TABLE `product_flat` ADD UNIQUE (`sku`);
            ",
            "
                ALTER TABLE `product_flat` ADD UNIQUE (`url_key`);
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
        echo "m190912_052057_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190912_052057_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
