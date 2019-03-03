<?php

use yii\db\Migration;

class m170706_050701_fecshop_tables extends Migration
{
    
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `customer` ADD `access_token_created_at` INT( 11 ) NULL COMMENT '创建token的时间',
            ADD `allowance` INT( 11 ) NULL COMMENT '限制次数访问',
            ADD `allowance_updated_at` INT( 11 ) NULL 
            "
            ,
            
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    

    public function safeDown()
    {
        echo "m170706_050701_fecshop_tables cannot be reverted.\n";

        return false;
    }

}
