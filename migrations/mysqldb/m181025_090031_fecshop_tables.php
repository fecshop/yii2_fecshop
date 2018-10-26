<?php

use yii\db\Migration;

/**
 * Class m181025_090031_fecshop_tables
 */
class m181025_090031_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            CREATE TABLE IF NOT EXISTS `admin_url_key` (
              `id` int(15) NOT NULL AUTO_INCREMENT,
              `name` varchar(150) DEFAULT NULL COMMENT 'url key 的名称',
              `tag` int(15) NOT NULL COMMENT 'tag名称，在同一个菜单里面的url_key可以设置成同一个Tag',
              `tag_sort_order` int(10) DEFAULT NULL DEFAULT 0,
              `url_key` varchar(255) NOT NULL ,
              `created_at` int(20) DEFAULT NULL,
              `updated_at` int(20) DEFAULT NULL,
              `can_delete` int(5) DEFAULT 2 COMMENT '是否可以被删除，1代表不可以删除，2代表可以删除',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 
            ",
            "
            CREATE TABLE IF NOT EXISTS `admin_role_url_key` (
              `id` int(20) NOT NULL AUTO_INCREMENT,
              `role_id` int(20) NOT NULL,
              `url_key_id` int(20) NOT NULL,
              `created_at` int(20) DEFAULT NULL,
              `updated_at` int(20) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;
            ",

            // admin_menu table is deprecated, admin origin role change rbac role
            "
            DROP TABLE `admin_menu`,
            ",
            // admin_role_menu table is deprecated, admin origin role change rbac role
            "
            DROP TABLE `admin_role_menu`,
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
        echo "m181025_090031_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181025_090031_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
