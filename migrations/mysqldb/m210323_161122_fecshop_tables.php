<?php

use yii\db\Migration;

/**
 * Class m210323_161122_fecshop_tables
 */
class m210323_161122_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `product_flat` CHANGE `description` `description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
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
        echo "m210323_161122_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_161122_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
