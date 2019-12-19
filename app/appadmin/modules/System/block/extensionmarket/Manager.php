<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\extensionmarket;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends \yii\base\BaseObject
{
    
    public $installedNameSpaceArr;
    public $versionArr;
    public $_param = [];
    public $localCreatedArr = [];
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        parent::init();
    }

    public function getLastData($param, $info)
    {
        $this->_param = $param;
        $addons = $info['addons'];
        $coll = isset($addons['coll']) ? $addons['coll'] : [];
        $count  = isset($addons['count']) ? $addons['count'] : 0;
        $this->initInstalledExtensions();
        $toolBar = $this->getToolBar($count, $this->_param['pageNum'], $this->_param['numPerPage']);
        $pagerForm = $this->getPagerForm();
        
        $can_hand_install_extensions = $this->canHandInstallExtensions($coll);
        $can_hand_upgrade_extensions = $this->canHandUpgradeExtensions($coll);
        //var_dump( $can_hand_install_extensions);
        return [
            'addon_list'=> $coll,
            'pagerForm' => $pagerForm,
            'toolBar' => $toolBar,
            'addon_count' => $count,
            'installed_extensions_namespace' => $this->installedNameSpaceArr,
            'can_hand_install_extensions' => $can_hand_install_extensions,
            'can_hand_upgrade_extensions' => $can_hand_upgrade_extensions,
            'versionArr' => $this->versionArr,
            'localCreatedArr' => $this->localCreatedArr ,
            
        ];
    }
    /**
     * @param $addon_list | array   ， 应用扩展列表
     * 手动更新应用列表
     */
    public function canHandUpgradeExtensions($addon_list)
    {
        $arr = [];
        if (!is_array($addon_list)) {
            return $arr;
        }
        foreach ($addon_list as $addon) {
            $packageName = isset($addon['addon_info']['package']) ? $addon['addon_info']['package'] : '';
            $folderName = isset($addon['addon_info']['folder']) ? $addon['addon_info']['folder'] : '';
            $namespaceName = isset($addon['addon_info']['namespace']) ? $addon['addon_info']['namespace'] : '';  //$addon['namespace']; 
            // 如果应用没安装
            if (!in_array($namespaceName, $this->installedNameSpaceArr)) {
                continue;
            }
            // 如果版本不需要升级
            if (!version_compare($this->versionArr[$namespace], $addon['addon_info']['version'] ,'<') ){
                continue;
            }
            // 文件检测
            $filePath = Yii::$service->extension->remoteService->getExtensionZipFilePath($packageName, $folderName);
            if (file_exists($filePath) && is_executable($filePath)) {
                
                $arr[] = $namespaceName;
            }
        }
        
        return $arr;
    }
    /**
     * @param $addon_list | array   ， 应用扩展列表
     *
     */
    public function canHandInstallExtensions($addon_list)
    {
        $arr = [];
        if (!is_array($addon_list)) {
            return $arr;
        }
        foreach ($addon_list as $addon) {
            $packageName = isset($addon['addon_info']['package']) ? $addon['addon_info']['package'] : '';
            $folderName = isset($addon['addon_info']['folder']) ? $addon['addon_info']['folder'] : '';
            $namespaceName = isset($addon['addon_info']['namespace']) ? $addon['addon_info']['namespace'] : '';  //$addon['namespace']; 
            // 如果应用已经安装
            if (in_array($namespaceName, $this->installedNameSpaceArr)) {
                continue;
            }
            $filePath = Yii::$service->extension->remoteService->getExtensionZipFilePath($packageName, $folderName);
            if (file_exists($filePath) && is_executable($filePath)) {
                
                $arr[] = $namespaceName;
            }
        }
        
        return $arr;
    }
    
    public function getPagerForm()
    {
        $str = '';
        if (is_array($this->_param) && !empty($this->_param)) {
            foreach ($this->_param as $k=>$v) {
                if ($k != '_csrf') {
                    $str .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                }
            }
        }

        return $str;
    }
    /**
     * list pager, it contains  numPerPage , pageNum , totalNum.
     */
    public function getToolBar($numCount, $pageNum, $numPerPage)
    {
        return    '<div class="pages">
					<span>' . Yii::$service->page->translate->__('Show') . '</span>
					<span>' . Yii::$service->page->translate->__('Line, Total {numCount} Line', ['numCount' => $numCount]) . '</span>
				</div>
				<div class="pagination" targetType="navTab" totalCount="'.$numCount.'" numPerPage="'.$numPerPage.'" pageNumShown="10" currentPage="'.$pageNum.'"></div>
				';
    }

    
    // namespace
    public function initInstalledExtensions()
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = Yii::$service->extension->coll($filter);
        $installedArr = [];
        $versionArr = [];
        $localCreatedArr = [];
        if (is_array($data['coll'])) {
            foreach ($data['coll'] as $one) {
                $namespace = $one['namespace'];
                if ($namespace) {
                    if (Yii::$service->extension->isInstalledStatus($one['installed_status'])) {
                        $installedArr[] = $namespace;
                    }
                    if (Yii::$service->extension->isTypeLocalCreated($one['type'])) {
                        $localCreatedArr[] = $namespace;
                    }
                    
                    $versionArr[$namespace] = $one['installed_version'];
                }
            }
        }
        $this->versionArr = $versionArr;
        $this->installedNameSpaceArr = $installedArr;
        $this->localCreatedArr = $localCreatedArr;
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
