<?php

use yii\db\Migration;

/**
 * Class m210527_074623_fecshop_tables
 */
class m210527_074623_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `script_date_control` ADD `error_info` TEXT NULL COMMENT '脚本执行错误结果记录'
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
        echo "m210527_074623_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210527_074623_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
