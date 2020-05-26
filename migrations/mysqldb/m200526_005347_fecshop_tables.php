<?php

use yii\db\Migration;

/**
 * Class m200526_005347_fecshop_tables
 */
class m200526_005347_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `customer` ADD `wx_micro_openid` VARCHAR( 200 ) NULL COMMENT '微信小程序openid' AFTER `wx_openid`
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
        echo "m200526_005347_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200526_005347_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
