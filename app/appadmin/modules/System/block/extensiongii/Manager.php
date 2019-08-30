<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\extensiongii;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager 
{
    
    
    // 传递给前端的数据 显示编辑form
    public function getLastData($developer_info)
    {
        $addon_author = isset($developer_info['addon_author']) ? $developer_info['addon_author'] : '';
        $package = isset($developer_info['package']) ? $developer_info['package'] : '';
        
        return [
            'addon_author' => $addon_author,
            'package' => $package,
            'saveUrl' => Yii::$service->url->getUrl('system/extensiongii/manager'),
        ];
    }
    
}
