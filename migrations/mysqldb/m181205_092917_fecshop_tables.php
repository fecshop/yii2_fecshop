<?php

use yii\db\Migration;

/**
 * Class m181205_092917_fecshop_tables
 */
class m181205_092917_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `admin_role` ADD `updated_at` INT( 11 ) NULL DEFAULT NULL ,ADD `created_at` INT( 11 ) NULL DEFAULT NULL 
            "
            ,

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
        echo "m181205_092917_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181205_092917_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
