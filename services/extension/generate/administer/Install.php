<?php
/**
 * 应用卸载类生成模板
 */

echo "<?php\n";
?>
/**
 * Fecmall Addons
 */
namespace <?= $namespaces ?>\administer;
use Yii;
/**
 * 应用安装类
 * 您可以在这里添加类变量，在配置中的值可以注入进来。
 */
class Install implements \fecshop\services\extension\InstallInterface
{
    // 安装初始版本号，不需要改动，不能为空
    public $version = '1.0.0';
    // 类变量，在config.php中可以通过配置注入值
    public $test;
    
    /**
     * @return mixed|void
     */
    public function run()
    {
        if (!$this->installDbSql()) {
            return false;
        }
        if (!$this->copyImageFile()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 进行数据库的初始化
     * sql语句执行，多个sql用分号  `;`隔开
     */
    public function installDbSql()
    {
        /**
         * 小知识：事务操作中，表数据的操作是可以回滚的，表结构改变的sql是无法回滚的。
        $db = Yii::$app->getDb();
        // 修改表sql
        $sql =  "ALTER TABLE  `customer` ADD  `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '该用户所属的供应商的id', ADD INDEX (  `bdmin_user_id` )";
        $db->createCommand($sql)->execute();
        
        // 新建表sql
        $sql =  "
            CREATE TABLE IF NOT EXISTS `statistical_bdmin_month` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `bdmin_user_id` INT( 11 ) NOT NULL COMMENT  '供应商ID',
                `complete_order_total` DECIMAL( 12, 2 ) NULL COMMENT  '完成的订单金额',
                `refund_return_total` DECIMAL( 12, 2 ) NULL COMMENT  '退货-退款总额',
                `month` INT( 5 ) NULL COMMENT  '月份',
                `updated_at` INT( 11 ) NULL COMMENT  '更新时间',
                `created_at` INT( 11 ) NULL COMMENT  '创建时间'
                ) ENGINE = INNODB;
        ";
        $db->createCommand($sql)->execute();
        
        // 后台添加url资源，以及添加admin用户的权限。
        $db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Home Page Config Save', 'config-homepage', 2, '/cms/homepage/managereditsave', 1554107065, 1554117214, 1)")->execute();
        $lastInsertId = $db->getLastInsertID() ;
        $db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)")->execute();
        
        
        */
        
        return true;
    }
    
    /**
     * 复制图片文件到appimage/common/addons/{namespace}，如果存在，则会被强制覆盖
     */
    public function copyImageFile()
    {
        /*
        $sourcePath = Yii::getAlias('@<?= $namespaces ?>/app/appimage/common/addons/<?= $namespaces ?>');
        
        Yii::$service->extension->administer->copyThemeFile($sourcePath);
        */
        return true;
    }
    
}