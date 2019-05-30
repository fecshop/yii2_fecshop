<?php

use yii\db\Migration;

/**
 * Class m190530_040928_fecshop_tables
 */
class m190530_040928_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE  `customer` ADD  `register_enable_token` VARCHAR( 100 ) NULL COMMENT  '注册账户需要邮件激活的token' AFTER  `password_reset_token` , ADD INDEX (  `register_enable_token` )
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
        echo "m190530_040928_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190530_040928_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
