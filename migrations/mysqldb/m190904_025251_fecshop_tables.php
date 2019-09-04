<?php

use yii\db\Migration;

/**
 * Class m190904_025251_fecshop_tables
 */
class m190904_025251_fecshop_tables extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
                CREATE TABLE IF NOT EXISTS `extensions` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `type` varchar(20) DEFAULT NULL COMMENT '应用类型：应用市场下载类型，本地开发类型',
                  `namespace` varchar(100) DEFAULT NULL COMMENT 'namespace',
                  `package` varchar(50) DEFAULT NULL COMMENT '应用所在的包名',
                  `folder` varchar(50) DEFAULT NULL COMMENT '应用所在的文件夹',
                  `name` varchar(150) DEFAULT NULL COMMENT '应用名称',
                  `status` int(5) DEFAULT NULL COMMENT '应用状态1.激活，2.关闭',
                  `config_file_path` varchar(255) DEFAULT NULL COMMENT '应用配置文件路径',
                  `created_at` int(12) DEFAULT NULL COMMENT '创建时间',
                  `updated_at` int(12) DEFAULT NULL COMMENT '更新时间',
                  `version` varchar(50) DEFAULT NULL COMMENT '下载的应用的版本（下载并不代表已安装）',
                  `installed_version` varchar(50) DEFAULT NULL COMMENT '应用当前的版本',
                  `priority` int(12) DEFAULT NULL COMMENT '优先级，数值越高，存在应用冲突的时候，越能生效',
                  `installed_status` int(5) DEFAULT NULL COMMENT '1代表已安装，2代表未安装',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`),
                  UNIQUE KEY `namespace` (`namespace`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;
            ";
        $this->execute($sql);
        
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Installed List', 'extension_installed', 1, '/system/extensioninstalled/manager', 1565580357, 1567160381, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");
        
        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Installed Edit', 'extension_installed', 2, '/system/extensioninstalled/manageredit', 1565580382, 1567160392, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 3
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Installed Save', 'extension_installed', 3, '/system/extensioninstalled/managereditsave', 1565580423, 1567160400, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 4
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Installed Delete', 'extension_installed', 4, '/system/extensioninstalled/managerdelete', 1565580449, 1567160408, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 5
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Market Manager', 'extension_manager', 7, '/system/extensionmarket/manager', 1565585008, 1565586051, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 6
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Extension Manager', 'extension_manager', 6, '/config/extension/manager', 1565585471, 1565586056, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 7
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Extension Save', 'extension_manager', 8, '/config/extension/managersave', 1565585491, 1565585491, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 8
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Market Login', 'extension_manager', 10, '/system/extensionmarket/login', 1566614814, 1566614814, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 9
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Market Install', 'extension_manager', 10, '/system/extensionmarket/install', 1566722341, 1566722416, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 10
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('ExtensionMarket Upgrade', 'extension_manager', 11, '/system/extensionmarket/upgrade', 1566784593, 1566870378, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 11
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Market Uninstall', 'extension_manager', 12, '/system/extensionmarket/uninstall', 1566870373, 1566870373, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 12
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Gii Generate', 'extension_developer_center', 15, '/system/extensiongii/manager', 1567065890, 1567065961, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 13
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Mutil Enable', 'extension_installed', 1, '/system/extensioninstalled/managerenable', 1567162910, 1567162992, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        // 14
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Extension Mutil Disable', 'extension_installed', 2, '/system/extensioninstalled/managerdisable', 1567162975, 1567162984, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1567162984, 1567162984)");

        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190904_025251_fecshop_tables cannot be reverted.\n";

        return false;
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190904_025251_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
