<?php

use yii\db\Migration;

class m171201_154002_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            INSERT INTO `admin_menu` (`id`, `name`, `level`, `parent_id`, `url_key`, `role_key`, `created_at`, `updated_at`, `sort_order`, `can_delete`) VALUES
            (199, 'ErrorHandler', 2, 164, '/system/error/index', '/system/error', '2017-12-01 21:45:56', '2017-12-01 23:32:39', 0, 2);
            "
            ,
            "
            INSERT INTO `admin_role_menu` (`id`, `menu_id`, `role_id`, `created_at`, `updated_at`) VALUES
            (124, 199, 4, '2017-12-01 21:46:11', '2017-12-01 21:46:11');
            "
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    
    }

    public function safeDown()
    {
        echo "m171201_154002_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_154002_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
