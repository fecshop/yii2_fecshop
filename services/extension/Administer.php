<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\extension;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Administer extends Service
{
    // 卸载应用，是否删除掉应用的文件夹
    public $uninstallRemoveFile = true;
    
    public $currentNamespace;
    
    /**
     * 1.插件的安装
     * @param $extension_name | string ， 插件名称（唯一）
     * @param $forceInstall | boolean ， 是否强制安装（即使安装了，还是强制执行安装的代码。）
     * @return boolean 安装成功返回的状态
     * 
     */
    public function install($extension_namespace, $forceInstall=false)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 插件已经安装
        $installed_status = $modelOne['installed_status'];
        if (!$forceInstall && Yii::$service->extension->isInstalledStatus($installed_status)) {
            Yii::$service->helper->errors->add('extension: {namespace} has installed', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 通过数据库找到应用的配置文件路径
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} [{extensionConfigFile}]config file is not exit', ['namespace' =>$extension_namespace, 'extensionConfigFile' => $extensionConfigFile ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件不需要进行安装操作。
        if (!isset($extensionConfig['administer']['install'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no install file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 事务操作, 只对mysql有效，如果是mongodb，无法回滚
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的install部分功能
            if (!Yii::$service->extension->installAddons($extensionConfig['administer']['install'], $modelOne)) {
                $innerTransaction->rollBack();
                
                return false;
            }
            $innerTransaction->commit();
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            return false;
        }
        
        return false;
    }
    
    
    public function testInstall($extension_namespace, $forceInstall=false)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 通过数据库找到应用的配置文件路径
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} [{extensionConfigFile}]config file is not exit', ['namespace' =>$extension_namespace, 'extensionConfigFile' => $extensionConfigFile ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件不需要进行安装操作。
        if (!isset($extensionConfig['administer']['install'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no install file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 事务操作, 只对mysql有效，如果是mongodb，无法回滚
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的install部分功能
            if (!Yii::$service->extension->testInstallAddons($extensionConfig['administer']['install'], $modelOne)) {
                $innerTransaction->rollBack();
                
                return false;
            }
            
            $innerTransaction->commit();
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            return false;
        }
        
        return false;
    }
    
    /**
     * 应用升级函数
     * @param $extension_namespace | string , 插件的名称
     */
    public function upgrade($extension_namespace)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 插件如果没有安装
        $installed_status = $modelOne['installed_status'];
        if (!Yii::$service->extension->isInstalledStatus($installed_status)) {
            Yii::$service->helper->errors->add('extension: {namespace} has not installed', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 通过数据库找到应用的配置文件路径，如果配置文件不存在
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} config file is not exit', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件不需要进行安装操作。
        if (!isset($extensionConfig['administer']['upgrade'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no upgrade file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 事务操作, 只对mysql有效，如果是mongodb，无法回滚
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的upgrade部分功能
            if (!Yii::$service->extension->upgradeAddons($extensionConfig['administer']['upgrade'], $modelOne)) {
                $innerTransaction->rollBack();
                return false;
            }
            $innerTransaction->commit();
            
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            return false;
        }
        
        return false;
    }
    
    
    public function testUpgrade($extension_namespace)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 通过数据库找到应用的配置文件路径，如果配置文件不存在
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} config file is not exit', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件不需要进行安装操作。
        if (!isset($extensionConfig['administer']['upgrade'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no upgrade file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 事务操作, 只对mysql有效，如果是mongodb，无法回滚
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的upgrade部分功能
            if (!Yii::$service->extension->testUpgradeAddons($extensionConfig['administer']['upgrade'], $modelOne)) {
                $innerTransaction->rollBack();
                return false;
            }
            $innerTransaction->commit();
            
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            return false;
        }
        
        return false;
    }
    
    /**
     * 3.插件卸载。
     *
     */
    public function uninstall($extension_namespace)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 插件如果没有安装
        $installed_status = $modelOne['installed_status'];
        if (!Yii::$service->extension->isInstalledStatus($installed_status)) {
            Yii::$service->helper->errors->add('extension: {namespace} has not installed', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 通过数据库找到应用的配置文件路径，如果配置文件不存在
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} config file is not exit', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件无法进行卸载操作
        if (!isset($extensionConfig['administer']['uninstall'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no uninstall file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        
        // 事务操作, 只对mysql有效，执行插件的uninstall，并在extensions表中进行删除扩展配置
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的upgrade部分功能
            if (!Yii::$service->extension->uninstallAddons($extensionConfig['administer']['uninstall'], $modelOne)) {
                $innerTransaction->rollBack();
                return false;
            }
            $innerTransaction->commit();
            
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            return false;
        }
        
        // 进行应用源文件的删除操作。
        $package = $modelOne['package'];
        $folder = $modelOne['folder'];
        if ($package && $folder && $this->uninstallRemoveFile) {
            $installPath = Yii::getAlias('@addons/' . $package . '/' . $folder);
            // 从配置中获取，是否进行应用文件夹的删除，如果是，则进行文件的删除。
            Yii::$service->helper->deleteDir($installPath);
        }
        
        return true;
    }
    
    public function testUninstall($extension_namespace)
    {
        // 插件不存在
        $modelOne = Yii::$service->extension->getByNamespace($extension_namespace);
        if (!$modelOne['namespace']) {
            Yii::$service->helper->errors->add('extension: {namespace} is not exist', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        $this->currentNamespace = $extension_namespace;
        // 通过数据库找到应用的配置文件路径，如果配置文件不存在
        $extensionConfigFile = Yii::getAlias($modelOne['config_file_path']);
        if (!file_exists($extensionConfigFile)) {
            Yii::$service->helper->errors->add('extension: {namespace} config file is not exit', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        // 加载应用配置
        $extensionConfig = require($extensionConfigFile);
        // 如果没有该配置，说明该插件无法进行卸载操作
        if (!isset($extensionConfig['administer']['uninstall'])) {
            Yii::$service->helper->errors->add('extension: {namespace}， have no uninstall file function', ['namespace' =>$extension_namespace ]);
            
            return false;
        }
        // 事务操作, 只对mysql有效，执行插件的uninstall，并在extensions表中进行删除扩展配置
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            // 执行应用的upgrade部分功能
            if (!Yii::$service->extension->testUninstallAddons($extensionConfig['administer']['uninstall'], $modelOne)) {
                $innerTransaction->rollBack();
                
                return false;
            }
            $innerTransaction->commit();
            
            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            Yii::$service->helper->errors->add($e->getMessage());
            
            return false;
        }
        
        // 进行应用源文件的删除操作。
        $package = $modelOne['package'];
        $folder = $modelOne['folder'];
        if ($package && $folder && $this->uninstallRemoveFile) {
            $installPath = Yii::getAlias('@addons/' . $package . '/' . $folder);
            // 从配置中获取，是否进行应用文件夹的删除，如果是，则进行文件的删除。
            Yii::$service->helper->deleteDir($installPath);
        }
        
        return true;
    }
    
    
    
    // 数据库的安装
    protected function installDbData($modelOne) 
    {
        
        
        
    }
    
    
    // theme文件进行copy到@app/theme/base/addons 下面。
    public function  copyThemeFile($sourcePath) 
    {
        if (!$this->currentNamespace) {
            Yii::$service->helper->errors->add('copyThemeFile: current extension: {namespace} is not exist', ['namespace' =>$this->currentNamespace ]);
            
            return false;
        }
        $targetPath = Yii::getAlias('@appimage/common/addons/'.$this->currentNamespace);
        
        Yii::$service->helper->copyDirImage($sourcePath, $targetPath);
        
    }
    
    // theme文件进行copy到@app/theme/base/addons 下面。
    public function  removeThemeFile() 
    {
        if (!$this->currentNamespace) {
            Yii::$service->helper->errors->add('removeThemeFile: current extension: {namespace} is not exist', ['namespace' =>$this->currentNamespace ]);
            
            return false;
        }
        $sourcePath = Yii::getAlias('@appimage/common/addons/'.$this->currentNamespace);
        Yii::$service->helper->deleteDir($sourcePath);
        
        return true;
    }
}
