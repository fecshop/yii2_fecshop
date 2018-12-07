<?php

use yii\db\Migration;

class m170706_091433_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            CREATE TABLE IF NOT EXISTS `session_storage` (
            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `uuid` VARCHAR( 200 ) NULL COMMENT '用户唯一标示',
            `key` VARCHAR( 200 ) NULL COMMENT 'session key',
            `value` TEXT NULL COMMENT 'session value',
            `timeout` INT( 11 ) NULL COMMENT '超时时间，秒',
            `updated_at` INT( 11 ) NULL COMMENT '创建时间'
            ) ENGINE = InnoDB;
            "
            ,
            "
            ALTER TABLE `session_storage` ADD INDEX ( `uuid` , `key` ) ;
            "
            ,
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170706_091433_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170706_091433_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
