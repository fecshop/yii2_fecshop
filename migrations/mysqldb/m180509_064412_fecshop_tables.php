<?php

use yii\db\Migration;

/**
 * Class m180509_064412_fecshop_tables
 */
class m180509_064412_fecshop_tables extends Migration
{
    /**
     * 后台增加了Newsletter菜单
     */
    public function safeUp()
    {
        $arr = [
            "
            INSERT INTO `admin_menu` (`id`, `name`, `level`, `parent_id`, `url_key`, `role_key`, `created_at`, `updated_at`, `sort_order`, `can_delete`) VALUES
            (201, 'Newsletter', 2, 191, '/customer/newsletter/index', '/customer/newsletter', '2018-05-09 06:40:59', '2018-05-09 06:40:59', 0, 2)
            "
            ,
            "
            INSERT INTO `admin_role_menu` (`menu_id`, `role_id`, `created_at`, `updated_at`) VALUES
            (201, 4, '2018-05-09 06:46:44', '2018-05-09 06:46:44')
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
        echo "m180509_064412_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180509_064412_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
