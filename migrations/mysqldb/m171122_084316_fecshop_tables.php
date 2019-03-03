<?php

use yii\db\Migration;

class m171122_084316_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `admin_user` ADD `access_token_created_at` INT( 11 ) NULL COMMENT 'access token 的创建时间，格式为Int类型的时间戳' AFTER `access_token` ;
            "
            ,
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170724_031142_fecshop_tables cannot be reverted.\n";

        return false;
    }
}
