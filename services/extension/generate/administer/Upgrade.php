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
class Upgrade implements \fecshop\services\extension\UpgradeInterface
{
    /**
     * @var array
     * 必须按照版本号依次填写，否则升级会导致问题。
     * 如果安装的初始版本号为1.0.0，那么下面的升级版本号必须比初始版本号大才行
     */
    public $versions = [
        // '1.0.1',
        // '1.0.2',
        // '1.0.3',
    ];
    
    /**
    * @param $version  最新版本号
    * @return boolean
    *  升级执行的函数，您可以在各个版本号需要执行的部分写入相应的操作。
    */
    public function run($version)
    {
        /**
         * 下面仅仅是一个sql例子，您可以将其换成您自己要升级的内容
        switch ($version)
        {
            case '1.0.1' :
                $this->upgrade101();
                break;
            case '1.0.2' :
                $this->upgrade102();
                break;
            case '1.0.3' :
                $this->upgrade103();
                break;
        }
        */
        
        return true;
    }
    // 1.0.1
    public function upgrade101()
    {
        // 增加测试 - 冗余的字段
        // $sql = "ALTER TABLE fecmall_addon_test1 ADD COLUMN redundancy_field_5255 varchar(48);";
        // Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    // 1.0.2
    public function upgrade102()
    {
        // 删除测试 - 冗余的字段
        // $sql = "ALTER TABLE fecmall_addon_test1 ADD COLUMN redundancy_field_566 varchar(48);";
        // Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    // 1.0.3
    public function upgrade103()
    {
        // 删除测试 - 冗余的字段
        // $sql = "ALTER TABLE fecmall_addon_test1 ADD COLUMN redundancy_field_567 varchar(48);";
        // Yii::$app->getDb()->createCommand($sql)->execute();
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