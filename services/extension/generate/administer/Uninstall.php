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
class Uninstall implements \fecshop\services\extension\UninstallInterface
{
    
    /**
     * 应用执行卸载的步骤执行的函数。
     */
    public function run()
    {
        if (!$this->uninstallDbSql()) {
            return false;
        }
        if (!$this->removeImageFile()) {
            return false;
        }
        return true;
    }
    
    public function uninstallDbSql()
    {
        /*
        $sql = "
        //    DROP TABLE IF EXISTS `fecmall_addon_test1`;
        //    DROP TABLE IF EXISTS `fecmall_addon_test2`;
        ";
        // 执行sql, 创建表结构的时候，这个函数会返回0，因此不能以返回值作为return
        Yii::$app->getDb()->createCommand($sql)->execute();
        */
        
        return true;
    }
    
    public function removeImageFile()
    {
        /*
        return Yii::$service->extension->administer->removeThemeFile();
        */
        
        return true;
    }
}