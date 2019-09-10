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
        $sql = "
        DROP TABLE IF EXISTS `fecmall_addon_test1`;
        CREATE TABLE `fecmall_addon_test1` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
          `title` varchar(50) NOT NULL COMMENT '标题',
          `cover` varchar(100) DEFAULT '' COMMENT '封面',
          `seo_key` varchar(50) DEFAULT '' COMMENT 'seo关键字',
          `seo_content` varchar(1000) DEFAULT '' COMMENT 'seo内容',
          `cate_id` int(10) DEFAULT '0' COMMENT '分类id',
          `description` char(140) DEFAULT '' COMMENT '描述".$this->test." ',
          `position` smallint(5) NOT NULL DEFAULT '0' COMMENT '推荐位',
          `content` longtext COMMENT '文章内容',
          `link` varchar(100) DEFAULT '' COMMENT '外链',
          `author` varchar(40) DEFAULT '' COMMENT '作者',
          `view` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
          `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
          `status` tinyint(4) DEFAULT '1' COMMENT '状态',
          `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
          `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
          PRIMARY KEY (`id`),
          KEY `article_id` (`id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章表';
        -- ----------------------------
        -- Table structure for rf_addon_article_adv
        -- ----------------------------
        DROP TABLE IF EXISTS `fecmall_addon_test2`;
        CREATE TABLE `fecmall_addon_test2` (
          `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
          `merchant_id` int(10) unsigned DEFAULT '0' COMMENT '商户id',
          `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
          `cover` varchar(100) DEFAULT '' COMMENT '图片',
          `location_id` int(11) DEFAULT '0' COMMENT '广告位ID',
          `silder_text` varchar(150) DEFAULT '' COMMENT '图片描述',
          `start_time` int(10) DEFAULT '0' COMMENT '开始时间',
          `end_time` int(10) DEFAULT '0' COMMENT '结束时间',
          `jump_link` varchar(150) DEFAULT '' COMMENT '跳转链接',
          `jump_type` tinyint(4) DEFAULT '1' COMMENT '跳转方式[1:新标签; 2:当前页]',
          `sort` int(10) DEFAULT '0' COMMENT '优先级',
          `status` tinyint(4) DEFAULT '1' COMMENT '状态',
          `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
          `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='扩展_文章_幻灯片表';

        ";
        // 执行sql, 创建表结构的时候，这个函数会返回0，因此不能以返回值作为return
        Yii::$app->getDb()->createCommand($sql)->execute();
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