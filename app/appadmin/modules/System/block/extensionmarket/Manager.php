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
        return [
            'addon_list'=> $coll,
            'addon_count' => $count,
            'installed_extensions' => $this->getInstalledExtensions(),
        ];
    }
    
    // namespace
    public function getInstalledExtensions()
    {
        return Yii::$service->extension->getAllNamespaces();
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
