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
class Manager 
{
    
    public $nameSpaceArr;
    public $versionArr;
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        parent::init();
    }

    public function getLastData($info)
    {
        $addons = $info['addons'];
        $coll = isset($addons['coll']) ? $addons['coll'] : [];
        $count  = isset($addons['count']) ? $addons['count'] : 0;
        $this->initInstalledExtensions();
        return [
            'addon_list'=> $coll,
            'addon_count' => $count,
            'installed_extensions_namespace' => $this->nameSpaceArr,
            'versionArr' => $this->versionArr,
            
        ];
    }
    
    // namespace
    public function initInstalledExtensions()
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = Yii::$service->extension->coll($filter);
        $arr = [];
        $versionArr = [];
        if (is_array($data['coll'])) {
            foreach ($data['coll'] as $one) {
                $namespace = $one['namespace'];
                if ($namespace) {
                    $arr[] = $namespace;
                    $versionArr[$namespace] = $one['version'];
                }
            }
        }
        $this->versionArr = $versionArr;
        $this->nameSpaceArr = $arr;
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
