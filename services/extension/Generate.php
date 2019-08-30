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
class Generate extends Service
{
    /**
     * @param $param | array 
     * 数组子项：package, addon_folder, namespaces, addon_name, addon_author
     * 创建应用初始化包到指定的文件路径。
     */
    public function  createAddonsFiles($param) 
    {
        $package = isset($param['package']) ? $param['package'] : '';
        $addon_folder = isset($param['addon_folder']) ? $param['addon_folder'] : '';
        $namespaces = isset($param['namespaces']) ? $param['namespaces'] : '';
        $addon_name = isset($param['addon_name']) ? $param['addon_name'] : '';
        $addon_author = isset($param['addon_author']) ? $param['addon_author'] : '';
        if (!$package || !$addon_folder || !$namespaces || !$addon_name || !$addon_author) {
            return false;
        }
        // 创建文件夹
        if (!$this->createFolder($param)) {
            return false;
        }
        // 开始渲染gii 模板
        
        //得到应用文件夹
        $addonPath = Yii::getAlias('@addons/'.$package.'/'.$addon_folder);
        
        // config.php文件写入
        $viewFile = '@fecshop/services/extension/generate/config.php';
        $configContent =Yii::$app->view->renderFile($viewFile, $param);
        // 写入的文件路径
        $addonConfigFile = $addonPath. '/config.php';
        if (@file_put_contents($addonConfigFile, $configContent) === false) {
            Yii::$service->helper->errors->add('Unable to write the file '.$addonConfigFile);
            
            return false;
        } 
        
        // 写入Install
        $viewFile = '@fecshop/services/extension/generate/administer/Install.php';
        $configContent =Yii::$app->view->renderFile($viewFile, $param);
        // 写入的文件路径
        $addonConfigFile = $addonPath. '/administer/Install.php';
        if (@file_put_contents($addonConfigFile, $configContent) === false) {
            Yii::$service->helper->errors->add('Unable to write the file '.$addonConfigFile);
            
            return false;
        } 
        
        // 写入Upgrade
        $viewFile = '@fecshop/services/extension/generate/administer/Upgrade.php';
        $configContent =Yii::$app->view->renderFile($viewFile, $param);
        // 写入的文件路径
        $addonConfigFile = $addonPath. '/administer/Upgrade.php';
        if (@file_put_contents($addonConfigFile, $configContent) === false) {
            Yii::$service->helper->errors->add('Unable to write the file '.$addonConfigFile);
            
            return false;
        } 
        
        // 写入 Uninstall
        $viewFile = '@fecshop/services/extension/generate/administer/Uninstall.php';
        $configContent =Yii::$app->view->renderFile($viewFile, $param);
        // 写入的文件路径
        $addonConfigFile = $addonPath. '/administer/Uninstall.php';
        if (@file_put_contents($addonConfigFile, $configContent) === false) {
            Yii::$service->helper->errors->add('Unable to write the file '.$addonConfigFile);
            
            return false;
        } 
        
        
        
        return true;
    }
    
    public function createFolder($param)
    {
        $package = isset($param['package']) ? $param['package'] : '';
        $addon_folder = isset($param['addon_folder']) ? $param['addon_folder'] : '';
        $namespaces = isset($param['namespaces']) ? $param['namespaces'] : '';
        $addon_name = isset($param['addon_name']) ? $param['addon_name'] : '';
        $addon_author = isset($param['addon_author']) ? $param['addon_author'] : '';
        
        $addonPath = Yii::getAlias('@addons/'.$package.'/'.$addon_folder);
        // 创建文件夹
        if (!$this->createDir($addonPath)) {
            return false;
        }
        // administer
        $administerPath = $addonPath . '/administer';
        if (!$this->createDir($administerPath)) {
            return false;
        }
        // app/appfront
        $appfrontPath = $addonPath . '/app/appfront';
        if (!$this->createDir($appfrontPath)) {
            return false;
        }
        // models
        $modelPath = $addonPath . '/models';
        if (!$this->createDir($modelPath)) {
            return false;
        }
        // services
        $servicesPath = $addonPath . '/services';
        if (!$this->createDir($servicesPath)) {
            return false;
        }
        
        return true;
    }
    
    public function createDir($dir)
    {
        if (is_dir($dir)) {
            Yii::$service->helper->errors->add('dir['.$dir.'] is exist');
            
            return false;
        }
        return mkdir($dir,0777,true);
    }
    
}
