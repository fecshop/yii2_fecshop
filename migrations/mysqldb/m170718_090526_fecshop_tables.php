<?php

use yii\db\Migration;

class m170718_090526_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `admin_role` DEFAULT CHARACTER SET utf8;
            "
            ,
            "
            ALTER TABLE `admin_role_menu` DEFAULT CHARACTER SET utf8;
            "
            ,
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170718_090526_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170718_090526_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
